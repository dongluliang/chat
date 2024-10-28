<?php
session_start();

$username = $_SESSION['username'];
$notificationFile = "notifications_{$username}.txt";

$hasNotification = false;

if (file_exists($notificationFile)) {
    $notifications = file($notificationFile, FILE_IGNORE_NEW_LINES);
    $latestNotification = end($notifications);
    
    if ($latestNotification > time() - 30) { // 检查最近30秒内的通知
        $hasNotification = true;
    }
    
    // 只有当窗口获得焦点时才清除通知
    if (isset($_GET['clear']) && $_GET['clear'] == 1) {
        file_put_contents($notificationFile, '');
    }
}

echo json_encode(['hasNotification' => $hasNotification]);

