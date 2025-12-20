<?php
// Shared helper functions for backend labs.
function logAuditEvent(mysqli $connection, int $userId, string $eventType, string $eventContext = ''): void
{
    if ($userId <= 0) {
        return;
    }
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    if (strlen($userAgent) > 250) {
        $userAgent = substr($userAgent, 0, 250);
    }
    $stmt = $connection->prepare('INSERT INTO audit_logs (user_id, event_type, event_context, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)');
    if (!$stmt) {
        return;
    }
    $stmt->bind_param('issss', $userId, $eventType, $eventContext, $ipAddress, $userAgent);
    $stmt->execute();
}

function unlockAchievement(mysqli $connection, int $userId, string $column): void
{
    $allowed = ['sqli', 'idor', 'xss', 'cookie', 'privesc'];
    if (!in_array($column, $allowed, true)) {
        return;
    }
    $sql = "INSERT INTO achievements (user_id, $column) VALUES (?, 1)
            ON DUPLICATE KEY UPDATE $column = IF($column = 0, 1, $column),
            updated_at = IF($column = 0, CURRENT_TIMESTAMP, updated_at)";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        return;
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        logAuditEvent($connection, $userId, 'achievement_unlock', $column);
    }
    updateFinalIfComplete($connection, $userId, 'all_labs');
}

function updateFinalIfComplete(mysqli $connection, int $userId, string $source = 'all_labs'): void
{
    if ($userId <= 0) {
        return;
    }
    $stmt = $connection->prepare('UPDATE achievements SET `final` = 1, completed_at = COALESCE(completed_at, NOW()), updated_at = CURRENT_TIMESTAMP
        WHERE user_id = ? AND `final` = 0 AND sqli = 1 AND idor = 1 AND xss = 1 AND cookie = 1 AND privesc = 1');
    if (!$stmt) {
        return;
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        logAuditEvent($connection, $userId, 'final_unlock', $source);
    }
}

function markFinalCompletion(mysqli $connection, int $userId, string $source = 'master_code'): void
{
    if ($userId <= 0) {
        return;
    }
    $stmt = $connection->prepare('INSERT INTO achievements (user_id, `final`, completed_at) VALUES (?, 1, NOW())
        ON DUPLICATE KEY UPDATE `final` = IF(`final` = 0, 1, `final`),
        completed_at = IF(`final` = 0, NOW(), completed_at),
        updated_at = IF(`final` = 0, CURRENT_TIMESTAMP, updated_at)');
    if (!$stmt) {
        return;
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        logAuditEvent($connection, $userId, 'final_unlock', $source);
    }
}

/**
 * Fetch a lab flag value from the database by key.
 * Falls back to the provided default if the table/row is missing.
 */
function getFlagValue(mysqli $connection, string $labKey, string $default = ''): string
{
    $stmt = $connection->prepare('SELECT flag_value FROM flags WHERE lab_key = ? LIMIT 1');
    if (!$stmt) {
        return $default;
    }
    $stmt->bind_param('s', $labKey);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    return $row ? (string) $row['flag_value'] : $default;
}

/**
 * Return current language based on cookie (defaults to en).
 */
function currentLang(): string
{
    $lang = $_COOKIE['ftf_lang'] ?? 'en';
    return $lang === 'ar' ? 'ar' : 'en';
}

/**
 * Simple translator helper for backend-rendered strings.
 */
function tr(string $en, string $ar, ?string $lang = null): string
{
    $lang = $lang ?? currentLang();
    return $lang === 'ar' ? $ar : $en;
}

/**
 * Load achievements for a user and return as a bool map.
 * Adds a computed "final" key when all labs are unlocked.
 */
function getUserAchievements(mysqli $connection, int $userId): array
{
    $defaults = ['sqli' => false, 'idor' => false, 'xss' => false, 'cookie' => false, 'privesc' => false, 'final' => false];
    if ($userId <= 0) {
        return $defaults;
    }
    $stmt = $connection->prepare('SELECT sqli, idor, xss, cookie, privesc, `final` FROM achievements WHERE user_id = ? LIMIT 1');
    $hasFinalColumn = true;
    if (!$stmt) {
        $stmt = $connection->prepare('SELECT sqli, idor, xss, cookie, privesc FROM achievements WHERE user_id = ? LIMIT 1');
        $hasFinalColumn = false;
    }
    if (!$stmt) {
        return $defaults;
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    if ($row) {
        foreach (['sqli', 'idor', 'xss', 'cookie', 'privesc'] as $key) {
            $defaults[$key] = (bool) ($row[$key] ?? 0);
        }
        if ($hasFinalColumn && array_key_exists('final', $row)) {
            $defaults['final'] = (bool) ($row['final'] ?? 0);
        }
    }
    $allComplete = ($defaults['sqli'] && $defaults['idor'] && $defaults['xss'] && $defaults['cookie'] && $defaults['privesc']);
    $defaults['final'] = $defaults['final'] || $allComplete;
    return $defaults;
}
