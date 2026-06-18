(function($) {
    'use strict';

    $(document).ready(function() {
        // Handle clicking a video item in the grid
        $(document).on('click', '.youtube-gallery-item', function(e) {
            e.preventDefault();
            
            var $item = $(this);
            var videoId = $item.attr('data-video-id');
            var $container = $item.closest('.youtube-gallery-container');
            
            // Check if it's a grid-only gallery
            if ($container.hasClass('youtube-gallery-grid-only')) {
                openLightbox(videoId, $container);
                return;
            }

            var $player = $container.find('.youtube-gallery-featured iframe');
            
            if (videoId && $player.length) {
                // Update the player source with autoplay enabled
                var newSrc = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&enablejsapi=1';
                $player.attr('src', newSrc);
                
                // Scroll smoothly to the player
                $('html, body').animate({
                    scrollTop: $container.find('.youtube-gallery-featured').offset().top - 80
                }, 500);
            }
        });

        function openLightbox(videoId, $container) {
            var $lightbox = $('#youtube-gallery-lightbox');
            if (!$lightbox.length) {
                var lightboxHtml = 
                    '<div id="youtube-gallery-lightbox" class="youtube-gallery-lightbox">' +
                        '<div class="youtube-gallery-lightbox-backdrop"></div>' +
                        '<div class="youtube-gallery-lightbox-content">' +
                            '<button class="youtube-gallery-lightbox-close" aria-label="Close lightbox">&times;</button>' +
                            '<div class="youtube-gallery-player-wrapper">' +
                                '<iframe src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
                $('body').append(lightboxHtml);
                $lightbox = $('#youtube-gallery-lightbox');
                
                // Event listener to close when clicking close button or backdrop
                $lightbox.on('click', '.youtube-gallery-lightbox-close, .youtube-gallery-lightbox-backdrop', function(e) {
                    closeLightbox();
                });
            }
            
            // Get overlay configurations
            var overlayColor = $container.attr('data-overlay-color') || '#000000';
            var overlayOpacity = $container.attr('data-overlay-opacity') || '0.8';
            
            // Set styles dynamically
            $lightbox.css({
                '--ytg-overlay-color': overlayColor,
                '--ytg-overlay-opacity': overlayOpacity
            });
            
            // Update iframe src with autoplay
            var embedUrl = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&enablejsapi=1';
            $lightbox.find('iframe').attr('src', embedUrl);
            
            // Show lightbox
            $lightbox.addClass('active');
            $('body').addClass('youtube-gallery-lightbox-open');
        }
        
        function closeLightbox() {
            var $lightbox = $('#youtube-gallery-lightbox');
            if ($lightbox.length) {
                $lightbox.removeClass('active');
                $lightbox.find('iframe').attr('src', '');
                $('body').removeClass('youtube-gallery-lightbox-open');
            }
        }
        
        // Handle ESC key press to close lightbox
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                closeLightbox();
            }
        });

        // Handle clicking the "Load More" button
        $(document).on('click', '.youtube-gallery-load-more', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var $container = $button.closest('.youtube-gallery-container');
            var playlistId = $container.attr('data-playlist-id');
            var pageToken = $container.attr('data-next-page-token');
            var $grid = $container.find('.youtube-gallery-grid');
            
            if (!playlistId || !pageToken) {
                return;
            }

            // Disable button and show loading state
            $button.prop('disabled', true).text('Loading...');

            $.ajax({
                url: yt_gallery_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'youtube_gallery_load_more',
                    playlist_id: playlistId,
                    page_token: pageToken,
                    nonce: yt_gallery_ajax.nonce
                },
                success: function(response) {
                    if (response.success && response.data) {
                        // Append new items
                        $grid.append(response.data.html);
                        
                        // Update next page token
                        if (response.data.next_page_token) {
                            $container.attr('data-next-page-token', response.data.next_page_token);
                            $button.prop('disabled', false).text('Load More...');
                        } else {
                            $container.removeAttr('data-next-page-token');
                            $button.hide();
                        }
                    } else {
                        console.error('YouTube Gallery Error:', response.data.message || 'Unknown error');
                        $button.prop('disabled', false).text('Load More...');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('YouTube Gallery AJAX Error:', error);
                    $button.prop('disabled', false).text('Load More...');
                }
            });
        });
    });

})(jQuery);
