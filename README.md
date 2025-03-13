# WordPress Slideshow Plugin

A powerful and flexible WordPress plugin to create beautiful slideshows using a shortcode. This plugin allows you to manage slideshow images for individual pages and provides global settings for a consistent look and feel across your site.

---

## Features

1. **Custom Meta Box for Slideshows**:
   - Add and manage slideshow images directly within the page editor.
   - Upload and reorder images using a user-friendly interface.

2. **Global Settings**:
   - Configure global slideshow settings, including transition effects and autoplay speed.
   - Upload default images to be used across all slideshows.

3. **Shortcode Integration**:
   - Use the `[wp_slideshow]` shortcode to display slideshows on any page or post.
   - Fallback to global images if no page-specific images are found.

4. **Responsive and Lightweight**:
   - Built with Slick Slider, a lightweight and responsive jQuery plugin.
   - Works seamlessly on all devices.

---

## How to Use

### 1. Install the Plugin
1. Download the plugin files.
2. Upload the `wp-slideshow-plugin` folder to the `wp-content/plugins/` directory.
3. Activate the plugin through the **Plugins** menu in WordPress.

### 2. Add Slideshow Images
1. Go to **Pages > Edit**.
2. Scroll down to the **Slideshow Images** meta box.
3. Upload images and reorder them as needed.

### 3. Configure Global Settings
1. Go to **WP Slideshow > Settings** in the WordPress admin.
2. Configure the following settings:
   - **Transition Effect**: Choose between fade and slide.
   - **Autoplay Speed**: Set the autoplay speed in milliseconds.
   - **Global Slideshow Images**: Upload default images to be used across all slideshows.

### 4. Display the Slideshow
1. Add the `[wp_slideshow]` shortcode to any page or post.
2. Optionally, specify the page ID to display a specific slideshow:
   ```html
   [wp_slideshow id="123"]
