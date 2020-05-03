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
	<div class="header-wrap mh-clearfix">
        <div class="mh-col mh-1-3 header-logo">
            <h1><?php mh_newsdesk_lite_logo(); ?></h1>
            <p class="header-date">Montevideo, <?php echo date_i18n( get_option( 'date_format' ) ); ?></p>
        </div>
        <div class="mh-col mh-2-3 header-ad"><?php dynamic_sidebar('top_ad_sidebar'); ?></div>
	</div>
	<div class="header-menu mh-clearfix">
		<nav class="main-nav mh-clearfix">
			<?php wp_nav_menu(array('theme_location' => 'main_nav')); ?>
		</nav>
	</div>
</header>