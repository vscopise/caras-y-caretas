<?php /* Template Name: Page - Inicio */ ?>
<?php 
    get_header(); 
    $page_front_options = get_option('mh_newsdesk_lite_options');
    $cabezal_home = $page_front_options[cabezal_home];
    $cabezal_grid = $page_front_options[cabezal_grid];
    $cabezal_block = $page_front_options[cabezal_block];
    $cabezal_block_title = $page_front_options[cabezal_block_title];
?>
<div class="home mh-section mh-group">
        <?php if ( '' != $cabezal_home ) : ?>
            <?php
                $i = 0;
                if ( function_exists( 'z_get_zone_query' ) ) {
                    $posts_in_zone_cabezal = z_get_posts_in_zone( $cabezal_home, array( 'posts_per_page' => 4 )  );
                    if ( ! empty ( $posts_in_zone_cabezal ) ) : foreach( $posts_in_zone_cabezal as $post) :
                        $id_post[$i] = $post->ID;
                        $excerpt[$i] = $post->post_excerpt;
                        $i++; 
                    endforeach; endif;
                } else {
                    $posts_in_cat = new WP_Query( array( 'category_name' => $cabezal_home, 'posts_per_page' => 4 ) );
                    if ( $posts_in_cat->have_posts() ) : while( $posts_in_cat->have_posts() ) : $posts_in_cat->the_post();
                        $id_post[$i] = get_the_ID();
                        $i++; 
                    endwhile; endif;
                }
                $id_img_0 = get_post_thumbnail_id( $id_post[0] );
                $id_img_1 = get_post_thumbnail_id( $id_post[1] );
                $id_img_2 = get_post_thumbnail_id( $id_post[2] );
                $id_img_3 = get_post_thumbnail_id( $id_post[3] );

                $img_0 = wp_get_attachment_image_src( $id_img_0, 'large' );
                $img_1 = wp_get_attachment_image_src( $id_img_1, 'large' );
                $img_2 = wp_get_attachment_image_src( $id_img_2, 'large' );
                $img_3 = wp_get_attachment_image_src( $id_img_3, 'large' );

                $tit_0 = get_the_title( $id_post[0] );
                $tit_1 = get_the_title( $id_post[1] );
                $tit_2 = get_the_title( $id_post[2] );
                $tit_3 = get_the_title( $id_post[3] );

                $exc_0 = get_the_excerpt( $id_post[0] );
            ?>

                <div class="w3-row cabezal">
                    <a href="<?php echo get_permalink( $id_post[0] )?>" title="<?php echo get_the_title( $id_post[0] ) ?>">
                        <div class="w3-col l6 s12 cab-a img-container" >
                            <div class="image" style="background-image: url('<?php echo $img_0[0] ?>')"></div>
                            <div class="meta">
                                <div class="content">
                                    <h2><?php echo $tit_0 ?></h2>
                                    <p><?php echo $exc_0 ?></p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="w3-col l6 s12">
                        <a href="<?php echo get_permalink( $id_post[1] )?>" title="<?php echo get_the_title( $id_post[1] ) ?>">
                            <div class="w3-row cab-b img-container">
                                <div class="image" style="background-image: url('<?php echo $img_1[0] ?>')"></div>
                                <div class="meta">
                                    <div class="content"><h3><?php echo $tit_1?></h3></div>
                                </div>
                            </div>
                        </a>
                        <div class="w3-row">
                            <a href="<?php echo get_permalink( $id_post[2] )?>" title="<?php echo get_the_title( $id_post[2] ) ?>">
                                <div class="w3-col l6 s12 cab-c img-container">
                                    <div class="image" style="background-image: url('<?php echo $img_2[0] ?>')"></div>
                                    <div class="meta">
                                        <div class="content"><h4><?php echo $tit_2?></h4></div>
                                    </div>
                                </div>
                            </a>
                            <a class="visible-desktop" href="<?php echo get_permalink( $id_post[3] )?>" title="<?php echo get_the_title( $id_post[3] ) ?>">
                                <div class="w3-col l6 s12 cab-d img-container">
                                    <div class="image" style="background-image: url('<?php echo $img_3[0] ?>')"></div>
                                    <div class="meta">
                                        <div class="content"><h4><?php echo $tit_3?></h4></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
        <?php endif; ?>
        <div class="visible-phone mh_newsdesk_lite_posts_large">
            <h4 class="widget-title"><span>Caras y Caretas TV</span></h4>
            <?php $post_in_cat_video = new WP_Query( array( 'category_name' => 'caras-y-caretas-tv', 'posts_per_page' => '1' ) ); ?>
                <?php if ( $post_in_cat_video->have_posts() ) : while($post_in_cat_video->have_posts()) : $post_in_cat_video->the_post(); ?>
                <div class="mh-fp-large-widget clearfix cyc_posts_large sb-widget ">
                    <article>
                        <div class="content-thumb content-lead-thumb">
                            <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark">
                                <?php $featuredVideoURL = get_post_meta( get_the_ID(), '_featuredVideoURL', true ); ?>
                                <?php //echo substr( $featuredVideoURL, strrpos( $featuredVideoURL, '/' ) ) ?>
                                <img src="https://img.youtube.com/vi<?php echo substr( $featuredVideoURL, strrpos( $featuredVideoURL, '/' ) ) ?>/mqdefault.jpg" />
                            </a>
                        </div>
                        <h3 class="content-lead-title">
                            <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a>
                        </h3>
                    </article>
                </div>
            <?php endwhile; endif; ?>
        </div>
        <div class="visible-desktop w3-row grid-block" style="margin-bottom: 20px;">
            <a href="/categoria/coronavirus/">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/templates/banner-coronavirus-2.jpg" />
            </a>
        </div>
        <div class="w3-row grid-block" style="background: #d00; padding: 9px 12px; margin-bottom: 20px;">
            <a href="/edicion-impresa/">
                <h3 style="color: #fff; ">Haga click para ir a la edición impresa</h3>
            </a>
        </div>
        <?php if (is_active_sidebar('home-8')) : ?>
                <div class="w3-row grid-block visible-phone">
                        <?php dynamic_sidebar('home-8'); ?>
                </div>
        <?php endif; ?>
    
        <?php if ( '' != $cabezal_grid || '' != $cabezal_block ) : ?>
            <div class="w3-row grid-block">
                <?php if ( '' != $cabezal_grid ) : $i = 0; ?>
                <div class="w3-row cabezal-grid">
                    <div class="w3-row">
                    <?php 
                        if ( function_exists( 'z_get_zone_query' ) ) {
                            $posts_in_zone_grid = z_get_posts_in_zone( $cabezal_grid, array( 'posts_per_page' => 8 )  );
                            if ( ! empty ( $posts_in_zone_grid ) ) : foreach( $posts_in_zone_grid as $post) :
                                $id_post[$i] = $post->ID;
                                $id_img[$i] = get_post_thumbnail_id( $id_post[$i] );
                                $img[$i] = wp_get_attachment_image_src( $id_img[$i], array(178,100) );
                                ?>
                                <div class="w3-col l3 s12">
                                    <a href="<?php echo get_permalink( $id_post[$i] ) ?>" title="<?php echo get_the_title( $id_post[$i] ) ?>">
                                        <div class="grid-image" style="background-image: url('<?php echo $img[$i][0] ?>')">
                                        </div>
                                        <h4><?php echo get_the_title( $id_post[$i] ) ?></h4>
                                    </a>
                                </div>
                                <?php
                                $i++; 
                                if($i%4==0) {
                                    echo '</div><div class="w3-row">';
                                }
                            endforeach; endif;
                        } else {
                            $posts_in_cat = new WP_Query( array( 'category_name' => $cabezal_grid, 'posts_per_page' => 8 ) );
                            if ( $posts_in_cat->have_posts() ) : while( $posts_in_cat->have_posts() ) : $posts_in_cat->the_post();
                                $id_post[$i] = get_the_ID();
                                $id_img[$i] = get_post_thumbnail_id( $id_post[$i] );
                                $img[$i] = wp_get_attachment_image_src( $id_img[$i], array(178,100) );
                                ?>
                                <div class="w3-col l3 s12">
                                    <a href="<?php echo get_permalink( $id_post[$i] ) ?>" title="<?php echo get_the_title( $id_post[$i] ) ?>">
                                        <img src="<?php echo $img[$i][0] ?>" />
                                        <h4><?php echo get_the_title( $id_post[$i] ) ?></h4>
                                    </a>
                                </div>
                                <?php
                                $i++;
                                if($i%4==0) {
                                    echo '</div><div class="w3-row">';
                                }
                            endwhile; endif;
                        }
                    ?>
                    </div>
                    
                </div>
                    
                <?php endif; ?>
                
                <?php if ( '' != $cabezal_block ) : ?>
                    <div class="cabezal-block">
                        <?php if ( '' != $cabezal_block_title ) : ?>
                            <h4 class="widget-title"><span><?php echo $cabezal_block_title ?></span></h4>
                            <?php if ( function_exists( 'z_get_zone_query' ) ) : ?>
                                <?php $zone_query = z_get_zone_query( $cabezal_block, array( 'posts_per_page' => 1 ) ); ?>
                                <?php if ( $zone_query->have_posts() ) : while ( $zone_query->have_posts() ) : $zone_query->the_post(); ?>
                                    <a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
                                        <?php the_post_thumbnail(array(341,203)); ?>
                                        <h3><?php the_title() ?></h3>
                                        <p>Por <?php the_author() ?></p>
                                    </a>
                                <?php endwhile; endif; ?>
                            <?php endif; ?>
                        
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    
	    <div id="main-content" class="home-columns">
	    
            <div>
                <?php $post_in_cat_video = new WP_Query( array( 'category_name' => 'caras-y-caretas-tv', 'posts_per_page' => 1 ) ); ?>
                <?php if ( $post_in_cat_video->have_posts() ) : while($post_in_cat_video->have_posts()) : $post_in_cat_video->the_post(); ?>
                <?php 
    	            $featuredVideoURL = get_post_meta( get_the_ID(), '_featuredVideoURL', true );
                    $video_id = explode("/", $featuredVideoURL); 
    	        ?>
                <div class="visible-desktop sb-widget">
                    
                    <h4 class="widget-title"><span><?php the_title(); ?></span></h4>
                    <iframe src="https://www.youtube.com/embed/<?php echo $video_id[3]?>" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="750" height="422" type="text/html">
                    </iframe>
                    <!-- AddToAny BEGIN -->
                    <div class="a2a_kit a2a_kit_size_32 a2a_default_style" data-a2a-url="https://www.youtube.com/embed/<?php echo $video_id[3]?>">
                        <a class="a2a_button_facebook"></a>
                        <a class="a2a_button_twitter"></a>
                        <a href="https://www.instagram.com/carasycaretasuy/?utm_source=ig_profile_share&igshid=OdnEddxkZXQ" target="_blank">
                            <i class="fa fa-instagram" aria-hidden="true" style="font-size: 32px;"></i>
                        </a>
                    </div>
                    <script>
                        var a2a_config = a2a_config || {};
                        a2a_config.locale = "es";
                    </script>
                    <script async src="https://static.addtoany.com/menu/page.js"></script>
                    <!-- AddToAny END -->
                </div>
                <?php endwhile; endif; ?>
                <?php $post_in_cat_video = new WP_Query( array( 'category_name' => 'caras-y-caretas-tv', 'posts_per_page' => 3, 'offset' => 1 ) ); ?>
                <?php if ( $post_in_cat_video->have_posts() ) : ?>
                    <div class="visible-desktop">
                        <h4 class="widget-title"><span>M&aacute;s de Caras y Caretas TV</span></h4>
                        <div class="mh-section mh-group">
                            <?php while($post_in_cat_video->have_posts()) : $post_in_cat_video->the_post(); ?>
                                <?php 
                    	            $featuredVideoURL = get_post_meta( get_the_ID(), '_featuredVideoURL', true );
                                    $video_id = explode("/", $featuredVideoURL); 
                    	        ?>
                                <div class="mh-col mh-1-3">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                        <div class="content-thumb">
                                            <img src="https://img.youtube.com/vi/<?php echo $video_id[3] ?>/maxresdefault.jpg" />    
                                        </div>
            		                    <h4><?php the_title() ?></h4>
            		                </a>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        
		<?php dynamic_sidebar('home-1'); ?>
		<?php if (is_active_sidebar('home-2') || is_active_sidebar('home-3')) : ?>
			<div class="mh-section mh-group">
				<?php if (is_active_sidebar('home-2')) { ?>
					<div class="mh-col mh-1-2 home-2">
						<?php dynamic_sidebar('home-2'); ?>
					</div>
				<?php } ?>
				<?php if (is_active_sidebar('home-3')) { ?>
					<div class="mh-col mh-1-2 home-3">
					    <?php if ( function_exists( 'z_get_zone_query' ) ) : ?>
                            <?php $zone_query = z_get_zone_query( 'zona2', array( 'posts_per_page' => 1 ) ); ?>
                            <?php if ( $zone_query->have_posts() ) : while ( $zone_query->have_posts() ) : $zone_query->the_post(); ?>
                                <div class="visible-desktop">
                                    <h4 class="widget-title"><span>Noticia destacada</span></h4>
                                    <div class="content-thumb content-lead-thumb">
                                        <a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
                                        <?php the_post_thumbnail(array(341,203)); ?>
                                    </a>        
                                    </div>
                                    <a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
                                        <h3 class="content-lead-title"><?php the_title() ?></h3>
                                    </a>    
                                </div>
                            <?php endwhile; endif; ?>
                        <?php endif; ?>
						<?php dynamic_sidebar('home-3'); ?>
					</div>
				<?php } ?>
			</div>
		<?php endif; ?>
		<?php dynamic_sidebar('home-4'); ?>
                <?php if (is_active_sidebar('home-6') || is_active_sidebar('home-7')) : ?>
                        <div class="mh-section mh-group">
				<?php if (is_active_sidebar('home-6')) { ?>
					<div class="mh-col mh-1-2 home-6">
						<?php dynamic_sidebar('home-6'); ?>
					</div>
				<?php } ?>
				<?php if (is_active_sidebar('home-7')) { ?>
					<div class="mh-col mh-1-2 home-7">
						<?php dynamic_sidebar('home-7'); ?>
					</div>
				<?php } ?>
			</div>
		<?php endif; ?>
	</div>
	<aside class="home-sidebar">
		<?php dynamic_sidebar('home-5'); ?>
	</aside>
</div>
<?php get_footer(); ?>