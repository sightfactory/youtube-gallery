<?php
/**
 * YouTube Grid Gallery Widget for Elementor (Lightbox Mode)
 *
 * @package YouTube_Gallery
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * YouTube Grid Gallery Widget Class.
 */
class YouTube_Gallery_Grid_Widget extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'youtube-gallery-grid';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'YouTube Grid Gallery', 'youtube-gallery' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'youtube-gallery-category' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'youtube-gallery-script' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'youtube-gallery-style' );
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {
		// CONTENT TAB: Query Settings.
		$this->start_controls_section(
			'section_query',
			array(
				'label' => esc_html__( 'Query Settings', 'youtube-gallery' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'channel',
			array(
				'label'       => esc_html__( 'Channel ID or URL', 'youtube-gallery' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Leave empty to use global plugin default.', 'youtube-gallery' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'playlist',
			array(
				'label'       => esc_html__( 'Playlist ID', 'youtube-gallery' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Leave empty to use channel uploads playlist.', 'youtube-gallery' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'max_results',
			array(
				'label'   => esc_html__( 'Max Videos', 'youtube-gallery' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 50,
				'step'    => 1,
				'default' => 12,
			)
		);

		$this->end_controls_section();

		// CONTENT TAB: Lightbox Settings.
		$this->start_controls_section(
			'section_lightbox',
			array(
				'label' => esc_html__( 'Lightbox Overlay', 'youtube-gallery' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label'   => esc_html__( 'Backdrop Color', 'youtube-gallery' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#000000',
			)
		);

		$this->add_control(
			'overlay_opacity',
			array(
				'label'   => esc_html__( 'Backdrop Opacity', 'youtube-gallery' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.05,
					),
				),
				'default' => array(
					'size' => 0.8,
				),
			)
		);

		$this->end_controls_section();

		// STYLE TAB: Video Grid Style.
		$this->start_controls_section(
			'section_grid_style',
			array(
				'label' => esc_html__( 'Video Grid', 'youtube-gallery' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'column_min_width',
			array(
				'label'      => esc_html__( 'Min Thumbnail Width', 'youtube-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 150,
						'max' => 450,
					),
				),
				'default'    => array(
					'size' => 240,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .youtube-gallery-grid' => 'grid-template-columns: repeat(auto-fill, minmax({{SIZE}}{{UNIT}}, 1fr)) !important;',
				),
			)
		);

		$this->add_control(
			'grid_gap',
			array(
				'label'      => esc_html__( 'Grid Gap', 'youtube-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 16,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .youtube-gallery-grid' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// STYLE TAB: Thumbnail Style.
		$this->start_controls_section(
			'section_thumb_style',
			array(
				'label' => esc_html__( 'Thumbnails', 'youtube-gallery' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'thumb_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'youtube-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .youtube-gallery-thumbnail-wrapper' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_thumb_style' );

		$this->start_controls_tab(
			'tab_thumb_normal',
			array(
				'label' => esc_html__( 'Normal', 'youtube-gallery' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'thumb_box_shadow',
				'selector' => '{{WRAPPER}} .youtube-gallery-thumbnail-wrapper',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_thumb_hover',
			array(
				'label' => esc_html__( 'Hover', 'youtube-gallery' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'thumb_box_shadow_hover',
				'selector' => '{{WRAPPER}} .youtube-gallery-item:hover .youtube-gallery-thumbnail-wrapper',
			)
		);

		$this->add_control(
			'thumb_hover_zoom',
			array(
				'label'      => esc_html__( 'Zoom Scale', 'youtube-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 1.25,
						'step' => 0.01,
					),
				),
				'default'    => array(
					'size' => 1.04,
				),
				'selectors'  => array(
					'{{WRAPPER}} .youtube-gallery-item:hover img' => 'transform: scale({{SIZE}});',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// STYLE TAB: Play Overlay & Icon Style.
		$this->start_controls_section(
			'section_play_style',
			array(
				'label' => esc_html__( 'Play Icon & Overlay', 'youtube-gallery' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_play_style' );

		$this->start_controls_tab(
			'tab_play_normal',
			array(
				'label' => esc_html__( 'Normal', 'youtube-gallery' ),
			)
		);

		$this->add_control(
			'overlay_bg_color',
			array(
				'label'     => esc_html__( 'Overlay Color', 'youtube-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-play-overlay' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'play_icon_bg_color',
			array(
				'label'     => esc_html__( 'Icon Background Color', 'youtube-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-play-icon' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'play_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'youtube-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-play-icon::after' => 'border-color: transparent transparent transparent {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_play_hover',
			array(
				'label' => esc_html__( 'Hover', 'youtube-gallery' ),
			)
		);

		$this->add_control(
			'overlay_bg_color_hover',
			array(
				'label'     => esc_html__( 'Overlay Color', 'youtube-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-item:hover .youtube-gallery-play-overlay' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'play_icon_bg_color_hover',
			array(
				'label'     => esc_html__( 'Icon Background Color', 'youtube-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-item:hover .youtube-gallery-play-icon' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'play_icon_color_hover',
			array(
				'label'     => esc_html__( 'Icon Color', 'youtube-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-item:hover .youtube-gallery-play-icon::after' => 'border-color: transparent transparent transparent {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'play_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'youtube-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 30,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .youtube-gallery-play-icon' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
				),
			)
		);

		$this->end_controls_section();

		// STYLE TAB: Buttons Style.
		$this->start_controls_section(
			'section_buttons_style',
			array(
				'label' => esc_html__( 'Footer & Buttons', 'youtube-gallery' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'buttons_align',
			array(
				'label'     => esc_html__( 'Alignment', 'youtube-gallery' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'youtube-gallery' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'youtube-gallery' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'youtube-gallery' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-footer' => 'justify-content: {{VALUE}} !important;',
				),
			)
		);

		// Load More Button Styles.
		$this->add_control(
			'heading_load_more',
			array(
				'label'     => esc_html__( 'Load More Button', 'youtube-gallery' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'load_more_typography',
				'selector' => '{{WRAPPER}} .youtube-gallery-load-more',
			)
		);

		$this->start_controls_tabs( 'tabs_load_more' );

		$this->start_controls_tab(
			'tab_load_more_normal',
			array(
				'label' => esc_html__( 'Normal', 'youtube-gallery' ),
			)
		);

		$this->add_control(
			'load_more_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'youtube-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-load-more' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'load_more_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'youtube-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-load-more' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_load_more_hover',
			array(
				'label' => esc_html__( 'Hover', 'youtube-gallery' ),
			)
		);

		$this->add_control(
			'load_more_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'youtube-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-load-more:hover' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'load_more_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'youtube-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-load-more:hover' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'load_more_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'youtube-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .youtube-gallery-load-more' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		// Subscribe Button Styles.
		$this->add_control(
			'heading_subscribe',
			array(
				'label'     => esc_html__( 'Subscribe Button', 'youtube-gallery' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subscribe_typography',
				'selector' => '{{WRAPPER}} .youtube-gallery-visit-channel',
			)
		);

		$this->start_controls_tabs( 'tabs_subscribe' );

		$this->start_controls_tab(
			'tab_subscribe_normal',
			array(
				'label' => esc_html__( 'Normal', 'youtube-gallery' ),
			)
		);

		$this->add_control(
			'subscribe_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'youtube-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-visit-channel' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'subscribe_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'youtube-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-visit-channel' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_subscribe_hover',
			array(
				'label' => esc_html__( 'Hover', 'youtube-gallery' ),
			)
		);

		$this->add_control(
			'subscribe_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'youtube-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-visit-channel:hover' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'subscribe_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'youtube-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-visit-channel:hover' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'subscribe_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'youtube-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .youtube-gallery-visit-channel' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$overlay_color   = ! empty( $settings['overlay_color'] ) ? $settings['overlay_color'] : '#000000';
		$overlay_opacity = isset( $settings['overlay_opacity']['size'] ) ? floatval( $settings['overlay_opacity']['size'] ) : 0.8;

		$atts = array(
			'channel'         => ! empty( $settings['channel'] ) ? sanitize_text_field( $settings['channel'] ) : '',
			'playlist'        => ! empty( $settings['playlist'] ) ? sanitize_text_field( $settings['playlist'] ) : '',
			'max_results'     => ! empty( $settings['max_results'] ) ? intval( $settings['max_results'] ) : 12,
			'overlay_color'   => $overlay_color,
			'overlay_opacity' => $overlay_opacity,
		);

		// Enqueue scripts and styles.
		wp_enqueue_style( 'youtube-gallery-style' );
		wp_enqueue_script( 'youtube-gallery-script' );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo generate_youtube_gallery_grid( $atts );
	}
}
