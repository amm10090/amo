# Latest Posts for Elementor

A WordPress plugin that adds a Latest Posts widget for the Elementor page builder.

## Description

Latest Posts for Elementor is a lightweight and customizable WordPress plugin that seamlessly integrates with Elementor. It provides a new widget that allows you to display your most recent blog posts anywhere on your Elementor-built pages.

Key Features:
- Easy integration with Elementor
- Customizable number of posts to display
- Responsive design
- Display post thumbnails, titles, excerpts, and meta information
- Customizable styles to match your website's design

## Requirements

- WordPress 5.0+
- PHP 7.0+
- Elementor 3.0.0+

## Installation

### For Users

1. Download the latest release from the [GitHub repository](https://github.com/amm10090/amo/releases).
2. Upload the plugin files to the `/wp-content/plugins/latest-posts-for-elementor` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress.

### For Developers

1. Clone this repository into your WordPress plugins directory:
   ```
   git clone https://github.com/amm10090/amo.git latest-posts-for-elementor
   ```

2. Navigate to the plugin directory:
   ```
   cd latest-posts-for-elementor
   ```

3. Install Plugin Update Checker:
   - Download the latest release from [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker)
   - Extract the contents to a `plugin-update-checker` directory within the plugin folder

4. Activate the plugin through the WordPress admin interface.

## Usage

1. Edit a page with Elementor.
2. Search for "Latest Posts" in the Elementor widgets panel.
3. Drag and drop the widget into your page.
4. Customize the widget settings as needed.

## Project Structure

```
latest-posts-for-elementor/
├── assets/
│   └── css/
│       └── latest-posts-widget.css
├── widgets/
│   └── latest-posts-widget.php
├── latest-posts-for-elementor.php
├── readme.txt
└── README.md
```

## Development Workflow

1. Create a new branch for your feature or bug fix:
   ```
   git checkout -b my-new-feature
   ```

2. Make your changes and commit them:
   ```
   git commit -am 'Add some feature'
   ```

3. Push to the branch:
   ```
   git push origin my-new-feature
   ```

4. Create a new Pull Request on GitHub.

## Releasing Updates

1. Update the version number in:
   - `latest-posts-for-elementor.php`
   - `readme.txt`

2. Update the changelog in `readme.txt`

3. Commit your changes and push to the main branch

4. Create a new release on GitHub with the new version number as the tag

5. Ensure the release includes a ZIP file of the plugin (excluding the `plugin-update-checker` directory)

## Plugin Update Checker

This plugin uses the [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker) library to enable automatic updates from GitHub. Here's what you need to know:

### Installation

1. Download the latest release of Plugin Update Checker from [its GitHub repository](https://github.com/YahnisElsts/plugin-update-checker).
2. Extract the contents to a `plugin-update-checker` directory within the plugin folder.

### Version Control

The `plugin-update-checker` directory is included in `.gitignore`. If you're cloning this repository for development, you'll need to manually add the Plugin Update Checker library as described above.

### Update Process

When a new release is created on GitHub:

1. Ensure the version number in `latest-posts-for-elementor.php` matches the new release version.
2. Update the changelog in `readme.txt`.
3. Create a new release on GitHub with the version number as the tag.
4. The plugin will automatically check for updates and notify users in the WordPress admin panel.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the GPL v2 or later.

## Credits

- [Elementor](https://elementor.com/) by Elementor.com, licensed under GPL
- [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker) by Yahnis Elsts, licensed under MIT