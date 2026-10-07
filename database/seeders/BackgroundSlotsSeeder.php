<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class BackgroundSlotsSeeder extends Seeder
{
    public function run(): void
    {
        // Pengaman data ukur 6 background upload-an admin (lihat tabel templates).
        // Hanya mengisi ULANG layout + slots (koordinat lubang & radius hasil ukur),
        // dicocokkan by nama — file gambar TIDAK disentuh (runtime, spesifik mesin).
        // Baris yang namanya tidak ketemu (mis. DB fresh) dilewati diam-diam.
        // Catatan: Good Moments 2 x 3 tidak punya kotak foto; slotnya adalah
        // grid 2x3 di area hitam tengah (estimasi terukur, sudah diverifikasi visual).
        $rows = [
            [
                'name' => 'Dream Bigger 2 x 2', 'layout_type' => '2x2',
                'slots' => ['fw' => 239, 'fh' => 589, 'radius' => 10, 'holes' => [
                    [22, 119, 94, 110], [124, 119, 96, 110],
                    [22, 236, 94, 109], [124, 236, 96, 109],
                ]],
            ],
            [
                'name' => 'Good Moments 2 x 2', 'layout_type' => '2x2',
                'slots' => ['fw' => 246, 'fh' => 530, 'radius' => 7, 'holes' => [
                    [33, 123, 86, 109], [128, 123, 85, 109],
                    [33, 242, 86, 109], [128, 242, 80, 109],
                ]],
            ],
            [
                'name' => 'PxT 2 x 2', 'layout_type' => '2x2',
                'slots' => ['fw' => 250, 'fh' => 601, 'radius' => 8, 'holes' => [
                    [24, 126, 96, 111], [135, 126, 96, 111],
                    [24, 246, 96, 105], [135, 246, 96, 105],
                ]],
            ],
            [
                'name' => 'Dream Bigger 2 x 3', 'layout_type' => '2x3',
                'slots' => ['fw' => 1200, 'fh' => 2880, 'radius' => 51, 'holes' => [
                    [98, 560, 476, 403], [625, 560, 476, 403],
                    [98, 1003, 476, 403], [625, 1003, 476, 403],
                    [98, 1446, 476, 403], [625, 1446, 476, 403],
                ]],
            ],
            [
                'name' => 'Good Moments 2 x 3', 'layout_type' => '2x3',
                'slots' => ['fw' => 1200, 'fh' => 2880, 'radius' => 0, 'holes' => [
                    [170, 680, 410, 453], [620, 680, 410, 453],
                    [170, 1173, 410, 453], [620, 1173, 410, 453],
                    [170, 1666, 410, 453], [620, 1666, 410, 453],
                ]],
            ],
            [
                'name' => 'PxT 2 x 3', 'layout_type' => '2x3',
                'slots' => ['fw' => 1200, 'fh' => 2880, 'radius' => 41, 'holes' => [
                    [86, 590, 496, 414], [617, 590, 496, 414],
                    [86, 1038, 496, 414], [617, 1038, 496, 414],
                    [86, 1486, 496, 414], [617, 1486, 496, 414],
                ]],
            ],
        ];

        foreach ($rows as $row) {
            $t = Template::where('name', $row['name'])->first();
            if (! $t) continue;
            // Validasi ringan: jumlah lubang harus pas dengan slot layoutnya.
            $need = $row['layout_type'] === '2x3' ? 6 : 4;
            if (count($row['slots']['holes']) !== $need) continue;
            $t->update(['layout_type' => $row['layout_type'], 'slots' => $row['slots']]);
        }
    }
}
