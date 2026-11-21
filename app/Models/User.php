<?php
// Keep account database queries in the User model.
class UserModel {
    // Find an account by email when someone logs in.
    public static function byEmail(string $email): array|false {
        $stmt = db()->prepare('SELECT id, full_name, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]); return $stmt->fetch();
    }
    // Create a buyer or farmer account with an already-hashed password.
    public static function create(string $name, string $email, string $hash, ?string $phone, string $role): void {
        $stmt = db()->prepare('INSERT INTO users (full_name, email, password_hash, phone, role) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$name, $email, $hash, $phone, $role]);
    }
    // Count registered users for the administrator dashboard.
    public static function countAll(): int { return (int)db()->query('SELECT COUNT(*) FROM users')->fetchColumn(); }
}
