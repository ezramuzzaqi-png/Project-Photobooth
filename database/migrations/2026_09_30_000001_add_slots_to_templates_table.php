<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            // Koordinat lubang foto dalam piksel frame, misal:
            // {"fw":1200,"fh":1800,"holes":[[x,y,w,h],...]} urutan kiri-kanan, atas-bawah.
            // Dipakai /camera agar foto digambar tepat di lubang frame transparan.
            // NULL = pakai grid baku (2x2/2x3/receipt).
            $table->json('slots')->nullable()->after('background_image');
        });
    }

    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn('slots');
        });
    }
};
