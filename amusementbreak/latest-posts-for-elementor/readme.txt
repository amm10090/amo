=== Latest Posts for Elementor ===
Contributors: huayangtian
Tags: elementor, posts, widget, latest posts
Requires at least: 5.0
Tested up to: 6.2
Stable tag: 4.0.0
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

Yes, ths plugin is an addon for Elementor and requires Elementor to be installed and activated.

= Can I customize the look of the posts? =

Yes, the widget provides various customization options. You can adjust the layout, typography, colors, and more using Elementor's built-in style options.

== Changelog ==

= 4.0.0=

# Latest Posts for Elementor 4.0 更新日志

## 新功能

1. 分页支持:
   - 新增数字分页选项
   - 新增"加载更多"按钮选项
   - 支持无限滚动加载
   - 可选择分页位置(顶部、底部或两者)

2. 动态每页项目数:
   - 现在可以通过Elementor控件设置每页显示的文章数量

3. 延迟加载:
   - 添加了延迟加载选项,提高页面加载速度

4. 分页预加载:
   - 新增预加载下一页内容的选项,提供更快的响应速度

5. 分页历史记录:
   - 支持使用浏览器的前进/后退按钮在分页间导航

6. 分页URL优化:
   - 为每个分页生成唯一的URL,有利于SEO

7. 跳页功能:
   - 允许用户直接跳转到指定页码

8. 响应式分页:
   - 根据设备屏幕大小自适应分页样式

9. 分页状态保持:
   - 记住用户最后浏览的页面,返回时自动跳转到该位置

## 优化

1. 性能提升:
   - 优化了文章加载逻辑,提高了大量文章时的加载速度
   - 实现了更高效的缓存机制

2. 代码重构:
   - 重构了小部件渲染逻辑,提高了代码可维护性
   - 优化了广告插入逻辑

3. 样式优化:
   - 改进了文章列表和分页的样式
   - 优化了移动端显示效果

4. 文章卡片可点击:
-将使整个文章卡片变为可点击区域,同时保持原有的视觉效果和功能。现在可以点击卡片的任何部分来访问相应的文章或广告页面,提供了更直观和便捷的交互体验。
-同时,CSS文件中的响应式设计部分确保了在移动设备上的良好显示效果。,整个文章卡片可点击并跳转到相应的页面。

## 其他改进

1. 增强了与其他插件的兼容性
2. 更新了插件文档,详细说明了新功能的使用方法
3. 修复了已知的小bug

注: 此版本包含重大更新,建议在更新前备份您的网站。
== Upgrade Notice ==
= 3.5.0 =
## 新功能
* 广告循环显示:
  - 添加了广告循环显示选项
  - 用户可以在Elementor控件中设置是否重复显示广告
* YouTube广告增强:
  - 在YouTube广告缩略图右上角添加了"AD"标志
  - 优化了YouTube广告的显示效果

## 优化
* 重构了广告显示逻辑:
  - 现在可以根据设置的位置和是否重复来显示广告
  - 提高了代码的可读性和可维护性
* 改进了CSS样式:
  - 优化了YouTube广告的样式
  - 确保了广告与普通文章列表的视觉一致性

## 文档
* 更新了readme.txt文件:
  - 添加了新功能的说明
  - 优化了文档的排版和结构

## 其他
* 代码清理和小bug修复

= 3.5.0 =
## 功能优化
* 缩略图显示:
  - 添加了缩略图比例控制,支持1:1、4:3、16:9和21:9四种比例
  - 为不同比例的缩略图设置了适当的最大宽度和高度
* 文章摘要:
  - 添加了摘要长度控制
  - 当文章没有设置摘要时,自动从文章内容中提取指定长度的文字作为摘要
  - 在摘要末尾添加省略号
* YouTube广告:
  - 添加了YouTube广告播放模式选择(内联播放或重定向到YouTube)
  - 在YouTube广告缩略图上添加了播放图标
  - 使用YouTube频道名称作为广告"作者"
  - 在YouTube广告缩略图右上角添加了半透明的"AD"标志

## 代码重构
* 将所有Elementor控件定义移至单独的文件(latest-posts-control.php),提高了代码的模块化

## 性能优化
* 为YouTube视频信息添加了缓存机制,减少API请求次数

## 响应式设计
* 优化了移动设备上的布局和缩略图显示

## 样式自定义
* 新增了标题、摘要和元数据的颜色和排版控制

=3.1.0=
# Latest Posts for Elementor 3.1 更新日志
##报错修复
修复了一处引用错误，导致插件无法正常运行。
## 新功能
1. 添加了国际化(i18n)支持
   - 创建了 `languages` 目录
   - 添加了 `latest-posts-for-elementor.pot` 翻译模板文件
   - 添加了 `latest-posts-for-elementor-zh_CN.po` 和 `latest-posts-for-elementor-zh_CN.mo` 中文翻译文件

2. 实现了 `LPFE_i18n` 类来处理翻译文件的加载

3. 在主插件文件中集成了国际化功能
   - 添加了文本域和域路径信息
   - 加载翻译文件

4. 更新了所有用户可见的字符串,使用适当的翻译函数包装

## 代码优化
1. 重构了主插件文件 `latest-posts-for-elementor.php`,增加了国际化支持
2. 更新了 `controls/latest-posts-control.php` 文件,使用翻译函数包装所有文本

## 文档更新
1. 更新了插件头部信息,添加了文本域和域路径
2. 添加了国际化相关的注释和说明

## 其他改进
1. 确保了代码的一致性和可读性
2. 优化了文件结构,便于未来的维护和扩展

注: 此更新主要聚焦于添加国际化支持,为插件的全球化使用奠定基础。
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
