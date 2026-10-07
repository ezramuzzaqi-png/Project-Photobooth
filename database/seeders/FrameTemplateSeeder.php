<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FrameTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Frame overlay PNG transparan untuk menu Camera > Desain Strip.
        // File master ikut git di database/seeders/assets/frames/ (karena
        // storage/app/public/* di-gitignore), lalu disalin ke disk 'public'
        // agar bisa diakses via asset('storage/...') dan paintFrameOverlay().
        // layout_type diambil dari nama file (2x2 = 4 slot, 2x3 = 6 slot)
        // agar otomatis muncul di /camera dan slotnya menyesuaikan.
        //
        // slots = koordinat lubang foto hasil ukur piksel transparan frame
        // (1200x1800), urutan kiri-kanan lalu atas-bawah. /camera memakai ini
        // agar foto digambar TEPAT di lubang frame (tidak lagi grid baku).
        $frames = [
            [
                'name' => 'Photobooth 2x2', 'layout_type' => '2x2', 'file' => 'photobooth-2x2.png',
                'slots' => ['fw' => 1200, 'fh' => 1800, 'radius' => 3, 'holes' => [
                    [40, 192, 539, 540], [620, 192, 539, 540],
                    [40, 772, 539, 540], [620, 772, 539, 540],
                ]],
            ],
            [
                'name' => 'Photobooth 2x3', 'layout_type' => '2x3', 'file' => 'photobooth-2x3.png',
                'slots' => ['fw' => 1200, 'fh' => 1800, 'radius' => 3, 'holes' => [
                    [40, 192, 539, 344], [620, 192, 539, 344],
                    [40, 572, 539, 344], [620, 572, 539, 344],
                    [40, 952, 539, 344], [620, 952, 539, 344],
                ]],
            ],
            [
                'name' => 'Pixel to Reality 2x2', 'layout_type' => '2x2', 'file' => 'pixel-to-reality-2x2.png',
                'slots' => ['fw' => 1200, 'fh' => 1800, 'radius' => 3, 'holes' => [
                    [40, 192, 539, 540], [620, 192, 539, 540],
                    [40, 772, 539, 540], [620, 772, 539, 540],
                ]],
            ],
            [
                'name' => 'Pixel to Reality 2x3', 'layout_type' => '2x3', 'file' => 'pixel-to-reality-2x3.png',
                'slots' => ['fw' => 1200, 'fh' => 1800, 'radius' => 3, 'holes' => [
                    [40, 192, 539, 344], [620, 192, 539, 344],
                    [40, 572, 539, 344], [620, 572, 539, 344],
                    [40, 952, 539, 344], [620, 952, 539, 344],
                ]],
            ],
            [
                'name' => 'RPL Pixel to Reality 2x2', 'layout_type' => '2x2', 'file' => 'rpl-pixel-to-reality-2x2.png',
                'slots' => ['fw' => 1200, 'fh' => 1800, 'radius' => 3, 'holes' => [
                    [40, 232, 539, 500], [620, 232, 539, 500],
                    [40, 812, 539, 500], [620, 812, 539, 500],
                ]],
            ],
            [
                'name' => 'RPL Pixel to Reality 2x3', 'layout_type' => '2x3', 'file' => 'rpl-pixel-to-reality-2x3.png',
                'slots' => ['fw' => 1200, 'fh' => 1800, 'radius' => 3, 'holes' => [
                    [40, 232, 539, 304], [620, 232, 539, 304],
                    [40, 612, 539, 304], [620, 612, 539, 304],
                    [40, 992, 539, 304], [620, 992, 539, 304],
                ]],
            ],
        ];

        foreach ($frames as $row) {
            $target = 'frames/' . $row['file'];

            if (! Storage::disk('public')->exists($target)) {
                $source = database_path('seeders/assets/frames/' . $row['file']);
                if (is_file($source)) {
                    Storage::disk('public')->put($target, file_get_contents($source));
                }
            }

            // updateOrCreate agar seed ulang mengisi slots pada baris lama.
            Template::updateOrCreate(
                ['name' => $row['name']],
                ['layout_type' => $row['layout_type'], 'frame_image' => $target, 'slots' => $row['slots']]
            );
        }
    }
}
