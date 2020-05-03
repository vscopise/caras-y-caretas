<?php /* Template Name: Page - Inicio */ ?>
<?php get_header(); ?>
<div class="home mh-section mh-group">
    <?php include_once( get_stylesheet_directory() . '/includes/home-main-grid.php'); ?>
	<?php if (is_active_sidebar('home-8')) : ?>
		<div class="w3-row grid-block">
			<?php dynamic_sidebar('home-8'); ?>
		</div>
	<?php endif; ?>
	<?php include_once( get_stylesheet_directory() . '/includes/home-grid-block.php'); ?>
	<div id="home-bottom" class="home-columns"></div>
</div>
<?php get_footer(); ?>