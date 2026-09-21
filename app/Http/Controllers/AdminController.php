<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        return back()->with('success', 'Sesi foto #' . $id . ' dihapus, ID diurut ulang.');
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

        $data = [
            'name' => $validated['name'],
            'layout_type' => $validated['layout_type'],
            'frame_image' => $request->hasFile('frame_image') ? $request->file('frame_image')->store('frames', 'public') : null,
            'background_image' => $request->hasFile('background_image') ? $request->file('background_image')->store('backgrounds', 'public') : null,
        ];

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

        // Ganti file frame bila diupload baru (file lama dihapus dari storage)
        if ($request->hasFile('frame_image')) {
            if ($template->frame_image) Storage::disk('public')->delete($template->frame_image);
            $data['frame_image'] = $request->file('frame_image')->store('frames', 'public');
        }
        if ($request->hasFile('background_image')) {
            if ($template->background_image) Storage::disk('public')->delete($template->background_image);
            $data['background_image'] = $request->file('background_image')->store('backgrounds', 'public');
        }

        $template->update($data);

        return redirect()->route('admin.templates')->with('success', 'Template "' . $template->name . '" diperbarui.');
    }

    public function destroyTemplate(int $id): RedirectResponse
    {
        $template = Template::findOrFail($id);

        if ($template->frame_image) Storage::disk('public')->delete($template->frame_image);
        if ($template->background_image) Storage::disk('public')->delete($template->background_image);
        $name = $template->name;
        $template->delete(); // foto yang memakai template ini otomatis jadi NULL (nullOnDelete)

        return back()->with('success', 'Template "' . $name . '" dihapus.');
    }

    public function updateUserRole(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:user,admin'],
        ]);

        $user = User::findOrFail($id);
        $user->update(['role' => $validated['role']]);

        return back()->with('success', 'Role ' . $user->email . ' diubah ke ' . $validated['role'] . '.');
    }
}
