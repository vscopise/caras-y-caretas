<?php

/***** Estilos padre e hijo *****/
function cyc_enqueue_parent_styles()
{
    $version = mt_rand();
    if (WP_DEBUG == true) {
        wp_enqueue_style(
            'parent-styles',
            get_template_directory_uri() . '/style.css',
            '',
            $version
        );
        wp_enqueue_style(
            'child-styles',
            get_stylesheet_directory_uri() . '/assets/styles/style.css',
            array('parent-styles'),
            $version
        );
        if (is_front_page()) {
            wp_enqueue_style(
                'front-page-styles',
                get_stylesheet_directory_uri() . '/assets/styles/front-page-styles.css',
                array('parent-styles'),
                $version
            );
        }
    } else {
        wp_enqueue_style(
            'parent-styles',
            get_stylesheet_directory_uri() . '/assets/styles/parent-styles.min.css',
            '',
            $version
        );
        wp_enqueue_style(
            'child-styles',
            get_stylesheet_directory_uri() . '/assets/styles/styles.min.css',
            array('parent-styles-minified'),
            $version
        );
        if (is_front_page()) {
            wp_enqueue_style(
                'front-page-styles',
                get_stylesheet_directory_uri() . '/assets/styles/front-page-styles.min.css',
                array('parent-styles-minified'),
                $version
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'cyc_enqueue_parent_styles');

function cyc_theme_scripts()
{
    $version = mt_rand();
    if (WP_DEBUG == true) {
        wp_enqueue_script(
            'theme_script',
            get_stylesheet_directory_uri() . '/assets/js/theme-scripts.js',
            array('jquery'),
            $version,
            true
        );
        if (is_front_page()) {
            wp_enqueue_script(
                'front-page_script',
                get_stylesheet_directory_uri() . '/assets/js/front-page-scripts.js',
                array('jquery'),
                $version,
                true
            );
        }
    } else {
        wp_enqueue_script(
            'theme_script',
            get_stylesheet_directory_uri() . '/assets/js/theme-scripts.min.js',
            array('jquery'),
            $version,
            true
        );
        if (is_front_page()) {
            wp_enqueue_script(
                'front-page_script',
                get_stylesheet_directory_uri() . '/assets/js/front-page-scripts.min.js',
                array('jquery'),
                $version,
                true
            );
        }
    }
    wp_localize_script('theme_script', 'theme_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'cyc_theme_scripts');

/***** Ticker de Noticias *****/
function cyc_header_ticker()
{
    $ticker_query = new WP_Query('posts_per_page=5');

    if ($ticker_query->have_posts()) :  while ($ticker_query->have_posts()) : $ticker_query->the_post();
            $ticker_posts[] = array(
                'title' => get_the_title(),
                'link'  => get_the_permalink(),
            );
        endwhile;
    endif;

    echo json_encode($ticker_posts);
    die();
}
add_action('wp_ajax_nopriv_cyc_header_ticker', 'cyc_header_ticker');
add_action('wp_ajax_cyc_header_ticker', 'cyc_header_ticker');

/***** Carga Ajax del Home bottom *****/
function cyc_home_bottom()
{
    ob_start();
    include('includes/home-bottom.php');
    echo ob_get_clean();
    die();
}
add_action('wp_ajax_nopriv_cyc_home_bottom', 'cyc_home_bottom');
add_action('wp_ajax_cyc_home_bottom', 'cyc_home_bottom');

/***** Zonas de Widgets adicionales *****/
function cyc_widgets_init()
{
    register_sidebar(array('name' => 'Home 6 - First Column (Bottom)', 'id' => 'home-6', 'before_widget' => '<div id="%1$s" class="sb-widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title"><span>', 'after_title' => '</span></h4>'));
    register_sidebar(array('name' => 'Home 7 - Second Column (Bottom)', 'id' => 'home-7', 'before_widget' => '<div id="%1$s" class="sb-widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title"><span>', 'after_title' => '</span></h4>'));
    register_sidebar(array('name' => 'Top Header Ad', 'id' => 'top_ad_sidebar', 'description' => '', 'before_widget' => '<div id="%1$s" class="clearfix %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title"><span>', 'after_title' => '</span></h4>'));
    register_sidebar(array('name' => 'Pop Ad', 'id' => 'pop_ad_sidebar', 'description' => '', 'before_widget' => '<div id="%1$s" class="clearfix pop_ad">', 'after_widget' => '</div>', 'before_title' => '', 'after_title' => ''));
    register_sidebar(array('name' => 'Anuncios antes del editorial', 'id' => 'home-8', 'before_widget' => '<div id="%1$s" class="w3-col">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title"><span>', 'after_title' => '</span></h4>'));
}
add_action('widgets_init', 'cyc_widgets_init', 99);

/***** Registrar Widgets adicionales *****/
function cyc_register_widgets()
{
    register_widget('cyc_posts_large');
    register_widget('cyc_featured_cat');
    register_widget('cyc_posts_grid');
}
add_action('widgets_init', 'cyc_register_widgets');

/***** Incluir Widgets adicionales *****/
require_once('includes/widgets/cyc-posts-large.php');
require_once('includes/widgets/cyc-featured-cat.php');
require_once('includes/widgets/cyc-posts-grid.php');

/***** Formato de las notas *****/
function cyc_add_post_formats()
{
    add_theme_support('post-formats', array('gallery', 'video'));
}
add_action('after_setup_theme', 'cyc_add_post_formats', 20);

/***** Metabox del video destacado *****/
function cyc_video_mb()
{
    add_meta_box(
        'featuredVideo_id',
        'Video destacado',
        'cyc_video_show_mb',
        'post',
        'normal',
        'core'
    );
}

function cyc_video_show_mb($post)
{
    $featuredVideoURL = get_post_meta($post->ID, '_featuredVideoURL', true);

    wp_nonce_field(plugin_basename(__FILE__), 'featuredVideo_nonce');
    ?><p>
        <label for=""><?php _e('Video URL', 'mh-magazine-lite') ?></label>
        <input class="widefat" type="text" name="featured_video_url" id="local_evento" value="<?php echo $featuredVideoURL ?>" />
    </p><?php
}

function cyc_video_save_postdata($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (!isset($_POST['featuredVideo_nonce'])) return;

    if (!wp_verify_nonce($_POST['featuredVideo_nonce'], plugin_basename(__FILE__))) return;

    update_post_meta($post_id, '_featuredVideoURL', htmlspecialchars(filter_input(INPUT_POST, 'featured_video_url')));
}
add_action('add_meta_boxes', 'cyc_video_mb');
add_action('save_post', 'cyc_video_save_postdata');

/***** Plugins requeridos *****/
function cyc_check_required_plugins()
{
    if (!is_plugin_active('zoninator/zoninator.php')) {
        add_action('admin_notices', 'cyc_alert_plugins_required');
    }
}
add_action('admin_init', 'cyc_check_required_plugins');

function cyc_alert_plugins_required()
{
?><div class="notice error is-dismissible">
        <p>El plugin <a href="https://wordpress.org/plugins/zoninator/" target="_blank">Zoninator</a> es recomendado para el correcto funcionamiento del tema</p>
    </div>
<?php
}

/***** Opciones extra del tema *****/
function cyc_customize_register($wp_customize)
{
    $wp_customize->add_section('mh_newsdesk_lite_socials', array('title' => esc_html__('Socials', 'mh-newsdesk-lite'), 'priority' => 2, 'panel' => 'mh_theme_options'));

    $wp_customize->add_setting('mh_newsdesk_lite_options[cabezal_home]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_newsdesk_lite_sanitize_text'));
    $wp_customize->add_setting('mh_newsdesk_lite_options[cabezal_grid]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_newsdesk_lite_sanitize_text'));
    $wp_customize->add_setting('mh_newsdesk_lite_options[cabezal_block]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_newsdesk_lite_sanitize_text'));
    $wp_customize->add_setting('mh_newsdesk_lite_options[cabezal_block_title]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_newsdesk_lite_sanitize_text'));
    $wp_customize->add_setting('mh_newsdesk_lite_options[analytics_code]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_newsdesk_lite_sanitize_text'));


    $wp_customize->add_setting('mh_newsdesk_lite_options[facebook_link]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_newsdesk_lite_sanitize_text'));
    $wp_customize->add_setting('mh_newsdesk_lite_options[twitter_link]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_newsdesk_lite_sanitize_text'));
    $wp_customize->add_setting('mh_newsdesk_lite_options[google_plus_link]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_newsdesk_lite_sanitize_text'));
    $wp_customize->add_setting('mh_newsdesk_lite_options[instagram_link]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_newsdesk_lite_sanitize_text'));
    $wp_customize->add_setting('mh_newsdesk_lite_options[youtube_link]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_newsdesk_lite_sanitize_text'));

    $wp_customize->add_control('cabezal_home', array('label' => esc_html__('Categoría / Zona en el cabezal', 'mh-newsdesk-lite'), 'section' => 'mh_newsdesk_lite_general', 'settings' => 'mh_newsdesk_lite_options[cabezal_home]', 'priority' => 4, 'type' => 'text'));
    $wp_customize->add_control('cabezal_grid', array('label' => esc_html__('Categoría / Zona de la grilla abajo del cabezal', 'mh-newsdesk-lite'), 'section' => 'mh_newsdesk_lite_general', 'settings' => 'mh_newsdesk_lite_options[cabezal_grid]', 'priority' => 5, 'type' => 'text'));
    $wp_customize->add_control('cabezal_block', array('label' => esc_html__('Categoría / Zona del block abajo del cabezal', 'mh-newsdesk-lite'), 'section' => 'mh_newsdesk_lite_general', 'settings' => 'mh_newsdesk_lite_options[cabezal_block]', 'priority' => 7, 'type' => 'text'));
    $wp_customize->add_control('cabezal_block_title', array('label' => esc_html__('Título del block abajo del cabezal', 'mh-newsdesk-lite'), 'section' => 'mh_newsdesk_lite_general', 'settings' => 'mh_newsdesk_lite_options[cabezal_block_title]', 'priority' => 8, 'type' => 'text'));
    $wp_customize->add_control('analytics_code', array('label' => esc_html__('Código de Google Analytics', 'mh-newsdesk-lite'), 'section' => 'mh_newsdesk_lite_general', 'settings' => 'mh_newsdesk_lite_options[analytics_code]', 'priority' => 9, 'type' => 'text'));

    $wp_customize->add_control('facebook_link', array('label' => esc_html__('Enlace de Facebook', 'mh-newsdesk-lite'), 'section' => 'mh_newsdesk_lite_socials', 'settings' => 'mh_newsdesk_lite_options[facebook_link]', 'priority' => 1, 'type' => 'text'));
    $wp_customize->add_control('twitter_link', array('label' => esc_html__('Enlace de Twitter', 'mh-newsdesk-lite'), 'section' => 'mh_newsdesk_lite_socials', 'settings' => 'mh_newsdesk_lite_options[twitter_link]', 'priority' => 2, 'type' => 'text'));
    $wp_customize->add_control('google_plus_link', array('label' => esc_html__('Enlace de Google+', 'mh-newsdesk-lite'), 'section' => 'mh_newsdesk_lite_socials', 'settings' => 'mh_newsdesk_lite_options[google_plus_link]', 'priority' => 3, 'type' => 'text'));
    $wp_customize->add_control('instagram_link', array('label' => esc_html__('Enlace de Instagram', 'mh-newsdesk-lite'), 'section' => 'mh_newsdesk_lite_socials', 'settings' => 'mh_newsdesk_lite_options[instagram_link]', 'priority' => 4, 'type' => 'text'));
    $wp_customize->add_control('youtube_link', array('label' => esc_html__('Enlace de Youtube', 'mh-newsdesk-lite'), 'section' => 'mh_newsdesk_lite_socials', 'settings' => 'mh_newsdesk_lite_options[youtube_link]', 'priority' => 5, 'type' => 'text'));
}
add_action('customize_register', 'cyc_customize_register');
