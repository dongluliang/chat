<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = $_SESSION['username'] . ': ' . htmlspecialchars($_POST['message']) . "\n";
    
    // 检查是否有@用户名
    preg_match_all('/@(\w+)/', $message, $mentions);
    if (!empty($mentions[1])) {
        foreach ($mentions[1] as $mentionedUser) {
            $notificationFile = "notifications_{$mentionedUser}.txt";
            file_put_contents($notificationFile, time() . "\n", FILE_APPEND);
        }
    }
    
    file_put_contents('talk.log', $message, FILE_APPEND);
}

if (file_exists('talk.log')) {
    $chat = file_get_contents('talk.log');
    $lines = explode("\n", $chat);
    
    foreach ($lines as $line) {
        if (trim($line) !== '') {
            // 将用户名转换为可点击的链接，并将整条消息包装在一个div中
            $line = preg_replace_callback(
                '/^(.+?):\s*(.*)$/s',
                function($matches) {
                    $username = trim($matches[1]);
                    $message = trim($matches[2]);
                    $usernameLink = "<a href='#' class='username-link' data-username='{$username}'>{$username}</a>";
                    return "<div class='message-container'>{$usernameLink}: {$message}</div>";
                },
                $line
            );
            echo $line;
        }
    }
} else {
    echo "聊天记录为空";
}

