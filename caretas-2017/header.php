<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php if (is_singular() && pings_open(get_queried_object())) : ?>
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php endif; ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="mh-wrapper">
<header class="mh-header">
	<div class="header-wrap clearfix">
            <div class="mh-col mh-1-3 header-logo">
		        <h1><?php mh_newsdesk_lite_logo(); ?></h1>
                <p class="header-date">Montevideo, <?php echo date_i18n( get_option( 'date_format' ) ); ?></p>
            </div>
            <?php if ( ! is_page_template( 'template-pagos-mercadopago.php' ) ) : ?>
            <div class="mh-col mh-2-3 header-ad"><?php dynamic_sidebar('top_ad_sidebar'); ?></div>
            <?php endif; ?>
	</div>
	<div class="header-menu clearfix">
		<nav class="main-menu clearfix">
                        <?php 
                            wp_nav_menu( array(
                                'theme_location' => 'main_nav',
                                'container' =>false,
                                'menu_class' => 'bombnav nav-res',
                                'walker' => new cyc_mega_walker()
                            ));
                        ?>
                        <div id="clima" class="clearfix">
                            <div class="icon"></div>
                            <div class="temp">
                                <div class="min"></div><div class="max"></div>
                            </div>
                        </div>
		</nav>
	</div>
        <div class="header-sub clearfix">
            <section id="ticker" class="news-ticker mh-col mh-2-3">
                <span class="ticker-title">Ultimas noticias</span>
                <?php $ticker_query = new WP_Query( 'posts_per_page=5' );?>
                <?php if( $ticker_query->have_posts()) : ?>
                <ul class="ticker-content">
                    <?php while( $ticker_query->have_posts()) : $ticker_query->the_post(); ?>
                    <li>
                        <a href="<?php echo the_permalink() ?>"><?php echo the_title() ?></a>
                    </li>
                    <?php endwhile; ?>
                </ul>
                <?php endif; ?>
            </section>
            <div id="redes">
                <a href="https://www.facebook.com/CarasyCaretasuy" target="_blank">
                    <i class="fa fa-facebook-square"></i>
                </a>
                <a href="https://twitter.com/CarasyCaretasuy" target="_blank">
                    <i class="fa fa-twitter-square"></i>
                </a>
                <a href="https://www.youtube.com/channel/UCGc2JWsZwMX9w4BQ3w2ubcA" target="_blank">
                    <i class="fa fa-youtube-square"></i>
                </a>
            </div>
        </div>
</header>