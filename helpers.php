<?php
// Shared helper functions for backend labs.
//يحسب الانجازات ويضيفهم
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
