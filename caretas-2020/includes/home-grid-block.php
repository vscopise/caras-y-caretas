<?php
$page_front_options = get_option('mh_newsdesk_lite_options');
$cabezal_grid = $page_front_options['cabezal_grid'];
$cabezal_block = $page_front_options['cabezal_block'];
$cabezal_block_title = $page_front_options['cabezal_block_title'];
$img_placeholder = get_stylesheet_directory_uri() . '/assets/images/img_placeholder.jpg';
?>
<?php if ('' != $cabezal_grid || '' != $cabezal_block) : ?>
    <div class="w3-row grid-block">
        <?php if ('' != $cabezal_grid) : ?>
            <div class="w3-row cabezal-grid">
                <div class="w3-row">
                    <?php
                    if (function_exists('z_get_zone_query')) :
                        $i = 0;
                        $posts_in_grid = z_get_posts_in_zone($cabezal_grid, array('posts_per_page' => 8));
                        if (!empty($posts_in_grid)) : foreach ($posts_in_grid as $post) :
                                $grid_post[] = array(
                                    'image'     => false,
                                    'link'      => false,
                                    'title'     => false,
                                );
                                $grid_post[$i]['link'] = get_the_permalink();
                                $grid_post[$i]['title'] = get_the_title();
                                $grid_post[$i]['image'] = has_post_thumbnail() ?
                                    get_the_post_thumbnail_url() : $img_placeholder;
                                $i++;
                            endforeach;
                        endif;
                    endif;
                    $i = 0;
                    ?>
                    <?php while ($i < 8) : ?>
                        <div class="w3-col l3 s12">
                            <a href="<?php echo $grid_post[$i]['link'] ?>" title="<?php echo $grid_post[$i]['title'] ?>">
                                <div class="grid-image" style="background-image: url('<?php echo $grid_post[$i]['image'] ?>')"></div>
                                <h4><?php echo $grid_post[$i]['title'] ?></h4>
                            </a>
                        </div>
                        <?php
                        $i++;
                        if ($i % 4 == 0) {
                            echo '</div><!-- end w3-row --><div class="w3-row">';
                        }
                        ?>
                    <?php endwhile; ?>
                </div><!-- end w3-row -->
            </div><!-- end cabezal-grid -->
        <?php endif; ?>
        <?php if ('' != $cabezal_block) : ?>
            <div class="cabezal-block home-sidebar">
                <?php if ('' != $cabezal_block_title) : ?>
                    <h4 class="widget-title"><span><?php echo $cabezal_block_title ?></span></h4>
                    <?php if (function_exists('z_get_zone_query')) : ?>
                        <?php $zone_query = z_get_zone_query($cabezal_block, array('posts_per_page' => 1)); ?>
                        <?php if ($zone_query->have_posts()) : while ($zone_query->have_posts()) : $zone_query->the_post(); ?>
                                <a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                    <h3><?php the_title() ?></h3>
                                    <p>Por <?php the_author() ?></p>
                                </a>
                        <?php endwhile;
                        endif; ?>
                    <?php endif; ?>

                <?php endif; ?>
            </div><!-- end cabezal-block -->
        <?php endif; ?>
    </div><!-- end grid-block -->
<?php endif; ?>