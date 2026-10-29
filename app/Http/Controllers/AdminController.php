<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_photos' => Photo::count(),
            'today_photos' => Photo::whereDate('created_at', today())->count(),
            'total_templates' => Template::count(),
        ];

        $photos = Photo::with(['user', 'template'])
            ->latest()
            ->take(10)
            ->get();

        $templates = Template::latest()->get();
        $users = User::latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'photos', 'templates', 'users'));
    }

    public function photos(): View
    {
        $photos = Photo::with(['user', 'template'])->latest()->paginate(15);

        return view('admin.photos', compact('photos'));
    }

    public function users(): View
    {
        $users = User::latest()->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function deletePhoto(int $id): RedirectResponse
    {
        DB::transaction(function () use ($id) {
            $photo = Photo::findOrFail($id);

            if ($photo->result_image_path) {
                Storage::disk('public')->delete($photo->result_image_path);
            }
            $photo->delete();

            // Nomor ulang ID agar selalu berurutan 1..n setelah ada yang dihapus.
            // Aman: tidak ada FK yang menunjuk ke photos.id.
            // Dipakai DB::table agar created_at/updated_at (Waktu Sesi) tidak ikut berubah.
            $ids = DB::table('photos')->orderBy('id')->pluck('id')->all();
            foreach ($ids as $old) {
                DB::table('photos')->where('id', $old)->update(['id' => -$old]);
            }
            $n = 0;
            foreach ($ids as $old) {
                $n++;
                DB::table('photos')->where('id', -$old)->update(['id' => $n]);
            }

            // Kembalikan AUTO_INCREMENT ke max+1 (MySQL & SQLite)
            try {
                $driver = DB::getDriverName();
                if ($driver === 'mysql') {
                    DB::statement('ALTER TABLE photos AUTO_INCREMENT = ?', [$n + 1]);
                } elseif ($driver === 'sqlite') {
                    DB::statement("UPDATE sqlite_sequence SET seq = ? WHERE name = 'photos'", [$n]);
                }
            } catch (\Throwable $e) {
                // abaikan: insert berikutnya tetap memakai max(id)+1
            }
        });

        return back()->with('success', 'Sesi foto #'.$id.' dihapus, ID diurut ulang.');
    }

    public function templates(): View
    {
        $templates = Template::latest()->paginate(15);

        return view('admin.templates', compact('templates'));
    }

    public function storeTemplate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'layout_type' => ['required', 'in:2x2,2x3,receipt,strip_1x4,1x4'],
            'frame_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:4096'],
            'background_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:4096'],
        ]);

        if (empty($validated['frame_image']) && empty($request->file('background_image'))) {
            return back()->withErrors(['frame_image' => 'Upload minimal satu: Frame (PNG transparan) atau Background.'])->withInput();
        }

        // Normalisasi alias layout
        if ($validated['layout_type'] === '1x4') {
            $validated['layout_type'] = 'strip_1x4';
        }

        try {
            $data = [
                'name' => $validated['name'],
                'layout_type' => $validated['layout_type'],
                'frame_image' => $request->hasFile('frame_image') ? $this->reencodeImage($request->file('frame_image'), 'frames') : null,
                'background_image' => $request->hasFile('background_image') ? $this->reencodeImage($request->file('background_image'), 'backgrounds') : null,
            ];
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        Template::create($data);

        return back()->with('success', 'Template berhasil ditambahkan.');
    }

    public function editTemplate(int $id): View
    {
        $template = Template::findOrFail($id);

        return view('admin.templates_edit', compact('template'));
    }

    public function updateTemplate(Request $request, int $id): RedirectResponse
    {
        $template = Template::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'layout_type' => ['required', 'in:2x2,2x3,receipt,strip_1x4,1x4'],
            'frame_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:4096'],
            'background_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:4096'],
            'clear_frame' => ['nullable', 'boolean'],
            'clear_background' => ['nullable', 'boolean'],
        ]);

        if ($validated['layout_type'] === '1x4') {
            $validated['layout_type'] = 'strip_1x4';
        }

        $data = [
            'name' => $validated['name'],
            'layout_type' => $validated['layout_type'],
        ];

        // Hapus frame/background jika dicentang
        if ($request->boolean('clear_frame') && $template->frame_image) {
            Storage::disk('public')->delete($template->frame_image);
            $data['frame_image'] = null;
        }
        if ($request->boolean('clear_background') && $template->background_image) {
            Storage::disk('public')->delete($template->background_image);
            $data['background_image'] = null;
        }

        try {
            // Ganti file frame bila diupload baru (file lama dihapus dari storage)
            if ($request->hasFile('frame_image')) {
                if ($template->frame_image) {
                    Storage::disk('public')->delete($template->frame_image);
                }
                $data['frame_image'] = $this->reencodeImage($request->file('frame_image'), 'frames');
            }
            if ($request->hasFile('background_image')) {
                if ($template->background_image) {
                    Storage::disk('public')->delete($template->background_image);
                }
                $data['background_image'] = $this->reencodeImage($request->file('background_image'), 'backgrounds');
            }
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $template->update($data);

        return redirect()->route('admin.templates')->with('success', 'Template "'.$template->name.'" diperbarui.');
    }

    /**
     * Batas dimensi piksel sebelum gambar didecode ke memori. Dicek melalui
     * getimagesize() (hanya baca header) sehingga gambar raksasa tidak pernah
     * memicu imagecreatefrompng/imagecreatefromjpeg.
     */
    private const MAX_UPLOAD_WIDTH = 4096;

    private const MAX_UPLOAD_HEIGHT = 4096;

    /**
     * Baca gambar asli dengan GD, resize ke ukuran maksimal wajar untuk canvas
     * strip, lalu simpan ulang sebagai file gambar bersih (menghapus metadata &
     * data tersisip/polyglot). Nama file digenerate sendiri (bukan nama asli
     * user) untuk mencegah path traversal dan penimpaan file.
     *
     * @return string|null Relatif path di disk public, atau null saat file
     *                     tidak diupload.
     *
     * @throws ValidationException saat berkas gagal
     *                             diproses (bukan gambar valid, terlalu besar, atau gagal simpan).
     */
    private function reencodeImage(UploadedFile $file, string $folder): ?string
    {
        $path = $file->getRealPath();

        // getimagesize hanya baca header; file non-gambar / polyglot yang
        // bukan citra valid akan menghasilkan false.
        $size = getimagesize($path);
        if ($size === false) {
            throw ValidationException::withMessages([
                'image' => 'Berkas bukan gambar PNG atau JPEG yang valid.',
            ]);
        }

        // Tolak dimensi piksel sangat besar SEBELUM didecode ke memori.
        if ($size[0] > self::MAX_UPLOAD_WIDTH || $size[1] > self::MAX_UPLOAD_HEIGHT) {
            throw ValidationException::withMessages([
                'image' => 'Gambar terlalu besar. Maksimum '.self::MAX_UPLOAD_WIDTH.'\u00d7'.self::MAX_UPLOAD_HEIGHT.' piksel.',
            ]);
        }

        $type = $size[2]; // IMAGETYPE_PNG=3, IMAGETYPE_JPEG=2
        if (! in_array($type, [IMAGETYPE_PNG, IMAGETYPE_JPEG], true)) {
            throw ValidationException::withMessages([
                'image' => 'Hanya berkas PNG dan JPEG yang diizinkan.',
            ]);
        }

        $src = match ($type) {
            IMAGETYPE_PNG => imagecreatefrompng($path),
            IMAGETYPE_JPEG => imagecreatefromjpeg($path),
            default => null,
        };

        if ($src === false || $src === null) {
            throw ValidationException::withMessages([
                'image' => 'Gagal membaca berkas gambar.',
            ]);
        }

        $srcW = imagesx($src);
        $srcH = imagesy($src);

        // Canvas strip resolusi penuh adalah 900 px lebar (lihat
        // strip-engine fullResStrip). Maksimum 1500×2250 memberi headroom
        // di atas tampilan 900 px tanpa menyimpan berkas berlebihan.
        $maxW = 1500;
        $maxH = 2250;
        $ratio = min(1.0, $maxW / $srcW, $maxH / $srcH);
        $dstW = (int) round($srcW * $ratio);
        $dstH = (int) round($srcH * $ratio);

        $dst = imagecreatetruecolor($dstW, $dstH);

        // Pertahankan alpha channel untuk PNG transparan.
        if ($type === IMAGETYPE_PNG) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);

        $extension = ($type === IMAGETYPE_PNG) ? 'png' : 'jpg';
        $filename = $folder.'/'.sha1_file($path).'_'.uniqid('', true).'.'.$extension;
        $target = storage_path('app/public/'.$filename);

        $success = match ($type) {
            IMAGETYPE_PNG => imagepng($dst, $target, 8),
            IMAGETYPE_JPEG => imagejpeg($dst, $target, 85),
            default => false,
        };

        imagedestroy($dst);
        imagedestroy($src);

        if (! $success) {
            throw ValidationException::withMessages([
                'image' => 'Gagal menyimpan berkas gambar.',
            ]);
        }

        return $filename;
    }

    public function destroyTemplate(int $id): RedirectResponse
    {
        $template = Template::findOrFail($id);

        if ($template->frame_image) {
            Storage::disk('public')->delete($template->frame_image);
        }
        if ($template->background_image) {
            Storage::disk('public')->delete($template->background_image);
        }
        $name = $template->name;
        $template->delete(); // foto yang memakai template ini otomatis jadi NULL (nullOnDelete)

        return back()->with('success', 'Template "'.$name.'" dihapus.');
    }

    public function updateUserRole(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:user,admin'],
        ]);

        $user = User::findOrFail($id);
        $user->update(['role' => $validated['role']]);

        return back()->with('success', 'Role '.$user->email.' diubah ke '.$validated['role'].'.');
    }
}
