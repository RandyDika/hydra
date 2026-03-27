<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/report_template.php';

hydra_cleanup_tmp();

$data = hydra_collect_request_data($_POST);
$token = hydra_save_payload($data);
$mode = (($_GET['mode'] ?? 'inline') === 'download') ? 'download' : 'inline';

// 🔥 render HTML langsung (TANPA URL)
$html = hydra_render_report($data);

// simpan HTML sementara
$tmpHtml = hydra_tmp_dir() . '/report-' . $token . '.html';
file_put_contents($tmpHtml, $html);

// output PDF
$tmpPdf = hydra_tmp_dir() . '/report-' . $token . '.pdf';

$nodeScript = __DIR__ . '/generate-pdf.js';
$nodeBin = getenv('NODE_BIN') ?: 'node';

// jalankan puppeteer (pakai FILE, bukan URL)
$cmd = escapeshellcmd($nodeBin) . ' '
    . escapeshellarg($nodeScript) . ' '
    . escapeshellarg($tmpHtml) . ' '
    . escapeshellarg($tmpPdf);

exec($cmd . ' 2>&1', $output, $exitCode);

// 🔥 HANDLE ERROR
if ($exitCode !== 0 || !file_exists($tmpPdf)) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');

    echo "Gagal membuat PDF dengan Puppeteer.\n\n";
    echo "Output error:\n" . implode("\n", $output);

    exit;
}

// 🔥 OUTPUT PDF
$filename = 'report-' . preg_replace('/[^a-zA-Z0-9\-_]+/', '-', strtolower($data['student']['name'] ?: 'hydra')) . '.pdf';

header('Content-Type: application/pdf');
header('Content-Length: ' . filesize($tmpPdf));
header('Content-Disposition: ' . ($mode === 'download' ? 'attachment' : 'inline') . '; filename="' . $filename . '"');

readfile($tmpPdf);

// cleanup
@unlink($tmpPdf);
@unlink($tmpHtml);

exit;