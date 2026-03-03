<?php
// CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

$uri   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', trim($uri, '/'));
$base  = $parts[0] ?? '';
$sub   = $parts[1] ?? '';

// Route API calls
if ($base === 'api') {
    header('Content-Type: application/json');
    match ($sub) {
        'auth'  => require __DIR__ . '/auth/index.php',
        'plan'  => require __DIR__ . '/api/plan.php',
        'admin' => require __DIR__ . '/admin/index.php',
        default => (function(){ http_response_code(404); echo json_encode(['ok'=>false,'msg'=>'API endpoint not found']); exit; })(),
    };
}

// Serve the SPA for all other routes
require __DIR__ . '/app.php';
