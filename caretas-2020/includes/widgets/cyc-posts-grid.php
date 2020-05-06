<?php

/***** MH Custom Grid Widget *****/

class cyc_posts_grid extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'mh_newsdesk_lite_custom_grid',
            'MH Custom Grid',
            array(
                'classname' => 'mh_newsdesk_lite_custom_grid',
                'description' => 'Muestra post en forma de grilla',
                'customize_selective_refresh' => true
            )
        );
    }

    function widget($args, $instance)
    {
        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $category = isset($instance['category']) ? $instance['category'] : '';
        $direction = (1 == $instance['direction']) ? 'left' : 'right';
        $mostrar_copete = $instance['mostrar_copete'];
        $img_placeholder = get_stylesheet_directory_uri() . '/assets/images/img_placeholder.jpg';

        echo $before_widget; ?>
        <div class="sb-widget cyc_posts_grid <?php echo $category ?>">
            <?php if (!empty($title)) echo $before_title . esc_attr($title) . $after_title; ?>
            <div class="mh-section mh-group <?php echo $direction ?>">
                <?php
                $posts = get_posts( array('category_name' => $category, 'posts_per_page' => 5) );
                if (!empty($posts)) : foreach ($posts as $post) :
                        $grid_posts[] = array(
                            'id'        => $post->ID,
                            'excerpt'   => $post->post_excerpt,
                            'link'      => get_the_permalink($post->ID),
                            'title'     => get_the_title($post->ID),
                            'image'     => has_post_thumbnail($post->ID) ? get_the_post_thumbnail_url($post) : $img_placeholder,
                        );
                    endforeach;
                endif;
                ?>
                <div class="mh-col mh-1-2">
                    <div class="content-thumb content-lead-thumb">
                        <a href="<?php echo $grid_posts[0]['link'] ?>" title="<?php echo $grid_posts[0]['title'] ?>">
                            <img src="<?php echo $grid_posts[0]['image'] ?>" />
                        </a>
                    </div>
                    <h3 class="content-lead-title">
                        <a href="<?php echo $grid_posts[0]['link'] ?>" title="<?php echo $grid_posts[0]['title'] ?>" rel="bookmark"><?php echo $grid_posts[0]['title'] ?></a>
                    </h3>
                    <?php if ($mostrar_copete == 1) : ?>
                        <div class="content-lead-excerpt"><?php echo $grid_posts[0]['excerpt'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="mh-col mh-1-2">
                    <div class="mh-section mh-group">
                        <div class="mh-col mh-1-2">
                            <?php $i = 1;
                            while ($i < 5) : ?>
                                <div>
                                    <div class="content-thumb content-lead-thumb">
                                        <a href="<?php echo $grid_posts[$i]['link'] ?>" title="<?php echo $grid_posts[$i]['title'] ?>">
                                            <img src="<?php echo $grid_posts[$i]['image'] ?>" />
                                        </a>
                                    </div>
                                    <h3 class="content-lead-title">
                                        <a href="<?php echo $grid_posts[$i]['link'] ?>" title="<?php echo $grid_posts[$i]['title'] ?>" rel="bookmark"><?php echo $grid_posts[$i]['title'] ?></a>
                                    </h3>
                                </div>
                                <?php $i++;
                                if ((2 * $i) % 3 == 0) {
                                    echo '</div><div class="mh-col mh-1-2">';
                                } ?>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- End cyc_posts_grid -->
    <?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = isset($new_instance['title']) ? wp_strip_all_tags($new_instance['title']) : '';
        $instance['category'] = isset($new_instance['category']) ? wp_strip_all_tags($new_instance['category']) : '';
        $instance['direction'] = isset($new_instance['direction']) ? 1 : false;
        $instance['mostrar_copete'] = isset($new_instance['mostrar_copete']) ? 1 : false;
        return $instance;
    }

    function form($instance)
    {
        $defaults = array(
            'title'             => '',
            'category'          => '',
            'direction'         => '',
            'mostrar_copete'    => '',
        );
        extract(wp_parse_args((array) $instance, $defaults)); ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Títiulo</label>
            <input class="widefat" type="text" value="<?php echo esc_attr($title); ?>" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('category'); ?>">Elija una Categoría</label>
            <input class="widefat" type="text" value="<?php echo esc_attr($category); ?>" name="<?php echo $this->get_field_name('category'); ?>" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
        <p>
            <input id="<?php echo esc_attr($this->get_field_id('direction')); ?>" name="<?php echo esc_attr($this->get_field_name('direction')); ?>" type="checkbox" value="1" <?php checked('1', $direction); ?> />
            <label for="<?php echo esc_attr($this->get_field_id('direction')); ?>">Imagen principal a la izquierda</label>
        </p>
        <p>
            <input id="<?php echo esc_attr($this->get_field_id('mostrar_copete')); ?>" name="<?php echo esc_attr($this->get_field_name('mostrar_copete')); ?>" type="checkbox" value="1" <?php checked('1', $mostrar_copete); ?> />
            <label for="<?php echo esc_attr($this->get_field_id('mostrar_copete')); ?>">Mostrar Copete</label>
        </p> <?php
            }
        }

                ?>