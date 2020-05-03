<?php

/***** CyC Posts Large Widget *****/
class cyc_posts_large extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'mh_cyc_posts_large',
            'MH Bloque grande',
            array(
                'classname' => 'mh_newsdesk_lite_posts_large',
                'description' => esc_html__('Display post with large image on front page for use in "Home 1" or "Home 4" widget areas.', 'mh-newsdesk-lite'),
                'customize_selective_refresh' => true
            )
        );
    }

    function widget($args, $instance)
    {
        extract($args);

        $title = isset($instance['title']) ? $instance['title'] : '';
        $category = isset($instance['category']) ? $instance['category'] : '';
        $offset = isset( $instance['offset'] ) ? $instance['offset'] : '';
        $mostrar_copete = isset($instance['mostrar_copete']) ? $instance['mostrar_copete'] : 1;
        $img_placeholder = get_stylesheet_directory_uri() . '/assets/images/img_placeholder.jpg';
        
        if (function_exists('z_get_zone_query') && z_get_zone($category)) {
            $posts_in_zone = z_get_posts_in_zone($category, array('posts_per_page' => 1));
            if (!empty($posts_in_zone)) : foreach ($posts_in_zone as $post) :
                setup_postdata($post);
                    $large_post = array(
                        'id'        => $post->ID,
                        'excerpt'   => $post->post_excerpt,
                        'link'      => get_the_permalink($post->ID),
                        'title'     => get_the_title($post->ID),
                        'image'     => has_post_thumbnail($post->ID) ?
                            get_the_post_thumbnail_url() : $img_placeholder,
                    );
                endforeach;
            endif;
        } else {
            $posts_in_cat = new WP_Query(array('category_name' => $category, 'posts_per_page' => 1, 'offset' => $offset));
            if ($posts_in_cat->have_posts()) : while ($posts_in_cat->have_posts()) : $posts_in_cat->the_post();
                    $id_post = get_the_ID();
                    $excerpt = get_the_excerpt();
                endwhile;
            endif;
        }

        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . esc_attr($title) . $after_title;
        }
        $args = array('posts_per_page' => '1', 'offset' => $offset, 'cat' => $category);
        //$widget_loop = new WP_Query($args); 
?>
        <div class="mh-fp-large-widget clearfix cyc_posts_large">
            <article <?php post_class('content-lead'); ?>>
                <div class="content-thumb content-lead-thumb">
                    <a href="<?php echo $large_post['link']; ?>" title="<?php echo $large_post['title']; ?>">
                        <img src="<?php echo $large_post['image']; ?>" alt="<?php echo $large_post['title']; ?>" />    
                        <?php
                        $post_format = get_post_format($large_post['id']);
                        $featuredVideoURL = get_post_meta($large_post['id'], '_featuredVideoURL', true);
                        ?>
                        <?php if ($post_format == 'video' && $featuredVideoURL != '') : ?>
                            <span class="thumb-icon"><i class="fa fa-play"></i></span>
                        <?php elseif ($post_format == 'gallery' && $gallery = get_post_gallery_images($post['id'])) : ?>
                            <span class="thumb-icon"><i class="fa fa-camera"></i></span>
                        <?php endif; ?>
                    </a>
                </div>
                <h3 class="content-lead-title">
                    <a href="<?php echo $large_post['link']; ?>" title="<?php echo $large_post['title']; ?>" rel="bookmark"><?php echo $large_post['title']; ?></a>
                </h3>
                <?php if ('' != $mostrar_copete) : ?>
                    <div class="content-lead-excerpt">
                        <?php echo cyc_get_paragraph($excerpt) ?>
                    </div>
                <?php endif; ?>
            </article>
            <hr class="mh-separator">
            <?php
            //endwhile;
            wp_reset_postdata(); ?>
        </div>
        <?php
            echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
        $instance['category'] = isset( $new_instance['category'] ) ? wp_strip_all_tags( $new_instance['category'] ) : '';
        $instance['offset']   = isset( $new_instance['offset'] ) ? wp_strip_all_tags( $new_instance['offset'] ) : 0;
        $instance['mostrar_copete'] = isset( $new_instance['mostrar_copete'] ) ? 1 : false;
        return $instance;
    }

    function form($instance)
    {
        $defaults = array(
            'title'             => '', 
            'category'          => '', 
            'offset'            => '', 
            'mostrar_copete'    => '' 
        );
        extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">Título</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>">Elija una Categoría / Zona</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>" type="text" value="<?php echo esc_attr( $category ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'offset' ); ?>">Saltear Posts (Offset)</label>
            <select name="<?php echo $this->get_field_name( 'offset' ); ?>" id="<?php echo $this->get_field_id( 'offset' ); ?>" class="widefat">
            <?php
            $options = array(
                '0' => 0,
                '1' => 1,
                '2' => 2,
                '3' => 3,
            );
            foreach ( $options as $key => $name ) {
                echo '<option value="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" '. selected( $offset, $key, false ) . '>'. $name . '</option>';

            } ?>
            </select>
        </p>
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'mostrar_copete' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'mostrar_copete' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $mostrar_copete ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'mostrar_copete' ) ); ?>">Mostrar Copete</label>
        </p> <?php
    }
}
?>