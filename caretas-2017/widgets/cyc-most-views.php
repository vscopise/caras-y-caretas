<?php
/***** Widget de Artículos más vistos *****/

class cyc_most_views extends WP_Widget {
    
    function __construct() {
            parent::WP_Widget(false, $name = 'Caras y Caretas - Entradas más vistas', array(
                    'description' => 'Muestra un listado de los artículos más vistos'
            ));
            
            parent::__construct(
                    'mh_newsdesk_lite_most_views', esc_html_x('MH Artículos más vistos', 'widget name', 'mh-newsdesk-lite'),
                    array('mh_newsdesk_lite_most_views' => 'mh_affiliate', 'description' => esc_html__('Display posts from any category/zone.', 'mh-newsdesk-lite'))
            );
            
    }
    
    function widget( $args, $instance ) { 
            global $post;
            
            extract( $args );
            $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
            $atts = array (
                'number' => 'yes',
                'meta_key' => 'post_views_count',
                'orderby' => 'meta_value_num',
                'range' => $instance['range'],
                'posts_per_page' => 9
            );
            echo $before_widget;
            if (!empty($title)) { echo $before_title . esc_attr($title) . $after_title; }
            
            if ( $atts['range'] ) {
                    if ($atts['range']=='1') {
                            $atts['date_query'] = array(
                                    array(
                                            'column' => 'post_date_gmt',
                                            'after' => '1 day ago'
                                    )
                            );
                    } elseif ($atts['range']=='2') {
                            $atts['date_query'] = array(
                                    array(
                                            'column' => 'post_date_gmt',
                                            'after' => '1 week ago'
                                    )
                            );
                    } elseif ($atts['range']=='3') {
                            $atts['date_query'] = array(
                                    array(
                                            'column' => 'post_date_gmt',
                                            'after' => '1 month ago'
                                    )
                            );
                    }
            }
            
            $query = new WP_Query( $atts );
            if ( $query->have_posts() ) :
                    $preserve_post = get_post();
                    $i = 1;
                    ?>
                    <div class="mh-cp-widget clearfix">
                        <ul class="mh-rp-widget widget-list">
                        <?php while ( $query->have_posts() ) : $query->the_post();?>
                        <li class="rp-widget-item">
                            <span class="list-count"><?php echo $i . '.'; ?></span>
                            <a href="<?php echo esc_url(get_permalink( get_the_ID() )); ?>" title="<?php echo esc_attr(get_the_title( get_the_ID() )); ?>" rel="bookmark"><?php echo esc_attr(get_the_title( get_the_ID() ));; ?></a>
                        </li>
                        <?php $i++; ?>
                        <?php endwhile; ?>
                            
                        </ul>
                    </div>
                    <?php
                    $post = $preserve_post;
                    setup_postdata( $post );
            endif; 
            //echo cc_block_nine_ajax_template($atts);
            wp_reset_query();
		
            echo $after_widget;
    }
    
    function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
		
            $instance['range'] = strip_tags($new_instance['range']);
		
            return $instance;
    }
    
    function form( $instance ) {
            $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
            $range = esc_attr($instance['range']);
            ?>
                    <div class="rd-widget-form">
                            <p>
                                    <label for="<?php echo $this->get_field_id('title'); ?>">Título</label>
                                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                            </p>
                            <p>
                                    <label for="range">Rango:</label>
                                    <select class="widefat" name="<?php echo $this->get_field_name('range'); ?>" id="range">
                                        <option <?php if($range == '1') { echo 'selected';} ?> value="1">Últimas 24 horas</option>
                                        <option <?php if($range == '2') { echo 'selected';} ?> value="2">Últimos 7 días</option>
                                        <option <?php if($range == '3') { echo 'selected';} ?> value="3">Últimos 30 días</option>
                                        <option <?php if($range == '0') { echo 'selected';} ?> value="0">Todos los tiempos</option>
                                    </select>
                            </p>
                    </div>
            <?php
    }
}


?>