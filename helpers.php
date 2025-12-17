<?php
// Shared helper functions for backend labs.
function unlockAchievement(mysqli $connection, int $userId, string $column): void
{
    $allowed = ['sqli', 'idor', 'xss', 'cookie', 'privesc'];
    if (!in_array($column, $allowed, true)) {
        return;
    }
    $sql = "INSERT INTO achievements (user_id, $column) VALUES (?, 1)
            ON DUPLICATE KEY UPDATE $column = VALUES($column), updated_at = CURRENT_TIMESTAMP";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        return;
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
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
