<?php
/**
 * NotificationService
 * 
 * Handles system-wide notifications
 */

class NotificationService
{
    /**
     * Send notification to a specific user
     */
    public static function send($userId, $type, $title, $message, $module = null, $id = null, $priority = 'medium')
    {
        try {
            return Database::insert('notifications', [
                'user_id' => $userId,
                'notification_type' => $type,
                'title' => $title,
                'message' => $message,
                'related_module' => $module,
                'related_id' => $id,
                'priority' => $priority,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            Logger::error("Failed to send notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Notify all users with a specific role
     */
    public static function notifyRole($roleId, $type, $title, $message, $module = null, $id = null, $deptId = null)
    {
        $sql = "SELECT user_id FROM users WHERE role_id = ? AND status = 'active' AND deleted_at IS NULL";
        $params = [$roleId];

        if ($deptId) {
            $sql .= " AND (department_id = ? OR department_id IS NULL)";
            $params[] = $deptId;
        }

        $users = Database::fetchAll($sql, $params);
        foreach ($users as $user) {
            self::send($user['user_id'], $type, $title, $message, $module, $id);
        }
    }

    /**
     * Queue an email (placeholder for actual SMTP logic)
     */
    public static function queueEmail($recipient, $subject, $body, $userId = null)
    {
        try {
            return Database::insert('email_queue', [
                'recipient_email' => $recipient,
                'recipient_user_id' => $userId,
                'subject' => $subject,
                'body' => $body,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            Logger::error("Failed to queue email: " . $e->getMessage());
            return false;
        }
    }
}
