<?php
// 开启错误报告
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['username'])) {
    $ai_models = array('GPT', 'BERT', 'DALL-E', 'LaMDA', 'PaLM', 'Claude', 'Stable Diffusion', 'Midjourney', 'Bard', 'Bing');
    $_SESSION['username'] = $ai_models[array_rand($ai_models)] . '-' . rand(1000, 9999);
}

// 添加调试信息
echo "PHP version: " . phpversion() . "<br>";
echo "Session username: " . $_SESSION['username'] . "<br>";
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical Support</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body, html {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #f1f3f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }
        #chat-window {
            height: calc(100vh - 200px);
            overflow-y: auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 2px 6px 2px rgba(60,64,67,0.15);
            padding: 16px;
            margin-bottom: 16px;
        }
        #chat-form {
            display: flex;
            gap: 8px;
        }
        #message {
            flex-grow: 1;
            border: 1px solid #dadce0;
            border-radius: 24px;
            padding: 12px 16px;
            font-size: 14px;
            outline: none;
            transition: box-shadow 0.3s;
        }
        #message:focus {
            box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15);
        }
        button {
            background-color: #1a73e8;
            color: white;
            border: none;
            border-radius: 24px;
            padding: 0 16px;
            height: 36px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #1765cc;
        }
        #upload-form {
            margin-top: 16px;
        }
        .file-input {
            display: none;
        }
        .file-label {
            display: inline-flex;
            align-items: center;
            background-color: #fff;
            color: #5f6368;
            border: 1px solid #dadce0;
            border-radius: 24px;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .file-label:hover {
            background-color: #f1f3f4;
        }
        .file-label i {
            margin-right: 8px;
        }
        #image-popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
            z-index: 1000;
        }
        #popup-image {
            max-width: 90%;
            max-height: 90%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="chat-window"></div>
        <form id="chat-form">
            <input type="text" id="message" placeholder="Type your question here..." required>
            <button type="submit">Send</button>
        </form>
        <form id="upload-form">
            <input type="file" id="file-input" class="file-input" name="file" accept="*/*">
            <label for="file-input" class="file-label">
                <i class="material-icons">attach_file</i>
                Choose a file
            </label>
            <button type="submit">Upload</button>
        </form>
    </div>
    <div id="image-popup">
        <img id="popup-image" src="" alt="Full size image">
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function scrollToBottom() {
                var chatWindow = $('#chat-window');
                chatWindow.scrollTop(chatWindow[0].scrollHeight);
            }

            function loadChat() {
                $('#chat-window').load('chat.php', function() {
                    scrollToBottom();
                    $('.image-popup').off('click').on('click', function(e) {
                        e.preventDefault();
                        var imgSrc = $(this).attr('href');
                        $('#popup-image').attr('src', imgSrc);
                        $('#image-popup').fadeIn();
                    });
                });
            }

            loadChat();
            setInterval(loadChat, 5000);

            $('#chat-form').submit(function(e) {
                e.preventDefault();
                $.post('chat.php', {
                    message: $('#message').val()
                }, function() {
                    $('#message').val('');
                    loadChat();
                });
            });

            $('#upload-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: 'upload.php',
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        var response = JSON.parse(data);
                        alert(response.message);
                        if (response.status === 'success') {
                            loadChat();
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            });

            $('#image-popup').click(function() {
                $(this).fadeOut();
            });

            $('#file-input').change(function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.file-label').html('<i class="material-icons">attach_file</i>' + fileName);
            });
        });
    </script>
</body>
</html>
