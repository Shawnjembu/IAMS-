<?php

require_once '../core/Model.php';

/**
 * Notification Model
 * Handles notification-related database operations
 */
class Notification extends Model
{
    protected $table = 'notifications';

    /**
     * Get notifications by user
     * 
     * @param int $userId User ID
     * @param string $userType User type
     * @param int $limit Limit results
     * @return array Array of notifications
     */
    public function getByUser($userId, $userType, $limit = 50)
    {
        $db = connectToDatabase();
        $userId = (int)$userId;
        $userType = mysqli_real_escape_string($db, $userType);
        $limit = (int)$limit;
        
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = {$userId} AND user_type = '{$userType}'
                ORDER BY created_at DESC
                LIMIT {$limit}";
        
        $result = mysqli_query($db, $sql);
        
        $notifications = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $notifications[] = $row;
        }
        
        return $notifications;
    }

    /**
     * Get unread notifications
     * 
     * @param int $userId User ID
     * @param string $userType User type
     * @return array Array of unread notifications
     */
    public function getUnread($userId, $userType)
    {
        $db = connectToDatabase();
        $userId = (int)$userId;
        $userType = mysqli_real_escape_string($db, $userType);
        
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = {$userId} AND user_type = '{$userType}' AND is_read = 0
                ORDER BY created_at DESC";
        
        $result = mysqli_query($db, $sql);
        
        $notifications = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $notifications[] = $row;
        }
        
        return $notifications;
    }

    /**
     * Get unread count
     * 
     * @param int $userId User ID
     * @param string $userType User type
     * @return int Unread count
     */
    public function getUnreadCount($userId, $userType)
    {
        $db = connectToDatabase();
        $userId = (int)$userId;
        $userType = mysqli_real_escape_string($db, $userType);
        
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE user_id = {$userId} AND user_type = '{$userType}' AND is_read = 0";
        
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_assoc($result);
        
        return (int)$row['count'];
    }

    /**
     * Create notification
     * 
     * @param array $data Notification data
     * @return int|bool Insert ID or false
     */
    public function create($data)
    {
        $db = connectToDatabase();
        
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_map([$db, 'real_escape_string'], array_values($data))) . "'";
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$values})";
        
        if (mysqli_query($db, $sql)) {
            return mysqli_insert_id($db);
        }
        return false;
    }

    /**
     * Send notification to user
     * 
     * @param int $userId User ID
     * @param string $userType User type
     * @param string $title Notification title
     * @param string $message Notification message
     * @param string $type Notification type (info, success, warning, error)
     * @param string $link Optional link
     * @return int|bool Insert ID or false
     */
    public function send($userId, $userType, $title, $message, $type = 'info', $link = '')
    {
        $data = [
            'user_id' => $userId,
            'user_type' => $userType,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->create($data);
    }

    /**
     * Mark notification as read
     * 
     * @param int $id Notification ID
     * @return bool True on success
     */
    public function markAsRead($id)
    {
        $db = connectToDatabase();
        $id = (int)$id;
        
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE id = {$id}";
        
        return mysqli_query($db, $sql);
    }

    /**
     * Mark all notifications as read for user
     * 
     * @param int $userId User ID
     * @param string $userType User type
     * @return bool True on success
     */
    public function markAllAsRead($userId, $userType)
    {
        $db = connectToDatabase();
        $userId = (int)$userId;
        $userType = mysqli_real_escape_string($db, $userType);
        
        $sql = "UPDATE {$this->table} SET is_read = 1 
                WHERE user_id = {$userId} AND user_type = '{$userType}'";
        
        return mysqli_query($db, $sql);
    }

    /**
     * Delete notification
     * 
     * @param int $id Notification ID
     * @return bool True on success
     */
    public function delete($id)
    {
        $db = connectToDatabase();
        $id = (int)$id;
        
        $sql = "DELETE FROM {$this->table} WHERE id = {$id}";
        
        return mysqli_query($db, $sql);
    }

    /**
     * Delete old notifications
     * 
     * @param int $days Days to keep
     * @return bool True on success
     */
    public function deleteOld($days = 30)
    {
        $db = connectToDatabase();
        $days = (int)$days;
        
        $sql = "DELETE FROM {$this->table} 
                WHERE created_at < DATE_SUB(NOW(), INTERVAL {$days} DAY)";
        
        return mysqli_query($db, $sql);
    }
}
