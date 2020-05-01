<?php /* Template Name: Edición impresa */ ?>
<?php 
        get_header(); 

        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
        $pag = ( get_query_var('pag') ) ? get_query_var('pag') : 'caras-y-caretas';
        $ed = get_query_var('ed');
?>
<div class="mh-section mh-group">
    <div id="main-content" class="mh-content">
        <article>
        <?php if ($ed != '') : ?>
                <?php $revistas = new WP_Query( array( 'post_type' => 'revistas', 'p' => $ed ) ); ?>
                <?php if ( $revistas->have_posts() ) : while ( $revistas-> have_posts()) : $revistas->the_post(); ?>
                <?php 
                    $producto = get_the_terms( $post, 'productos');
                
                    $issuu = ( '' == get_post_meta( $post->ID, '_issuu', true ) ) ? '&nbsp;' : get_post_meta( $post->ID, '_issuu', true );
                    $dflip = get_post_meta( $post->ID, '_dflip', true );
                ?>
                <?php if ( class_exists( 'DFlip' ) && '' != $dflip ) :?>
                    <div class="mh-section mh-group"><?php echo do_shortcode( html_entity_decode ( $dflip ) ); ?></div>
                <?php else: ?>
                    <div class="mh-section mh-group">
                        <div><p><?php echo html_entity_decode( $issuu ); ?></p></div>
                    </div>
                <?php endif; ?>
                    
                <?php endwhile; endif; ?>
        <?php else : ?>
                    
            <div class="clearfix" >
                <p>Haga click en la miniatura de la imagen para ver la versi&oacute;n completa</p>
                    
                </div>
                    <?php mh_newsdesk_lite_before_page_content(); ?>
                    <?php while (have_posts()) : the_post(); ?>
                    
                            <header class="entry-header"><?php mh_newsdesk_lite_page_title(); ?></header>
                            <div class="clearfix">
                                    <?php 
                                        $revistas = new WP_Query( array(
                                                'post_type' => 'revistas',
                                                'tax_query' => array(
                                                    array(
                                                        'taxonomy' => 'productos',
                                                        'field'    => 'slug',
                                                        'terms'    => $pag,
                                                    ),
                                                ),
                                                'posts_per_page' => 20,
                                                'paged' => $paged
                                        ));
                                    ?>
                                    <?php if ( $revistas->have_posts() ) : $i = 0; ?>
                                            <?php while( $revistas->have_posts() ) : $revistas->the_post(); $i++; ?>
                                                    <?php 
                                                    $producto = get_the_terms( $post, 'productos');
                                                    
                                                    $issuu = get_post_meta( $post->ID, '_issuu', true );
                                                    $dflip = get_post_meta( $post->ID, '_dflip', true );
                                                    
                                                    if ( $issuu == '' && $dflip == '' ) {
                                                        $link = 'http://subscriber.pagesuite.com/subscribe.aspx?source=4&eid=c42ba4af-3c20-44ed-8739-7caeef7ab433';
                                                    } else {
                                                        $link = add_query_arg( array('ed' =>  $post->ID), home_url( add_query_arg(array(), $wp->request) ) );
                                                    }
                                                    
                                                    ?>
                                                    <article class="mh-col mh-1-4 content-grid">
                                                        <div class="content-thumb content-grid-thumb">
                                                            <a href="<?php echo $link ?>" target="_blank"><?php the_post_thumbnail( array(260, 346) ); ?></a>
                                                        </div>
                                                        <h3 class="entry-title content-grid-title">
                                                            <a href="<?php echo $link ?>" target="_blank"><?php the_title() ?></a>
                                                        </h3>
                                                    </article>
                                                    <?php if ( $i % 4 == 0 ) : ?>
                                                            </div>
                                                            <hr class="mh-separator hidden-sm">
                                                            <div class="mh-section mh-group">
                                                    <?php endif; ?>
                                            <?php endwhile; ?>
                                    <?php endif; ?>
                                    
                            </div>
                    
                    
                    <?php endwhile; ?>
                <?php endif; ?>
                        
                                                            
                        <?php
                            if (function_exists(cyc_custom_pagination)) {
                                cyc_custom_pagination( $revistas->max_num_pages );
                            }
                        ?>
                </article>
	</div>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>