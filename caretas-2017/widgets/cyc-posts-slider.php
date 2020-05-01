<?php

/***** MH Posts Slider Widget *****/

class cyc_posts_slider extends WP_Widget {
    function __construct() {
            parent::__construct(
                    'mh_newsdesk_lite_posts_slider', esc_html_x('MH Posts Slider', 'widget name', 'mh-newsdesk-lite'),
                    array('mh_newsdesk_lite_posts_slider' => 'mh_affiliate', 'description' => esc_html__('Grid Slider from any category/zone.', 'mh-newsdesk-lite'))
            );
    }
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $category = isset($instance['category']) ? $instance['category'] : '';
        $postcount = empty($instance['postcount']) ? '5' : $instance['postcount'];
        $offset = empty($instance['offset']) ? '' : $instance['offset'];
        $sticky = isset($instance['sticky']) ? $instance['sticky'] : 1;

        if ($category) {
            $cat_url = get_category_link( get_category_by_slug( $category )->term_id );
            $before_title = $before_title . '<a href="' . esc_url($cat_url) . '" class="widget-title-link">';
            $after_title = '</a>' . $after_title;
        }

        echo $before_widget;
        
        if (!empty($title)) { echo $before_title . esc_attr($title) . $after_title; }
            if ( function_exists( 'z_get_zone_query' ) && z_get_zone( $category ) ) { ?>
		<div class="mh-cp-widget clearfix"><?php
                
                $posts_in_zone = z_get_posts_in_zone( $category, array( 'posts_per_page' => $postcount )  );
                if ( ! empty ( $posts_in_zone ) ) {
                    foreach( $posts_in_zone as $post ) { 
                        $colgado = get_post_meta( $post->ID, 'COLGADO', true );?>
                        <article class="cp-wrap cp-small clearfix">
                            <div class="cp-thumb-small">
                                <a href="<?php echo get_permalink( $post->ID ); ?>" title="<?php the_title_attribute(); ?>">
                                    <?php if (has_post_thumbnail( $post->ID )) : echo get_the_post_thumbnail( $post->ID ); else : ?>
                                        <img src="<?php echo get_template_directory_uri() ?>/images/placeholder-thumb-small.jpg" alt="No Picture" /> 
                                    <?php endif; ?>
                                </a>
                            </div>
                            <p class="colgado"><?php echo $colgado; ?></p>
                            <h3 class="cp-title-small">
                                <a href="<?php echo get_permalink( $post->ID ); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php echo $post->post_title; ?></a>
                            </h3>
                        </article>
                        <hr class="mh-separator"><?php
                    }
                } ?>
		</div><?php
            } else {
		$args = array('posts_per_page' => $postcount, 'offset' => $offset, 'category_name' => $category, 'ignore_sticky_posts' => $sticky);
		$widget_loop = new WP_Query($args); ?>
                    <div class="mh-section mh-group cyc-slider owl-carousel owl-theme"><?php
                    if ( $widget_loop->have_posts() ) : 
                        while ($widget_loop->have_posts()) : $widget_loop->the_post(); $i++; ?>
                            <div class="item">
                                <div class="content-thumb content-grid-thumb">
                                    <a href="<?php echo get_the_permalink() ?>" title="<?php echo get_the_title() ?>">
                                        <?php if (has_post_thumbnail()) { the_post_thumbnail('content-single', array( 'class' => 'no-lazy' )); } else { echo '<img src="' . get_template_directory_uri() . '/images/placeholder-content-single.jpg' . '" alt="No Picture" />'; } ?>
                                    </a>
                                </div>
                                <h3 class="">
                                    <a href="<?php echo get_the_permalink() ?>" title="<?php echo get_the_title() ?>"><?php echo get_the_title() ?></a>
                                </h3>
                            </div>
                        <?php
                        endwhile;
                    endif;
                    wp_reset_postdata(); ?>
                    </div>
                    <?php
            }
            echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['category'] = $new_instance['category'];
        $instance['postcount'] = absint($new_instance['postcount']);
        $instance['offset'] = absint($new_instance['offset']);
        $instance['sticky'] = isset($new_instance['sticky']) ? strip_tags($new_instance['sticky']) : '';
        return $instance;
    }
    function form($instance) {
        $defaults = array('title' => '', 'category' => '', 'postcount' => '5', 'offset' => '0', 'sticky' => 1);
        $instance = wp_parse_args((array) $instance, $defaults); ?>

        <p>
        	<label for="<?php echo $this->get_field_id('title'); ?>">Títiulo</label>
                <input class="widefat" type="text" value="<?php echo esc_attr($instance['title']); ?>" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
        <p>
                <label for="<?php echo $this->get_field_id('category'); ?>">Elija una Categoría / Zona</label>
                <input class="widefat" type="text" value="<?php echo esc_attr($instance['category']); ?>" name="<?php echo $this->get_field_name('category'); ?>" id="<?php echo $this->get_field_id('title'); ?>" />
		</p>
	    <p>
        	<label for="<?php echo $this->get_field_id('postcount'); ?>">Número de Post a mostrar</label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['postcount']); ?>" name="<?php echo $this->get_field_name('postcount'); ?>" id="<?php echo $this->get_field_id('postcount'); ?>" />
	    </p>
	    <p>
        	<label for="<?php echo $this->get_field_id('offset'); ?>">Saltear Posts (Offset)</label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['offset']); ?>" name="<?php echo $this->get_field_name('offset'); ?>" id="<?php echo $this->get_field_id('offset'); ?>" />
	    </p>
        <p>
      		<input id="<?php echo $this->get_field_id('sticky'); ?>" name="<?php echo $this->get_field_name('sticky'); ?>" type="checkbox" value="1" <?php checked('1', $instance['sticky']); ?>/>
                <label for="<?php echo $this->get_field_id('sticky'); ?>">Obviar Sticky Posts</label>
    	</p><?php
    }
}
?>