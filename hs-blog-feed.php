<?php
/*
Plugin Name: HS Blog Feed
Plugin URI: http://www.tezrosolutions.com
Description: This plugin displays HS Blog feed.
Version: 1.0
Author: Muhammad Umair
Author URI: http://www.tezrosolutions.com
License: GPL
*/

/**
* Defining Configurations
**/
define("HS_API_KEY", "YOUR API KEY");
define("HS_BLOG_ID", "4026147924");
define("HS_BLOG_ID", "4026147924");
define("BLOG_ITEMS_LIMIT", "12");
define("BLOG_ITEMS_OFFSET", "0");


/** 
* Adding short code
**/
add_shortcode( 'hsfeed', 'hsfeed_func' );
function hsfeed_func( $atts ) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://api.hubapi.com/content/api/v2/blog-posts?hapikey='.HS_API_KEY.'&content_group_id='.HS_BLOG_ID.'&limit='.BLOG_ITEMS_LIMIT.'&offset='.BLOG_ITEMS_OFFSET,
    ));
    $resp = curl_exec($curl);
    curl_close($curl);
    
    $posts = json_decode($resp);
    $markup = '<h1 style="text-align: center;">Logistics Technology & Business Insights</h1><div id="blog-listing" class="latest-blog-listing">';
    

    
    foreach($posts->objects as $post) {
        $body = substr(strip_tags($post->post_body), 0, 100);
        $time = gmdate("M d, Y", ($post->created/1000));
        $markup .= '<div class="left-f"><div class="image"><a> <img src="'.$post->featured_image.'" alt="" /> </a></div><!--// image --><div class="title"><a href="'.$post->url.'"> '.$post->name.' </a></div><!--// title--><div class="timse-date-general"><span class="author">BY '.$post->blog_author->full_name.' | '. $time .' </span></div><!--// image --><div class="desc"><div class="excerpt">'.$body.' ...<a href="'.$post->url.'">Read More</a></div></div><!-- //desc --></div><!--//span4 left-f-->';
    }
    
    $markup .= '</div>';
     
    return $markup;
}


/** 
* Adding scripts and styles 
**/
function hbf_adding_styles() {
    wp_register_script('owl-css-2', plugins_url('includes/owl-carousel/owl.carousel.css', __FILE__));
    wp_enqueue_script('owl-css-2');
    
    wp_register_script('owl-css-1', plugins_url('includes/owl-carousel/owl.theme.css', __FILE__));
    wp_enqueue_script('owl-css-1');
}
add_action( 'wp_enqueue_scripts', 'hbf_adding_styles' );  


function hbf_adding_scripts() {
    wp_register_script('owl-js', plugins_url('includes/owl-carousel/owl.carousel.min.js', __FILE__), array('jquery'),'1.1', true);
    wp_enqueue_script('owl-js');
}
add_action( 'wp_enqueue_scripts', 'hbf_adding_scripts' );  


function hbf_inline_script() {
?>
<script type="text/javascript">
    $(document).ready(function() {
      $("#blog-listing").owlCarousel({
                            loop: false,
                            nav: false,
                            navSpeed: 800,
                            dots: true,
                            dotsSpeed: 800,
                            lazyLoad: false,
                            autoplay: false,
                            autoplayHoverPause: true,
                            autoplayTimeout: 1200,
                            autoplaySpeed:  1000,
                            margin: 30,
                            stagePadding: 0,
                            freeDrag: false,
                            mouseDrag: false,
                            touchDrag: true,
                            slideBy: 3,
                            fallbackEasing: "swing",
                            responsiveClass: true,
                            navText: [ "previous", "next" ],
                            responsive:{
                                0:{items: 1},
                                600:{items: 2},
                                1000:{items: 3}
                                
                            },
                            autoHeight: true
                        });
    });
</script>
<?php
}
add_action( 'wp_footer', 'hbf_inline_script' );
?>