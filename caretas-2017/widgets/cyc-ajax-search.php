<?php

/***** Ajax Search Widget *****/

class cyc_ajax_search extends WP_Widget {
    
        function cyc_ajax_search() {
            if ( wp_verify_nonce( $_POST['nonce'], 'cyc_ajax_search_nonce' ) ) {
                $input = $_POST['input'];
                $query = new WP_Query( array( 'posts_per_page' => 10, 's' => $input ));
                $response = array();
                if ($query->have_posts() ) : 
                    $count = $query->post_count;
                    while ($query->have_posts()) : 
                        $query ->the_post();
                        $title = get_the_title();
                        $link = get_the_permalink();
                        array_push($response, array( 'count' => $count, 'title' => $title, 'link' => $link ) );
                    endwhile;
                else :
                    array_push( $response, array('count' => 0));
                endif;
                echo json_encode($response);
                wp_die();
            }
        }
        
        function cyc_ajax_search_register_script() {
                $cyc_ajax_search_object = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
            
                wp_register_script( 'cyc_ajax_search', get_stylesheet_directory_uri() . '/js/cyc_ajax_search.js', array('jquery') );
                wp_localize_script( 'cyc_ajax_search', 'cyc_ajax_search', $cyc_ajax_search_object );
        }
        function cyc_ajax_search_print_scripts() {
                wp_print_scripts( 'cyc_ajax_search' );
        }
    
               
        function __construct() {
                parent::__construct(
			'mh_newsdesk_lite_ajax_search', esc_html_x('MH Buscador Ajax', 'widget name', 'mh-newsdesk-lite'),
			array('mh_newsdesk_lite_ajax_search' => 'mh_affiliate', 'description' => 'Buscador Ajax')
		);
                
                add_action ( 'init', array( $this, 'cyc_ajax_search_register_script' ) );
                
                if ( is_active_widget( false, false, $this->id_base, true ) ) {
                    add_action( 'wp_head', array( $this, 'cyc_ajax_search_print_scripts' ) );
                }
                add_action( 'wp_ajax_nopriv_cyc_ajax_search', array( $this, 'cyc_ajax_search'));
                add_action( 'wp_ajax_cyc_ajax_search', array( $this, 'cyc_ajax_search'));
        }
        
        function widget( $args, $instance ) {
                extract( $args );
                $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

                add_action( 'wp_enqueue_scripts', 'cyc_ajax_search_scripts' );
                
                echo $before_widget;
                if ( $title ) {
			echo $before_title . $title . $after_title;
		}
                ?>
                        <div class="cyc_ajax_search">
                                <?php wp_nonce_field( 'cyc_ajax_search_nonce', 'cyc_ajax_search_nonce' ) ?>
                                <input type="search" placeholder="Buscar..." />
                                <div class="cyc_ajax_result"></div>
                        </div>
    

                <?php
                
                echo $after_widget;
        }

        function update($new_instance, $old_instance) {
                $instance = $old_instance;
                
                $instance['title'] = strip_tags($new_instance['title']);
                
                return $instance;
        }
        
        function form( $instance ) {
                $defaults = array('title' => '');
                $instance = wp_parse_args((array) $instance, $defaults); 
                ?>
                <div class="rd-widget-form">
                    <p>
                        <label for="<?php echo $this->get_field_id('title'); ?>">Título</label>
                        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                    </p>
                </div>
                <?php
        }
}
?>