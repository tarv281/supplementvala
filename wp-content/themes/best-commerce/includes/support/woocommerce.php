<?php
/**
 * WooCommerce support class.
 *
 * @package Best_Commerce
 */

/**
 * Woocommerce support class.
 *
 * @since 1.0.0
 */
class Best_Commerce_Woocommerce {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		$this->init();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 */
	function init() {

		// Wrapper.
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		add_action( 'woocommerce_before_main_content', array( $this, 'woo_wrapper_start' ), 10 );
		add_action( 'woocommerce_after_main_content', array( $this, 'woo_wrapper_end' ), 10 );

		// Breadcrumb.
		add_filter( 'woocommerce_breadcrumb_defaults', array( $this, 'custom_woocommerce_breadcrumbs_defaults' ) );
		add_action( 'wp', array( $this, 'hooking_woo' ) );

		// Sidebar.
		add_action( 'woocommerce_sidebar', array( $this, 'add_secondary_sidebar' ), 11 );

		// Modify global layout.
		add_filter( 'best_commerce_filter_theme_global_layout', array( $this, 'modify_global_layout' ), 15 );

		// Customizer options.
		add_action( 'customize_register', array( $this, 'customizer_fields' ) );

		// Add default options.
		add_filter( 'best_commerce_filter_default_theme_options', array( $this, 'default_options' ) );

		// Remove archive title.
		add_filter( 'woocommerce_show_page_title', '__return_false' );

		// Remove product title.
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

		// Loop per page.
		add_filter( 'loop_shop_per_page', array( $this, 'custom_loop_shop_per_page' ) );

		// Loop columns.
		add_filter( 'loop_shop_columns', array( $this, 'custom_loop_columns' ) );

		// Upsell columns.
		add_filter( 'woocommerce_upsells_columns', array( $this, 'custom_upsell_columns' ) );

		// Related posts loop columns.
		add_filter( 'woocommerce_related_products_columns', array( $this, 'custom_related_products_columns' ) );

		// Loop image size.
		add_filter( 'single_product_archive_thumbnail_size', array( $this, 'loop_image_size' ) );
	}

	/**
	 * Default options.
	 *
	 * @param  array $input Passed default options.
	 * @return array Modified default options.
	 */
	function default_options( $input ) {

		$input['woo_page_layout']       = 'right-sidebar';
		$input['woo_product_per_page']  = 12;
		$input['woo_product_per_row']   = 3;
		$input['woo_sorting_dropdown']  = true;
		return $input;

	}

	/**
	 * Hooking Woocommerce.
	 *
	 * @since 1.0.0
	 */
	function hooking_woo() {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

		if ( is_woocommerce() ) {
			add_action( 'best_commerce_add_breadcrumb', 'woocommerce_breadcrumb', 10 );
			remove_action( 'best_commerce_add_breadcrumb', 'best_commerce_add_breadcrumb', 10 );
		}

		// Fixing primary sidebar.
		$global_layout = best_commerce_get_option( 'global_layout' );
		$global_layout = apply_filters( 'best_commerce_filter_theme_global_layout', $global_layout );

		if ( in_array( $global_layout, array( 'no-sidebar' ), true ) ) {
			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
		}

		// Hide page title.
		if ( is_shop() ) {
			add_filter( 'woocommerce_show_page_title', '__return_false' );
		}

		// Hide custom header in shop page.
		if ( is_shop() && is_front_page() ) {
			remove_action( 'best_commerce_action_before_content', 'best_commerce_add_custom_header', 6 );
		}

		// Custom shop title.
		if ( is_shop() && ! is_front_page() ) {
			remove_action( 'best_commerce_action_custom_header_title', 'best_commerce_add_title_in_custom_header' );
			add_action( 'best_commerce_action_custom_header_title', array( $this, 'custom_shop_title' ) );
		}

		$woo_sorting_dropdown = best_commerce_get_option( 'woo_sorting_dropdown' );
		if ( false === $woo_sorting_dropdown ) {
			// Hide sorting dropdown.
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		}
	}

	/**
	 * Modify global layout.
	 *
	 * @since 1.0.0
	 *
	 * @param string $layout Layout.
	 */
	function modify_global_layout( $layout ) {

		$woo_page_layout = best_commerce_get_option( 'woo_page_layout' );

		if ( is_woocommerce() && ! empty( $woo_page_layout ) ) {
			$layout = esc_attr( $woo_page_layout );
		}

		// Fix for shop page.
		if ( is_shop() && ( $shop_id = absint( wc_get_page_id( 'shop' ) ) ) > 0 ) {
			$post_options = get_post_meta( $shop_id, 'best_commerce_settings', true );
			if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
				$layout = esc_attr( $post_options['post_layout'] );
			}
		}

		return $layout;
	}

	/**
	 * Add extra customizer options for WooCommerce.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	function customizer_fields( $wp_customize ) {

		$default = best_commerce_get_default_theme_options();

		// WooCommerce Section.
		$wp_customize->add_section( 'section_theme_woocommerce',
			array(
				'title'       => esc_html__( 'WooCommerce Options', 'best-commerce' ),
				'description' => esc_html__( 'Settings specific to WooCommerce. Note: WooCommerce Page means shop page, product page and product archive page.', 'best-commerce' ),
				'priority'    => 100,
				'capability'  => 'edit_theme_options',
				'panel'       => 'theme_option_panel',
			)
		);

		// Setting - woo_page_layout.
		$wp_customize->add_setting( 'theme_options[woo_page_layout]',
			array(
				'default'           => $default['woo_page_layout'],
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'best_commerce_sanitize_select',
			)
		);
		$wp_customize->add_control( 'theme_options[woo_page_layout]',
			array(
				'label'   => esc_html__( 'Content Layout', 'best-commerce' ),
				'section' => 'section_theme_woocommerce',
				'type'    => 'select',
				'choices' => best_commerce_get_global_layout_options(),
			)
		);

		// Setting - woo_product_per_page.
		$wp_customize->add_setting(
			'theme_options[woo_product_per_page]',
			array(
				'default'           => $default['woo_product_per_page'],
				'sanitize_callback' => 'best_commerce_sanitize_positive_integer',
			)
		);
		$wp_customize->add_control(
			'theme_options[woo_product_per_page]',
			array(
				'label'       => esc_html__( 'Products Per Page', 'best-commerce' ),
				'section'     => 'section_theme_woocommerce',
				'type'        => 'number',
				'input_attrs' => array(
					'min'   => 1,
					'max'   => 100,
					'style' => 'width: 55px;',
				),
			)
		);

		// Setting - woo_product_per_row.
		$wp_customize->add_setting(
			'theme_options[woo_product_per_row]',
			array(
				'default'           => $default['woo_product_per_row'],
				'sanitize_callback' => 'best_commerce_sanitize_positive_integer',
			)
		);
		$wp_customize->add_control(
			'theme_options[woo_product_per_row]',
			array(
				'label'       => esc_html__( 'Products Per Row', 'best-commerce' ),
				'section'     => 'section_theme_woocommerce',
				'type'        => 'number',
				'input_attrs' => array(
					'min'   => 3,
					'max'   => 4,
					'style' => 'width: 55px;',
				),
			)
		);

		// Setting - woo_sorting_dropdown.
		$wp_customize->add_setting(
			'theme_options[woo_sorting_dropdown]',
			array(
				'default'           => $default['woo_sorting_dropdown'],
				'sanitize_callback' => 'best_commerce_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			'theme_options[woo_sorting_dropdown]',
			array(
				'label'   => esc_html__( 'Enable Sorting Dropdown', 'best-commerce' ),
				'section' => 'section_theme_woocommerce',
				'type'    => 'checkbox',
			)
		);

	}

	/**
	 * Add secondary sidebar in Woocommerce.
	 *
	 * @since 1.0.0
	 */
	function add_secondary_sidebar() {

		$global_layout = best_commerce_get_option( 'global_layout' );
		$global_layout = apply_filters( 'best_commerce_filter_theme_global_layout', $global_layout );

		switch ( $global_layout ) {
			case 'three-columns':
				get_sidebar( 'secondary' );
			break;

			default:
			break;
		}

	}

	/**
	 * Woocommerce content wrapper start.
	 *
	 * @since 1.0.0
	 */
	function woo_wrapper_start() {
		echo '<div id="primary">';
		echo '<main role="main" class="site-main" id="main">';
	}

	/**
	 * Woocommerce content wrapper end.
	 *
	 * @since 1.0.0
	 */
	function woo_wrapper_end() {
		echo '</main><!-- #main -->';
		echo '</div><!-- #primary -->';
	}

	/**
	 * Woocommerce breadcrumb defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param array $defaults Breadcrumb defaults.
	 * @return array Modified breadcrumb defaults.
	 */
	function custom_woocommerce_breadcrumbs_defaults( $defaults ) {

		$defaults['delimiter']   = '';
		$defaults['wrap_before'] = '<div id="breadcrumb" itemprop="breadcrumb"><ul id="crumbs">';
		$defaults['wrap_after']  = '</ul></div>';
		$defaults['before']      = '<li>';
		$defaults['after']       = '</li>';
		$defaults['home']        = esc_html__( 'Home', 'best-commerce' );
		return $defaults;

	}

	/**
	 * Custom loop shop per page.
	 *
	 * @since 1.0.0
	 *
	 * @param int $col Number.
	 * @return int Modified number.
	 */
	function custom_loop_shop_per_page( $col ) {
		$woo_product_per_page = best_commerce_get_option( 'woo_product_per_page' );

		if ( absint( $woo_product_per_page ) > 0 ) {
			$col = absint( $woo_product_per_page );
		}

		return $col;
	}

	/**
	 * Custom loop columns.
	 *
	 * @since 1.0.0
	 *
	 * @param string $column Number.
	 * @return string Modified number.
	 */
	function custom_loop_columns( $column ) {
		$woo_product_per_row = best_commerce_get_option( 'woo_product_per_row' );

		if ( absint( $woo_product_per_row ) > 0 ) {
			$column = absint( $woo_product_per_row );
		}

		return $column;
	}

	/**
	 * Custom upsell columns.
	 *
	 * @since 1.0.0
	 *
	 * @param string $column Number.
	 * @return string Modified number.
	 */
	function custom_upsell_columns( $column ) {
		$woo_product_per_row = best_commerce_get_option( 'woo_product_per_row' );

		if ( absint( $woo_product_per_row ) > 0 ) {
			$column = absint( $woo_product_per_row );
		}

		return $column;
	}

	/**
	 * Columns in related products.
	 *
	 * @since 1.0.0
	 *
	 * @param string $column Number.
	 * @return string Modified number.
	 */
	function custom_related_products_columns( $column ) {
		$woo_product_per_row = best_commerce_get_option( 'woo_product_per_row' );

		if ( absint( $woo_product_per_row ) > 0 ) {
			$column = absint( $woo_product_per_row );
		}

		return $column;
	}

	/**
	 * Loop image size.
	 *
	 * @since 1.0.0
	 *
	 * @param string $input Size.
	 * @return string Modified size.
	 */
	function loop_image_size( $input ) {

		$input = 'best-commerce-product';
		return $input;
	}

	/**
	 * Custom shop title.
	 *
	 * @since 1.0.0
	 */
	function custom_shop_title( $input ) {

		$shop_title = esc_html__( 'Shop', 'best-commerce' );
		$shop_page_id = get_option( 'woocommerce_shop_page_id' );

		if ( $shop_page_id ) {
			$shop_title = get_the_title( $shop_page_id );
		}

		echo '<h1 class="page-title">';
		echo esc_html( $shop_title );
		echo '</h1>';
	}
}

// Initialize.
$best_commerce_woocommerce = new Best_Commerce_Woocommerce();
