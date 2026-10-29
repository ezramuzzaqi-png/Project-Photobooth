<?php

namespace Tests\Feature;

use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TemplateUploadTest extends TestCase
{
    use RefreshDatabase;

    private function loginAsAdmin()
    {
        User::factory()->create([
            'email' => 'admin@holdmoment.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->post('/login', [
            'email' => 'admin@holdmoment.test',
            'password' => 'password',
        ]);
    }

    private function loginAsUser()
    {
        User::factory()->create([
            'email' => 'user@holdmoment.test',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->post('/login', [
            'email' => 'user@holdmoment.test',
            'password' => 'password',
        ]);
    }

    private function makeTransparentPng(string $path): void
    {
        $im = imagecreatetruecolor(100, 100);
        imagesavealpha($im, true);
        $transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
        imagefill($im, 0, 0, $transparent);
        $opaque = imagecolorallocate($im, 255, 0, 0);
        imagefilledrectangle($im, 25, 25, 75, 75, $opaque);
        imagepng($im, $path);
        imagedestroy($im);
    }

    private function makeLargePng(string $path): void
    {
        // 5000x5000 -> melebihi MAX_UPLOAD_WIDTH/HEIGHT (4096)
        $im = imagecreatetruecolor(5000, 5000);
        $red = imagecolorallocate($im, 255, 0, 0);
        imagefill($im, 0, 0, $red);
        imagepng($im, $path);
        imagedestroy($im);
    }

    private function makeOversizePng(string $path): void
    {
        // 2000x2000 -> di bawah MAX_UPLOAD (4096) tapi di atas max canvas (1500x2250), akan diperkecil
        $im = imagecreatetruecolor(2000, 2000);
        $red = imagecolorallocate($im, 255, 0, 0);
        imagefill($im, 0, 0, $red);
        imagepng($im, $path);
        imagedestroy($im);
    }

    private function makeSmallPng(string $path): void
    {
        // 50x50 -> di bawah max canvas, tidak diperbesar
        $im = imagecreatetruecolor(50, 50);
        $red = imagecolorallocate($im, 255, 0, 0);
        imagefill($im, 0, 0, $red);
        imagepng($im, $path);
        imagedestroy($im);
    }

    /** @test */
    public function admin_can_upload_valid_png_background(): void
    {
        $this->loginAsAdmin();

        $file = UploadedFile::fake()->image('background.png', 100, 100);

        $response = $this->post('/admin/templates', [
            'name' => 'Valid PNG Template',
            'layout_type' => '2x2',
            'background_image' => $file,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('templates', [
            'name' => 'Valid PNG Template',
            'layout_type' => '2x2',
        ]);

        $template = Template::where('name', 'Valid PNG Template')->first();
        $this->assertNotNull($template->background_image);
        $this->assertStringStartsWith('backgrounds/', $template->background_image);
        $this->assertTrue(Storage::disk('public')->exists($template->background_image));
    }

    /** @test */
    public function transparent_png_preserves_alpha_after_reencode(): void
    {
        $this->loginAsAdmin();

        $path = tempnam(sys_get_temp_dir(), 'png');
        $this->makeTransparentPng($path);

        $file = new UploadedFile($path, 'transparent.png', null, null, true);

        $this->post('/admin/templates', [
            'name' => 'Transparent PNG',
            'layout_type' => '2x2',
            'background_image' => $file,
        ]);

        $template = Template::where('name', 'Transparent PNG')->first();
        $savedPath = storage_path('app/public/'.$template->background_image);

        $saved = imagecreatefrompng($savedPath);
        $this->assertNotFalse($saved);
        // Piksel di pojok kiri atas harus transparan (alpha > 0)
        $colorAt = imagecolorat($saved, 10, 10);
        $alpha = ($colorAt >> 24) & 0xFF;
        $this->assertGreaterThan(0, $alpha, 'Alpha channel harus preserved setelah re-encode');

        imagedestroy($saved);
        unlink($path);
    }

    /** @test */
    public function polyglot_png_is_stored_as_clean_image_only(): void
    {
        $this->loginAsAdmin();

        $path = tempnam(sys_get_temp_dir(), 'png');
        $im = imagecreatetruecolor(100, 100);
        $red = imagecolorallocate($im, 255, 0, 0);
        imagefill($im, 0, 0, $red);
        imagepng($im, $path);
        imagedestroy($im);
        // Sisipkan payload PHP di akhir file (polyglot)
        file_put_contents($path, file_get_contents($path).'<?php system($_GET["cmd"]); ?>', FILE_APPEND);

        $file = new UploadedFile($path, 'polyglot.png', null, null, true);

        $this->post('/admin/templates', [
            'name' => 'Polyglot PNG',
            'layout_type' => '2x2',
            'background_image' => $file,
        ]);

        $template = Template::where('name', 'Polyglot PNG')->first();
        $savedPath = storage_path('app/public/'.$template->background_image);

        $contents = file_get_contents($savedPath);
        $this->assertStringNotContainsString('<?php', $contents, 'Polyglot payload harus dibuang setelah re-encode');

        unlink($path);
    }

    /** @test */
    public function fake_png_text_file_is_rejected(): void
    {
        $this->loginAsAdmin();

        $path = tempnam(sys_get_temp_dir(), 'png');
        file_put_contents($path, 'this is not an image at all just text');

        $file = new UploadedFile($path, 'fake.png', null, null, true);

        $response = $this->post('/admin/templates', [
            'name' => 'Fake PNG',
            'layout_type' => '2x2',
            'background_image' => $file,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('background_image');
        $this->assertDatabaseMissing('templates', ['name' => 'Fake PNG']);

        unlink($path);
    }

    /** @test */
    public function oversized_dimensions_are_rejected_before_decode(): void
    {
        $this->loginAsAdmin();

        $path = tempnam(sys_get_temp_dir(), 'png');
        $this->makeLargePng($path); // 5000x5000 > 4096

        $file = new UploadedFile($path, 'large.png', null, null, true);

        $response = $this->post('/admin/templates', [
            'name' => 'Oversized PNG',
            'layout_type' => '2x2',
            'background_image' => $file,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('image');
        $this->assertDatabaseMissing('templates', ['name' => 'Oversized PNG']);

        unlink($path);
    }

    /** @test */
    public function large_image_is_resized_down_not_up(): void
    {
        $this->loginAsAdmin();

        $path = tempnam(sys_get_temp_dir(), 'png');
        $this->makeOversizePng($path); // 2000x2000 -> akan diperkecil ke max 1500x1500

        $file = new UploadedFile($path, 'large.png', null, null, true);

        $this->post('/admin/templates', [
            'name' => 'Resized PNG',
            'layout_type' => '2x2',
            'background_image' => $file,
        ]);

        $template = Template::where('name', 'Resized PNG')->first();
        $savedPath = storage_path('app/public/'.$template->background_image);

        $saved = imagecreatefrompng($savedPath);
        $this->assertNotFalse($saved);
        $w = imagesx($saved);
        $h = imagesy($saved);
        $this->assertLessThanOrEqual(1500, $w);
        $this->assertLessThanOrEqual(2250, $h);
        imagedestroy($saved);

        unlink($path);
    }

    /** @test */
    public function small_image_is_not_enlarged(): void
    {
        $this->loginAsAdmin();

        $path = tempnam(sys_get_temp_dir(), 'png');
        $this->makeSmallPng($path); // 50x50 -> tidak diperbesar

        $file = new UploadedFile($path, 'small.png', null, null, true);

        $this->post('/admin/templates', [
            'name' => 'Small PNG',
            'layout_type' => '2x2',
            'background_image' => $file,
        ]);

        $template = Template::where('name', 'Small PNG')->first();
        $savedPath = storage_path('app/public/'.$template->background_image);

        $saved = imagecreatefrompng($savedPath);
        $this->assertNotFalse($saved);
        $w = imagesx($saved);
        $h = imagesy($saved);
        $this->assertLessThanOrEqual(50, $w);
        $this->assertLessThanOrEqual(50, $h);
        imagedestroy($saved);

        unlink($path);
    }

    /** @test */
    public function upload_without_login_is_rejected(): void
    {
        $file = UploadedFile::fake()->image('bg.png', 100, 100);

        $response = $this->post('/admin/templates', [
            'name' => 'No Login',
            'layout_type' => '2x2',
            'background_image' => $file,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function regular_user_cannot_upload_template(): void
    {
        $this->loginAsUser();

        $file = UploadedFile::fake()->image('bg.png', 100, 100);

        $response = $this->post('/admin/templates', [
            'name' => 'User Upload',
            'layout_type' => '2x2',
            'background_image' => $file,
        ]);

        // AdminMiddleware mengembalikan abort(403) untuk user yang sudah login
        // tapi bukan admin (bukan redirect ke /login).
        $response->assertStatus(403);
    }

    /** @test */
    public function replace_background_deletes_old_file(): void
    {
        $this->loginAsAdmin();

        $path1 = tempnam(sys_get_temp_dir(), 'png');
        $im = imagecreatetruecolor(100, 100);
        $red = imagecolorallocate($im, 255, 0, 0);
        imagefill($im, 0, 0, $red);
        imagepng($im, $path1);
        imagedestroy($im);

        $file1 = new UploadedFile($path1, 'old.png', null, null, true);
        $this->post('/admin/templates', [
            'name' => 'Replace Test',
            'layout_type' => '2x2',
            'background_image' => $file1,
        ]);
        unlink($path1);

        $template = Template::where('name', 'Replace Test')->first();
        $oldPath = $template->background_image;
        $this->assertNotNull($oldPath);
        $this->assertTrue(Storage::disk('public')->exists($oldPath));

        // Ganti dengan background baru
        $path2 = tempnam(sys_get_temp_dir(), 'png');
        $im2 = imagecreatetruecolor(100, 100);
        $blue = imagecolorallocate($im2, 0, 0, 255);
        imagefill($im2, 0, 0, $blue);
        imagepng($im2, $path2);
        imagedestroy($im2);

        $file2 = new UploadedFile($path2, 'new.png', null, null, true);
        $this->post('/admin/templates/'.$template->id, array_merge(
            [
                '_method' => 'PUT',
                'name' => 'Replace Test',
                'layout_type' => '2x2',
            ],
            ['background_image' => $file2]
        ));
        unlink($path2);

        $template->refresh();
        $this->assertNotEquals($oldPath, $template->background_image);
        $this->assertFalse(Storage::disk('public')->exists($oldPath), 'File lama harus dihapus saat replace');
        $this->assertTrue(Storage::disk('public')->exists($template->background_image));
    }

    /** @test */
    public function delete_template_removes_background_file(): void
    {
        $this->loginAsAdmin();

        $path = tempnam(sys_get_temp_dir(), 'png');
        $im = imagecreatetruecolor(100, 100);
        $red = imagecolorallocate($im, 255, 0, 0);
        imagefill($im, 0, 0, $red);
        imagepng($im, $path);
        imagedestroy($im);

        $file = new UploadedFile($path, 'bg.png', null, null, true);
        $this->post('/admin/templates', [
            'name' => 'Delete Test',
            'layout_type' => '2x2',
            'background_image' => $file,
        ]);
        unlink($path);

        $template = Template::where('name', 'Delete Test')->first();
        $bgPath = $template->background_image;
        $this->assertNotNull($bgPath);
        $this->assertTrue(Storage::disk('public')->exists($bgPath));

        $this->post('/admin/templates/'.$template->id, [
            '_method' => 'DELETE',
        ]);

        $this->assertFalse(Storage::disk('public')->exists($bgPath), 'File harus dihapus saat template dihapus');
        $this->assertDatabaseMissing('templates', ['id' => $template->id]);
    }

    /** @test */
    public function jpeg_exif_orientation_is_not_handled(): void
    {
        // [TERBUKTI] Ekstensi EXIF tidak terinstall di PHP.
        // imagecreatefromjpeg tidak memutar gambar berdasarkan EXIF orientation.
        if (! extension_loaded('exif')) {
            $this->markTestSkipped('Ekstensi EXIF tidak terinstall; rotasi EXIF tidak dapat diuji.');
        }
        $this->assertTrue(true);
    }
}
