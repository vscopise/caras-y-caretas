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
                if ( is_user_logged_in() ) {
                    get_currentuserinfo(); 
                    $user_id = $current_user->ID;
                    $max_reading = get_user_meta( $user_id, 'max_reading', true );
                    $reading = ( get_user_meta( $user_id, 'reading', true ) == '' ) ? 0 : get_user_meta( $user_id, 'reading', true );
                    $last_read = get_user_meta( $user_id, 'last_read', true );

                    if ( $last_read == '' ) {                                   //nunca leyo una revista
                            //$date = date('j/n/Y');
                            $date = strtotime( str_replace('/', '-', date('j/n/Y')) );
                            update_usermeta( $user_id, 'last_read', $date );
                            $reading = 0;
                    } else {
                            $last_read_date = strtotime( str_replace('/', '-', $last_read) );
                            $date = strtotime( date('j-n-Y') );
                            //if ( $date > $last_read_date ) { $reading = 0; }    //primera lectura del día
                            if ( $date > $last_read ) { $reading = 0; }    //primera lectura del día
                    }
                    $enable_reading = ( has_the_subscription($user_id, $date) && $reading < $max_reading );
                } else {
                    if ( $producto[0]->slug != 'caras-y-caretas' ) {
                        $enable_reading = TRUE;
                    }
                }
                ?>
                    <?php if ( $enable_reading ) : ?>
                            <?php
                                $reading = $reading + 1;
                                update_usermeta( $user_id, 'reading', $reading );
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
                    <?php else : ?>
                    <h3>Ud. no tiene acceso para ver este archivo...</h3>
                    <?php endif; ?>
                <?php endwhile; endif; ?>
        <?php else : ?>
                    
            <div class="clearfix" >
                    <?php if ( is_user_logged_in() || $pag != 'caras-y-caretas' ) : ?>
                            <?php if ( $enable_reading || $producto[0]->slug != 'caras-y-caretas' ) : ?>
                                    <p>Haga click en la miniatura de la imagen para ver la versi&oacute;n completa</p>
                            <?php endif; ?>
                    <?php else : ?>
                            <?php $link_login = '#TB_inline?height=300&amp;width=350&amp;inlineId=cyc-login-popup' ?>
                            <h4 class="content-list-title">
                                    <a href="<?php echo $link_login?>" class="thickbox">
                                            ¿ Ya est&aacute; registrado ? Ingrese aqui
                                    </a>
                            </h4>
                            <?php $link_suscripciones = get_page_link( get_page_by_path( 'suscripciones' )->ID ); ?>
                            <h4 class="content-list-title">
                                    <a href="<?php echo $link_suscripciones ?>">
                                            ¿No lo está? Entonces suscríbase aquí.
                                    </a>
                            </h4>
                    <?php endif; ?>
                </div>
                <?php
                if ( is_user_logged_in() ) {
                    //$producto = get_the_terms( $post, 'productos');
                    get_currentuserinfo(); 
                    $user_id = $current_user->ID;
                    $max_reading = get_user_meta( $user_id, 'max_reading', true );
                    $reading = ( get_user_meta( $user_id, 'reading', true ) == '' ) ? 0 : get_user_meta( $user_id, 'reading', true );
                    $last_read = get_user_meta( $user_id, 'last_read', true );
                    
                    $date = strtotime( date('j-n-Y') );

                    if ( $last_read == '' ) {                                   //nunca leyo una revista
                            $last_read = date('j/n/Y');
                            $reading = 0;
                    } else {
                            $last_read_date = strtotime( str_replace('/', '-', $last_read) );
                            //$date = strtotime( date('j-n-Y') );
                            if ( $date > $last_read_date ) { $reading = 0; }    //primera lectura del día
                    }
                    //update_usermeta( $user_id, 'last_read', $last_read );

                    $enable_reading = ( has_the_subscription($user_id, $date) && $reading < $max_reading );

                }
                ?>
        

	
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
                                                    if ( $enable_reading || $producto[0]->slug != 'caras-y-caretas' ) {
                                                        $link = add_query_arg( array('ed' =>  $post->ID), home_url( add_query_arg(array(), $wp->request) ) );
                                                    } else {
                                                        //$link = home_url(add_query_arg(array(),$wp->request));
                                                        $link = get_permalink( get_page_by_path( 'suscripciones' ) );
                                                    }
                                                    ?>
                                                    <article class="mh-col mh-1-4 content-grid">
                                                        <div class="content-thumb content-grid-thumb">
                                                            <a href="<?php echo $link ?>"><?php the_post_thumbnail( array(260, 346) ); ?></a>
                                                        </div>
                                                        <h3 class="entry-title content-grid-title">
                                                            <a href="<?php echo $link ?>"><?php the_title() ?></a>
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