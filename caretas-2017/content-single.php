<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header clearfix">
                <?php $colgado = get_post_meta( $post->ID, 'COLGADO', true ); ?>
                <?php if ( $colgado ) : ?>
                <p class="colgado"><?php echo $colgado ?></p>
                <?php endif; ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
                <?php if (has_excerpt() ) : ?>
                <div class="entry-excerpt clearfix"><?php the_excerpt() ?></div>
                <?php endif; ?>
	</header>
    
        <?php 
            $post_format = get_post_format();
            $featuredVideoURL = get_post_meta( get_the_ID(), '_featuredVideoURL', true );
        ?>
    
	<?php if ( has_post_format( 'gallery' ) && $gallery = get_post_gallery_images() ) : ?>
            <?php $content = str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', strip_shortcode_gallery( get_the_content() ) ) ); ?>
        <div class="cyc-slider owl-theme">
            <?php foreach( $gallery as $image ) : ?>
                <?php 
                    $image_id = cyc_get_attachment_id_from_url ( $image );
                    $image_attributes = wp_get_attachment_image_src( $image_id, 'content-single' );
                    $caption = get_post( $image_id ) -> post_excerpt;
                    $src = $image_attributes[0];
                    $width = $image_attributes[1];
                    $height = $image_attributes[2];
                ?>
                <div class="item">
                    <img src="<?php echo $src; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" />
                    <?php if ( '' != $caption ) : ?>
                    <p class="caption"><span><i class="fa fa-times" aria-hidden="true"></i></span><?php echo $caption ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ( $post_format=='video' && $featuredVideoURL != '' ) : ?>
        <?php $video_url = substr( $featuredVideoURL, strpos($featuredVideoURL, 'youtu.be/' ) + 9 );?>
        <div class="video-container">
            <iframe width="777" height="437" src="https://www.youtube.com/embed/<?php echo $video_url ?>" frameborder="0" allowfullscreen></iframe>
        </div>
    <?php else : ?>
	<?php mh_newsdesk_lite_featured_image(); ?>
    <?php endif; ?>
    <?php mh_newsdesk_lite_post_meta(); ?>
	<div class="entry-content clearfix">
		<?php //echo $content; ?>
            <?php 
            if ( has_post_format( 'gallery' ) && $gallery = get_post_gallery_images() ) :
                echo $content;
            else: 
                the_content();
            endif;
            ?>
	</div>
</article>
<?php if ( function_exists('MRP_get_related_posts') && ( NULL != MRP_get_related_posts( get_the_ID() ) ) ) : ?>
<div class="cyc-relacionados sb-widget mh_newsdesk_posts_grid">
    <?php $options = get_option('MRP_options') ?>
    <h3 class="content-lead-title"><?php echo $options['title'] ?></h3>
    <div class="sb-widget mh_newsdesk_posts_grid">
        <div class="mh-fp-grid-widget clearfix">
            <div class="mh-fp-grid-widget clearfix">
                <?php $relacionados = MRP_get_related_posts( get_the_ID() ); ?>
                <?php $i = 0; ?>
                <?php foreach ( $relacionados as $id_post => $title ) : ?>
                        <?php 
                        $id_img = get_post_thumbnail_id( $id_post ); 
                        $img = wp_get_attachment_image_src( $id_img, 'medium' );
                        ?>
                        <article class="mh-col mh-1-4 content-grid">
                            <div class="content-thumb content-grid-thumb">
                                <a href="<?php echo get_permalink( $id_post ) ?>">
                                    <div class="grid" style="background-image: url('<?php echo $img[0] ?>')"></div>
                                </a>
                            </div>
                            <h3 class="entry-title content-grid-title">
                                <a href="<?php echo get_permalink( $id_post ) ?>"><?php echo $title ?></a>
                            </h3>
                        </article>
                    
                <?php $i++; if( $i%4 == 0 ) { echo '</div><div class="mh-fp-grid-widget clearfix">'; } ?>
                <?php endforeach; ?>
            </div>
            
        </div>
    </div>
</div>
<?php endif; ?>