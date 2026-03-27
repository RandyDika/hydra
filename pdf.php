<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

hydra_cleanup_tmp();

$data = hydra_collect_request_data($_POST);
$token = hydra_save_payload($data);
$mode = (($_GET['mode'] ?? 'inline') === 'download') ? 'download' : 'inline';

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? '127.0.0.1';
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
$reportUrl = $scheme . '://' . $host . ($basePath === '' ? '' : $basePath) . '/report.php?token=' . urlencode($token);

$tmpPdf = hydra_tmp_dir() . '/report-' . $token . '.pdf';
$nodeScript = __DIR__ . '/generate-pdf.js';
$nodeBin = getenv('NODE_BIN') ?: 'node';

$cmd = escapeshellcmd($nodeBin) . ' ' . escapeshellarg($nodeScript) . ' ' . escapeshellarg($reportUrl) . ' ' . escapeshellarg($tmpPdf);
exec($cmd . ' 2>&1', $output, $exitCode);

if ($exitCode !== 0 || !file_exists($tmpPdf)) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "Gagal membuat PDF dengan Puppeteer.\n\n";
    echo "Periksa hal berikut:\n";
    echo "1. Node.js sudah terinstall\n";
    echo "2. Jalankan npm install\n";
    echo "3. Bila perlu set lokasi node dengan env NODE_BIN\n\n";
    echo "Output error:\n" . implode("\n", $output);
    exit;
}

$filename = 'report-' . preg_replace('/[^a-zA-Z0-9\-_]+/', '-', strtolower($data['student']['name'] ?: 'hydra')) . '.pdf';
header('Content-Type: application/pdf');
header('Content-Length: ' . filesize($tmpPdf));
header('Content-Disposition: ' . ($mode === 'download' ? 'attachment' : 'inline') . '; filename="' . $filename . '"');
readfile($tmpPdf);
@unlink($tmpPdf);
exit;
