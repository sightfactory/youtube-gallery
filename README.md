# 🎥 YouTube Channel — WordPress Gallery Plugin

[![WordPress Plugin](https://img.shields.io/badge/WordPress-Plugin-blue.svg?logo=wordpress&logoColor=white)](https://wordpress.org/)
[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-green.svg)](https://www.gnu.org/licenses/gpl-3.0.html)
[![Version](https://img.shields.io/badge/Version-2.0-orange.svg)](#changelog)

A modern, responsive, and highly customizable WordPress plugin to display YouTube channel uploads or playlists as dynamic galleries. Featuring a customizable featured player layout, an interactive lightbox grid, performance-optimized transient caching, and native **Elementor Page Builder** integration with comprehensive styling controls.

---

## 🚀 Key Features

* **⚡ Two Layout Modes**:
  * **Standard Gallery**: Large featured player at the top with an interactive grid below. Clicking any grid video dynamically loads it into the featured player and scrolls smoothly to it.
  * **Lightbox Grid**: A clean, compact grid layout. Clicking any video opens a responsive overlay lightbox modal with customizable backdrop color and opacity.
* **🔌 Native Elementor Widgets**: Fully integrated widgets (`YouTube Gallery` and `YouTube Grid Gallery`) featuring comprehensive style controls for borders, shadows, backgrounds, icons, alignments, typography, and hover zoom effects.
* **🛡️ Security & Performance**:
  * Uses WordPress transients to cache API requests (duration customizable), preventing quota exhaustion and ensuring ultra-fast page loads.
  * AJAX "Load More" pagination protected by WordPress security nonces.
  * Lazy-loading on all thumbnail images to improve SEO and Core Web Vitals.
* **🧠 Smart Handle Resolution**: Automatically resolves YouTube channel URLs, usernames, handles (e.g., `@google`), or direct Channel IDs to retrieve uploads seamlessly.

---

## 🛠️ Installation & Setup

1. **Upload the Plugin**:
   * Download the plugin and extract the zip file.
   * Upload the `youtube-gallery` directory to your WordPress site's `/wp-content/plugins/` folder.
   * *Or*, upload the zip file directly via the WordPress Admin: **Plugins > Add New > Upload Plugin**.

2. **Activate the Plugin**:
   * Navigate to **Plugins > Installed Plugins** and click **Activate** under **YouTube Channel**.

3. **Configure Settings**:
   * Navigate to **Settings > YouTube Gallery** in the admin sidebar.
   * Enter your **YouTube Data API Key** (v3).
   * Specify default settings (default Channel URL/ID or default Playlist ID) and set your preferred **Cache Expiration** time.

---

## 📝 Shortcodes Guide

### 1. Standard Gallery shortcode
```html
[youtube_gallery]
```
Generates a featured player layout followed by a grid of thumbnails.

| Attribute | Type | Default | Description |
| :--- | :--- | :--- | :--- |
| `channel` | `string` | *Option Default* | YouTube channel ID, custom URL, or handle (e.g., `https://www.youtube.com/@google`). |
| `playlist` | `string` | *Option Default* | Specific YouTube Playlist ID (starts with `PL` or `UU`). |
| `max_results` | `int` | `11` | Number of initial videos to fetch (11 fits a standard 3 + 4 + 4 layout). |

**Example:**
```html
[youtube_gallery channel="https://www.youtube.com/@google" max_results="15"]
```

### 2. Lightbox Grid shortcode
```html
[youtube_gallery_grid]
```
Generates a pure grid. Clicking any video opens a high-performance lightbox overlay.

| Attribute | Type | Default | Description |
| :--- | :--- | :--- | :--- |
| `channel` | `string` | *Option Default* | YouTube channel ID, custom URL, or handle. |
| `playlist` | `string` | *Option Default* | Specific YouTube Playlist ID. |
| `max_results` | `int` | `12` | Number of initial videos to fetch. |
| `overlay_color` | `string` | `#000000` | Hex code for the lightbox backdrop background. |
| `overlay_opacity` | `string` | `0.8` | Opacity of the lightbox backdrop (from `0` to `1`). |

**Example:**
```html
[youtube_gallery_grid playlist="PLUhFi1bHlZKX7igvDfvk8z1oQbLqzZl6M" overlay_color="#1a1a2e" overlay_opacity="0.95"]
```

---

## 🎨 Elementor Integration

Once Elementor is active, you will find a new widget category called **YouTube Gallery** containing two native widgets:

### 1. YouTube Gallery Widget
Renders the standard featured player layout. Customize everything visually inside the Elementor Editor:
* **Query Settings**: Set override channels/playlists and video count.
* **Featured Player Styles**: Adjust border-radius and add box shadows.
* **Video Grid**: Toggle the special featured row layout (where the first 3 thumbnails span wider) or set fixed grid columns (1 to 6) and custom grid gaps.
* **Thumbnail Styles**: Configure border-radius, normal/hover box shadows, and hover zoom animation scale.
* **Play Overlay & Icons**: Change play overlay color, play icon background, icon arrow color, and icon size for normal and hover states.
* **Footer & Buttons**: Align the footer button group and apply custom typography, text/background colors, and border-radii for both the **Load More** and **Subscribe** buttons.

### 2. YouTube Grid Gallery Widget
Renders the lightbox grid layout. In addition to the standard widget styling controls, it adds:
* **Lightbox Overlay Options**: Edit the backdrop color and backdrop opacity sliders directly in the Content Tab.
* **Responsive Grid Width**: Customize the minimum thumbnail width (in `px` or `%`) to dynamically auto-fill columns.

---

## ⚙️ Technical Architecture

### 📂 File Structure
```text
youtube-gallery/
├── css/
│   └── style.css            # Responsive layout rules, lightbox styles, and Elementor selectors
├── includes/
│   ├── class-youtube-gallery-widget.php       # YouTube Gallery Elementor Widget Class
│   └── class-youtube-gallery-grid-widget.php  # YouTube Grid Gallery Elementor Widget Class
├── index.php                # Directory silence file
├── youtube-gallery.php      # Main plugin entry point (Shortcodes, API, AJAX, and Settings)
└── youtube.js               # Event handlers for featured player swap, lightbox, and AJAX loading
```

### 🧠 Under the Hood
1. **Handle/URL Resolution**: 
   When a user inputs a channel URL or handle like `@google`, the plugin performs a lookup against `googleapis.com/youtube/v3/channels` using `forHandle` or `forUsername`. The resolved 24-character Channel ID (starting with `UC`) is cached for 30 days in transients to optimize API requests.
2. **Automated Uploads Playlist Detection**:
   YouTube Channel IDs starting with `UC` are automatically converted to their uploads playlist counterparts by swapping `UC` to `UU`. This eliminates unnecessary API calls to locate the uploads playlist ID.
3. **Transient Caching**:
   API responses from `playlistItems` endpoints are stored as WordPress transients (`yt_gallery_` + md5 hash). Caching is fully configurable in the admin settings to respect API rate limits.
4. **Secure AJAX Pagination**:
   Clicking "Load More" triggers a secure WordPress admin AJAX endpoint (`youtube_gallery_load_more`). It passes the `playlist_id`, the `next_page_token` (from the YouTube API), and a verification `nonce` to validate the session.

---

## 📄 License

This project is licensed under the GPLv3 License - see the LICENSE file for details.
