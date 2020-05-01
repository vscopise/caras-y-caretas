<?php

/***** Cyc Custom Posts Widget *****/

class cyc_custom_posts extends WP_Widget {
    function __construct() {
		parent::__construct(
			'mh_custom_posts', esc_html_x('MH Entradas Personalizadas', 'widget name', 'mh-newsdesk-lite'),
			array('mh_newsdesk_lite_custom_posts' => 'mh_affiliate', 'description' => 'Muestra entradas de una categoría.')
		);
	}
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $category = isset($instance['category']) ? $instance['category'] : '';
        $class = isset($instance['class']) ? $instance['class'] : '';
        $postcount = empty($instance['postcount']) ? '5' : $instance['postcount'];
        $sticky = isset($instance['sticky']) ? $instance['sticky'] : 1;
        $show_excerpt = isset($instance['show_excerpt']) ? $instance['show_excerpt'] : 1;
        $show_author = isset($instance['show_author']) ? $instance['show_author'] : 1;
        $large_post = isset($instance['large_post']) ? $instance['large_post'] : 1;

        echo $before_widget; ?>
        <div class="sb-widget <?php echo $class ?>">
        <?php if (!empty($title)) { echo $before_title . esc_attr($title) . $after_title; } ?>
            <?php if ( $large_post == 1 ) : ?>
                <div class="sb-widget">
                    <?php $args = array( 'posts_per_page' => 1, 'cat' => $category, 'ignore_sticky_posts' => $sticky ) ?>
                    <?php $widget_loop = new WP_Query( $args ); ?>
                    <?php if ( $widget_loop->have_posts() ) : while ($widget_loop->have_posts()) : $widget_loop->the_post(); ?>
                        <?php 
                        $post_format = get_post_format();
                        $featuredVideoURL = get_post_meta( get_the_ID(), '_featuredVideoURL', true );
                        ?>
                        <article <?php post_class('content-lead'); ?>>
                                <div class="content-thumb content-lead-thumb">
                                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                            <?php if (has_post_thumbnail()) { the_post_thumbnail('content-single'); } else { echo '<img src="' . get_template_directory_uri() . '/images/placeholder-content-single.jpg' . '" alt="No Picture" />'; } ?>
                                            <?php if ( $post_format=='video' && $featuredVideoURL != '' ) : ?>
                                            <span class="thumb-icon"><i class="fa fa-play"></i></span>
                                            <?php elseif ( $post_format=='gallery' && $gallery = get_post_gallery_images( get_the_ID() ) ) : ?>
                                            <span class="thumb-icon"><i class="fa fa-camera"></i></span>
                                            <?php endif; ?>
                                        </a>
                                </div>
                                <h3 class="content-lead-title">
                                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
                                </h3>
                                <?php if ( $show_author == 1 ) : ?>
                                    <p>Por: <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author_link(); ?></a></p>
                                <?php endif; ?>
                                <div class="content-lead-excerpt">
                                        <?php echo cyc_get_paragraph( get_the_excerpt() ) ?>
                                </div>
                        </article>
                    <?php endwhile; endif; ?>
                    <?php wp_reset_postdata(); $offset = 1; ?>
                </div>
            <?php endif; ?>
            <?php
                $post_per_page = ( $large_post == 1 ) ? $postcount - 1 : $postcount;
                $args = array('posts_per_page' => $post_per_page, 'offset' => $offset, 'cat' => $category, 'ignore_sticky_posts' => $sticky);
                $widget_loop = new WP_Query($args); 
            ?>
                <div class="mh-cp-widget clearfix">
                    <?php if ( $widget_loop->have_posts() ) : while ($widget_loop->have_posts()) : $widget_loop->the_post(); ?>
                        <?php
                        $post_format = get_post_format();
                        $featuredVideoURL = get_post_meta( get_the_ID(), '_featuredVideoURL', true ); 
                        ?>
                        <article class="cp-wrap cp-small clearfix">
                            <div class="cp-thumb-small">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                        <?php if (has_post_thumbnail()) { the_post_thumbnail(array(120,67)); } else { echo '<img src="' . get_template_directory_uri() . '/images/placeholder-content-single.jpg' . '" alt="No Picture" />'; } ?>
                                        <?php if ( $post_format=='video' && $featuredVideoURL != '' ) : ?>
                                        <span class="thumb-icon"><i class="fa fa-play"></i></span>
                                        <?php elseif ( $post_format=='gallery' && $gallery = get_post_gallery_images( get_the_ID() ) ) : ?>
                                        <span class="thumb-icon"><i class="fa fa-camera"></i></span>
                                        <?php endif; ?>
                                    </a>
                            </div>
                            <h3 class="cp-title-small">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
                            </h3>
                            <?php if ( $show_author == 1 ) : ?>
                                <p>Por: <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author_link(); ?></a></p>
                            <?php endif; ?>
                        </article>
                        <hr class="mh-separator">
                    <?php endwhile; endif; wp_reset_postdata(); ?>
                </div>
        </div>
        <?php echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['category'] = $new_instance['category'];
        $instance['class'] = $new_instance['class'];
        $instance['postcount'] = absint($new_instance['postcount']);
        $instance['sticky'] = isset($new_instance['sticky']) ? strip_tags($new_instance['sticky']) : '';
        $instance['show_author'] = absint($new_instance['show_author']);
        $instance['show_excerpt'] = absint($new_instance['show_excerpt']);
        $instance['large_post'] = absint($new_instance['large_post']);
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
            <label for="<?php echo $this->get_field_id('category'); ?>">Elija una Categoría</label>
            
            <select id="<?php echo $this->get_field_id('category'); ?>" class="widefat" name="<?php echo $this->get_field_name('category'); ?>">
                    <option value="0" <?php if (!$instance['category']) echo 'selected="selected"'; ?>><?php _e('All', 'mh-newsdesk-lite'); ?></option>
                    <?php
                    $categories = get_categories(array('type' => 'post'));
                    foreach($categories as $cat) {
                            echo '<option value="' . $cat->cat_ID . '"';
                            if ($cat->cat_ID == $instance['category']) { echo ' selected="selected"'; }
                            echo '>' . $cat->cat_name . ' (' . $cat->category_count . ')';
                            echo '</option>';
                    }
                    ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('class'); ?>">Clase</label>
            <input class="widefat" type="text" value="<?php echo esc_attr($instance['class']); ?>" name="<?php echo $this->get_field_name('class'); ?>" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('postcount'); ?>">Número de Post a mostrar</label>
            <input class="widefat" type="text" value="<?php echo esc_attr($instance['postcount']); ?>" name="<?php echo $this->get_field_name('postcount'); ?>" id="<?php echo $this->get_field_id('postcount'); ?>" />
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('sticky'); ?>" name="<?php echo $this->get_field_name('sticky'); ?>" type="checkbox" value="1" <?php checked('1', $instance['sticky']); ?>/>
            <label for="<?php echo $this->get_field_id('sticky'); ?>">Obviar Sticky Posts</label>
    	</p>
        <p>
            <input id="<?php echo $this->get_field_id('show_excerpt'); ?>" name="<?php echo $this->get_field_name('show_excerpt'); ?>" type="checkbox" value="1" <?php checked('1', $instance['show_excerpt']); ?>/>
            <label for="<?php echo $this->get_field_id('show_excerpt'); ?>">Mostrar Copete</label>
    	</p>
        <p>
            <input id="<?php echo $this->get_field_id('show_author'); ?>" name="<?php echo $this->get_field_name('show_author'); ?>" type="checkbox" value="1" <?php checked('1', $instance['show_author']); ?>/>
            <label for="<?php echo $this->get_field_id('show_author'); ?>">Mostrar Autor</label>
    	</p>
        <p>
            <input id="<?php echo $this->get_field_id('large_post'); ?>" name="<?php echo $this->get_field_name('large_post'); ?>" type="checkbox" value="1" <?php checked('1', $instance['large_post']); ?>/>
            <label for="<?php echo $this->get_field_id('large_post'); ?>">Primer artículo grande</label>
    	</p>
            <?php
    }
}
?>