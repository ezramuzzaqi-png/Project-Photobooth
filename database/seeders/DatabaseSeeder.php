<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@holdmoment.test'],
            [
                'name' => 'Admin HOLD',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $defaults = [
            ['name' => 'Classic Cream 2x2', 'layout_type' => '2x2', 'frame_image' => 'frames/classic-2x2.png'],
            ['name' => 'Warm Retro 2x3', 'layout_type' => '2x3', 'frame_image' => 'frames/warm-2x3.png'],
            ['name' => 'Strip Minimal 1x4', 'layout_type' => 'strip_1x4', 'frame_image' => 'frames/strip-1x4.png'],
            // Receipt 2 strip kembar — biar muncul di /admin/templates + bisa di-edit background-nya
            ['name' => 'Receipt', 'layout_type' => 'receipt', 'frame_image' => null, 'background_image' => null],
        ];

        foreach ($defaults as $row) {
            Template::firstOrCreate(['name' => $row['name']], $row);
        }
    }
}
