<?php
/**
 * VW Ecommerce Store Theme Customizer
 *
 * @package VW Ecommerce Store
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

function vw_ecommerce_store_custom_controls() {
	load_template( trailingslashit( get_template_directory() ) . '/inc/custom-controls.php' );
}
add_action( 'customize_register', 'vw_ecommerce_store_custom_controls' );

function vw_ecommerce_store_customize_register( $wp_customize ) {

	load_template( trailingslashit( get_template_directory() ) . 'inc/customize-homepage/class-customize-homepage.php' );

	//add home page setting pannel
	$wp_customize->add_panel( 'vw_ecommerce_store_panel_id', array(
	    'priority' => 10,
	    'capability' => 'edit_theme_options',
	    'theme_supports' => '',
	    'title' => __( 'VW Settings', 'vw-ecommerce-store' ),
	) );

	// Layout
	$wp_customize->add_section( 'vw_ecommerce_store_left_right', array(
    	'title'      => __( 'General Settings', 'vw-ecommerce-store' ),
		'panel' => 'vw_ecommerce_store_panel_id'
	) );

	$wp_customize->add_setting('vw_ecommerce_store_width_option',array(
        'default' => __('Full Width','vw-ecommerce-store'),
        'sanitize_callback' => 'vw_ecommerce_store_sanitize_choices'
	));
	$wp_customize->add_control(new VW_Ecommerce_Store_Image_Radio_Control($wp_customize, 'vw_ecommerce_store_width_option', array(
        'type' => 'select',
        'label' => __('Width Layouts','vw-ecommerce-store'),
        'description' => __('Here you can change the width layout of Website.','vw-ecommerce-store'),
        'section' => 'vw_ecommerce_store_left_right',
        'choices' => array(
            'Full Width' => get_template_directory_uri().'/assets/images/full-width.png',
            'Wide Width' => get_template_directory_uri().'/assets/images/wide-width.png',
            'Boxed' => get_template_directory_uri().'/assets/images/boxed-width.png',
    ))));

	// Add Settings and Controls for Layout
	$wp_customize->add_setting('vw_ecommerce_store_theme_options',array(
        'default' => __('Right Sidebar','vw-ecommerce-store'),
        'sanitize_callback' => 'vw_ecommerce_store_sanitize_choices'	        
	) );
	$wp_customize->add_control('vw_ecommerce_store_theme_options', array(
        'type' => 'select',
        'label' => __('Post Sidebar Layout','vw-ecommerce-store'),
        'description' => __('Here you can change the sidebar layout for posts. ','vw-ecommerce-store'),
        'section' => 'vw_ecommerce_store_left_right',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','vw-ecommerce-store'),
            'Right Sidebar' => __('Right Sidebar','vw-ecommerce-store'),
            'One Column' => __('One Column','vw-ecommerce-store'),
            'Three Columns' => __('Three Columns','vw-ecommerce-store'),
            'Four Columns' => __('Four Columns','vw-ecommerce-store'),
            'Grid Layout' => __('Grid Layout','vw-ecommerce-store')
        ),
	));

	$wp_customize->add_setting('vw_ecommerce_store_page_layout',array(
        'default' => __('One Column','vw-ecommerce-store'),
        'sanitize_callback' => 'vw_ecommerce_store_sanitize_choices'
	));
	$wp_customize->add_control('vw_ecommerce_store_page_layout',array(
        'type' => 'select',
        'label' => __('Page Sidebar Layout','vw-ecommerce-store'),
        'description' => __('Here you can change the sidebar layout for pages. ','vw-ecommerce-store'),
        'section' => 'vw_ecommerce_store_left_right',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','vw-ecommerce-store'),
            'Right Sidebar' => __('Right Sidebar','vw-ecommerce-store'),
            'One Column' => __('One Column','vw-ecommerce-store')
        ),
	) );

	//Pre-Loader
	$wp_customize->add_setting( 'vw_ecommerce_store_loader_enable',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_ecommerce_store_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Ecommerce_Store_Toggle_Switch_Custom_Control( $wp_customize, 'vw_ecommerce_store_loader_enable',array(
        'label' => esc_html__( 'Pre-Loader','vw-ecommerce-store' ),
        'section' => 'vw_ecommerce_store_left_right'
    )));

	$wp_customize->add_setting('vw_ecommerce_store_loader_icon',array(
        'default' => __('Two Way','vw-ecommerce-store'),
        'sanitize_callback' => 'vw_ecommerce_store_sanitize_choices'
	));
	$wp_customize->add_control('vw_ecommerce_store_loader_icon',array(
        'type' => 'select',
        'label' => __('Pre-Loader Type','vw-ecommerce-store'),
        'section' => 'vw_ecommerce_store_left_right',
        'choices' => array(
            'Two Way' => __('Two Way','vw-ecommerce-store'),
            'Dots' => __('Dots','vw-ecommerce-store'),
            'Rotate' => __('Rotate','vw-ecommerce-store')
        ),
	) );

	//Topbar Discount
	$wp_customize->add_section( 'vw_ecommerce_store_top_discount', array(
    	'title'      => __( 'Topbar Discount Settings', 'vw-ecommerce-store' ),
		'panel' => 'vw_ecommerce_store_panel_id'
	) );

	$wp_customize->add_setting( 'vw_ecommerce_store_top_discount_box', array(
		'default'           => '',
		'sanitize_callback' => 'vw_ecommerce_store_sanitize_dropdown_pages'
	) );
	$wp_customize->add_control( 'vw_ecommerce_store_top_discount_box', array(
		'label'    => __( 'Select Discount Page', 'vw-ecommerce-store' ),
		'description' => __('Discount image size (1500 x 100)','vw-ecommerce-store'),
		'section'  => 'vw_ecommerce_store_top_discount',
		'type'     => 'dropdown-pages'
	) );

	//Topbar
	$wp_customize->add_section( 'vw_ecommerce_store_topbar', array(
    	'title'      => __( 'Topbar Settings', 'vw-ecommerce-store' ),
		'panel' => 'vw_ecommerce_store_panel_id'
	) );

	$wp_customize->add_setting( 'vw_ecommerce_store_topbar_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_ecommerce_store_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Ecommerce_Store_Toggle_Switch_Custom_Control( $wp_customize, 'vw_ecommerce_store_topbar_hide_show',array(
		'label' => esc_html__( 'Show / Hide Topbar','vw-ecommerce-store' ),
		'section' => 'vw_ecommerce_store_topbar'
    )));

    //Sticky Header
	$wp_customize->add_setting( 'vw_ecommerce_store_sticky_header',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_ecommerce_store_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Ecommerce_Store_Toggle_Switch_Custom_Control( $wp_customize, 'vw_ecommerce_store_sticky_header',array(
        'label' => esc_html__( 'Sticky Header','vw-ecommerce-store' ),
        'section' => 'vw_ecommerce_store_topbar'
    )));

	$wp_customize->add_setting( 'vw_ecommerce_store_order_tracking',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_ecommerce_store_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Ecommerce_Store_Toggle_Switch_Custom_Control( $wp_customize, 'vw_ecommerce_store_order_tracking',array(
		'label' => esc_html__( 'On / Off Order Tracking','vw-ecommerce-store' ),
		'section' => 'vw_ecommerce_store_topbar'
    )));

    $wp_customize->add_setting( 'vw_ecommerce_store_header_search',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_ecommerce_store_switch_sanitization'
    ));
    $wp_customize->add_control( new VW_Ecommerce_Store_Toggle_Switch_Custom_Control( $wp_customize, 'vw_ecommerce_store_header_search',array(
		'label' => esc_html__( 'On / Off Search','vw-ecommerce-store' ),
		'section' => 'vw_ecommerce_store_topbar'
    )));

	$wp_customize->add_setting('vw_ecommerce_store_location',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_ecommerce_store_location',array(
		'label'	=> __('Add Location','vw-ecommerce-store'),
		'input_attrs' => array(
            'placeholder' => __( '828 N. Iqyreesrs Street Liocnss Park', 'vw-ecommerce-store' ),
        ),
		'section'=> 'vw_ecommerce_store_topbar',
		'type'=> 'text'
	));

	//Slider
	$wp_customize->add_section( 'vw_ecommerce_store_slidersettings' , array(
    	'title'      => __( 'Slider Settings', 'vw-ecommerce-store' ),
		'panel' => 'vw_ecommerce_store_panel_id'
	) );

	$wp_customize->add_setting( 'vw_ecommerce_store_slider_hide_show',array(
          'default' => 1,
          'transport' => 'refresh',
          'sanitize_callback' => 'vw_ecommerce_store_switch_sanitization'
    ));  
    $wp_customize->add_control( new VW_Ecommerce_Store_Toggle_Switch_Custom_Control( $wp_customize, 'vw_ecommerce_store_slider_hide_show',array(
          'label' => esc_html__( 'Show / Hide Slider','vw-ecommerce-store' ),
          'section' => 'vw_ecommerce_store_slidersettings'
    )));


	for ( $count = 1; $count <= 4; $count++ ) {

		$wp_customize->add_setting( 'vw_ecommerce_store_slider_page' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'vw_ecommerce_store_sanitize_dropdown_pages'
		) );
		$wp_customize->add_control( 'vw_ecommerce_store_slider_page' . $count, array(
			'label'    => __( 'Select Slider Page', 'vw-ecommerce-store' ),
			'description' => __('Slider image size (770 x 430)','vw-ecommerce-store'),
			'section'  => 'vw_ecommerce_store_slidersettings',
			'type'     => 'dropdown-pages'
		) );
	}

	//content layout
	$wp_customize->add_setting('vw_ecommerce_store_slider_content_option',array(
        'default' => __('Left','vw-ecommerce-store'),
        'sanitize_callback' => 'vw_ecommerce_store_sanitize_choices'
	));
	$wp_customize->add_control(new VW_Ecommerce_Store_Image_Radio_Control($wp_customize, 'vw_ecommerce_store_slider_content_option', array(
        'type' => 'select',
        'label' => __('Slider Content Layouts','vw-ecommerce-store'),
        'section' => 'vw_ecommerce_store_slidersettings',
        'choices' => array(
            'Left' => get_template_directory_uri().'/assets/images/slider-content1.png',
            'Center' => get_template_directory_uri().'/assets/images/slider-content2.png',
            'Right' => get_template_directory_uri().'/assets/images/slider-content3.png',
    ))));

    //Slider excerpt
	$wp_customize->add_setting( 'vw_ecommerce_store_slider_excerpt_number', array(
		'default'              => 30,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'absint',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'vw_ecommerce_store_slider_excerpt_number', array(
		'label'       => esc_html__( 'Slider Excerpt length','vw-ecommerce-store' ),
		'section'     => 'vw_ecommerce_store_slidersettings',
		'type'        => 'range',
		'settings'    => 'vw_ecommerce_store_slider_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	//Opacity
	$wp_customize->add_setting('vw_ecommerce_store_slider_opacity_color',array(
      'default'              => 0.5,
      'sanitize_callback' => 'vw_ecommerce_store_sanitize_choices'
	));

	$wp_customize->add_control( 'vw_ecommerce_store_slider_opacity_color', array(
	'label'       => esc_html__( 'Slider Image Opacity','vw-ecommerce-store' ),
	'section'     => 'vw_ecommerce_store_slidersettings',
	'type'        => 'select',
	'settings'    => 'vw_ecommerce_store_slider_opacity_color',
	'choices' => array(
      '0' =>  esc_attr('0','vw-ecommerce-store'),
      '0.1' =>  esc_attr('0.1','vw-ecommerce-store'),
      '0.2' =>  esc_attr('0.2','vw-ecommerce-store'),
      '0.3' =>  esc_attr('0.3','vw-ecommerce-store'),
      '0.4' =>  esc_attr('0.4','vw-ecommerce-store'),
      '0.5' =>  esc_attr('0.5','vw-ecommerce-store'),
      '0.6' =>  esc_attr('0.6','vw-ecommerce-store'),
      '0.7' =>  esc_attr('0.7','vw-ecommerce-store'),
      '0.8' =>  esc_attr('0.8','vw-ecommerce-store'),
      '0.9' =>  esc_attr('0.9','vw-ecommerce-store')
	),
	));

	//Sale Banner
	$wp_customize->add_section( 'vw_ecommerce_store_sale' , array(
    	'title'      => __( 'Sale Banner Settings', 'vw-ecommerce-store' ),
		'panel' => 'vw_ecommerce_store_panel_id'
	) );

	$wp_customize->add_setting( 'vw_ecommerce_store_sale_banner_hide',
       array(
          'default' => 1,
          'transport' => 'refresh',
          'sanitize_callback' => 'vw_ecommerce_store_switch_sanitization'
    ));  
    $wp_customize->add_control( new VW_Ecommerce_Store_Toggle_Switch_Custom_Control( $wp_customize, 'vw_ecommerce_store_sale_banner_hide',
       array(
          'label' => esc_html__( 'On / Off Banner','vw-ecommerce-store' ),
          'section' => 'vw_ecommerce_store_sale'
    )));

	for ( $count = 1; $count <= 2; $count++ ) {

		$wp_customize->add_setting( 'vw_ecommerce_store_sale_page' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'vw_ecommerce_store_sanitize_dropdown_pages'
		) );
		$wp_customize->add_control( 'vw_ecommerce_store_sale_page' . $count, array(
			'label'    => __( 'Select Sale Banner Page', 'vw-ecommerce-store' ),
			'description' => __('Sale banner size (370 x 200)','vw-ecommerce-store'),
			'section'  => 'vw_ecommerce_store_sale',
			'type'     => 'dropdown-pages'
		) );
	}
    
	//Our Services section
	$wp_customize->add_section( 'vw_ecommerce_store_services_section' , array(
    	'title'      => __( 'Our Best Seller', 'vw-ecommerce-store' ),
		'priority'   => null,
		'panel' => 'vw_ecommerce_store_panel_id'
	) );

	$wp_customize->add_setting('vw_ecommerce_store_section_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_ecommerce_store_section_title',array(
		'label'	=> __('Add Section Title','vw-ecommerce-store'),
		'input_attrs' => array(
            'placeholder' => __( 'OUR BEST SELLER', 'vw-ecommerce-store' ),
        ),
		'section'=> 'vw_ecommerce_store_services_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'vw_ecommerce_store_product_page' , array(
		'default'           => '',
		'sanitize_callback' => 'vw_ecommerce_store_sanitize_dropdown_pages'
	) );
	$wp_customize->add_control( 'vw_ecommerce_store_product_page' , array(
		'label'    => __( 'Select Product Page', 'vw-ecommerce-store' ),
		'description' => __('Product Image size (270 x 260)','vw-ecommerce-store'),
		'section'  => 'vw_ecommerce_store_services_section',		
		'type'     => 'dropdown-pages'
	) );

	//Blog Post
	$wp_customize->add_section('vw_ecommerce_store_blog_post',array(
		'title'	=> __('Blog Post Settings','vw-ecommerce-store'),
		'panel' => 'vw_ecommerce_store_panel_id',
	));	

	$wp_customize->add_setting( 'vw_ecommerce_store_toggle_postdate',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_ecommerce_store_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Ecommerce_Store_Toggle_Switch_Custom_Control( $wp_customize, 'vw_ecommerce_store_toggle_postdate',array(
        'label' => esc_html__( 'Post Date','vw-ecommerce-store' ),
        'section' => 'vw_ecommerce_store_blog_post'
    )));

    $wp_customize->add_setting( 'vw_ecommerce_store_toggle_author',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_ecommerce_store_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Ecommerce_Store_Toggle_Switch_Custom_Control( $wp_customize, 'vw_ecommerce_store_toggle_author',array(
		'label' => esc_html__( 'Author','vw-ecommerce-store' ),
		'section' => 'vw_ecommerce_store_blog_post'
    )));

    $wp_customize->add_setting( 'vw_ecommerce_store_toggle_comments',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_ecommerce_store_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Ecommerce_Store_Toggle_Switch_Custom_Control( $wp_customize, 'vw_ecommerce_store_toggle_comments',array(
		'label' => esc_html__( 'Comments','vw-ecommerce-store' ),
		'section' => 'vw_ecommerce_store_blog_post'
    )));

    $wp_customize->add_setting( 'vw_ecommerce_store_excerpt_number', array(
		'default'              => 30,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'absint',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'vw_ecommerce_store_excerpt_number', array(
		'label'       => esc_html__( 'Excerpt length','vw-ecommerce-store' ),
		'section'     => 'vw_ecommerce_store_blog_post',
		'type'        => 'range',
		'settings'    => 'vw_ecommerce_store_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	//Content Craetion
	$wp_customize->add_section( 'vw_ecommerce_store_content_section' , array(
    	'title' => __( 'Customize Home Page Settings', 'vw-ecommerce-store' ),
		'priority' => null,
		'panel' => 'vw_ecommerce_store_panel_id'
	) );

	$wp_customize->add_setting('vw_ecommerce_store_content_creation_main_control', array(
		'sanitize_callback' => 'esc_html',
	) );

	$homepage= get_option( 'page_on_front' );

	$wp_customize->add_control(	new VW_Ecommerce_Store_Content_Creation( $wp_customize, 'vw_ecommerce_store_content_creation_main_control', array(
		'options' => array(
			esc_html__( 'First select static page in homepage setting for front page.Below given edit button is to customize Home Page. Just click on the edit option, add whatever elements you want to include in the homepage, save the changes and you are good to go.','vw-ecommerce-store' ),
		),
		'section' => 'vw_ecommerce_store_content_section',
		'button_url'  => admin_url( 'post.php?post='.$homepage.'&action=edit'),
		'button_text' => esc_html__( 'Edit', 'vw-ecommerce-store' ),
	) ) );

	//Footer Text
	$wp_customize->add_section('vw_ecommerce_store_footer',array(
		'title'	=> __('Footer Settings','vw-ecommerce-store'),
		'panel' => 'vw_ecommerce_store_panel_id',
	));	
	
	$wp_customize->add_setting('vw_ecommerce_store_footer_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('vw_ecommerce_store_footer_text',array(
		'label'	=> __('Copyright Text','vw-ecommerce-store'),
		'input_attrs' => array(
            'placeholder' => __( 'Copyright 2019, .....', 'vw-ecommerce-store' ),
        ),
		'section'=> 'vw_ecommerce_store_footer',
		'type'=> 'text'
	));	

	$wp_customize->add_setting( 'vw_ecommerce_store_hide_show_scroll',array(
    	'default' => 1,
      	'transport' => 'refresh',
      	'sanitize_callback' => 'vw_ecommerce_store_switch_sanitization'
    ));  
    $wp_customize->add_control( new VW_Ecommerce_Store_Toggle_Switch_Custom_Control( $wp_customize, 'vw_ecommerce_store_hide_show_scroll',array(
      	'label' => esc_html__( 'Show / Hide Scroll To Top','vw-ecommerce-store' ),
      	'section' => 'vw_ecommerce_store_footer'
    )));

	$wp_customize->add_setting('vw_ecommerce_store_scroll_top_alignment',array(
        'default' => __('Right','vw-ecommerce-store'),
        'sanitize_callback' => 'vw_ecommerce_store_sanitize_choices'
	));
	$wp_customize->add_control(new VW_Ecommerce_Store_Image_Radio_Control($wp_customize, 'vw_ecommerce_store_scroll_top_alignment', array(
        'type' => 'select',
        'label' => __('Scroll To Top','vw-ecommerce-store'),
        'section' => 'vw_ecommerce_store_footer',
        'settings' => 'vw_ecommerce_store_scroll_top_alignment',
        'choices' => array(
            'Left' => get_template_directory_uri().'/assets/images/layout1.png',
            'Center' => get_template_directory_uri().'/assets/images/layout2.png',
            'Right' => get_template_directory_uri().'/assets/images/layout3.png'
    ))));
}

add_action( 'customize_register', 'vw_ecommerce_store_customize_register' );

load_template( trailingslashit( get_template_directory() ) . '/inc/logo/logo-resizer.php' );

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class VW_Ecommerce_Store_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	*/
	public function sections( $manager ) {

		// Load custom sections.
		load_template( trailingslashit( get_template_directory() ) . '/inc/section-pro.php' );

		// Register custom section types.
		$manager->register_section_type( 'VW_Ecommerce_Store_Customize_Section_Pro' );

		// Register sections.
		$manager->add_section(new VW_Ecommerce_Store_Customize_Section_Pro($manager,'example_1',array(
			'priority'   => 1,
			'title'    => esc_html__( 'VW ECOMMERCE PRO', 'vw-ecommerce-store' ),
			'pro_text' => esc_html__( 'UPGRADE PRO', 'vw-ecommerce-store' ),
			'pro_url'  => esc_url('https://www.vwthemes.com/themes/wordpress-ecommerce-theme/'),
		)));

		$manager->add_section(new VW_Ecommerce_Store_Customize_Section_Pro($manager,'example_2',array(
			'priority'   => 1,
			'title'    => esc_html__( 'DOCUMENATATION', 'vw-ecommerce-store' ),
			'pro_text' => esc_html__( 'DOCS', 'vw-ecommerce-store' ),
			'pro_url'  => admin_url('themes.php?page=vw_ecommerce_store_guide'),
		)));
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'vw-ecommerce-store-customize-controls', trailingslashit( get_template_directory_uri() ) . '/assets/js/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'vw-ecommerce-store-customize-controls', trailingslashit( get_template_directory_uri() ) . '/assets/css/customize-controls.css' );
	}
}

// Doing this customizer thang!
VW_Ecommerce_Store_Customize::get_instance();