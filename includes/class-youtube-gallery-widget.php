<?php
/**
 * YouTube Gallery Widget for Elementor
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
 * YouTube Gallery Widget Class.
 */
class YouTube_Gallery_Widget extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'youtube-gallery';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'YouTube Gallery', 'youtube-gallery' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-youtube';
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
				'default' => 11,
			)
		);

		$this->end_controls_section();

		// STYLE TAB: Featured Player Style.
		$this->start_controls_section(
			'section_player_style',
			array(
				'label' => esc_html__( 'Featured Player', 'youtube-gallery' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'player_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'youtube-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .youtube-gallery-featured' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'player_box_shadow',
				'selector' => '{{WRAPPER}} .youtube-gallery-featured',
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
			'featured_grid_layout',
			array(
				'label'        => esc_html__( 'Featured First Row', 'youtube-gallery' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'youtube-gallery' ),
				'label_off'    => esc_html__( 'No', 'youtube-gallery' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Make the first 3 items in the grid span wider.', 'youtube-gallery' ),
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'     => esc_html__( 'Columns', 'youtube-gallery' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4',
				'options'   => array(
					'12' => '1',
					'6'  => '2',
					'4'  => '3',
					'3'  => '4',
					'2'  => '6',
				),
				'selectors' => array(
					'{{WRAPPER}} .youtube-gallery-item' => 'grid-column: span {{VALUE}} !important;',
					'{{WRAPPER}} .youtube-gallery-item:nth-child(-n+3)' => 'grid-column: span {{VALUE}} !important;',
				),
				'condition' => array(
					'featured_grid_layout!' => 'yes',
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

		$atts = array(
			'channel'     => ! empty( $settings['channel'] ) ? sanitize_text_field( $settings['channel'] ) : '',
			'playlist'    => ! empty( $settings['playlist'] ) ? sanitize_text_field( $settings['playlist'] ) : '',
			'max_results' => ! empty( $settings['max_results'] ) ? intval( $settings['max_results'] ) : 11,
		);

		// Enqueue scripts and styles to verify they are loaded in editor preview.
		wp_enqueue_style( 'youtube-gallery-style' );
		wp_enqueue_script( 'youtube-gallery-script' );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo generate_youtube_gallery( $atts );
	}
}
