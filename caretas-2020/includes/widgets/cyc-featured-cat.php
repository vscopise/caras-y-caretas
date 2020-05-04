<?php

/***** CyC Featured Cat Widget *****/
class cyc_featured_cat extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'mh_cyc_featured_cat',
            'MH Categoría destacada',
            array(
                'classname' => 'mh_newsdesk_lite_featured_cat',
                'description' => 'Muestra un bloque con los últimos 4 post de una categoría.',
                'customize_selective_refresh' => true
            )
        );
    }

    function widget($args, $instance)
    {
        extract($args);

        $cat = isset($instance['cat']) ? $instance['cat'] : '';

        $posts = get_posts(array('category_name' => $cat, 'posts_per_page' => 4));

        $img_placeholder = get_stylesheet_directory_uri() . '/assets/images/img_placeholder.jpg';
        $img0_url = has_post_thumbnail($posts[0]->ID) ? get_the_post_thumbnail_url($posts[0]) : $img_placeholder;
?>
        <div class="featured_cat visible-desktop sb-widget">
            <h4 class="widget-title">
                <span><?php echo get_the_title($posts[0]->ID) ?></span>
            </h4>
            <div class="mh-fp-large-widget clearfix cyc_posts_large">
                <article>
                    <div class="content-thumb content-lead-thumb">
                        <a href="<?php echo get_the_permalink($posts[0]->ID) ?>" title="<?php echo get_the_title($posts[0]->ID); ?>">
                            <img src="<?php echo $img0_url; ?>" alt="<?php echo get_the_title($posts[0]->ID); ?>" />
                            <?php
                            $post_format = get_post_format($posts[0]->ID);
                            $featuredVideoURL = get_post_meta($posts[0]->ID, '_featuredVideoURL', true);
                            ?>
                            <?php if ($post_format == 'video' && $featuredVideoURL != '') : ?>
                                <span class="thumb-icon"><i class="fa fa-play"></i></span>
                            <?php elseif ($post_format == 'gallery' && $gallery = get_post_gallery_images($posts[0]->ID)) : ?>
                                <span class="thumb-icon"><i class="fa fa-camera"></i></span>
                            <?php endif; ?>
                        </a>
                    </div>
                </article>
            </div>
            <div class="sharing">Sharing</div>
            <h4 class="widget-title">
                <span>Más de <?php echo $cat ?></span>
            </h4>
            <div class="mh-section mh-group">
                <?php $i = 1;
                while ($i < 4) : ?>
                    <div class="mh-col mh-1-3">
                        <a href="" title="">
                            <?php $img_url = has_post_thumbnail($posts[$i]->ID) ? get_the_post_thumbnail_url($posts[$i]) : $img_placeholder; ?>
                            <div class="content-thumb">
                                <img src="<?php echo $img_url; ?>" />
                            </div>
                            <h4><?php echo get_the_title($posts[$i]->ID) ?></h4>
                        </a>
                    </div>
                <?php $i++;
                endwhile; ?>
            </div>
        </div><!-- End Featured Cat -->
    <?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['cat'] = isset($new_instance['cat']) ? wp_strip_all_tags($new_instance['cat']) : '';
        return $instance;
    }

    function form($instance)
    {
        $defaults = array(
            'cat'  => '',
        );
        extract(wp_parse_args((array) $instance, $defaults)); ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('cat')); ?>">Categoría</label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('cat')); ?>" name="<?php echo esc_attr($this->get_field_name('cat')); ?>">
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    $selected = $category->slug == $cat ? ' selected="selected"' : '';
                    echo '<option value="' . $category->slug . '" id="' . $category->slug . '"' . $selected . '>' . $category->name . '</option>';
                }
                ?>
            </select>
        </p> <?php
            }
        }
