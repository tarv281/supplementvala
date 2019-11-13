<?php
/**
 * The template part for header
 *
 * @package VW Ecommerce Store 
 * @subpackage vw_ecommerce_store
 * @since VW Ecommerce Store 1.0
 */
?>
<div class="container">
	<div id="header" class="menubar">
		<div class="header-menu <?php if( get_theme_mod( 'vw_ecommerce_store_sticky_header') != '') { ?> header-sticky"<?php } else { ?>close-sticky <?php } ?>">
			<div class="row">
				<div class="<?php if( get_theme_mod( 'vw_ecommerce_store_header_search') != '') { ?>col-lg-11 col-md-11 col-6"<?php } else { ?>col-lg-12 col-md-12 <?php } ?>">
					<div class="toggle-nav mobile-menu">
					    <button role="tab" onclick="menu_openNav()"><i class="fas fa-bars"></i><span class="screen-reader-text"><?php esc_html_e('Open Button','vw-ecommerce-store'); ?></span></button>
					</div> 
					<div id="mySidenav" class="nav sidenav">
			          	<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'vw-ecommerce-store' ); ?>">
				            <a href="javascript:void(0)" class="closebtn mobile-menu" onclick="menu_closeNav()"><i class="fas fa-times"></i><span class="screen-reader-text"><?php esc_html_e('Close Button','vw-ecommerce-store'); ?></span></a>
				            <?php 
				              wp_nav_menu( array( 
				                'theme_location' => 'primary',
				                'container_class' => 'main-menu clearfix' ,
				                'menu_class' => 'clearfix',
				                'items_wrap' => '<ul id="%1$s" class="%2$s mobile_nav">%3$s</ul>',
				                'fallback_cb' => 'wp_page_menu',
				              ) ); 
				            ?>
			          	</nav>
			        </div>
				</div>
				<div class="col-lg-1 col-md-1 col-6 search-box">
			        <?php if( get_theme_mod( 'vw_ecommerce_store_header_search') != '') { ?>
			        	<span><i class="fas fa-search"></i></span>
			        <?php }?>
			    </div>
			</div>
		</div>
		<div class="serach_outer">
	      	<div class="closepop"><i class="far fa-window-close"></i></div>
	      	<?php if(class_exists('woocommerce')){ ?>
		      	<div class="serach_inner">
		        	<?php get_product_search_form(); ?>
		      	</div>
	      	<?php }
	      	else { ?>
	      		<div class="serach_inner">
		        	<?php get_search_form(); ?>
		      	</div>
		    <?php } ?>
	    </div>
	</div>
</div>