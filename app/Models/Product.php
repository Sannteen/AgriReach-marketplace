<?php
// Keep produce database queries in the Product model.
class ProductModel {
    // Show the newest available produce on the homepage.
    public static function featured(): array {
        return db()->query("SELECT p.*, u.full_name AS farmer FROM products p JOIN users u ON u.id = p.farmer_id WHERE p.status = 'available' ORDER BY p.created_at DESC LIMIT 3")->fetchAll();
    }
    // Search produce safely using prepared parameters supplied by the controller.
    public static function search(string $search, string $category): array {
        $sql = "SELECT p.*, u.full_name AS farmer FROM products p JOIN users u ON u.id = p.farmer_id WHERE p.status = 'available'";
        $params = [];
        if ($search !== '') { $sql .= ' AND (p.name LIKE ? OR p.description LIKE ? OR u.full_name LIKE ?)'; $term = '%' . $search . '%'; $params = [$term, $term, $term]; }
        if ($category !== '') { $sql .= ' AND p.category = ?'; $params[] = $category; }
        $sql .= ' ORDER BY p.created_at DESC LIMIT 100';
        $stmt = db()->prepare($sql); $stmt->execute($params); return $stmt->fetchAll();
    }
    // Find one publicly available listing and its farmer's contact details.
    public static function publicById(int $id): array|false {
        $stmt = db()->prepare("SELECT p.*, u.full_name AS farmer, u.email AS farmer_email, u.phone AS farmer_phone FROM products p JOIN users u ON u.id = p.farmer_id WHERE p.id = ? AND p.status = 'available' LIMIT 1");
        $stmt->execute([$id]); return $stmt->fetch();
    }
    // Count a farmer's total and available listings.
    public static function farmerStats(int $farmerId): array {
        $stmt = db()->prepare("SELECT COUNT(*) AS total, SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) AS available FROM products WHERE farmer_id = ?");
        $stmt->execute([$farmerId]); return $stmt->fetch();
    }
    // List only the products belonging to the logged-in farmer.
    public static function farmerProducts(int $farmerId): array {
        $stmt = db()->prepare('SELECT * FROM products WHERE farmer_id = ? ORDER BY created_at DESC');
        $stmt->execute([$farmerId]); return $stmt->fetchAll();
    }
    // Check ownership before allowing a farmer to edit or delete a listing.
    public static function ownedById(int $id, int $farmerId): array|false {
        $stmt = db()->prepare('SELECT * FROM products WHERE id = ? AND farmer_id = ? LIMIT 1');
        $stmt->execute([$id, $farmerId]); return $stmt->fetch();
    }
    // Save a new product listing with its optional image path.
    public static function create(int $farmerId, string $name, string $description, string $category, string $price, string $unit, string $quantity, int $delivery, int $pickup, string $status, ?string $imagePath): void {
        $stmt = db()->prepare('INSERT INTO products (farmer_id, name, description, category, price, unit, quantity, delivery_available, pickup_available, status, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$farmerId, $name, $description, $category, $price, $unit, $quantity, $delivery, $pickup, $status, $imagePath]);
    }
    // Update a product only when the logged-in farmer owns it.
    public static function update(int $id, int $farmerId, string $name, string $description, string $category, string $price, string $unit, string $quantity, int $delivery, int $pickup, string $status, ?string $imagePath): void {
        $stmt = db()->prepare('UPDATE products SET name=?, description=?, category=?, price=?, unit=?, quantity=?, delivery_available=?, pickup_available=?, status=?, image_path=? WHERE id=? AND farmer_id=?');
        $stmt->execute([$name, $description, $category, $price, $unit, $quantity, $delivery, $pickup, $status, $imagePath, $id, $farmerId]);
    }
    // Delete a product using its ID, and optionally restrict deletion to its owner.
    public static function delete(int $id, ?int $farmerId = null): bool {
        $stmt = $farmerId === null ? db()->prepare('DELETE FROM products WHERE id = ?') : db()->prepare('DELETE FROM products WHERE id = ? AND farmer_id = ?');
        $stmt->execute($farmerId === null ? [$id] : [$id, $farmerId]); return $stmt->rowCount() > 0;
    }
    // Find a product's photo path before an administrator deletes it.
    public static function imageById(int $id): array|false {
        $stmt = db()->prepare('SELECT image_path FROM products WHERE id = ?'); $stmt->execute([$id]); return $stmt->fetch();
    }
    // Count all listings and available listings for the administrator dashboard.
    public static function adminCounts(): array {
        return ['products' => (int)db()->query('SELECT COUNT(*) FROM products')->fetchColumn(), 'available' => (int)db()->query("SELECT COUNT(*) FROM products WHERE status = 'available'")->fetchColumn()];
    }
    // Show the most recent listings to administrators.
    public static function adminRecent(): array {
        return db()->query('SELECT p.id, p.name, p.category, p.price, p.status, p.created_at, u.full_name AS farmer FROM products p JOIN users u ON u.id = p.farmer_id ORDER BY p.created_at DESC LIMIT 100')->fetchAll();
    }
}
