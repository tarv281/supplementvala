<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

class APPMAKER_WC_perfect_brands
{

    public function __construct()
    {
        add_filter('appmaker_wc_product_filters', array($this, 'brand_filter'), 10, 1);

    }


    public function brand_filter($return)
    {
        $posts=array();
        if(isset($_REQUEST['category'])){
            $args = array(
                'post_type'             => 'product',
                'post_status'           => 'publish',
                'ignore_sticky_posts'   => 1,
                'posts_per_page'        => -1,
                'tax_query'             => array(
                    array(
                        'taxonomy'      => 'product_cat',
                        'field' => 'term_id', //This is optional, as it defaults to 'term_id'
                        'terms'         => $_REQUEST['category'],
                        'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
                    ),
                    array(
                        'taxonomy'      => 'product_visibility',
                        'field'         => 'slug',
                        'terms'         => 'exclude-from-catalog', // Possibly 'exclude-from-search' too
                        'operator'      => 'NOT IN'
                    )
                )
            );
            $query = new WP_Query($args);
            $i=0;
            while( $query->have_posts()): $query->the_post();

                {
                    $posts[$i]=$query->post;
                    $i++;
                }

            endwhile;

            $brands_list = array();
        $result_brands=array();
        foreach($posts as $post => $product){
           $product_id=$product->ID;
            $product_brands = wp_get_post_terms($product_id, 'pwb-brand');
            foreach($product_brands as $brand) $result_brands[] = $brand->term_id;
        }
        $result_brands= array_unique($result_brands);
        foreach ($result_brands as $brand) {
                $brands_list[]= get_term($brand);
        }
        }else {
            $brands_list = get_terms('pwb-brand');
        }


        if ( ! empty( $brands_list ) && is_array( $brands_list ) ) {
            $return['items']['pwb-brand'] = array(
                'id'     => 'pwb-brand',
                'type'   => 'checkbox',
                'label'  => __( 'Brands' ),
                'values' => array(),
            );

            foreach ( $brands_list as $term ) {
                $return['items']['pwb-brand']['values'][] = array(
                    'label' => strip_tags( html_entity_decode( $term->name ) ),
                    'value' => $term->slug,
                );
            }
        }

          return $return;
    }

}
new APPMAKER_WC_perfect_brands();