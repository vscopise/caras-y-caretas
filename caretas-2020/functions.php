<?php

/***** Estilos padre e hijo *****/
function cyc_enqueue_parent_styles()
{

    if (WP_DEBUG == true) {
        wp_enqueue_style(
            'parent-styles',
            get_template_directory_uri() . '/style.css'
        );
        wp_enqueue_style(
            'child-styles',
            get_stylesheet_directory_uri() . '/assets/styles/style.css',
            array('parent-styles')
        );
        if ( is_front_page() ) {
            wp_enqueue_style(
                'front-page-styles',
                get_stylesheet_directory_uri() . '/assets/styles/front-page-styles.css',
                array('parent-styles')
            );
        }
    } else {
        $random = mt_rand();
        wp_enqueue_style(
            'parent-styles-minified',
            get_stylesheet_directory_uri() . '/assets/styles/parent-styles.min.css',
            '',
            $random
        );
        wp_enqueue_style(
            'child-styles-minified',
            get_stylesheet_directory_uri() . '/assets/styles/styles.min.css',
            array('parent-styles-minified')
        );
        if ( is_front_page() ) {
            wp_enqueue_style(
                'front-page-styles-minified',
                get_stylesheet_directory_uri() . '/assets/styles/front-page-styles.min.css',
                array('parent-styles-minified')
            );
        }
    }

    //wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' . '.css' );
    //wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/css/styles.css', NULL, filemtime( get_stylesheet_directory() . '/css/styles.css' ) );
}
add_action('wp_enqueue_scripts', 'cyc_enqueue_parent_styles');

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
?>
    <div class="notice error is-dismissible">
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
