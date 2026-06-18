<?php
/*
Plugin Name: YouTube Channel
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Generates a youtube gallery with a custom player, interactive grid, and settings page.
Version: 2.0
Author: sightFACTORY
Author URI: http://www.sightfactory.com
License: GPL3
*/

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register options page & settings
add_action( 'admin_menu', 'youtube_gallery_add_admin_menu' );
add_action( 'admin_init', 'youtube_gallery_settings_init' );

function youtube_gallery_add_admin_menu() {
    add_options_page(
        'YouTube Gallery Settings',
        'YouTube Gallery',
        'manage_options',
        'youtube-gallery',
        'youtube_gallery_options_page'
    );
}

function youtube_gallery_settings_init() {
    register_setting( 'youtube_gallery_settings_group', 'youtube_gallery_options' );

    add_settings_section(
        'youtube_gallery_main_section',
        __( 'YouTube API & Default Configuration', 'youtube-gallery' ),
        'youtube_gallery_section_callback',
        'youtube-gallery-settings'
    );

    add_settings_field(
        'api_key',
        __( 'YouTube Data API Key', 'youtube-gallery' ),
        'youtube_gallery_api_key_callback',
        'youtube-gallery-settings',
        'youtube_gallery_main_section'
    );

    add_settings_field(
        'default_channel_id',
        __( 'Default Channel ID or URL', 'youtube-gallery' ),
        'youtube_gallery_channel_id_callback',
        'youtube-gallery-settings',
        'youtube_gallery_main_section'
    );

    add_settings_field(
        'default_playlist_id',
        __( 'Default Playlist ID', 'youtube-gallery' ),
        'youtube_gallery_playlist_id_callback',
        'youtube-gallery-settings',
        'youtube_gallery_main_section'
    );

    add_settings_field(
        'cache_time',
        __( 'Cache Expiration (Hours)', 'youtube-gallery' ),
        'youtube_gallery_cache_time_callback',
        'youtube-gallery-settings',
        'youtube_gallery_main_section'
    );
}

function youtube_gallery_section_callback() {
    echo '<p>' . __( 'Configure the global YouTube API settings. These defaults will be used if parameters are not specified in the shortcode.', 'youtube-gallery' ) . '</p>';
}

function youtube_gallery_api_key_callback() {
    $options = get_option( 'youtube_gallery_options' );
    $val = isset( $options['api_key'] ) ? $options['api_key'] : '';
    echo '<input type="password" name="youtube_gallery_options[api_key]" value="' . esc_attr( $val ) . '" class="regular-text" />';
}

function youtube_gallery_channel_id_callback() {
    $options = get_option( 'youtube_gallery_options' );
    $val = isset( $options['default_channel_id'] ) ? $options['default_channel_id'] : '';
    echo '<input type="text" name="youtube_gallery_options[default_channel_id]" value="' . esc_attr( $val ) . '" class="regular-text" />';
    echo '<p class="description">' . __( 'Enter a Channel ID (e.g. UCKSmOaLAQCm3WM4K1mCdoWw), full Channel URL, or handle (@channelhandle).', 'youtube-gallery' ) . '</p>';
}

function youtube_gallery_playlist_id_callback() {
    $options = get_option( 'youtube_gallery_options' );
    $val = isset( $options['default_playlist_id'] ) ? $options['default_playlist_id'] : '';
    echo '<input type="text" name="youtube_gallery_options[default_playlist_id]" value="' . esc_attr( $val ) . '" class="regular-text" />';
    echo '<p class="description">' . __( 'Optional. The default YouTube Playlist ID (starts with UU or PL, e.g. UUKSmOaLAQCm3WM4K1mCdoWw). If not set, it will automatically default to the Channel Uploads playlist.', 'youtube-gallery' ) . '</p>';
}

function youtube_gallery_cache_time_callback() {
    $options = get_option( 'youtube_gallery_options' );
    $val = isset( $options['cache_time'] ) ? intval( $options['cache_time'] ) : 12;
    echo '<input type="number" name="youtube_gallery_options[cache_time]" value="' . esc_attr( $val ) . '" min="1" max="168" class="small-text" /> Hours';
    echo '<p class="description">' . __( 'How long to cache YouTube API responses. Set to 12 hours by default to avoid hitting API limit.', 'youtube-gallery' ) . '</p>';
}

function youtube_gallery_options_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <div style="display: flex; flex-wrap: wrap; gap: 30px; margin-top: 20px;">
            <div style="flex: 2; min-width: 300px; max-width: 800px;">
                <form action="options.php" method="post">
                    <?php
                    settings_fields( 'youtube_gallery_settings_group' );
                    do_settings_sections( 'youtube-gallery-settings' );
                    submit_button( 'Save Settings' );
                    ?>
                </form>
            </div>
            <div style="flex: 1; min-width: 300px; max-width: 400px; background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px; height: fit-content; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                <h2 style="margin-top: 0; padding-bottom: 10px; border-bottom: 1px solid #eee;"><?php esc_html_e( 'Shortcode Usage Examples', 'youtube-gallery' ); ?></h2>

                <p><strong><?php esc_html_e( '1. Default Gallery:', 'youtube-gallery' ); ?></strong><br>
                <span class="description"><?php esc_html_e( 'Uses the settings configured on the left.', 'youtube-gallery' ); ?></span></p>
                <code style="display:block; padding: 8px; background: #f0f0f1; border-radius: 3px; margin-bottom: 15px;">[youtube_gallery]</code>

                <p><strong><?php esc_html_e( '2. Custom Channel (by URL/Handle):', 'youtube-gallery' ); ?></strong><br>
                <span class="description"><?php esc_html_e( 'Override the default channel using a URL or handle.', 'youtube-gallery' ); ?></span></p>
                <code style="display:block; padding: 8px; background: #f0f0f1; border-radius: 3px; margin-bottom: 15px;">[youtube_gallery channel="https://www.youtube.com/@google"]</code>

                <p><strong><?php esc_html_e( '3. Custom Playlist:', 'youtube-gallery' ); ?></strong><br>
                <span class="description"><?php esc_html_e( 'Directly load a specific playlist ID.', 'youtube-gallery' ); ?></span></p>
                <code style="display:block; padding: 8px; background: #f0f0f1; border-radius: 3px; margin-bottom: 15px;">[youtube_gallery playlist="PLUhFi1bHlZKX7igvDfvk8z1oQbLqzZl6M"]</code>

                <p><strong><?php esc_html_e( '4. Custom Number of Videos:', 'youtube-gallery' ); ?></strong><br>
                <span class="description"><?php esc_html_e( 'Specify how many videos to fetch initially.', 'youtube-gallery' ); ?></span></p>
                <code style="display:block; padding: 8px; background: #f0f0f1; border-radius: 3px; margin-bottom: 15px;">[youtube_gallery max_results="15"]</code>

                <p><strong><?php esc_html_e( '5. Grid Gallery (Lightbox Mode):', 'youtube-gallery' ); ?></strong><br>
                <span class="description"><?php esc_html_e( 'Generate just a video grid, clicking on videos opens a lightbox.', 'youtube-gallery' ); ?></span></p>
                <code style="display:block; padding: 8px; background: #f0f0f1; border-radius: 3px; margin-bottom: 15px;">[youtube_gallery_grid]</code>

                <p><strong><?php esc_html_e( '6. Grid Gallery with Custom Overlay:', 'youtube-gallery' ); ?></strong><br>
                <span class="description"><?php esc_html_e( 'Customize the background color and opacity of the lightbox overlay.', 'youtube-gallery' ); ?></span></p>
                <code style="display:block; padding: 8px; background: #f0f0f1; border-radius: 3px; margin-bottom: 5px;">[youtube_gallery_grid overlay_color="#1a1a2e" overlay_opacity="0.95"]</code>
            </div>
        </div>
    </div>
    <?php
}

// Enqueue styles and scripts
function youtube_gallery_enqueue_assets() {
    wp_enqueue_style(
        'youtube-gallery-style',
        plugins_url( 'css/style.css', __FILE__ ),
        array(),
        '1.2'
    );
    wp_enqueue_script(
        'youtube-gallery-script',
        plugins_url( 'youtube.js', __FILE__ ),
        array( 'jquery' ),
        '1.2',
        true
    );

    // Pass AJAX URL and security nonce to JavaScript
    wp_localize_script(
        'youtube-gallery-script',
        'yt_gallery_ajax',
        array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'youtube_gallery_load_more_nonce' ),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'youtube_gallery_enqueue_assets' );

// Resolve Channel URL, handle, or username to a Channel ID
function youtube_gallery_resolve_channel_id( $input, $api_key ) {
    $input = trim( $input );
    if ( empty( $input ) ) {
        return '';
    }

    // Check if it's already a valid 24-character Channel ID starting with UC
    if ( preg_match( '/^UC[a-zA-Z0-9_-]{22}$/', $input ) ) {
        return $input;
    }

    // Check if it's a YouTube URL
    if ( filter_var( $input, FILTER_VALIDATE_URL ) ) {
        $path = parse_url( $input, PHP_URL_PATH );

        // Match channel ID: /channel/UC...
        if ( preg_match( '/\/channel\/(UC[a-zA-Z0-9_-]{22})/', $path, $matches ) ) {
            return $matches[1];
        }

        // Match handle: /@handle
        if ( preg_match( '/\/@([a-zA-Z0-9_-]+)/', $path, $matches ) ) {
            $input = '@' . $matches[1];
        }
        // Match user: /user/name
        elseif ( preg_match( '/\/user\/([a-zA-Z0-9_-]+)/', $path, $matches ) ) {
            $input = $matches[1];
        }
    }

    // Cache the resolved ID so we don't repeat API calls
    $cache_key = 'yt_gallery_resolved_channel_' . md5( $input );
    $channel_id = get_transient( $cache_key );
    if ( false !== $channel_id ) {
        return $channel_id;
    }

    // If it's a handle (starts with @)
    if ( strpos( $input, '@' ) === 0 ) {
        $url = add_query_arg( array(
            'part'      => 'id',
            'forHandle' => $input,
            'key'       => $api_key,
        ), 'https://www.googleapis.com/youtube/v3/channels' );

        $response = wp_remote_get( $url );
        if ( ! is_wp_error( $response ) ) {
            $body = wp_remote_retrieve_body( $response );
            $data = json_decode( $body );
            if ( ! empty( $data->items[0]->id ) ) {
                $channel_id = $data->items[0]->id;
                set_transient( $cache_key, $channel_id, 30 * DAY_IN_SECONDS );
                return $channel_id;
            }
        }
    }

    // Fallback: Try forUsername (standard legacy username lookup)
    $url = add_query_arg( array(
        'part'        => 'id',
        'forUsername' => ltrim( $input, '@' ),
        'key'         => $api_key,
    ), 'https://www.googleapis.com/youtube/v3/channels' );

    $response = wp_remote_get( $url );
    if ( ! is_wp_error( $response ) ) {
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body );
        if ( ! empty( $data->items[0]->id ) ) {
            $channel_id = $data->items[0]->id;
            set_transient( $cache_key, $channel_id, 30 * DAY_IN_SECONDS );
            return $channel_id;
        }
    }

    return '';
}

// Resolve Channel ID to uploads playlist ID (legacy fallback helper)
function youtube_gallery_fetch_uploads_playlist( $channel_id, $api_key ) {
    $cache_key = 'yt_gallery_uploads_' . md5( $channel_id );
    $playlist_id = get_transient( $cache_key );

    if ( false === $playlist_id ) {
        $url = add_query_arg( array(
            'part' => 'contentDetails',
            'id'   => $channel_id,
            'key'  => $api_key,
        ), 'https://www.googleapis.com/youtube/v3/channels' );

        $response = wp_remote_get( $url );
        if ( ! is_wp_error( $response ) ) {
            $body = wp_remote_retrieve_body( $response );
            $data = json_decode( $body );
            if ( ! empty( $data->items[0]->contentDetails->relatedPlaylists->uploads ) ) {
                $playlist_id = $data->items[0]->contentDetails->relatedPlaylists->uploads;
                set_transient( $cache_key, $playlist_id, 30 * DAY_IN_SECONDS ); // Cache for 30 days
            }
        }
    }

    return $playlist_id ? $playlist_id : '';
}

// Main Shortcode Handlers
add_shortcode( 'youtube_gallery', 'generate_youtube_gallery' );
add_shortcode( 'youtube_gallery_grid', 'generate_youtube_gallery_grid' );

function generate_youtube_gallery( $atts ) {
    $atts = shortcode_atts(
        array(
            'channel'     => '',
            'playlist'    => '',
            'max_results' => 11, // Default 11 to match 3 + 4 + 4 grid layout
        ),
        $atts,
        'youtube_gallery'
    );

    $options = get_option( 'youtube_gallery_options' );
    $api_key = isset( $options['api_key'] ) ? trim( $options['api_key'] ) : '';

    if ( empty( $api_key ) ) {
        return '<p class="youtube-gallery-error">' . esc_html__( 'Please configure your YouTube API Key in the settings page.', 'youtube-gallery' ) . '</p>';
    }

    $channel_input = ! empty( $atts['channel'] ) ? trim( $atts['channel'] ) : ( isset( $options['default_channel_id'] ) ? trim( $options['default_channel_id'] ) : '' );
    $channel_id = youtube_gallery_resolve_channel_id( $channel_input, $api_key );
    $playlist_id = ! empty( $atts['playlist'] ) ? trim( $atts['playlist'] ) : ( isset( $options['default_playlist_id'] ) ? trim( $options['default_playlist_id'] ) : '' );

    // Auto-resolve playlist if empty
    if ( empty( $playlist_id ) && ! empty( $channel_id ) ) {
        if ( strpos( $channel_id, 'UC' ) === 0 ) {
            $playlist_id = 'UU' . substr( $channel_id, 2 );
        } else {
            $playlist_id = youtube_gallery_fetch_uploads_playlist( $channel_id, $api_key );
        }
    }

    if ( empty( $playlist_id ) ) {
        return '<p class="youtube-gallery-error">' . esc_html__( 'No valid YouTube Channel or Playlist specified.', 'youtube-gallery' ) . '</p>';
    }

    $cache_hours = isset( $options['cache_time'] ) ? intval( $options['cache_time'] ) : 12;
    $cache_seconds = $cache_hours * HOUR_IN_SECONDS;
    $transient_key = 'yt_gallery_' . md5( $playlist_id . '_first_page_' . $atts['max_results'] );

    $data = get_transient( $transient_key );

    if ( false === $data ) {
        $url = add_query_arg( array(
            'part'       => 'snippet',
            'playlistId' => $playlist_id,
            'key'        => $api_key,
            'maxResults' => intval( $atts['max_results'] ),
        ), 'https://www.googleapis.com/youtube/v3/playlistItems' );

        $response = wp_remote_get( $url );

        if ( is_wp_error( $response ) ) {
            return '<p class="youtube-gallery-error">' . esc_html__( 'Failed to fetch videos from YouTube.', 'youtube-gallery' ) . '</p>';
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body );

        if ( empty( $data ) || ! empty( $data->error ) ) {
            $error_msg = isset( $data->error->message ) ? $data->error->message : __( 'Unknown API error.', 'youtube-gallery' );
            return '<p class="youtube-gallery-error">' . sprintf( esc_html__( 'YouTube API Error: %s', 'youtube-gallery' ), esc_html( $error_msg ) ) . '</p>';
        }

        set_transient( $transient_key, $data, $cache_seconds );
    }

    if ( empty( $data->items ) ) {
        return '<p class="youtube-gallery-error">' . esc_html__( 'No videos found in this playlist.', 'youtube-gallery' ) . '</p>';
    }

    wp_enqueue_style( 'youtube-gallery-style' );
    wp_enqueue_script( 'youtube-gallery-script' );

    $videos = array();
    foreach ( $data->items as $item ) {
        $vid = isset( $item->snippet->resourceId->videoId ) ? $item->snippet->resourceId->videoId : '';
        if ( empty( $vid ) ) {
            continue;
        }
        $title = isset( $item->snippet->title ) ? $item->snippet->title : '';
        $thumb = isset( $item->snippet->thumbnails->high->url ) ? $item->snippet->thumbnails->high->url : ( isset( $item->snippet->thumbnails->medium->url ) ? $item->snippet->thumbnails->medium->url : '' );

        $videos[] = array(
            'id'        => $vid,
            'title'     => $title,
            'thumbnail' => $thumb,
        );
    }

    if ( empty( $videos ) ) {
        return '<p class="youtube-gallery-error">' . esc_html__( 'No valid videos found in this playlist.', 'youtube-gallery' ) . '</p>';
    }

    $featured_video = $videos[0];
    $next_page_token = isset( $data->nextPageToken ) ? $data->nextPageToken : '';
    $channel_id_resolved = isset( $data->items[0]->snippet->channelId ) ? $data->items[0]->snippet->channelId : $channel_id;
    $channel_url = 'https://www.youtube.com/channel/' . $channel_id_resolved;
    $gallery_unique_id = uniqid( 'ytg_' );

    ob_start();
    ?>
    <div class="youtube-gallery-container" id="youtube-gallery-<?php echo esc_attr( $gallery_unique_id ); ?>" data-playlist-id="<?php echo esc_attr( $playlist_id ); ?>" data-next-page-token="<?php echo esc_attr( $next_page_token ); ?>">
        <!-- Featured Video Player -->
        <div class="youtube-gallery-featured">
            <div class="youtube-gallery-player-wrapper">
                <iframe id="youtube-gallery-player-<?php echo esc_attr( $gallery_unique_id ); ?>" src="https://www.youtube.com/embed/<?php echo esc_attr( $featured_video['id'] ); ?>?enablejsapi=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>

        <!-- Video Grid -->
        <div class="youtube-gallery-grid">
            <?php foreach ( $videos as $index => $video ) : ?>
                <div class="youtube-gallery-item" data-video-id="<?php echo esc_attr( $video['id'] ); ?>" title="<?php echo esc_attr( $video['title'] ); ?>">
                    <div class="youtube-gallery-thumbnail-wrapper">
                        <img src="<?php echo esc_url( $video['thumbnail'] ); ?>" alt="<?php echo esc_attr( $video['title'] ); ?>" loading="lazy">
                        <div class="youtube-gallery-play-overlay">
                            <span class="youtube-gallery-play-icon"></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Gallery Footer Buttons -->
        <div class="youtube-gallery-footer">
            <button class="youtube-gallery-load-more" <?php echo empty( $next_page_token ) ? 'style="display:none;"' : ''; ?>><?php esc_html_e( 'Load More...', 'youtube-gallery' ); ?></button>
            <a href="<?php echo esc_url( $channel_url ); ?>" class="youtube-gallery-visit-channel" target="_blank" rel="noopener noreferrer">
                <svg viewBox="0 0 24 24" class="youtube-gallery-icon-svg" xmlns="http://www.w3.org/2000/svg">
                    <path fill="currentColor" d="M23.498 6.163a3.003 3.003 0 0 0-2.11-2.11C19.517 3.545 12 3.545 12 3.545s-7.517 0-9.388.508a3.003 3.003 0 0 0-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 0 0 2.11 2.11c1.871.508 9.388.508 9.388.508s7.517 0 9.388-.508a3.003 3.003 0 0 0 2.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
                <?php esc_html_e( 'Subscribe', 'youtube-gallery' ); ?>
            </a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function generate_youtube_gallery_grid( $atts ) {
    $atts = shortcode_atts(
        array(
            'channel'         => '',
            'playlist'        => '',
            'max_results'     => 12, // Default 12 for grid-only layout
            'overlay_color'   => '#000000',
            'overlay_opacity' => '0.8',
        ),
        $atts,
        'youtube_gallery_grid'
    );

    $options = get_option( 'youtube_gallery_options' );
    $api_key = isset( $options['api_key'] ) ? trim( $options['api_key'] ) : '';

    if ( empty( $api_key ) ) {
        return '<p class="youtube-gallery-error">' . esc_html__( 'Please configure your YouTube API Key in the settings page.', 'youtube-gallery' ) . '</p>';
    }

    $channel_input = ! empty( $atts['channel'] ) ? trim( $atts['channel'] ) : ( isset( $options['default_channel_id'] ) ? trim( $options['default_channel_id'] ) : '' );
    $channel_id = youtube_gallery_resolve_channel_id( $channel_input, $api_key );
    $playlist_id = ! empty( $atts['playlist'] ) ? trim( $atts['playlist'] ) : ( isset( $options['default_playlist_id'] ) ? trim( $options['default_playlist_id'] ) : '' );

    // Auto-resolve playlist if empty
    if ( empty( $playlist_id ) && ! empty( $channel_id ) ) {
        if ( strpos( $channel_id, 'UC' ) === 0 ) {
            $playlist_id = 'UU' . substr( $channel_id, 2 );
        } else {
            $playlist_id = youtube_gallery_fetch_uploads_playlist( $channel_id, $api_key );
        }
    }

    if ( empty( $playlist_id ) ) {
        return '<p class="youtube-gallery-error">' . esc_html__( 'No valid YouTube Channel or Playlist specified.', 'youtube-gallery' ) . '</p>';
    }

    $cache_hours = isset( $options['cache_time'] ) ? intval( $options['cache_time'] ) : 12;
    $cache_seconds = $cache_hours * HOUR_IN_SECONDS;
    $transient_key = 'yt_gallery_' . md5( $playlist_id . '_grid_page_' . $atts['max_results'] );

    $data = get_transient( $transient_key );

    if ( false === $data ) {
        $url = add_query_arg( array(
            'part'       => 'snippet',
            'playlistId' => $playlist_id,
            'key'        => $api_key,
            'maxResults' => intval( $atts['max_results'] ),
        ), 'https://www.googleapis.com/youtube/v3/playlistItems' );

        $response = wp_remote_get( $url );

        if ( is_wp_error( $response ) ) {
            return '<p class="youtube-gallery-error">' . esc_html__( 'Failed to fetch videos from YouTube.', 'youtube-gallery' ) . '</p>';
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body );

        if ( empty( $data ) || ! empty( $data->error ) ) {
            $error_msg = isset( $data->error->message ) ? $data->error->message : __( 'Unknown API error.', 'youtube-gallery' );
            return '<p class="youtube-gallery-error">' . sprintf( esc_html__( 'YouTube API Error: %s', 'youtube-gallery' ), esc_html( $error_msg ) ) . '</p>';
        }

        set_transient( $transient_key, $data, $cache_seconds );
    }

    if ( empty( $data->items ) ) {
        return '<p class="youtube-gallery-error">' . esc_html__( 'No videos found in this playlist.', 'youtube-gallery' ) . '</p>';
    }

    wp_enqueue_style( 'youtube-gallery-style' );
    wp_enqueue_script( 'youtube-gallery-script' );

    $videos = array();
    foreach ( $data->items as $item ) {
        $vid = isset( $item->snippet->resourceId->videoId ) ? $item->snippet->resourceId->videoId : '';
        if ( empty( $vid ) ) {
            continue;
        }
        $title = isset( $item->snippet->title ) ? $item->snippet->title : '';
        $thumb = isset( $item->snippet->thumbnails->high->url ) ? $item->snippet->thumbnails->high->url : ( isset( $item->snippet->thumbnails->medium->url ) ? $item->snippet->thumbnails->medium->url : '' );

        $videos[] = array(
            'id'        => $vid,
            'title'     => $title,
            'thumbnail' => $thumb,
        );
    }

    if ( empty( $videos ) ) {
        return '<p class="youtube-gallery-error">' . esc_html__( 'No valid videos found in this playlist.', 'youtube-gallery' ) . '</p>';
    }

    $next_page_token = isset( $data->nextPageToken ) ? $data->nextPageToken : '';
    $channel_id_resolved = isset( $data->items[0]->snippet->channelId ) ? $data->items[0]->snippet->channelId : $channel_id;
    $channel_url = 'https://www.youtube.com/channel/' . $channel_id_resolved;
    $gallery_unique_id = uniqid( 'ytg_' );

    ob_start();
    ?>
    <div class="youtube-gallery-container youtube-gallery-grid-only"
         id="youtube-gallery-<?php echo esc_attr( $gallery_unique_id ); ?>"
         data-playlist-id="<?php echo esc_attr( $playlist_id ); ?>"
         data-next-page-token="<?php echo esc_attr( $next_page_token ); ?>"
         data-overlay-color="<?php echo esc_attr( $atts['overlay_color'] ); ?>"
         data-overlay-opacity="<?php echo esc_attr( $atts['overlay_opacity'] ); ?>">

        <!-- Video Grid -->
        <div class="youtube-gallery-grid">
            <?php foreach ( $videos as $video ) : ?>
                <div class="youtube-gallery-item" data-video-id="<?php echo esc_attr( $video['id'] ); ?>" title="<?php echo esc_attr( $video['title'] ); ?>">
                    <div class="youtube-gallery-thumbnail-wrapper">
                        <img src="<?php echo esc_url( $video['thumbnail'] ); ?>" alt="<?php echo esc_attr( $video['title'] ); ?>" loading="lazy">
                        <div class="youtube-gallery-play-overlay">
                            <span class="youtube-gallery-play-icon"></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Gallery Footer Buttons -->
        <div class="youtube-gallery-footer">
            <button class="youtube-gallery-load-more" <?php echo empty( $next_page_token ) ? 'style="display:none;"' : ''; ?>><?php esc_html_e( 'Load More...', 'youtube-gallery' ); ?></button>
            <a href="<?php echo esc_url( $channel_url ); ?>" class="youtube-gallery-visit-channel" target="_blank" rel="noopener noreferrer">
                <svg viewBox="0 0 24 24" class="youtube-gallery-icon-svg" xmlns="http://www.w3.org/2000/svg">
                    <path fill="currentColor" d="M23.498 6.163a3.003 3.003 0 0 0-2.11-2.11C19.517 3.545 12 3.545 12 3.545s-7.517 0-9.388.508a3.003 3.003 0 0 0-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 0 0 2.11 2.11c1.871.508 9.388.508 9.388.508s7.517 0 9.388-.508a3.003 3.003 0 0 0 2.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
                <?php esc_html_e( 'Subscribe', 'youtube-gallery' ); ?>
            </a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// AJAX Load More Endpoint
add_action( 'wp_ajax_youtube_gallery_load_more', 'youtube_gallery_load_more_handler' );
add_action( 'wp_ajax_nopriv_youtube_gallery_load_more', 'youtube_gallery_load_more_handler' );

function youtube_gallery_load_more_handler() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'youtube_gallery_load_more_nonce' ) ) {
        wp_send_json_error( array( 'message' => 'Invalid security token' ), 403 );
    }

    $playlist_id = isset( $_POST['playlist_id'] ) ? sanitize_text_field( $_POST['playlist_id'] ) : '';
    $page_token = isset( $_POST['page_token'] ) ? sanitize_text_field( $_POST['page_token'] ) : '';

    if ( empty( $playlist_id ) || empty( $page_token ) ) {
        wp_send_json_error( array( 'message' => 'Missing parameters' ), 400 );
    }

    $options = get_option( 'youtube_gallery_options' );
    $api_key = isset( $options['api_key'] ) ? trim( $options['api_key'] ) : '';

    if ( empty( $api_key ) ) {
        wp_send_json_error( array( 'message' => 'API Key not configured' ), 500 );
    }

    $cache_hours = isset( $options['cache_time'] ) ? intval( $options['cache_time'] ) : 12;
    $cache_seconds = $cache_hours * HOUR_IN_SECONDS;
    $transient_key = 'yt_gallery_' . md5( $playlist_id . '_' . $page_token . '_8' );

    $data = get_transient( $transient_key );

    if ( false === $data ) {
        $url = add_query_arg( array(
            'part'       => 'snippet',
            'playlistId' => $playlist_id,
            'key'        => $api_key,
            'maxResults' => 8, // Load 8 more videos per page
            'pageToken'  => $page_token,
        ), 'https://www.googleapis.com/youtube/v3/playlistItems' );

        $response = wp_remote_get( $url );

        if ( is_wp_error( $response ) ) {
            wp_send_json_error( array( 'message' => 'Failed to fetch videos' ), 500 );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body );

        if ( empty( $data ) || ! empty( $data->error ) ) {
            wp_send_json_error( array( 'message' => 'API error' ), 500 );
        }

        set_transient( $transient_key, $data, $cache_seconds );
    }

    $html = '';
    if ( ! empty( $data->items ) ) {
        foreach ( $data->items as $item ) {
            $vid = isset( $item->snippet->resourceId->videoId ) ? $item->snippet->resourceId->videoId : '';
            if ( empty( $vid ) ) {
                continue;
            }
            $title = isset( $item->snippet->title ) ? $item->snippet->title : '';
            $thumb = isset( $item->snippet->thumbnails->high->url ) ? $item->snippet->thumbnails->high->url : ( isset( $item->snippet->thumbnails->medium->url ) ? $item->snippet->thumbnails->medium->url : '' );

            ob_start();
            ?>
            <div class="youtube-gallery-item" data-video-id="<?php echo esc_attr( $vid ); ?>" title="<?php echo esc_attr( $title ); ?>">
                <div class="youtube-gallery-thumbnail-wrapper">
                    <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
                    <div class="youtube-gallery-play-overlay">
                        <span class="youtube-gallery-play-icon"></span>
                    </div>
                </div>
            </div>
            <?php
            $html .= ob_get_clean();
        }
    }

    $next_page_token = isset( $data->nextPageToken ) ? $data->nextPageToken : '';

    wp_send_json_success( array(
        'html'            => $html,
        'next_page_token' => $next_page_token,
    ) );
}

add_action( 'elementor/elements/categories_registered', 'ytgal_register_elementor_category' );
/**
 * Register custom category for Elementor.
 *
 * @param object $elements_manager Elementor elements manager.
 */
function ytgal_register_elementor_category( $elements_manager ) {
	$elements_manager->add_category(
		'youtube-gallery-category',
		array(
			'title' => esc_html__( 'YouTube Gallery', 'youtube-gallery' ),
			'icon'  => 'eicon-youtube',
		)
	);
}

add_action( 'elementor/widgets/register', 'ytgal_register_elementor_widgets' );
/**
 * Register Elementor widgets.
 *
 * @param object $widgets_manager Elementor widgets manager.
 */
function ytgal_register_elementor_widgets( $widgets_manager ) {
	$includes_dir = plugin_dir_path( __FILE__ ) . 'includes/';

	if ( file_exists( $includes_dir . 'class-youtube-gallery-widget.php' ) ) {
		require_once $includes_dir . 'class-youtube-gallery-widget.php';
		$widgets_manager->register( new \YouTube_Gallery_Widget() );
	}

	if ( file_exists( $includes_dir . 'class-youtube-gallery-grid-widget.php' ) ) {
		require_once $includes_dir . 'class-youtube-gallery-grid-widget.php';
		$widgets_manager->register( new \YouTube_Gallery_Grid_Widget() );
	}
}



