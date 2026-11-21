<?php
// Render a view with the shared Bootstrap header and footer.
function renderView(string $view, array $data = []): void {
    // Prevent view paths from escaping the application Views folder.
    if (!preg_match('~^[a-zA-Z0-9_/-]+$~', $view)) { throw new InvalidArgumentException('Invalid view name.'); }
    $file = dirname(__DIR__) . '/Views/' . $view . '.php';
    if (!is_file($file)) { throw new RuntimeException('View not found.'); }
    // Make controller values available to the HTML template.
    extract($data, EXTR_SKIP);
    require dirname(__DIR__) . '/Views/layouts/header.php';
    require $file;
    require dirname(__DIR__) . '/Views/layouts/footer.php';
}
