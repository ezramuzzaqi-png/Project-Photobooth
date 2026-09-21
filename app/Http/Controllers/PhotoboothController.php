<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Template;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PhotoboothController extends Controller
{
    public function camera(): View
    {
        // Hanya desain yang layout-nya didukung halaman camera (2x2 / 2x3 / receipt)
        $templates = Template::whereIn('layout_type', ['2x2', '2x3', 'receipt'])
            ->orderBy('name')
            ->get();

        // Payload JSON untuk pemilih desain di JS (dibuat di controller
        // agar Blade tidak perlu parsing ekspresi kompleks di @json)
        $designs = $templates->map(fn (Template $t) => [
            'id' => $t->id,
            'name' => $t->name,
            'layout_type' => $t->layout_type,
            'frame_url' => $t->frame_image ? asset('storage/' . $t->frame_image) : null,
            'background_url' => $t->background_image ? asset('storage/' . $t->background_image) : null,
        ])->values();

        return view('camera', compact('templates', 'designs'));
    }

    /**
     * Menerima hasil Canvas (base64 PNG/JPEG) + biodata via Fetch/AJAX.
     * Body: visitor_name, visitor_social, template_id?, layout_type?, image (dataURL)
     */
    public function savePhoto(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'visitor_name' => ['required', 'string', 'max:255'],
            'visitor_social' => ['required', 'string', 'max:255'],
            'template_id' => ['nullable', 'exists:templates,id'],
            'layout_type' => ['nullable', 'string', 'max:20'],
            'image' => ['required', 'string'],
        ]);

        $dataUrl = $validated['image'];

        if (! str_contains($dataUrl, ';base64,')) {
            return response()->json(['message' => 'Format gambar tidak valid.'], 422);
        }

        [$meta, $base64] = explode(';base64,', $dataUrl, 2);
        $binary = base64_decode($base64, true);

        if ($binary === false) {
            return response()->json(['message' => 'Gagal membaca data gambar.'], 422);
        }

        $extension = str_contains($meta, 'image/jpeg') ? 'jpg' : 'png';
        $filename = 'strips/' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $extension;
        // Batas upload Railway/nginx default 2-8MB — tolak yang kebesaran dengan pesan jelas
        if (strlen($binary) > 8 * 1024 * 1024) {
            return response()->json(['message' => 'Gambar terlalu besar (>8MB). Coba lagi.'], 413);
        }

        Storage::disk('public')->put($filename, $binary);

        $photo = Photo::create([
            'user_id' => auth()->id(),
            'visitor_name' => $validated['visitor_name'],
            'visitor_social' => $validated['visitor_social'],
            'template_id' => $validated['template_id'] ?? null,
            'result_image_path' => $filename,
        ]);

        return response()->json([
            'message' => 'Foto berhasil disimpan.',
            'id' => $photo->id,
            'redirect' => route('photo.result', $photo->id),
        ], 201);
    }

    public function result(int $id): View
    {
        $photo = Photo::with('template')->findOrFail($id);

        return view('save', compact('photo'));
    }
}
