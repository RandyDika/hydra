<?php

declare(strict_types=1);

function hydra_sections(): array
{
    $basicIndicators = [
        'Mampu melakukan kicking dengan kaki lurus, ritme stabil, dan menjaga posisi kaki tetap pada garis tubuh sesuai gaya.',
        'Menjaga posisi tubuh tetap streamline dan stabil sepanjang renangan.',
        'Melakukan tarikan tangan yang efisien, panjang, dan terkoordinasi dengan baik.',
        'Mengambil napas dengan ritme yang tepat tanpa mengganggu koordinasi gerak atau kecepatan renang.',
        'Menyelesaikan renang dengan sentuhan tangan yang tepat waktu dan tanpa memperlambat kecepatan mendekati dinding.',
    ];

    return [
        'freestyle' => [
            'title' => 'Gaya Bebas / Freestyle',
            'indicators' => array_map(fn(string $text) => ['text' => $text, 'html' => false], $basicIndicators),
        ],
        'backstroke' => [
            'title' => 'Gaya Punggung / Backstroke',
            'indicators' => array_map(fn(string $text) => ['text' => $text, 'html' => false], $basicIndicators),
        ],
        'breaststroke' => [
            'title' => 'Gaya Dada / Breaststroke',
            'indicators' => array_map(fn(string $text) => ['text' => $text, 'html' => false], $basicIndicators),
        ],
        'fly' => [
            'title' => 'Gaya Kupu / Fly',
            'indicators' => array_map(fn(string $text) => ['text' => $text, 'html' => false], $basicIndicators),
        ],
        'additional' => [
            'title' => 'Skill Tambahan',
            'indicators' => [
                [
                    'text' => '<strong>Underwater</strong><br>Melakukan dorongan awal dengan streamline kuat dan kontrol jarak underwater yang konsisten.',
                    'html' => true,
                ],
                [
                    'text' => '<strong>Flip / Open Turn</strong><br>Mampu melakukan putaran dengan cepat, rapat, dan kembali ke streamline dengan menjaga momentum.',
                    'html' => true,
                ],
                [
                    'text' => '<strong>Jump / Start</strong><br>Melakukan start dengan loncatan stabil, masuk air streamline, dan minim splash.',
                    'html' => true,
                ],
            ],
        ],
    ];
}

function hydra_score_legend(): array
{
    return [
        ['score' => 1, 'label' => 'Belum Bisa', 'description' => 'Anak masih kesulitan memahami atau melakukan gerakan.'],
        ['score' => 2, 'label' => 'Perlu Latihan', 'description' => 'Gerakan sudah dicoba, tapi belum konsisten atau masih salah posisi.'],
        ['score' => 3, 'label' => 'Cukup Baik', 'description' => 'Gerakan sudah sesuai teknik dasar, masih perlu perbaikan kecil.'],
        ['score' => 4, 'label' => 'Sangat Baik', 'description' => 'Gerakan dilakukan dengan benar, ritme stabil, dan percaya diri.'],
    ];
}

function hydra_default_data(): array
{
    return [
        'student' => [
            'name' => 'ASKA',
            'class' => 'BASIC',
            'month' => 'NOVEMBER 2025',
        ],
        'scores' => [
            'freestyle' => [1 => 3, 2 => 3, 3 => 3, 4 => 3, 5 => 3],
            'backstroke' => [1 => '', 2 => '', 3 => '', 4 => '', 5 => ''],
            'breaststroke' => [1 => '', 2 => '', 3 => '', 4 => '', 5 => ''],
            'fly' => [1 => '', 2 => '', 3 => '', 4 => '', 5 => ''],
            'additional' => [1 => 2, 2 => 3, 3 => 3],
        ],
        'remarks' => [
            'freestyle' => [1 => 'Kadang nekuk', 2 => '', 3 => '', 4 => '', 5 => ''],
            'backstroke' => [1 => '', 2 => '', 3 => '', 4 => '', 5 => ''],
            'breaststroke' => [1 => '', 2 => '', 3 => '', 4 => '', 5 => ''],
            'fly' => [1 => '', 2 => '', 3 => '', 4 => '', 5 => ''],
            'additional' => [1 => 'Masih perlu dorongan lebih kuat', 2 => '', 3 => ''],
        ],
        'coach_notes' => "1. Sangat aktif dan berani\n2. Berani jump\n3. Kicknya kuat",
    ];
}

function hydra_collect_request_data(array $source): array
{
    $data = hydra_default_data();
    $sections = hydra_sections();

    $data['student']['name'] = trim((string)($source['student_name'] ?? $data['student']['name']));
    $data['student']['class'] = trim((string)($source['student_class'] ?? $data['student']['class']));
    $data['student']['month'] = trim((string)($source['student_month'] ?? $data['student']['month']));
    $data['coach_notes'] = trim((string)($source['coach_notes'] ?? $data['coach_notes']));

    foreach ($sections as $sectionKey => $section) {
        foreach ($section['indicators'] as $index => $_indicator) {
            $rowNo = $index + 1;
            $score = $source['scores'][$sectionKey][$rowNo] ?? $data['scores'][$sectionKey][$rowNo] ?? '';
            $remark = $source['remarks'][$sectionKey][$rowNo] ?? $data['remarks'][$sectionKey][$rowNo] ?? '';

            $score = in_array((string)$score, ['1', '2', '3', '4'], true) ? (int)$score : '';
            $remark = trim((string)$remark);

            $data['scores'][$sectionKey][$rowNo] = $score;
            $data['remarks'][$sectionKey][$rowNo] = $remark;
        }
    }

    return $data;
}

function hydra_h(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function hydra_logo_src(): ?string
{
    $path = __DIR__ . '/logo.png';

    if (!file_exists($path)) {
        return null;
    }

    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);

    return 'data:image/' . $type . ';base64,' . base64_encode($data);
}

function hydra_tmp_dir(): string
{
    $dir = __DIR__ . '/tmp';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    return $dir;
}

function hydra_save_payload(array $data): string
{
    $token = bin2hex(random_bytes(16));
    $path = hydra_tmp_dir() . '/' . $token . '.json';
    file_put_contents($path, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    return $token;
}

function hydra_load_payload(string $token): array
{
    if (!preg_match('/^[a-f0-9]{32}$/', $token)) {
        return hydra_default_data();
    }

    $path = hydra_tmp_dir() . '/' . $token . '.json';
    if (!file_exists($path)) {
        return hydra_default_data();
    }

    $json = file_get_contents($path);
    $data = json_decode((string)$json, true);
    return is_array($data) ? $data : hydra_default_data();
}

function hydra_cleanup_tmp(int $maxAgeSeconds = 3600): void
{
    $dir = hydra_tmp_dir();
    foreach (glob($dir . '/*') ?: [] as $file) {
        if (is_file($file) && (time() - filemtime($file) > $maxAgeSeconds)) {
            @unlink($file);
        }
    }
}
