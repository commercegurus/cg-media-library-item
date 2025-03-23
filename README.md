# CG Media Library Item

A WordPress plugin that enhances the display of media library items with a modern, accessible interface.

## Description

CG Media Library Item provides a stylish and accessible way to display documents and files from your WordPress media library. The plugin creates a visually appealing component that shows the file type, title, size, and a download button.

## Features

- **Modern Design**: Clean, responsive layout for displaying media items
- **File Information**: Shows file type, title, and size
- **Download Button**: Easy access to download the file
- **Accessibility**: Built with WCAG compliance in mind
- **Elementor Integration**: Custom widget for Elementor page builder
- **Shortcode Support**: Use anywhere with a simple shortcode

## Installation

1. Upload the `cg-media-library-item` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the shortcode or Elementor widget to display media items

## Usage

### Shortcode

Use the shortcode with the following parameters:

```
[cg_media_library_item id="123" title="My Document" download-title="Download Now"]
```

Parameters:

- `id` (required): The WordPress media attachment ID
- `title` (optional): Custom title for the document (defaults to attachment title)
- `download-title` (optional): Text for the download button (defaults to "Download")

### Elementor Widget

1. Edit a page with Elementor
2. Search for "Media Library Item" in the widgets panel
3. Drag the widget to your page
4. Select a file from the media library
5. Customize the title and download button text if needed

## Development

### Requirements

- WordPress 6.0+
- PHP 7.4+
- Node.js and npm (for development)

### Local Development

1. Clone the repository
2. Install dependencies:
   ```
   npm install
   composer install
   ```
3. Start the local development environment:
   ```
   npm start
   ```

### Code Standards

This project follows the WordPress Coding Standards. To check your code:

```
composer run phpcs
```

## Changelog

### 1.0.0

- Initial release with basic functionality

## License

This project is licensed under the GPL v2 or later.

## Credits

- Document and download icons from [Heroicons](https://heroicons.com/)
- Built by [CommerceGurus](https://commercegurus.com)
