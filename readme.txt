=== YouTube Channel ===
Contributors: Sightactory
Tags: youtube, video, gallery, elementor, youtube gallery, youtube channel, video player, video grid, lightbox
Requires at least: 5.6
Tested up to: 6.5
Stable tag: 2.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Generates a YouTube gallery with a custom player, interactive grid, and settings page. Fully integrated with Elementor.

== Description ==

YouTube Channel is a modern, responsive, and highly customizable WordPress plugin that integrates YouTube channels or playlists into your website. It provides gorgeous gallery layouts, a custom player, an interactive video grid, and a full Elementor integration.

Features:
* **Custom Featured Player**: Display a featured video at the top of a custom layout with a smooth play/pause interaction.
* **Interactive Grid Layout**: Responsive grid that scales from 1 to 4 columns. Special featured layout for the first 3 items in the row.
* **Grid-Only Lightbox Mode**: Display videos as a grid where clicking thumbnails launches a responsive overlay lightbox player.
* **Elementor Page Builder Integration**: Two native Elementor widgets (`YouTube Gallery` and `YouTube Grid Gallery`) with extensive styling controls for border-radius, shadows, colors, typography, gaps, alignment, and hover animations.
* **Performance-First Caching**: Automatic transient caching of YouTube API requests (configurable duration) to prevent exceeding API limits and speed up page load.
* **AJAX "Load More" Pagination**: Dynamic pagination that fetches and appends videos asynchronously without page reloads.
* **YouTube Handle & Channel URL Resolution**: Supports inputting Channel URLs, legacy usernames, handles (e.g., `@google`), or direct Channel IDs.
* **Custom Overlay Styles**: Full controls for lightbox overlay backdrop color and opacity.

== Installation ==

1. Upload the entire `youtube-gallery` folder to the `/wp-content/plugins/` directory, or install the plugin directly through the WordPress admin panel.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to **Settings -> YouTube Gallery** in the WordPress admin menu.
4. Enter your **YouTube Data API Key** and set default options (such as default Channel ID or Playlist ID).
5. Add the gallery to your pages or posts using shortcodes or Elementor widgets.

== Shortcodes ==

= [youtube_gallery] =
Generates a default YouTube gallery with a featured player at the top and a video grid below.

Attributes:
* `channel` (string): URL, handle, or ID of the YouTube channel. Defaults to global settings.
* `playlist` (string): Specific playlist ID to load. Defaults to channel uploads playlist.
* `max_results` (int): Number of initial videos to fetch. Default: 11.

Example:
`[youtube_gallery channel="https://www.youtube.com/@google" max_results="15"]`

= [youtube_gallery_grid] =
Generates a grid-only gallery where clicking a thumbnail opens the video in a modal lightbox.

Attributes:
* `channel` (string): URL, handle, or ID of the YouTube channel.
* `playlist` (string): Specific playlist ID to load.
* `max_results` (int): Number of initial videos to fetch. Default: 12.
* `overlay_color` (hex color): Custom background color for the lightbox. Default: `#000000`.
* `overlay_opacity` (float): Backdrop opacity (0 to 1). Default: `0.8`.

Example:
`[youtube_gallery_grid playlist="PLUhFi1bHlZKX7igvDfvk8z1oQbLqzZl6M" overlay_color="#1a1a2e" overlay_opacity="0.95"]`

== Elementor Widgets ==

The plugin registers a custom Elementor category **YouTube Gallery** with two widgets:

1. **YouTube Gallery**:
   * **Query Settings**: Channel ID/URL, Playlist ID, Max Videos.
   * **Styling Controls**:
     * Featured Player: Border-radius, Box Shadow.
     * Video Grid: Toggle Featured First Row layout (first 3 span wider), Columns layout (1 to 6 columns if not featured), Grid Gap.
     * Thumbnails: Border-radius, Box shadow (Normal/Hover), Hover Zoom scale.
     * Play Icon & Overlay: Colors (Normal/Hover), Icon Background Color, Icon Color, Icon Size.
     * Footer & Buttons: Layout alignment (Left/Center/Right), Load More typography, colors, border-radius, Subscribe button typography, colors, border-radius.

2. **YouTube Grid Gallery**:
   * Same Query Settings as above.
   * **Lightbox Overlay Settings**: Custom Backdrop Color and Backdrop Opacity.
   * **Styling Controls**:
     * Video Grid: Minimum Thumbnail Width (responsive grid item size), Grid Gap.
     * Thumbnails, Play Icon & Overlay, and Footer & Buttons settings matching the standard gallery widget.

== Frequently Asked Questions ==

= How do I get a YouTube Data API Key? =
Go to the Google Cloud Console, create a project, enable the YouTube Data API v3, and generate an API key. Copy and paste this key into the plugin settings.

= Can I use handles like @channelname? =
Yes, the plugin automatically resolves handles (e.g. `@google`) and channel URLs to their respective Channel IDs.

= How does caching work? =
To prevent hitting YouTube API quotas, the plugin caches responses in WordPress transients. You can adjust the expiration time (in hours) on the settings page (default is 12 hours).

== Changelog ==

= 2.0 =
* Added Elementor widgets integration (`YouTube Gallery` and `YouTube Grid Gallery`).
* Added responsive lightbox mode for grid-only galleries.
* Improved API caching and AJAX pagination.

= 1.2 =
* Updated script and stylesheet enqueues.
* Added AJAX security nonces.

= 1.0 =
* Initial release of the plugin with shortcode features.
