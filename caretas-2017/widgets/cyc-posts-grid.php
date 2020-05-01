<?php

/***** MH Custom Grid Widget *****/

class cyc_posts_grid extends WP_Widget {
    function __construct() {
            parent::__construct(
                    'mh_newsdesk_lite_custom_grid', esc_html_x('MH Custom Grid', 'widget name', 'mh-newsdesk-lite'),
                    array('cyc_custom_grid' => 'mh_affiliate', 'description' => 'Muestra post en forma de grilla')
            );
    }
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $category = isset($instance['category']) ? $instance['category'] : '';
        $mostrar_copete = $instance['mostrar_copete'];
        $class = ( '' != $instance['class'] ) ? $instance['class'] : 'cyc_posts_grid';
        $direction = isset($instance['direction']) ? $instance['direction'] : 0;
        $i = 0;

        echo $before_widget; ?>
        <div class="sb-widget <?php echo $class ?>">
        <?php if (!empty($title)) { echo $before_title . esc_attr($title) . $after_title; } ?>
            <div class="mh-section mh-group">
            <?php if ( $direction == 0 ) : ?>
            <?php
		$args = array( 'posts_per_page' => 4, 'offset' => 1, 'category_name' => $category );
		$widget_loop = new WP_Query($args);  ?>
                <div class="mh-col mh-1-2">
                    <div class="mh-section mh-group">
                        <?php while ($widget_loop->have_posts()) : $widget_loop->the_post(); $i++; ?>
                            <article class="mh-col mh-1-2">
                                <div class="content-thumb content-grid-thumb">
                                    <a href="<?php echo get_the_permalink() ?>" title="<?php echo get_the_title() ?>">
                                        <?php if (has_post_thumbnail()) { the_post_thumbnail(array(178,100)); } else { echo '<img src="' . get_template_directory_uri() . '/images/placeholder-content-single.jpg' . '" alt="No Picture" />'; } ?>
                                        <?php 
                                            $post_format = get_post_format();
                                            $featuredVideoURL = get_post_meta( get_the_ID(), '_featuredVideoURL', true );
                                        ?>
                                        <?php if ( $post_format=='video' && $featuredVideoURL != '' ) : ?>
                                        <span class="thumb-icon"><i class="fa fa-play"></i></span>
                                        <?php elseif ( $post_format=='gallery' && $gallery = get_post_gallery_images( get_the_ID() ) ) : ?>
                                        <span class="thumb-icon"><i class="fa fa-camera"></i></span>
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <h3 class="content-grid-title">
                                    <a href="<?php echo get_the_permalink() ?>" title="<?php echo get_the_title() ?>"><?php echo get_the_title() ?></a>
                                </h3>
                            </article>
                            <?php if ( $i % 2 == 0 ) : ?>
                            </div>
                            <hr class="mh-separator hidden-sm">
                            <div class="mh-section mh-group">
                            <?php endif; ?>
                        <?php
                        endwhile;
                    ?>
                    </div>
                </div>
                <?php
		$args = array( 'posts_per_page' => 1, 'category_name' => $category );
                $widget_loop = new WP_Query($args);?>
                <div class="mh-col mh-1-2">
                    <div class="widget_mh_newsdesk_lite_posts_large"><?php
                        while ($widget_loop->have_posts()) : $widget_loop->the_post();
                            ?>
                            <article <?php post_class('content-lead'); ?>>
                                    <div class="content-thumb content-lead-thumb">
                                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                                <?php if (has_post_thumbnail()) { the_post_thumbnail(array(366,206)); } else { echo '<img src="' . get_template_directory_uri() . '/images/placeholder-content-single.jpg' . '" alt="No Picture" />'; } ?>
                                                <?php 
                                                    $post_format = get_post_format();
                                                    $featuredVideoURL = get_post_meta( get_the_ID(), '_featuredVideoURL', true );
                                                ?>
                                                <?php if ( $post_format=='video' && $featuredVideoURL != '' ) : ?>
                                                <span class="thumb-icon"><i class="fa fa-play"></i></span>
                                                <?php elseif ( $post_format=='gallery' && $gallery = get_post_gallery_images( get_the_ID() ) ) : ?>
                                                <span class="thumb-icon"><i class="fa fa-camera"></i></span>
                                                <?php endif; ?>
                                            </a>
                                    </div>
                                    <h3 class="content-lead-title">
                                            <a href="<?php echo get_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
                                    </h3>
                                    <?php if ( $mostrar_copete == 1 ) : ?>
                                            <div class="content-lead-excerpt"> <?php echo cyc_get_paragraph( get_the_excerpt() ) ?></div>
                                    <?php endif; ?>
                            </article>
                            <?php
                        endwhile;
                        wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>
                
                
            <?php else : ?>
            <?php
		$args = array( 'posts_per_page' => 1, 'category_name' => $category );
                $widget_loop = new WP_Query($args);?>
                <div class="mh-col mh-1-2">
                    <div class="widget_mh_newsdesk_lite_posts_large"><?php
                        while ($widget_loop->have_posts()) : $widget_loop->the_post();
                            ?>
                            <article <?php post_class('content-lead'); ?>>
                                    <div class="content-thumb content-lead-thumb">
                                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                                <?php if (has_post_thumbnail()) { the_post_thumbnail(array(366,206)); } else { echo '<img src="' . get_template_directory_uri() . '/images/placeholder-content-single.jpg' . '" alt="No Picture" />'; } ?>
                                                <?php 
                                                    $post_format = get_post_format();
                                                    $featuredVideoURL = get_post_meta( get_the_ID(), '_featuredVideoURL', true );
                                                ?>
                                                <?php if ( $post_format=='video' && $featuredVideoURL != '' ) : ?>
                                                <span class="thumb-icon"><i class="fa fa-play"></i></span>
                                                <?php elseif ( $post_format=='gallery' && $gallery = get_post_gallery_images( get_the_ID() ) ) : ?>
                                                <span class="thumb-icon"><i class="fa fa-camera"></i></span>
                                                <?php endif; ?>
                                            </a>
                                    </div>
                                    <h3 class="content-lead-title">
                                            <a href="<?php echo get_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
                                    </h3>
                                <?php if ( $mostrar_copete == 1 ) : ?>
                                    <div class="content-lead-excerpt"> <?php echo cyc_get_paragraph( get_the_excerpt() ) ?></div>
                                <?php endif; ?>
                            </article>
                            <?php
                        endwhile;
                        wp_reset_postdata(); ?>
                    </div>
                </div>
                <?php
                $i=0;
		$args = array( 'posts_per_page' => 4, 'offset' => 1, 'category_name' => $category );
		$widget_loop = new WP_Query($args); ?>
                <div class="mh-col mh-1-2">
                    <div class="mh-section mh-group">
                        <?php while ($widget_loop->have_posts()) : $widget_loop->the_post(); $i++; ?>
                            <article class="mh-col mh-1-2">
                                <div class="content-thumb content-grid-thumb">
                                    <a href="<?php echo get_the_permalink() ?>" title="<?php echo get_the_title() ?>">
                                        <?php if (has_post_thumbnail()) { the_post_thumbnail(array(178,100)); } else { echo '<img src="' . get_template_directory_uri() . '/images/placeholder-content-single.jpg' . '" alt="No Picture" />'; } ?>
                                        <?php 
                                            
                                            $post_format = get_post_format();
                                            $featuredVideoURL = get_post_meta( get_the_ID(), '_featuredVideoURL', true );
                                        ?>
                                        <?php if ( $post_format=='video' && $featuredVideoURL != '' ) : ?>
                                        <span class="thumb-icon"><i class="fa fa-play"></i></span>
                                        <?php elseif ( $post_format=='gallery' && $gallery = get_post_gallery_images( get_the_ID() ) ) : ?>
                                        <span class="thumb-icon"><i class="fa fa-camera"></i></span>
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <h3 class="content-grid-title">
                                    <a href="<?php echo get_the_permalink() ?>" title="<?php echo get_the_title() ?>"><?php echo get_the_title() ?></a>
                                </h3>
                            </article>
                            <?php if ( $i % 2 == 0 ) : ?>
                            </div>
                            <hr class="mh-separator hidden-sm">
                            <div class="mh-section mh-group">
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
                <?php
            echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['category'] = $new_instance['category'];
        $instance['class'] = $new_instance['class'];
        $instance['direction'] = $new_instance['direction'];
        $instance['mostrar_copete'] = ( $new_instance['mostrar_copete']=='1') ? 1 : 0;
        return $instance;
    }
    function form($instance) {
        $defaults = array('title' => '', 'category' => '', 'class' => '','direction' => 0);
        $instance = wp_parse_args((array) $instance, $defaults); ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Títiulo</label>
            <input class="widefat" type="text" value="<?php echo esc_attr($instance['title']); ?>" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('category'); ?>">Elija una Categoría</label>
            <input class="widefat" type="text" value="<?php echo esc_attr($instance['category']); ?>" name="<?php echo $this->get_field_name('category'); ?>" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('class'); ?>">Clase</label>
            <input class="widefat" type="text" value="<?php echo esc_attr($instance['class']); ?>" name="<?php echo $this->get_field_name('class'); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('direction'); ?>">Ubicación de la Imagen principal</label><br />
            <input type="radio" name="<?php echo $this->get_field_name( 'direction' ); ?>" value=1 <?php checked( $instance['direction'], 1 ) ?> /> Izquierda<br />
            <input type="radio" name="<?php echo $this->get_field_name( 'direction' ); ?>" value=0 <?php checked( $instance['direction'], 0 ) ?> /> Derecha
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('mostrar_copete'); ?>" name="<?php echo $this->get_field_name('mostrar_copete'); ?>" type="checkbox" value="1" <?php checked('1', $instance['mostrar_copete']); ?>/>
            <label for="<?php echo $this->get_field_id('mostrar_copete'); ?>">Mostrar Copete</label>
    	</p>
        <?php
    }
}
?>