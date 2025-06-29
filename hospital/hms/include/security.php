<?php
/**
 * Security utilities for the Hospital Management System.
 *
 * Provides:
 *  - CSRF token generation and validation
 *  - Password hashing (bcrypt) with MD5 fallback for legacy data
 *  - Input sanitisation helpers
 */

// ── CSRF Protection ──────────────────────────────────────────────────────────

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

function csrf_verify(): bool {
    $token = $_POST['csrf_token'] ?? '';
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// ── Password Hashing ────────────────────────────────────────────────────────

/**
 * Hash a password using bcrypt (PHP's password_hash).
 */
function hash_password(string $password): string {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Verify a password against a hash.
 * Supports both bcrypt (new) and MD5 (legacy) hashes.
 * If an MD5 hash matches, returns true but the caller should
 * upgrade the stored hash to bcrypt.
 */
function verify_password(string $password, string $hash): bool {
    // Try bcrypt first (starts with $2y$ or $2b$)
    if (str_starts_with($hash, '$2y$') || str_starts_with($hash, '$2b$')) {
        return password_verify($password, $hash);
    }
    // Fallback: legacy MD5 check
    return md5($password) === $hash;
}

/**
 * Check if a stored hash needs upgrading from MD5 to bcrypt.
 */
function needs_rehash(string $hash): bool {
    return !(str_starts_with($hash, '$2y$') || str_starts_with($hash, '$2b$'));
}

// ── Input Sanitisation ──────────────────────────────────────────────────────

function sanitize_string(string $input): string {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function sanitize_int($input): int {
    return intval($input);
}

function sanitize_email(string $input): string {
    $email = filter_var(trim($input), FILTER_SANITIZE_EMAIL);
    return $email ?: '';
}

function validate_email(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}
