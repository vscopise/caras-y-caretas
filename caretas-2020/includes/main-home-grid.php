<?php
$page_front_options = get_option('mh_newsdesk_lite_options');
$cabezal_home = $page_front_options['cabezal_home'];
$img_placeholder = get_stylesheet_directory_uri() . '/assets/images/img_placeholder.jpg';
?>
<?php if ('' != $cabezal_home) : ?>
    <?php
    $i = 0;
    if (function_exists('z_get_zone_query')) {
        $posts_in_cabezal = z_get_posts_in_zone( $cabezal_home, array( 'posts_per_page' => 4 )  );
        if (!empty($posts_in_cabezal)) : foreach ($posts_in_cabezal as $post) :
            $cabezal_post[] = array( 
                'excerpt'   => false, 
                'image'     => false,
                'link'      => false,
                'title'     => false,
            );
            $cabezal_post[$i]['link'] = get_the_permalink();
            if(has_excerpt()) {
                $cabezal_post[$i]['excerpt'] = get_the_excerpt();
            }
            $cabezal_post[$i]['title'] = get_the_title();
            if (has_post_thumbnail()) {
                $cabezal_post[$i]['image'] = get_the_post_thumbnail_url():
            }
            $i++;
        endforeach; endif;
    } else {
        $posts_in_cat = new WP_Query(array('category_name' => $cabezal_home, 'posts_per_page' => 4));
        if ($posts_in_cat->have_posts()) : while ($posts_in_cat->have_posts()) : $posts_in_cat->the_post();
                $id_post[$i] = get_the_ID();
                $i++;
            endwhile;
        endif;
    }
    $bgd_image_0 = $cabezal_post[0]['image'] ? $cabezal_post[0]['image'] : $img_placeholder;
    $bgd_image_1 = $cabezal_post[1]['image'] ? $cabezal_post[1]['image'] : $img_placeholder;
    $bgd_image_2 = $cabezal_post[2]['image'] ? $cabezal_post[2]['image'] : $img_placeholder;
    $bgd_image_3 = $cabezal_post[3]['image'] ? $cabezal_post[3]['image'] : $img_placeholder;
    ?>
    <div class="w3-row cabezal">
        <a href="<?php echo $cabezal_post[0]['link'] ?>" title="<?php echo $cabezal_post[0]['title'] ?>">
            <div class="w3-col l6 s12 cab-a img-container">
                <div class="image" style="background-image: url('<?php echo $bgd_image_0 ?>')"></div>
                <div class="meta">
                    <div class="content">
                        <h2><?php echo $cabezal_post[0]['title'] ?></h2>
                        <p><?php echo $cabezal_post[0]['excerpt'] ?></p>
                    </div>
                </div>
            </div>
        </a>
        <div class="w3-col l6 s12">
            <a href="<?php echo $cabezal_post[1]['link'] ?>" title="<?php echo $cabezal_post[1]['title'] ?>">
                <div class="w3-row cab-b img-container">
                        <div class="image" style="background-image: url('<?php echo $bgd_image_1 ?>')"></div>
                        <div class="meta">
                            <div class="content">
                                <h3><?php echo $cabezal_post[1]['title'] ?></h3>
                            </div>
                        </div>
                </div>
            </a>
            <div class="w3-row">
                <a href="<?php echo $cabezal_post[2]['link'] ?>" title="<?php echo $cabezal_post[2]['title'] ?>">
                    <div class="w3-col l6 s12 cab-c img-container">
                        <div class="image" style="background-image: url('<?php echo $bgd_image_2 ?>')"></div>
                        <div class="meta">
                            <div class="content">
                                <h4><?php echo $cabezal_post[2]['title'] ?></h4>
                            </div>
                        </div>
                    </div>
                </a>
                <a class="visible-desktop" href="<?php echo $cabezal_post[3]['link'] ?>" title="<?php echo $cabezal_post[3]['title'] ?>">
                    <div class="w3-col l6 s12 cab-d img-container">
                        <div class="image" style="background-image: url('<?php echo $bgd_image_3 ?>"></div>
                        <div class="meta">
                            <div class="content">
                                <h4><?php echo $cabezal_post[3]['title'] ?></h4>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div><!-- end cabezal -->
<?php endif; ?>