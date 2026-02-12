<?php
/**
 * CMS Admin - Helper Functions
 * CSRF tokens, flash messages, pagination, utilities
 */

// ============================================
// CSRF Protection
// ============================================

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

function csrf_meta(): string {
    return '<meta name="csrf-token" content="' . htmlspecialchars(csrf_token()) . '">';
}

function verify_csrf(string $token = null): bool {
    $token = $token ?? ($_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

function require_csrf() {
    if (!verify_csrf()) {
        if (is_ajax()) {
            json_response(['error' => 'Invalid security token. Please refresh the page.'], 403);
        }
        set_flash('error', 'Invalid security token. Please try again.');
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin'));
        exit;
    }
}

// ============================================
// Flash Messages
// ============================================

function set_flash(string $type, string $message) {
    $_SESSION['flash_messages'][] = ['type' => $type, 'message' => $message];
}

function get_flash_messages(): array {
    $messages = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);
    return $messages;
}

function render_flash_messages(): string {
    $messages = get_flash_messages();
    if (empty($messages)) return '';

    $html = '';
    foreach ($messages as $msg) {
        $bgClass = match ($msg['type']) {
            'success' => 'bg-green-500/10 border-green-500/30 text-green-400',
            'error'   => 'bg-red-500/10 border-red-500/30 text-red-400',
            'warning' => 'bg-yellow-500/10 border-yellow-500/30 text-yellow-400',
            default   => 'bg-blue-500/10 border-blue-500/30 text-blue-400',
        };
        $icon = match ($msg['type']) {
            'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
            'error'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
            'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>',
            default   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        };

        $html .= '<div class="flash-message ' . $bgClass . ' border rounded-xl p-4 mb-4 flex items-center gap-3" data-flash>';
        $html .= '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">' . $icon . '</svg>';
        $html .= '<span class="text-sm">' . htmlspecialchars($msg['message']) . '</span>';
        $html .= '<button onclick="this.parentElement.remove()" class="ml-auto text-white/40 hover:text-white/80 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>';
        $html .= '</div>';
    }
    return $html;
}

// ============================================
// Request Helpers
// ============================================

function is_ajax(): bool {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function json_response(array $data, int $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function redirect(string $url, string $flashType = null, string $flashMessage = null) {
    if ($flashType && $flashMessage) {
        set_flash($flashType, $flashMessage);
    }
    header('Location: ' . $url);
    exit;
}

// ============================================
// Pagination
// ============================================

function paginate(PDO $pdo, string $countQuery, string $dataQuery, array $params = [], int $perPage = 20): array {
    $page = max(1, (int)($_GET['page'] ?? 1));
    $offset = ($page - 1) * $perPage;

    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute($params);
    $total = (int)$countStmt->fetchColumn();

    $dataStmt = $pdo->prepare($dataQuery . " LIMIT {$perPage} OFFSET {$offset}");
    $dataStmt->execute($params);
    $items = $dataStmt->fetchAll();

    return [
        'items'       => $items,
        'total'       => $total,
        'page'        => $page,
        'per_page'    => $perPage,
        'total_pages' => max(1, ceil($total / $perPage)),
    ];
}

function render_pagination(int $currentPage, int $totalPages, string $baseUrl = ''): string {
    if ($totalPages <= 1) return '';

    $separator = strpos($baseUrl, '?') !== false ? '&' : '?';
    $html = '<nav class="flex items-center justify-center gap-2 mt-8">';

    // Previous
    if ($currentPage > 1) {
        $html .= '<a href="' . $baseUrl . $separator . 'page=' . ($currentPage - 1) . '" class="px-3 py-2 rounded-lg bg-dark-300 text-white/60 hover:text-white hover:bg-dark-400 transition text-sm">&laquo; Prev</a>';
    }

    // Page numbers
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);

    if ($start > 1) {
        $html .= '<a href="' . $baseUrl . $separator . 'page=1" class="px-3 py-2 rounded-lg text-white/60 hover:text-white text-sm">1</a>';
        if ($start > 2) $html .= '<span class="text-white/30 px-1">...</span>';
    }

    for ($i = $start; $i <= $end; $i++) {
        if ($i === $currentPage) {
            $html .= '<span class="px-3 py-2 rounded-lg bg-primary text-dark font-semibold text-sm">' . $i . '</span>';
        } else {
            $html .= '<a href="' . $baseUrl . $separator . 'page=' . $i . '" class="px-3 py-2 rounded-lg text-white/60 hover:text-white hover:bg-dark-400 transition text-sm">' . $i . '</a>';
        }
    }

    if ($end < $totalPages) {
        if ($end < $totalPages - 1) $html .= '<span class="text-white/30 px-1">...</span>';
        $html .= '<a href="' . $baseUrl . $separator . 'page=' . $totalPages . '" class="px-3 py-2 rounded-lg text-white/60 hover:text-white text-sm">' . $totalPages . '</a>';
    }

    // Next
    if ($currentPage < $totalPages) {
        $html .= '<a href="' . $baseUrl . $separator . 'page=' . ($currentPage + 1) . '" class="px-3 py-2 rounded-lg bg-dark-300 text-white/60 hover:text-white hover:bg-dark-400 transition text-sm">Next &raquo;</a>';
    }

    $html .= '</nav>';
    return $html;
}

// ============================================
// String Helpers
// ============================================

function slugify(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

function truncate(string $text, int $length = 100): string {
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . '...';
}

function e(string $text): string {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
