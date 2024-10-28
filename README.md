# 本地聊天系统

一个基于PHP的轻量级内网聊天系统，支持实时消息交互、文件上传、@提醒等功能。

## 功能特性

- 💬 实时消息交互
- 📎 文件和图片上传功能
- 🔔 @用户提醒功能
- 🖼️ 图片预览功能
- 🔄 自动刷新聊天记录
- 👤 随机用户名
- 🔍 聊天记录查看
- 💡 简洁的Google风格界面

## 技术栈

- PHP 7.0+
- jQuery 3.6.0
- HTML5
- CSS3
- JavaScript

## 安装说明

1. 克隆仓库到本地：
```bash
git clone git@github.com:dongluliang/chat.git
```
2. 确保您的服务器环境满足以下要求：
   - PHP 7.0 或更高版本
   - Web服务器（Apache/Nginx）
   - 文件写入权限
   - 上传文件大小可文章phpinfo.php查看本地设置

3. 配置文件权限：

## 目录结构

```
├── index.php          # 主页面
├── chat.php          # 聊天处理
├── upload.php        # 文件上传处理
├── clear_chat.php    # 清空聊天记录
├── upload/           # 上传文件存储目录
├── talk.log         # 聊天记录文件
└── README.md        # 项目说明文档
```

## 使用说明

1. 打开网页后会自动分配一个AI模型相关的随机用户名
2. 在输入框中输入消息，点击发送或按回车键发送消息
3. 点击用户名可以快速输入@该用户
4. 点击上传按钮可以上传文件或图片
5. 上传的图片可以点击查看大图
6. 被@时会收到浏览器通知
7. 删除服务端talk.log时将清空聊天记录

## 注意事项

- 本项目使用文件系统存储聊天记录，适合小型应用场景
- 不要在生产环境中部署
- 请确保upload目录具有适当的写入权限
- 建议配置HTTPS以确保通信安全
- 本聊天未进行上传文件过滤，为防止被上传恶意木马请注意修改配置文件：
-- Nginx 配置文件使用需要添加
  ```
 location /upload {
    location ~ \.php$ {
        deny all;
    }
}
```
- 如果使用 Apache，确保在主配置文件中启用了 `.htaccess`：
```
<Directory /path/to/upload>
    AllowOverride All
</Directory>
```
## 贡献指南

欢迎提交 Issue 和 Pull Request 来帮助改进这个项目。

## 许可证

[MIT License](LICENSE)

## 作者

[dongluliang]

## 更新日志

### v1.0.0 (2024-10-28)
- 初始版本发布
- 实现基本聊天功能
- 添加文件上传功能
- 添加@提醒功能
