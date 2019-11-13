<?php
	
	/*---------------------------First highlight color-------------------*/

	$vw_ecommerce_store_first_color = get_theme_mod('vw_ecommerce_store_first_color');

	$custom_css = '';

	if($vw_ecommerce_store_first_color != false){
		$custom_css .='button.product-btn, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, span.cart-value, .search-box i, .serach_inner button, .products li:hover span.onsale, input[type="submit"], #footer .tagcloud a:hover, #sidebar .custom-social-icons i, #footer .custom-social-icons i, #footer-2, .scrollup i, #sidebar h3, .pagination .current, .pagination a:hover, #sidebar .tagcloud a:hover, #comments input[type="submit"], nav.woocommerce-MyAccount-navigation ul li, .woocommerce div.product span.onsale, .discount-btn a, #comments a.comment-reply-link, .toggle-nav i{';
			$custom_css .='background-color: '.esc_html($vw_ecommerce_store_first_color).';';
		$custom_css .='}';
	}
	if($vw_ecommerce_store_first_color != false){
		$custom_css .='a, #topbar .custom-social-icons i:hover, .carousel-caption1 a:hover, .carousel-caption a:hover, .products li:hover h2, #footer .custom-social-icons i:hover, #footer li a:hover, .post-main-box:hover h3, .more-btn a:hover, #sidebar ul li a:hover, .post-navigation a:hover .post-title, .post-navigation a:focus .post-title, .main-navigation a:hover, .main-navigation ul.sub-menu a:hover, .entry-content a, .sidebar .textwidget p a, .textwidget p a, #comments p a, .slider .inner_carousel p a, .post-navigation a:hover, .post-navigation a:focus{';
			$custom_css .='color: '.esc_html($vw_ecommerce_store_first_color).';';
		$custom_css .='}';
	}
	if($vw_ecommerce_store_first_color != false){
		$custom_css .='.carousel-caption1 a:hover, .carousel-caption a:hover, .more-btn a:hover{';
			$custom_css .='border-color: '.esc_html($vw_ecommerce_store_first_color).';';
		$custom_css .='}';
	}
	if($vw_ecommerce_store_first_color != false){
		$custom_css .='#serv-section hr, .main-navigation ul ul{';
			$custom_css .='border-top-color: '.esc_html($vw_ecommerce_store_first_color).';';
		$custom_css .='}';
	}
	if($vw_ecommerce_store_first_color != false){
		$custom_css .='#footer h3:after, .main-navigation ul ul, .header-fixed{';
			$custom_css .='border-bottom-color: '.esc_html($vw_ecommerce_store_first_color).';';
		$custom_css .='}';
	}
	if($vw_ecommerce_store_first_color != false){
		$custom_css .='span.cart-value:before{';
			$custom_css .='border-right-color: '.esc_html($vw_ecommerce_store_first_color).';';
		$custom_css .='}';
	}

	/*---------------------------Width Layout -------------------*/

	$theme_lay = get_theme_mod( 'vw_ecommerce_store_width_option','Full Width');
    if($theme_lay == 'Boxed'){
		$custom_css .='body{';
			$custom_css .='max-width: 1140px; width: 100%; padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto;';
		$custom_css .='}';
		$custom_css .='.search-box i{';
			$custom_css .='padding: 18px 24px;';
		$custom_css .='}';
	}else if($theme_lay == 'Wide Width'){
		$custom_css .='body{';
			$custom_css .='width: 100%;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;';
		$custom_css .='}';
	}else if($theme_lay == 'Full Width'){
		$custom_css .='body{';
			$custom_css .='max-width: 100%;';
		$custom_css .='}';
	}

	/*--------------------------- Slider Opacity -------------------*/

	$theme_lay = get_theme_mod( 'vw_ecommerce_store_slider_opacity_color','0.5');
	if($theme_lay == '0'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0';
		$custom_css .='}';
		}else if($theme_lay == '0.1'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.1';
		$custom_css .='}';
		}else if($theme_lay == '0.2'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.2';
		$custom_css .='}';
		}else if($theme_lay == '0.3'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.3';
		$custom_css .='}';
		}else if($theme_lay == '0.4'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.4';
		$custom_css .='}';
		}else if($theme_lay == '0.5'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.5';
		$custom_css .='}';
		}else if($theme_lay == '0.6'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.6';
		$custom_css .='}';
		}else if($theme_lay == '0.7'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.7';
		$custom_css .='}';
		}else if($theme_lay == '0.8'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.8';
		$custom_css .='}';
		}else if($theme_lay == '0.9'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.9';
		$custom_css .='}';
		}

	/*---------------------------Slider Content Layout -------------------*/

	$theme_lay = get_theme_mod( 'vw_ecommerce_store_slider_content_option','Left');
    if($theme_lay == 'Left'){
		$custom_css .='#slider .carousel-caption, #slider .inner_carousel, #slider .inner_carousel h2{';
			$custom_css .='text-align:left; left:10%; right:30%; top:40%;';
		$custom_css .='}';
	}else if($theme_lay == 'Center'){
		$custom_css .='#slider .carousel-caption, #slider .inner_carousel, #slider .inner_carousel h2{';
			$custom_css .='text-align:center; left:20%; right:20%; top:40%;';
		$custom_css .='}';
	}else if($theme_lay == 'Right'){
		$custom_css .='#slider .carousel-caption, #slider .inner_carousel, #slider .inner_carousel h2{';
			$custom_css .='text-align:right; left:30%; right:10%; top:40%;';
		$custom_css .='}';
	}