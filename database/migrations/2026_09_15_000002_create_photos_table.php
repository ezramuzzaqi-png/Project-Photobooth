<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('visitor_name');   // Biodata Nama
            $table->string('visitor_social'); // Biodata Media Sosial
            $table->foreignId('template_id')->nullable()->constrained()->nullOnDelete();
            $table->string('result_image_path'); // Path file hasil akhir strip
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
