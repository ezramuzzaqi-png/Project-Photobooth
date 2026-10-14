{{-- Mesin gambar strip (shared): dipakai halaman camera + halaman pilih desain.
Syarat dari halaman pemanggil (di dalam IIFE script yang sama):
  - const state = { layout, filter, templateId, builtin, shots, visitor:{name,social}, ... }
  - const TEMPLATES = [...] (payload desain + slots + frame/background_url)
  - const stripPreview = <canvas> (untuk drawStripPreview)
--}}
    // Preload frame overlay & background agar preview langsung tampil saat desain dipilih
    const frameCache = {};
    const backgroundCache = {};
    const detectedCache = {}; // hasil ukur otomatis lubang per template (kunci: id_total)
    function invalidateDetected(id) {
        Object.keys(detectedCache).forEach(k => { if (k.startsWith(id + '_')) delete detectedCache[k]; });
    }
    TEMPLATES.forEach(t => {
        if (t.frame_url) {
            const img = new Image();
            img.onload = () => { frameCache[t.id] = img; invalidateDetected(t.id); if (String(state.templateId) === String(t.id)) drawStripPreview(); };
            img.onerror = () => { delete frameCache[t.id]; };
            img.src = t.frame_url;
        }
        if (t.background_url) {
            const bg = new Image();
            bg.onload = () => { backgroundCache[t.id] = bg; invalidateDetected(t.id); if (String(state.templateId) === String(t.id)) drawStripPreview(); };
            bg.onerror = () => { delete backgroundCache[t.id]; };
            bg.src = t.background_url;
        }
    });

    // Ukur otomatis lubang foto pada gambar frame/background (transparan ATAU
    // kotak putih) untuk template upload-an admin yang belum punya slots.
    // Murni JS (canvas getImageData), tanpa library. Gagal → null (pakai grid baku).
    function detectHoles(img, total) {
        const fw = img.naturalWidth, fh = img.naturalHeight;
        if (!fw || !fh) return null;
        if (!Number.isInteger(total / 2) || total < 1) return null;
        const aw = Math.min(fw, 360);
        const ah = Math.max(1, Math.round(aw * fh / fw));
        const cv = document.createElement('canvas');
        cv.width = aw; cv.height = ah;
        const cx = cv.getContext('2d', { willReadFrequently: true });
        cx.drawImage(img, 0, 0, aw, ah);
        let data;
        try { data = cx.getImageData(0, 0, aw, ah).data; }
        catch (e) { return null; }
        const isHole = (x, y) => {
            if (x < 0 || y < 0 || x >= aw || y >= ah) return false;
            const i = (y * aw + x) * 4;
            if (data[i + 3] < 128) return true;
            return data[i] >= 215 && data[i + 1] >= 215 && data[i + 2] >= 215; // kotak terang
        };
        const holes = [];
        const minH = ah * 0.07, minW = (aw / 2) * 0.55;
        [Math.floor(aw / 4), Math.floor(3 * aw / 4)].forEach(px => {
            const half = px < aw / 2 ? [0, Math.floor(aw / 2)] : [Math.floor(aw / 2), aw];
            const runs = [];
            let inRun = false, s = 0;
            for (let y = 0; y < ah; y++) {
                const t = isHole(px, y);
                if (t && !inRun) { inRun = true; s = y; }
                else if (!t && inRun) { inRun = false; runs.push([s, y]); }
            }
            if (inRun) runs.push([s, ah]);
            runs.forEach(r => {
                if ((r[1] - r[0]) < minH) return;
                const ym = Math.floor((r[0] + r[1]) / 2);
                let l = px; while (l > half[0] && isHole(l - 1, ym)) l--;
                let rr = px; while (rr < half[1] - 1 && isHole(rr + 1, ym)) rr++;
                if ((rr - l) < minW) return;
                const midx = Math.floor((l + rr) / 2);
                let t = r[0]; while (t > 0 && isHole(midx, t - 1)) t--;
                let b = r[1]; while (b < ah - 1 && isHole(midx, b + 1)) b++;
                holes.push([l, t, rr - l, b - t]);
            });
        });
        let kept = holes;
        if (kept.length > total) {
            const areas = kept.map(hh => hh[2] * hh[3]).sort((a, b) => a - b);
            const med = areas[Math.floor(areas.length / 2)];
            kept = kept.filter(hh => (hh[2] * hh[3]) >= 0.55 * med);
        }
        if (kept.length !== total) return null;
        kept.sort((a, b) => (a[1] - b[1]) || (a[0] - b[0]));
        const k = fw / aw;
        return { fw, fh, holes: kept.map(hh => [Math.round(hh[0] * k), Math.round(hh[1] * k), Math.round(hh[2] * k), Math.round(hh[3] * k)]) };
    }

    function selectedTemplate() {
        return TEMPLATES.find(t => String(t.id) === String(state.templateId)) || null;
    }

    const LAYOUTS = {
        '2x2': { cols: 2, rows: 2, total: 4 },
        '2x3': { cols: 2, rows: 3, total: 6 },
        'receipt': { cols: 2, rows: 2, total: 4 }, // 2 strip kembar, tiap strip 1 kolom × 2 foto
        'receipt_2x3': { cols: 2, rows: 3, total: 6 }, // 2 strip kembar, tiap strip 1 kolom × 3 foto
    };

    // Foto mengikuti lubang desain (bukan grid baku) bila tersedia:
    // 1) slots presisi dari database, 2) hasil ukur otomatis gambarnya.
    // Syarat: jumlah lubang harus sama dengan total slot layout aktif.
    function slottedTemplate() {
        const tpl = selectedTemplate();
        if (!tpl || !LAYOUTS[state.layout]) return null;
        const total = LAYOUTS[state.layout].total;
        if (tpl.slots && Array.isArray(tpl.slots.holes) && tpl.slots.fw && tpl.slots.fh
            && tpl.slots.holes.length === total) return tpl.slots;
        const key = tpl.id + '_' + total;
        if (!(key in detectedCache)) {
            const img = (tpl.frame_url && frameCache[tpl.id]) || (tpl.background_url && backgroundCache[tpl.id]) || null;
            detectedCache[key] = (img && img.complete && img.naturalWidth) ? detectHoles(img, total) : null;
        }
        return detectedCache[key];
    }

    function requiredTotal() {
        const s = slottedTemplate();
        return s ? s.holes.length : LAYOUTS[state.layout].total;
    }

    // ===== Mesin gambar strip (dipakai preview & file simpanan) =====
    // Pola barcode deterministik agar preview dan hasil simpan identik
    const BARCODE_BARS = (() => {
        let seed = 20260915;
        const rnd = () => (seed = (seed * 1103515245 + 12345) & 0x7fffffff) / 0x7fffffff;
        const bars = [];
        for (let i = 0; i < 64; i++) bars.push(1 + Math.floor(rnd() * 4));
        return bars;
    })();

    function isReceipt() { return state.builtin === 'receipt'; }

    // Geometri strip untuk lebar W (s = skala, preview 600px / simpan 900px)
    function geomFor(W) {
        const slots = slottedTemplate();
        if (slots) {
            // Kanvas mengikuti rasio asli frame (1200x1800) agar overlay
            // tidak melar. Foto sedikit DILEBARKAN (+3px) bila ada frame
            // overlay di atasnya (tepi terselip di bawah bingkai), tapi
            // DIKECILKAN (-3px) bila tanpa frame agar tidak menutupi
            // border milik desain background.
            const k = W / slots.fw;
            const hasFrame = !!(selectedTemplate() && selectedTemplate().frame_url);
            const pad = hasFrame ? 3 : -3;
            const holes = slots.holes.map(h => ({
                x: (h[0] - pad) * k,
                y: (h[1] - pad) * k,
                w: (h[2] + pad * 2) * k,
                h: (h[3] + pad * 2) * k,
            }));
            // radius sudut mengikuti lubang desain (px frame → px kanvas);
            // hasil ukur otomatis (tanpa radius) pakai default 12px frame.
            const radius = (slots.radius != null ? slots.radius : 12) * k;
            return { slotted: true, s: k, holes, radius, H: slots.fh * k };
        }
        const s = W / 600;
        if (state.layout === 'receipt' || state.layout === 'receipt_2x3') {
            // Dua strip struk kembar kiri-kanan; tiap strip 1 kolom × N foto
            const rows = LAYOUTS[state.layout].rows;
            const pad = 16 * s, gutter = 24 * s, ip = 14 * s, gap = 12 * s;
            const colW = (W - pad * 2 - gutter) / 2;
            const cellW = colW - ip * 2;
            const cellH = cellW * 0.75;
            const headerH = 250 * s, footerH = 150 * s;
            const H = pad + headerH + cellH * rows + gap * (rows - 1) + footerH + pad;
            return { double: true, cols: 2, rows, s, pad, gutter, ip, gap, colW, cellW, cellH, headerH, footerH, radius: 3 * s, H };
        }
        const { cols, rows } = LAYOUTS[state.layout];
        const pad = 20 * s, gap = 12 * s;
        const cellW = (W - pad * 2 - gap * (cols - 1)) / cols;
        const cellH = cellW * 0.75;
        const headerH = 110 * s, footerH = 90 * s;
        const H = headerH + rows * cellH + (rows - 1) * gap + footerH + pad * 2;
        return { double: false, cols, rows, s, pad, gap, cellW, cellH, headerH, footerH, radius: 12 * s, H };
    }

    function dashedLine(ctx, x1, x2, y, s) {
        ctx.save();
        ctx.strokeStyle = '#1C1C1C';
        ctx.lineWidth = Math.max(1, 1.5 * s);
        ctx.setLineDash([6 * s, 4 * s]);
        ctx.beginPath();
        ctx.moveTo(x1, y);
        ctx.lineTo(x2, y);
        ctx.stroke();
        ctx.restore();
    }

    // Kecilkan font sampai teks muat di maxW (jamin tidak meluber keluar kolom)
    function fitFont(ctx, text, maxW, startPx, family, weight) {
        let px = startPx;
        const apply = (p) => { ctx.font = (weight ? weight + ' ' : '') + p + 'px ' + family; };
        apply(px);
        while (px > 6 && ctx.measureText(text).width > maxW) {
            px -= 1;
            apply(px);
        }
        return px;
    }

    // Kop satu kolom struk (dipakai tiap strip kembar kiri & kanan)
    function paintReceiptHead(ctx, x0, colW, topY, g) {
        const s = g.s, ip = g.ip, cx = x0 + colW / 2;
        const qtyX = x0 + ip + 4 * s;          // awal kolom QTY
        const itemX = x0 + ip + 82 * s;        // awal kolom ITEM
        const rightEdge = x0 + colW - ip - 4 * s;
        const qtyCX = qtyX + 34 * s;           // tengah kolom QTY
        ctx.fillStyle = '#111';
        ctx.textAlign = 'center';
        fitFont(ctx, '*****RECEIPT*****', colW - ip * 2 - 4 * s, 40 * s, '"Playfair Display", Georgia, serif', '800');
        ctx.fillText('*****RECEIPT*****', cx, topY + 46 * s);
        dashedLine(ctx, x0 + ip, x0 + colW - ip, topY + 62 * s, s);
        ctx.font = (22 * s) + 'px "Courier New", monospace';
        ctx.fillText('QTY', qtyCX, topY + 88 * s);
        ctx.textAlign = 'left';
        ctx.fillText('ITEM', itemX, topY + 88 * s);
        ctx.textAlign = 'center';
        dashedLine(ctx, x0 + ip, x0 + colW - ip, topY + 102 * s, s);
        const rows = [
            ['7', 'HOURS A DAY THINKING ABOUT YOU'],
            ['24', 'DAYS IN A WEEK'],
            ['365', 'TIRED ALL THE TIME'],
        ];
        rows.forEach((r, i) => {
            const y = topY + (132 + i * 34) * s;
            fitFont(ctx, r[1], rightEdge - itemX, 19 * s, '"Courier New", monospace', '');
            ctx.textAlign = 'center';
            ctx.fillText(r[0], qtyCX, y);
            ctx.textAlign = 'left';
            ctx.fillText(r[1], itemX, y);
        });
        ctx.textAlign = 'center';
    }

    // Latar + kop: cream HOLD' MOMENT atau kertas struk (tunggal/ganda)
    function paintChrome(ctx, W, g) {
        const s = g.s, pad = g.pad, cx = W / 2;
        ctx.fillStyle = g.double ? '#FBFAF6' : '#F6F4EE';
        ctx.fillRect(0, 0, W, g.H);
        if (g.double) {
            for (let k = 0; k < 2; k++) {
                paintReceiptHead(ctx, g.pad + k * (g.colW + g.gutter), g.colW, g.pad, g);
            }
            return;
        }
        ctx.fillStyle = '#1C1C1C';
        ctx.font = '800 ' + (34 * s) + 'px "Playfair Display", Georgia, serif';
        ctx.textAlign = 'center';
        ctx.fillText("HOLD' MOMENT", cx, 55 * s);
        ctx.fillStyle = '#D95B32';
        ctx.font = '600 ' + (14 * s) + 'px "Plus Jakarta Sans", sans-serif';
        ctx.fillText('• • •  PHOTOSTRIP  • • •', cx, 80 * s);
    }

    // Satu foto cover-fit ke sel
    function drawCover(ctx, im, x, y, w, h, r) {
        const ir = im.width / im.height, cr = w / h;
        let sw, sh, sx, sy;
        if (ir > cr) { sh = im.height; sw = sh * cr; sx = (im.width - sw) / 2; sy = 0; }
        else { sw = im.width; sh = sw / cr; sx = 0; sy = (im.height - sh) / 2; }
        ctx.save();
        ctx.beginPath();
        ctx.roundRect(x, y, w, h, r);
        ctx.clip();
        ctx.drawImage(im, sx, sy, sw, sh, x, y, w, h);
        ctx.restore();
    }

    // Sel foto — strip ganda: tiap strip 1 kolom × g.rows foto; slot kosong = kertas
    function paintPhotos(ctx, g, imgs) {
        if (g.double) {
            for (let k = 0; k < 2; k++) {
                for (let j = 0; j < g.rows; j++) {
                    const im = imgs[k * g.rows + j];
                    if (!im) continue;
                    const x = g.pad + k * (g.colW + g.gutter) + g.ip;
                    const y = g.pad + g.headerH + j * (g.cellH + g.gap);
                    drawCover(ctx, im, x, y, g.cellW, g.cellH, g.radius);
                }
            }
            return;
        }
        imgs.forEach((im, i) => {
            if (!im) return;
            const c = i % g.cols, r = Math.floor(i / g.cols);
            const x = g.pad + c * (g.cellW + g.gap);
            const y = g.headerH + r * (g.cellH + g.gap);
            drawCover(ctx, im, x, y, g.cellW, g.cellH, g.radius);
        });
    }

    function paintBarcode(ctx, cx, y, h, s, maxW) {
        const sum = BARCODE_BARS.reduce((a, b) => a + b, 0) + BARCODE_BARS.length - 1;
        let unit = 2.2 * s;
        if (maxW) unit = Math.min(unit, maxW / sum);
        const totalW = sum * unit;
        let x = cx - totalW / 2;
        ctx.fillStyle = '#111';
        BARCODE_BARS.forEach(w => {
            ctx.fillRect(x, y, w * unit, h);
            x += (w + 1) * unit;
        });
    }

    // Kaki strip: biodata pengunjung atau THANK YOU + barcode tiap strip kembar
    function paintFooter(ctx, W, g) {
        const s = g.s, cx = W / 2, H = g.H;
        ctx.textAlign = 'center';
        if (g.double) {
            for (let k = 0; k < 2; k++) {
                const ccx = g.pad + k * (g.colW + g.gutter) + g.colW / 2;
                const fy = g.pad + g.headerH + g.cellH * g.rows + g.gap * (g.rows - 1);
                ctx.fillStyle = '#111';
                fitFont(ctx, '*******THANK YOU!*******', g.colW - g.ip * 2 - 4 * s, 22 * s, '"Courier New", monospace', '');
                ctx.fillText('*******THANK YOU!*******', ccx, fy + 30 * s);
                paintBarcode(ctx, ccx, fy + 44 * s, 62 * s, s, g.colW - g.ip * 2);
                ctx.fillStyle = '#737373';
                ctx.font = '500 ' + (14 * s) + 'px "Plus Jakarta Sans", sans-serif';
                ctx.fillText((state.visitor.name || 'Pengunjung') + ' • ' + (state.visitor.social || '@sosmed'), ccx, fy + 128 * s);
            }
            return;
        }
        ctx.fillStyle = '#1C1C1C';
        ctx.font = '700 ' + (20 * s) + 'px "Plus Jakarta Sans", sans-serif';
        ctx.fillText(state.visitor.name || 'Pengunjung', cx, H - 55 * s);
        ctx.fillStyle = '#737373';
        ctx.font = '500 ' + (15 * s) + 'px "Plus Jakarta Sans", sans-serif';
        ctx.fillText((state.visitor.social || '@sosmed') + '  •  ' + new Date().toLocaleDateString('id-ID'), cx, H - 30 * s);
    }

    // Frame PNG upload-an admin (digambar paling atas bila ada & cocok)
    function paintFrameOverlay(ctx, W, H) {
        const fr = state.templateId ? frameCache[state.templateId] : null;
        if (fr && fr.complete && fr.naturalWidth) {
            ctx.drawImage(fr, 0, 0, W, H);
        }
    }

    function paintStrip(canvas, W, imgs) {
        const g = geomFor(W);
        canvas.width = W;
        canvas.height = g.H;
        const ctx = canvas.getContext('2d');
        if (g.slotted) {
            // Background digambar utuh mengikuti rasio aslinya (tidak melar);
            // frame transparan menutupi penuh kecuali lubang; keduanya opsional.
            // Tiap foto digambar tepat di lubangnya (milik background kotak
            // putih/hitam maupun lubang transparan frame).
            const bg = state.templateId ? backgroundCache[state.templateId] : null;
            if (bg && bg.complete && bg.naturalWidth) {
                ctx.drawImage(bg, 0, 0, W, g.H);
            } else {
                ctx.fillStyle = '#1C1C1C';
                ctx.fillRect(0, 0, W, g.H);
            }
            imgs.forEach((im, i) => {
                const hole = g.holes[i];
                if (!im || !hole) return;
                drawCover(ctx, im, hole.x, hole.y, hole.w, hole.h, g.radius);
            });
            paintFrameOverlay(ctx, W, g.H);
            return;
        }
        const bg = state.templateId ? backgroundCache[state.templateId] : null;
        const hasBg = bg && bg.complete && bg.naturalWidth;
        if (hasBg) {
            // Background upload admin — 100% persis seperti file (pixel sky, dll.) cover seluruh strip
            ctx.drawImage(bg, 0, 0, W, g.H);
        } else {
            paintChrome(ctx, W, g);
        }
        paintPhotos(ctx, g, imgs);
        if (!hasBg) paintFooter(ctx, W, g);
        // Jika pakai background, visitor name tidak digambar ulang agar 100% sesuai file upload
        // (data tetap tersimpan di DB dan tampil di halaman result/admin)
        paintFrameOverlay(ctx, W, g.H);
    }

    const loadShot = (src) => new Promise((res) => {
        const im = new Image();
        im.onload = () => res(im);
        im.onerror = () => res(null);
        im.src = src;
    });

    function drawStripPreview() {
        // Geometri dihitung ulang saat gambar selesai dimuat (layout bisa berubah)
        Promise.all(state.shots.map(loadShot)).then((imgs) => paintStrip(stripPreview, 600, imgs));
    }

    function fullResStrip() {
        // 900px + JPEG 0.75 → ~400KB, aman untuk Railway (limit 4MB)
        return Promise.all(state.shots.map(loadShot)).then((imgs) => {
            const cv = document.createElement('canvas');
            paintStrip(cv, 900, imgs);
            return cv.toDataURL('image/jpeg', 0.75);
        });
    }
