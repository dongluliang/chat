<?php
// 设置错误报告
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

echo "Script started<br>";

session_start();

echo "Session started<br>";
echo "PHP version: " . phpversion() . "<br>";
echo "POST data: <pre>" . print_r($_POST, true) . "</pre>";
echo "FILES data: <pre>" . print_r($_FILES, true) . "</pre>";

function sanitizeFileName($fileName) {
    // 将文件名转换为 UTF-8 编码
    $fileName = iconv('UTF-8', 'UTF-8//IGNORE', $fileName);
    // 移除非法字符
    $fileName = preg_replace("/[^\w\-\.]/", '', $fileName);
    return $fileName;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    echo "File upload detected<br>";
    
    $uploadDir = 'upload/';
    $maxFileSize = 5 * 1024 * 1024; // 5 MB

    if ($_FILES['file']['size'] > $maxFileSize) {
        echo json_encode(['status' => 'error', 'message' => "文件大小超过限制（最大 5MB）"]);
        exit;
    }

    $originalFileName = basename($_FILES['file']['name']);
    $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
    $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    $safeFileName = time() . '_' . sanitizeFileName($originalFileName);
    $uploadFile = $uploadDir . $safeFileName;

    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            echo json_encode(['status' => 'error', 'message' => "上传目录创建失败"]);
            exit;
        }
    }

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        if (in_array($fileExtension, $allowedImageExtensions)) {
            $message = $_SESSION['username'] . ' 上传了图片: <a href="' . $uploadFile . '" target="_blank" class="image-popup"><img src="' . $uploadFile . '" alt="' . htmlspecialchars($originalFileName) . '" style="max-width: 200px; max-height: 200px;"></a>' . "\n";
        } else {
            $message = $_SESSION['username'] . ' 上传了文件: <a href="' . $uploadFile . '" target="_blank">' . htmlspecialchars($originalFileName) . '</a>' . "\n";
        }
        file_put_contents('talk.log', $message, FILE_APPEND);
        echo json_encode(['status' => 'success', 'message' => '文件上传成功']);
    } else {
        echo json_encode(['status' => 'error', 'message' => "文件上传失败"]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => '无效的请求']);
}

echo "Script ended<br>";
