<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Template;
use App\Models\User;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        // 3 desain terbaru yang punya gambar untuk showcase (fallback: desain apapun).
        try {
            $showcase = Template::whereNotNull('frame_image')
                ->orWhereNotNull('background_image')
                ->latest()
                ->take(3)
                ->get();
        } catch (\Throwable $e) {
            $showcase = collect();
        }

        return view('home', ['stats' => $this->stats(), 'showcase' => $showcase]);
    }

    public function about(): View
    {
        return view('about', ['stats' => $this->stats()]);
    }

    public function howItWorks(): View
    {
        return view('how-it-works');
    }

    public function gallery(): View
    {
        try {
            $templates = Template::latest()->get();
        } catch (\Throwable $e) {
            $templates = collect();
        }

        return view('gallery', compact('templates'));
    }

    /** Statistik live untuk Home & About (aman saat DB mati: fallback 0). */
    private function stats(): array
    {
        try {
            return [
                'photos' => Photo::count(),
                'users' => User::count(),
                'rating' => '4,5/5',
            ];
        } catch (\Throwable $e) {
            return ['photos' => 0, 'users' => 0, 'rating' => '4,5/5'];
        }
    }
}
