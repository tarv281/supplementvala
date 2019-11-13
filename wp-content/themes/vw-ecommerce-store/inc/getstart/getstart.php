<?php
//about theme info
add_action( 'admin_menu', 'vw_ecommerce_store_gettingstarted' );
function vw_ecommerce_store_gettingstarted() {    	
	add_theme_page( esc_html__('About VW Ecommerce Store', 'vw-ecommerce-store'), esc_html__('About VW Ecommerce Store', 'vw-ecommerce-store'), 'edit_theme_options', 'vw_ecommerce_store_guide', 'vw_ecommerce_store_mostrar_guide');   
}

// Add a Custom CSS file to WP Admin Area
function vw_ecommerce_store_admin_theme_style() {
   wp_enqueue_style( 'vw-ecommerce-store-font', vw_ecommerce_store_admin_font_url(), array() );
   wp_enqueue_style('custom-admin-style', get_template_directory_uri() . '/inc/getstart/getstart.css');
   wp_enqueue_script('tabs', get_template_directory_uri() . '/inc/getstart/js/tab.js');
}
add_action('admin_enqueue_scripts', 'vw_ecommerce_store_admin_theme_style');

// Theme Font URL
function vw_ecommerce_store_admin_font_url() {
	$font_url = '';
	$font_family = array();
	$font_family[] = 'Muli:300,400,600,700,800,900';

	$query_args = array(
		'family'	=> rawurlencode(implode('|',$font_family)),
	);
	$font_url = add_query_arg($query_args,'//fonts.googleapis.com/css');
	return $font_url;
}

//guidline for about theme
function vw_ecommerce_store_mostrar_guide() { 
	//custom function about theme customizer
	$return = add_query_arg( array()) ;
	$theme = wp_get_theme( 'vw-ecommerce-store' );
?>

<div class="wrapper-info">
    <div class="col-left">
    	<h2><?php esc_html_e( 'Welcome to VW Ecommerce Store Theme', 'vw-ecommerce-store' ); ?> <span class="version">Version: <?php echo esc_html($theme['Version']);?></span></h2>
    	<p><?php esc_html_e('All our WordPress themes are modern, minimalist, 100% responsive, seo-friendly,feature-rich, and multipurpose that best suit designers, bloggers and other professionals who are working in the creative fields.','vw-ecommerce-store'); ?></p>
    </div>
    <div class="col-right">
    	<div class="logo">
			<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/final-logo.png" alt="" />
		</div>
		<div class="update-now">
			<h4><?php esc_html_e('Buy VW Ecommerce Store at 20% Discount','vw-ecommerce-store'); ?></h4>
			<h4><?php esc_html_e('Use Coupon','vw-ecommerce-store'); ?> ( <span><?php esc_html_e('vwpro20','vw-ecommerce-store'); ?></span> ) </h4> 
			<div class="info-link">
				<a href="<?php echo esc_url( VW_ECOMMERCE_STORE_BUY_NOW ); ?>" target="_blank"> <?php esc_html_e( 'Upgrade to Pro', 'vw-ecommerce-store' ); ?></a>
			</div>
		</div>
    </div>

    <div class="tab-sec">
		<div class="tab">
		  <button class="tablinks" onclick="openCity(event, 'ecommerce_lite')"><?php esc_html_e( 'Getting Started', 'vw-ecommerce-store' ); ?></button>		  
		  <button class="tablinks" onclick="openCity(event, 'ecommerce_pro')"><?php esc_html_e( 'Get Premium', 'vw-ecommerce-store' ); ?></button>
		  <button class="tablinks" onclick="openCity(event, 'free_pro')"><?php esc_html_e( 'Support', 'vw-ecommerce-store' ); ?></button>
		</div>

		<!-- Tab content -->
		<div id="ecommerce_lite" class="tabcontent open">
			<h3><?php esc_html_e( 'Lite Theme Information', 'vw-ecommerce-store' ); ?></h3>
			<hr class="h3hr">
		  	<p><?php esc_html_e('VW ecommerce store is a multipurpose category store not only for the online apparel and fashion accessories but it can be applied for various other businesses as well like the sports equipment shop, cosmetic shop, jewellery shop or any mobile and gadget store. If you are planning to start a business of supermarket or grocery or an online food delivery website, VW ecommerce store is a very good option. It also can display the business information for the online product sale and can be used for multiple ecommerce stores right from fashion to technology. Known for its feature richness and some incredible features like multipurpose, CTA, Bootstrap framework, SEO friendly, optimised codes and much more, it is very good in case of opening the shop related to the home appliances or in case you are interested in the automobile shopping site, VW ecommerce store is a fine option. It is good for almost all types of stores under the sun like gadgets store, home appliances shop, automobile shopping site, interior store, books store, photo store, movies store, foods and restaurants online order sites and more. It comes with the customization options and secondly it is translation ready, mobile friendly and interactive.','vw-ecommerce-store'); ?></p>
		  	<div class="col-left-inner">
		  		<h4><?php esc_html_e( 'Theme Documentation', 'vw-ecommerce-store' ); ?></h4>
				<p><?php esc_html_e( 'If you need any assistance regarding setting up and configuring the Theme, our documentation is there.', 'vw-ecommerce-store' ); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( VW_ECOMMERCE_STORE_FREE_THEME_DOC ); ?>" target="_blank"> <?php esc_html_e( 'Documentation', 'vw-ecommerce-store' ); ?></a>
				</div>
				<hr>
				<h4><?php esc_html_e('Theme Customizer', 'vw-ecommerce-store'); ?></h4>
				<p> <?php esc_html_e('To begin customizing your website, start by clicking "Customize".', 'vw-ecommerce-store'); ?></p>
				<div class="info-link">
					<a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>"><?php esc_html_e('Customizing', 'vw-ecommerce-store'); ?></a>
				</div>
				<hr>				
				<h4><?php esc_html_e('Having Trouble, Need Support?', 'vw-ecommerce-store'); ?></h4>
				<p> <?php esc_html_e('Our dedicated team is well prepared to help you out in case of queries and doubts regarding our theme.', 'vw-ecommerce-store'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( VW_ECOMMERCE_STORE_SUPPORT ); ?>" target="_blank"><?php esc_html_e('Support Forum', 'vw-ecommerce-store'); ?></a>
				</div>
				<hr>
				<h4><?php esc_html_e('Reviews & Testimonials', 'vw-ecommerce-store'); ?></h4>
				<p> <?php esc_html_e('All the features and aspects of this WordPress Theme are phenomenal. I\'d recommend this theme to all.', 'vw-ecommerce-store'); ?>  </p>
				<div class="info-link">
					<a href="<?php echo esc_url( VW_ECOMMERCE_STORE_REVIEW ); ?>" target="_blank"><?php esc_html_e('Reviews', 'vw-ecommerce-store'); ?></a>
				</div>
		  		<div class="link-customizer">
					<h3><?php esc_html_e( 'Link to customizer', 'vw-ecommerce-store' ); ?></h3>
					<hr class="h3hr">
					<div class="first-row">
						<div class="row-box">
							<div class="row-box1">
								<span class="dashicons dashicons-format-image"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[control]=custom_logo') ); ?>" target="_blank"><?php esc_html_e('Upload your logo','vw-ecommerce-store'); ?></a>
							</div>
							<div class="row-box2">
								<span class="dashicons dashicons-images-alt"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_ecommerce_store_slidersettings') ); ?>" target="_blank"><?php esc_html_e('Slider Settings','vw-ecommerce-store'); ?></a>
							</div>
						</div>
						<div class="row-box">
							<div class="row-box1">
								<span class="dashicons dashicons-align-center"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_ecommerce_store_topbar') ); ?>" target="_blank"><?php esc_html_e('Topbar Settings','vw-ecommerce-store'); ?></a>
							</div>
							<div class="row-box2">
								<span class="dashicons dashicons-editor-table"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_ecommerce_store_services_section') ); ?>" target="_blank"><?php esc_html_e('Our Best Seller','vw-ecommerce-store'); ?></a>
							</div>
						</div>
						<div class="row-box">
							<div class="row-box1">
								<span class="dashicons dashicons-editor-aligncenter"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=nav_menus') ); ?>" target="_blank"><?php esc_html_e('Menus','vw-ecommerce-store'); ?></a>
							</div>
							<div class="row-box2">
								<span class="dashicons dashicons-screenoptions"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=widgets') ); ?>" target="_blank"><?php esc_html_e('Footer Widget','vw-ecommerce-store'); ?></a>
							</div>
						</div>
						
						<div class="row-box">
							<div class="row-box1">
								<span class="dashicons dashicons-welcome-write-blog"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_ecommerce_store_left_right') ); ?>" target="_blank"><?php esc_html_e('Blog Layout','vw-ecommerce-store'); ?></a>
							</div>
							<div class="row-box2">
								<span class="dashicons dashicons-align-center"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_ecommerce_store_footer') ); ?>" target="_blank"><?php esc_html_e('Footer Text','vw-ecommerce-store'); ?></a>
							</div>
						</div>
					</div>
				</div>
		  	</div>
			<div class="col-right-inner">
				<h3 class="page-template"><?php esc_html_e('How to set up Home Page Template','vw-ecommerce-store'); ?></h3>
			  	<hr class="h3hr">
				<p><?php esc_html_e('Follow these instructions to setup Home page.','vw-ecommerce-store'); ?></p>
                <ul>
                	<li><?php esc_html_e('1. Create a Page to set template:  Go to ','vw-ecommerce-store'); ?>
                  	<b><?php esc_html_e('Dashboard &gt;&gt; Pages &gt;&gt; Add New Page','vw-ecommerce-store'); ?></b>.
                  	<p><?php esc_html_e('Label it "home" or anything as you wish. Then select template "home-page" from template dropdown.','vw-ecommerce-store'); ?></p></li>
                  	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/home-page-template.png" alt="" />
                  	<p></p><span class="strong"><?php esc_html_e('2. Set the front page:','vw-ecommerce-store'); ?></span><?php esc_html_e(' Go to ','vw-ecommerce-store'); ?>
				  	<b><?php esc_html_e(' Settings -&gt; Reading --&gt; Set the front page display static page to home page ','vw-ecommerce-store'); ?></b></p>
                  	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/set-front-page.png" alt="" />
                  	<p><?php esc_html_e(' Once you are done with this, you can see all the demo content on front page. ','vw-ecommerce-store'); ?></p>
                </ul>
		  	</div>
		</div>	

		<div id="ecommerce_pro" class="tabcontent">
		  	<h3><?php esc_html_e( 'Premium Theme Information', 'vw-ecommerce-store' ); ?></h3>
			<hr class="h3hr">
		    <div class="col-left-pro">
		    	<p><?php esc_html_e('WordPress Ecommerce theme is a theme of premium order and is highly suitable for the businesses like online apparel shops as well as the fashion accessory stores. It is also good for sports equipment shop, cosmetics shop, mobile and gadgets store, jewellery shop, furniture shop, supermarket, grocery store or online food delivering website. It has some incredible features to support and some of these are retina ready, professional, personalization options, testimonial section, customization options, multipurpose, translation ready and much more. This WordPress theme is totally designed for the ecommerce sites and there is an option to display the business information and the option of the product sale online. It is also specially designed for shops and eCommerce sites. It could be used for all types of e-commerce online stores, including Fashion, Shoes, Jewellery, Watch, Sport, Technology, Mobile.','vw-ecommerce-store'); ?></p>
		    	<div class="pro-links">
			    	<a href="<?php echo esc_url( VW_ECOMMERCE_STORE_LIVE_DEMO ); ?>" target="_blank"><?php esc_html_e('Live Demo', 'vw-ecommerce-store'); ?></a>
					<a href="<?php echo esc_url( VW_ECOMMERCE_STORE_BUY_NOW ); ?>"><?php esc_html_e('Buy Pro', 'vw-ecommerce-store'); ?></a>
					<a href="<?php echo esc_url( VW_ECOMMERCE_STORE_PRO_DOC ); ?>" target="_blank"><?php esc_html_e('Pro Documentation', 'vw-ecommerce-store'); ?></a>
				</div>
		    </div>
		    <div class="col-right-pro">
		    	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/responsive.png" alt="" />
		    </div>
		    <div class="featurebox">
			    <h3><?php esc_html_e( 'Theme Features', 'vw-ecommerce-store' ); ?></h3>
				<hr class="h3hr">
				<div class="table-image">
					<table class="tablebox">
						<thead>
							<tr>
								<th></th>
								<th><?php esc_html_e('Free Themes', 'vw-ecommerce-store'); ?></th>
								<th><?php esc_html_e('Premium Themes', 'vw-ecommerce-store'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php esc_html_e('Theme Customization', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Responsive Design', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Logo Upload', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Social Media Links', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Slider Settings', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Number of Slides', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><?php esc_html_e('4', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><?php esc_html_e('Unlimited', 'vw-ecommerce-store'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Template Pages', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><?php esc_html_e('3', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><?php esc_html_e('6', 'vw-ecommerce-store'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Home Page Template', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><?php esc_html_e('1', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><?php esc_html_e('1', 'vw-ecommerce-store'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Contact us Page Template', 'vw-ecommerce-store'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('1', 'vw-ecommerce-store'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Blog Templates & Layout', 'vw-ecommerce-store'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('3(Full width/Left/Right Sidebar)', 'vw-ecommerce-store'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Page Templates & Layout', 'vw-ecommerce-store'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('2(Left/Right Sidebar)', 'vw-ecommerce-store'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Full Documentation', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Latest WordPress Compatibility', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Woo-Commerce Compatibility', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Support 3rd Party Plugins', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Secure and Optimized Code', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Exclusive Functionalities', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/w-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Section Enable / Disable', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/w-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Section Google Font Choices', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/w-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Gallery', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/w-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Simple & Mega Menu Option', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/w-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Support to add custom CSS / JS ', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/w-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Shortcodes', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/w-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Custom Background, Colors, Header, Logo & Menu', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/w-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Premium Membership', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/w-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Budget Friendly Value', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/w-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Priority Error Fixing', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/w-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Custom Feature Addition', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/w-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr>
								<td><?php esc_html_e('All Access Theme Pass', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/w-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Seamless Customer Support', 'vw-ecommerce-store'); ?></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/w-arrow.png" alt="" /></td>
								<td class="table-img"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/right-arrow.png" alt="" /></td>
							</tr>
							<tr>
								<td></td>
								<td class="table-img"></td>
								<td class="update-link"><a href="<?php echo esc_url( VW_ECOMMERCE_STORE_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Upgrade to Pro', 'vw-ecommerce-store'); ?></a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div id="free_pro" class="tabcontent">
		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-star-filled"></span><?php esc_html_e('Pro Version', 'vw-ecommerce-store'); ?></h4>
				<p> <?php esc_html_e('To gain access to extra theme options and more interesting features, upgrade to pro version.', 'vw-ecommerce-store'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( VW_ECOMMERCE_STORE_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Get Pro', 'vw-ecommerce-store'); ?></a>
				</div>
		  	</div>
		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-cart"></span><?php esc_html_e('Pre-purchase Queries', 'vw-ecommerce-store'); ?></h4>
				<p> <?php esc_html_e('If you have any pre-sale query, we are prepared to resolve it.', 'vw-ecommerce-store'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( VW_ECOMMERCE_STORE_CONTACT ); ?>" target="_blank"><?php esc_html_e('Question', 'vw-ecommerce-store'); ?></a>
				</div>
		  	</div>
		  	<div class="col-3">		  		
		  		<h4><span class="dashicons dashicons-admin-customizer"></span><?php esc_html_e('Child Theme', 'vw-ecommerce-store'); ?></h4>
				<p> <?php esc_html_e('For theme file customizations, make modifications in the child theme and not in the main theme file.', 'vw-ecommerce-store'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( VW_ECOMMERCE_STORE_CHILD_THEME ); ?>" target="_blank"><?php esc_html_e('About Child Theme', 'vw-ecommerce-store'); ?></a>
				</div>
		  	</div>

		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-admin-comments"></span><?php esc_html_e('Frequently Asked Questions', 'vw-ecommerce-store'); ?></h4>
				<p> <?php esc_html_e('We have gathered top most, frequently asked questions and answered them for your easy understanding. We will list down more as we get new challenging queries. Check back often.', 'vw-ecommerce-store'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( VW_ECOMMERCE_STORE_FAQ ); ?>" target="_blank"><?php esc_html_e('View FAQ','vw-ecommerce-store'); ?></a>
				</div>
		  	</div>

		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-sos"></span><?php esc_html_e('Support Queries', 'vw-ecommerce-store'); ?></h4>
				<p> <?php esc_html_e('If you have any queries after purchase, you can contact us. We are eveready to help you out.', 'vw-ecommerce-store'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( VW_ECOMMERCE_STORE_SUPPORT ); ?>" target="_blank"><?php esc_html_e('Contact Us', 'vw-ecommerce-store'); ?></a>
				</div>
		  	</div>
		</div>
	</div>
</div>
<?php } ?>