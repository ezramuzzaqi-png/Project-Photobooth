-- Seed 6 frame overlay transparan untuk menu Camera > Desain Strip.
-- Cara pakai (pilih salah satu):
--   1) php artisan migrate --force && php artisan db:seed --class=FrameTemplateSeeder --force
--      (cara utama; butuh kredensial MySQL benar di .env)
--   2) Import file ini via phpMyAdmin / HeidiSQL / mysql CLI (pastikan kolom
--      `templates`.`slots` sudah ada — dari migrasi 2026_09_30_000001).
-- Path frame relatif ke storage/app/public/ (diakses via public/storage setelah php artisan storage:link).
-- Kolom slots = koordinat lubang foto hasil ukur (1200x1800) + radius sudut agar foto pas di grid frame.

INSERT INTO `templates` (`name`, `layout_type`, `frame_image`, `background_image`, `slots`, `created_at`, `updated_at`)
VALUES
  ('Photobooth 2x2', '2x2', 'frames/photobooth-2x2.png', NULL, '{"fw":1200,"fh":1800,"radius":3,"holes":[[40,192,539,540],[620,192,539,540],[40,772,539,540],[620,772,539,540]]}', NOW(), NOW()),
  ('Photobooth 2x3', '2x3', 'frames/photobooth-2x3.png', NULL, '{"fw":1200,"fh":1800,"radius":3,"holes":[[40,192,539,344],[620,192,539,344],[40,572,539,344],[620,572,539,344],[40,952,539,344],[620,952,539,344]]}', NOW(), NOW()),
  ('Pixel to Reality 2x2', '2x2', 'frames/pixel-to-reality-2x2.png', NULL, '{"fw":1200,"fh":1800,"radius":3,"holes":[[40,192,539,540],[620,192,539,540],[40,772,539,540],[620,772,539,540]]}', NOW(), NOW()),
  ('Pixel to Reality 2x3', '2x3', 'frames/pixel-to-reality-2x3.png', NULL, '{"fw":1200,"fh":1800,"radius":3,"holes":[[40,192,539,344],[620,192,539,344],[40,572,539,344],[620,572,539,344],[40,952,539,344],[620,952,539,344]]}', NOW(), NOW()),
  ('RPL Pixel to Reality 2x2', '2x2', 'frames/rpl-pixel-to-reality-2x2.png', NULL, '{"fw":1200,"fh":1800,"radius":3,"holes":[[40,232,539,500],[620,232,539,500],[40,812,539,500],[620,812,539,500]]}', NOW(), NOW()),
  ('RPL Pixel to Reality 2x3', '2x3', 'frames/rpl-pixel-to-reality-2x3.png', NULL, '{"fw":1200,"fh":1800,"radius":3,"holes":[[40,232,539,304],[620,232,539,304],[40,612,539,304],[620,612,539,304],[40,992,539,304],[620,992,539,304]]}', NOW(), NOW());
