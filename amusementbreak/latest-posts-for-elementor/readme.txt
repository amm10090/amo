=== Latest Posts for Elementor ===
Contributors: huayangtian
Tags: elementor, posts, widget, latest posts
Requires at least: 5.0
Tested up to: 6.2
Stable tag: 3.0.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds a customizable Latest Posts widget for Elementor page builder.

== Description ==

Latest Posts for Elementor is a lightweight and customizable WordPress plugin that seamlessly integrates with Elementor. It provides a new widget that allows you to display your most recent blog posts anywhere on your Elementor-built pages.
Latest Posts for Elementor 是一个为Elementor页面构建器添加最新文章小部件的WordPress插件。它允许您轻松地在您的页面中显示最新的博客文章，并提供多种自定义选项。

Key Features:

* Easy integration with Elementor
* Customizable number of posts to display
* Responsive design
* Display post thumbnails, titles, excerpts, and meta information
* Customizable styles to match your website's design

* 显示最新文章列表
* 可自定义显示的文章数量
* 可选择是否显示文章标题、发布日期、作者和缩略图
* 支持在文章列表中插入广告
* 支持三种广告类型：YouTube视频、自定义图片和自定义HTML
* 可设置广告的位置和是否重复显示
* 使用YouTube API获取指定频道的最新视频
* 可自定义样式，包括缩略图尺寸和标题颜色
* 性能优化：使用WordPress缓存机制和transients
* 响应式设计
* 国际化支持
* SEO优化
== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/latest-posts-for-elementor` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the widget in Elementor by searching for "Latest Posts" in the Elementor editor.


1. 上传 `latest-posts-for-elementor` 文件夹到 `/wp-content/plugins/` 目录
2. 在WordPress后台激活插件
3. 在Elementor编辑器中使用 "Latest Posts" 小部件
== Frequently Asked Questions ==

= Does this plugin require Elementor? =

Yes, this plugin is an addon for Elementor and requires Elementor to be installed and activated.

= Can I customize the look of the posts? =

Yes, the widget provides various customization options. You can adjust the layout, typography, colors, and more using Elementor's built-in style options.

== Changelog ==
=3.0.0=
# Latest Posts for Elementor 3.0 更新日志

## 新功能
1. 新增 YouTube 视频广告支持
   - 可设置 YouTube 频道 ID 和 API 密钥
   - 支持自动播放选项
   - 可选择是否显示播放图标

2. 新增自定义图片广告功能
   - 可上传自定义广告图片
   - 支持设置广告链接

3. 新增自定义 HTML 广告功能
   - 可插入自定义 HTML 代码作为广告

4. 广告位置和重复设置
   - 可自定义广告插入位置
   - 支持广告重复显示选项

## 优化
1. 重构控件结构，将控件注册逻辑移至 `Latest_Posts_Control` 类
2. 优化 CSS 样式，提升响应式布局表现
3. 改进 JavaScript 代码，提高 YouTube 视频加载和播放的性能

## 性能提升
1. 优化数据查询逻辑，提高大量文章时的加载速度
2. 实现延迟加载，提升页面初始加载速度

## UI/UX 改进
1. 重新设计文章列表样式，增加悬停效果
2. 优化广告标签显示，提高用户体验
3. 改进移动端布局，确保在各种设备上的良好显示

## 代码质量
1. 重构代码结构，提高可维护性
2. 增加代码注释，提高可读性
3. 实现更严格的类型检查和错误处理

## 兼容性
1. 确保与最新版本的 Elementor 和 WordPress 兼容
2. 优化与其他常用插件的兼容性

## 文档
1. 更新使用文档，详细说明新功能的使用方法
2. 添加开发者文档，方便进行二次开发和扩展

## 国际化
1. 更新翻译文件，支持更多语言
2. 优化文本域处理，确保所有字符串可翻译

## 安全性
1. 增强数据验证和清理流程
2. 实现更安全的 API 调用方式

## 其他
1. 修复已知 bug
2. 性能优化和代码清理
= 1.1.1 =
功能增强：
1. 新增显示控制选项：
   - 可单独控制标题、日期、作者和缩略图的显示
2. 增加样式自定义功能：
   - 缩略图尺寸现在支持像素和百分比设置
   - 可自定义"NEWS"文字颜色
3. 新增广告（AD）帖子功能：
   - 可设置AD帖子显示位置
   - 支持循环显示AD帖子选项

样式优化：
1. 改进缩略图样式，支持responsive设计
2. 优化整体布局，提高可读性

代码改进：
1. 重构Latest_Posts_Widget类，提高代码可维护性
2. 优化CSS结构，增加AD帖子相关样式

其他改进：
1. 增加中文注释，提高代码可读性
2. 完善Elementor编辑器中的设置选项，提供更直观的用户体验

注意：此更新包含多项新功能和样式变更，建议用户更新后检查并调整现有页面中使用的Latest Posts小部件设置。

==  版本 1.1.1 ==

### 功能增强

 新功能
1. 新增广告类型选项:
   - YouTube视频广告
   - 自定义图片广告
   - 自定义HTML广告
2. 为YouTube广告添加自动播放选项
3. 为YouTube广告添加显示/隐藏播放图标选项
4. 添加广告重复显示选项

样式优化 改进
1. 优化广告位置设置,允许更灵活的广告插入
2. 改进YouTube数据获取和缓存机制
3. 增强CSS样式,提升整体视觉效果和响应式设计

性能优化
1. 实现文章查询结果缓存,提高加载速度
2. 优化JavaScript代码,提升前端性能

 其他更改
1. 更新小部件注册方法,以兼容最新版本的Elementor
2. 改进代码结构,提高可维护性
3. 添加新的本地化字符串,增强国际化支持

 开发者说明
- 新增 `youtube-widget.js` 文件,处理YouTube广告交互
- 更新 `latest-posts-widget.css`,新增广告相关样式
- 重构 `Latest_Posts_Widget` 类,支持新的广告功能
== Upgrade Notice ==

=3.0.0=
# Latest Posts for Elementor 3.0 更新日志

## 新功能
1. 新增 YouTube 视频广告支持
   - 可设置 YouTube 频道 ID 和 API 密钥
   - 支持自动播放选项
   - 可选择是否显示播放图标

2. 新增自定义图片广告功能
   - 可上传自定义广告图片
   - 支持设置广告链接

3. 新增自定义 HTML 广告功能
   - 可插入自定义 HTML 代码作为广告

4. 广告位置和重复设置
   - 可自定义广告插入位置
   - 支持广告重复显示选项

## 优化
1. 重构控件结构，将控件注册逻辑移至 `Latest_Posts_Control` 类
2. 优化 CSS 样式，提升响应式布局表现
3. 改进 JavaScript 代码，提高 YouTube 视频加载和播放的性能

## 性能提升
1. 优化数据查询逻辑，提高大量文章时的加载速度
2. 实现延迟加载，提升页面初始加载速度

## UI/UX 改进
1. 重新设计文章列表样式，增加悬停效果
2. 优化广告标签显示，提高用户体验
3. 改进移动端布局，确保在各种设备上的良好显示

## 代码质量
1. 重构代码结构，提高可维护性
2. 增加代码注释，提高可读性
3. 实现更严格的类型检查和错误处理

## 兼容性
1. 确保与最新版本的 Elementor 和 WordPress 兼容
2. 优化与其他常用插件的兼容性

## 文档
1. 更新使用文档，详细说明新功能的使用方法
2. 添加开发者文档，方便进行二次开发和扩展

## 国际化
1. 更新翻译文件，支持更多语言
2. 优化文本域处理，确保所有字符串可翻译

## 安全性
1. 增强数据验证和清理流程
2. 实现更安全的 API 调用方式

## 其他
1. 修复已知 bug
2. 性能优化和代码清理

== 贡献 ==

如果您想为这个项目做出贡献，请访问我们的GitHub仓库：
https://github.com/amm10090/amo/
