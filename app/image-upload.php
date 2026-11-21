<?php
// Validate an uploaded produce photo and save it with a random filename.
function saveProductImage(array $file): string {
    // Reject incomplete uploads and files larger than 3 MB.
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('The photo could not be uploaded.');
    }
    if (($file['size'] ?? 0) < 1 || $file['size'] > 3 * 1024 * 1024) {
        throw new RuntimeException('Choose a photo smaller than 3 MB.');
    }
    if (!is_uploaded_file($file['tmp_name'] ?? '')) {
        throw new RuntimeException('Invalid photo upload.');
    }
    // Check the actual file contents instead of trusting the filename or browser.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($extensions[$mime]) || @getimagesize($file['tmp_name']) === false) {
        throw new RuntimeException('Only JPEG, PNG, and WebP photos are allowed.');
    }
    // Store uploaded photos separately from PHP application files.
    $directory = dirname(__DIR__) . '/public/uploads/products';
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException('The photo folder could not be created.');
    }
    $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
    if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $filename)) {
        throw new RuntimeException('The photo could not be saved.');
    }
    // Store a relative URL in the existing products.image_path column.
    return '/uploads/products/' . $filename;
}

// Delete only images stored in the managed product photo folder.
function removeProductImage(?string $path): void {
    if (!$path || !preg_match('~^/uploads/products/[a-f0-9]{32}\.(jpg|png|webp)$~', $path)) return;
    $file = dirname(__DIR__) . '/public' . $path;
    if (is_file($file)) @unlink($file);
}

// Display only approved product photo paths in browser markup.
function productImageUrl(?string $path): ?string {
    return $path && preg_match('~^/uploads/products/[a-f0-9]{32}\.(jpg|png|webp)$~', $path) ? $path : null;
}
