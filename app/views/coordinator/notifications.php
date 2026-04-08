<?php
$title = 'Notifications';
$pageTitle = 'Notifications';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-bell me-2"></i> Notifications
        <?php $unread = array_filter($notifications ?? [], fn($n) => !$n['is_read']); ?>
        <?php if (count($unread) > 0): ?>
            <span class="badge bg-danger ms-2"><?php echo count($unread); ?> unread</span>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if (empty($notifications)): ?>
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                <h5>No Notifications</h5>
                <p class="text-muted">You have no notifications.</p>
            </div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($notifications as $notif): ?>
                    <?php
                    $iconMap = ['success' => 'check-circle text-success', 'error' => 'times-circle text-danger', 'warning' => 'exclamation-triangle text-warning', 'info' => 'info-circle text-info'];
                    $icon = $iconMap[$notif['type'] ?? 'info'] ?? 'info-circle text-info';
                    ?>
                    <div class="list-group-item <?php echo !$notif['is_read'] ? 'list-group-item-light border-start border-primary border-3' : ''; ?>">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="d-flex">
                                <i class="fas fa-<?php echo $icon; ?> me-3 mt-1 fa-lg"></i>
                                <div>
                                    <h6 class="mb-1"><?php echo htmlspecialchars($notif['title']); ?></h6>
                                    <p class="mb-1 text-muted"><?php echo htmlspecialchars($notif['message']); ?></p>
                                    <small class="text-muted"><?php echo !empty($notif['created_at']) ? date('M d, Y H:i', strtotime($notif['created_at'])) : ''; ?></small>
                                </div>
                            </div>
                            <?php if (!$notif['is_read']): ?>
                                <form method="POST" action="<?php echo BASE_URL; ?>coordinator/mark-notification-read" class="ms-3">
                                    <input type="hidden" name="notification_id" value="<?php echo $notif['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Mark Read</button>
                                </form>
                            <?php else: ?>
                                <span class="badge bg-secondary ms-3">Read</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
