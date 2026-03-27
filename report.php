<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/report_template.php';

hydra_cleanup_tmp();
$token = (string)($_GET['token'] ?? '');
$data = $token !== '' ? hydra_load_payload($token) : hydra_default_data();

echo hydra_render_report($data);
