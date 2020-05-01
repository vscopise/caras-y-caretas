<?php
//Pagos con MercadoPago
function cyc_mercadopago_scripts() {
    if ( is_page_template( 'template-pagos-mercadopago.php' ) ) {
        $cyc_options = mh_newsdesk_lite_theme_options();
        $mp_public_key = $cyc_options['mp_public_key'];
        wp_enqueue_script( 
            'mercadopago_script', 
            get_stylesheet_directory_uri() . '/js/mercadopago-payment.js', 
            array( 'jquery' ),
            filemtime( get_stylesheet_directory() . '/js/mercadopago-payment.js' ),
            true
        );
        wp_localize_script(
            'mercadopago_script', 
            'MercadoaPgoObject', 
            array(
                'mp_public_key' => $mp_public_key,
            )
        );
        wp_enqueue_style(
            'mercadopago_styles', 
            get_stylesheet_directory_uri() . '/css/pagos-socios.css'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'cyc_mercadopago_scripts' );

function cyc_mercadopago_custom_data($wp_customize) {
    $wp_customize->add_section(
        'mh_newsdesk_lite_mercadopago', 
        array(
            'title' => 'Mercado Pago', 
            'priority' => 3, 
            'panel' => 'mh_theme_options',
            'description' => 'Opciones de la API de pagos de Mercado Pago'
        )
    );
    
    $wp_customize->add_setting(
        'mh_newsdesk_lite_options[mp_public_key]', 
        array(
            'default' => '', 
            'type' => 'option', 
            'sanitize_callback' => 'mh_newsdesk_lite_sanitize_text'
        )
    );
    $wp_customize->add_control(
        'mp_public_key', 
        array(
            'label' => 'Public Key', 
            'section' => 'mh_newsdesk_lite_mercadopago', 
            'settings' => 'mh_newsdesk_lite_options[mp_public_key]', 
            'type' => 'text'
        )
    );
    
    $wp_customize->add_setting(
        'mh_newsdesk_lite_options[mp_access_token]', 
        array(
            'default' => '', 
            'type' => 'option', 
            'sanitize_callback' => 'mh_newsdesk_lite_sanitize_text'
        )
    );
    $wp_customize->add_control(
        'mp_access_token', 
        array(
            'label' => 'Access Token', 
            'section' => 'mh_newsdesk_lite_mercadopago', 
            'settings' => 'mh_newsdesk_lite_options[mp_access_token]', 
            'type' => 'text'
        )
    );
}
add_action('customize_register', 'cyc_mercadopago_custom_data');

//Permitir comentarios anonimos
function cyc_rest_allow_anonymous_comments() {
    return true;
}
add_filter( 'rest_allow_anonymous_comments','cyc_rest_allow_anonymous_comments' );

//Banners en el contenido
function cyc_inline_content_ads( $content ) {
    ob_start();
    include ( get_stylesheet_directory() . '/templates/inline-ads.php' );
    $ad_code = ob_get_clean();
    if ( is_single() && ! is_admin() ) {
        return prefix_insert_after_paragraph( $ad_code, 2, $content );
    }
    return $content;
}
add_filter( 'the_content', 'cyc_inline_content_ads' );

function prefix_insert_after_paragraph( $insertion, $paragraph_id, $content ) {
    $closing_p = '</p>';
    $paragraphs = explode( $closing_p, $content );
    foreach ($paragraphs as $index => $paragraph) {
        if ( trim( $paragraph ) ) {
            $paragraphs[$index] .= $closing_p;
        }
        if ( $paragraph_id == $index + 1 ) {
            $paragraphs[$index] .= $insertion;
        }
    }
 
    return implode( '', $paragraphs );
}

//Google DFP Inline tags
function cyc_gdfp_inline_tags(){
    ?>
    <script async="async" src="https://www.googletagservices.com/tag/js/gpt.js"></script>
    <script>
        var googletag = googletag || {};
        googletag.cmd = googletag.cmd || [];
    </script>
    <?php
}
add_action( 'wp_head', 'cyc_gdfp_inline_tags' );

//Zonas de Widgets adicionales
function cyc_widgets_init() {
    register_sidebar(array('name' => 'Home 6 - First Column (Bottom)', 'id' => 'home-6', 'before_widget' => '<div id="%1$s" class="sb-widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title"><span>', 'after_title' => '</span></h4>'));
    register_sidebar(array('name' => 'Home 7 - Second Column (Bottom)', 'id' => 'home-7', 'before_widget' => '<div id="%1$s" class="sb-widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title"><span>', 'after_title' => '</span></h4>'));
    register_sidebar(array('name' => 'Top Header Ad', 'id' => 'top_ad_sidebar', 'description' => '', 'before_widget' => '<div id="%1$s" class="clearfix %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title"><span>', 'after_title' => '</span></h4>'));
    register_sidebar(array('name' => 'Pop Ad', 'id' => 'pop_ad_sidebar', 'description' => '', 'before_widget' => '<div id="%1$s" class="clearfix pop_ad">', 'after_widget' => '</div>', 'before_title' => '', 'after_title' => ''));
    register_sidebar(array('name' => 'Anuncios antes del editorial', 'id' => 'home-8', 'before_widget' => '<div id="%1$s" class="w3-col">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title"><span>', 'after_title' => '</span></h4>'));
}
add_action( 'widgets_init', 'cyc_widgets_init', 99 );

//Estilos padre e hijo
function cyc_enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/css/styles.css', NULL, filemtime( get_stylesheet_directory() . '/css/styles.css' ) );
}
add_action( 'wp_enqueue_scripts', 'cyc_enqueue_parent_styles' );

function cyc_enqueue_home_style() {
    if ( is_front_page() ) {
        wp_enqueue_style( 'home-style', get_stylesheet_directory_uri() . '/css/home-style.css' );
    }
}
add_action( 'wp_enqueue_scripts', 'cyc_enqueue_home_style' );

/***** Registrar Widgets *****/
function cyc_register_widgets() {
	register_widget( 'cyc_posts_large' );
	register_widget( 'cyc_posts_grid' );
	register_widget( 'cyc_tapa_revista' );
	register_widget( 'cyc_user_list' );
	register_widget( 'cyc_most_views' );
	register_widget( 'cyc_posts_slider' );
	register_widget( 'cyc_custom_posts' );
	register_widget( 'cyc_ajax_search' );
}
add_action( 'widgets_init', 'cyc_register_widgets' );

/***** Incluir Widgets *****/
require_once( 'widgets/cyc-posts-large.php' );
require_once( 'widgets/cyc-posts-grid.php' );
require_once( 'widgets/cyc-tapa-revista.php' );
require_once( 'widgets/cyc-users-list.php' );
require_once( 'widgets/cyc-most-views.php' );
require_once( 'widgets/cyc-posts-slider.php' );
require_once( 'widgets/cyc-custom-posts.php' );
require_once( 'widgets/cyc-ajax-search.php' );

/***** Zona de Widgets para Popups *****/
function cyc_footer() {
    if ( is_active_sidebar( 'pop_ad_sidebar' ) ) {
        dynamic_sidebar( 'pop_ad_sidebar' );
        //wp_enqueue_script( 'pop_ad_sidebar', get_stylesheet_directory_uri() . '/js/pop_ad_sidebar.js', array('jquery'), false ,true );
    }
    wp_enqueue_script( 'menu_zocalo', get_stylesheet_directory_uri() . '/js/menu_zocalo.js', array('jquery'), false ,true );
}
add_action( 'wp_footer', 'cyc_footer' );

/***** Plugins requeridos *****/
function cyc_check_required_plugins() {
    if ( ! is_plugin_active( 'zoninator/zoninator.php' ) ) {
        add_action( 'admin_notices', 'cyc_alert_plugins_required' );
    } 
}
add_action( 'admin_init', 'cyc_check_required_plugins' );

function cyc_alert_plugins_required() {
     ?>
    <div class="notice error is-dismissible" >
        <p>El plugin <a href="https://wordpress.org/plugins/zoninator/" target="_blank">Zoninator</a> es recomendado para el correcto funcionamiento del tema</p>
    </div>
    <?php
}


/***** Retorna frace, limitada por un punto *****/
function cyc_get_paragraph( $string ) {
        preg_match('/^(.*?)[.?!]\s/', $string . ' ', $result);
        return $result[0];
}

/***** Ticker e Iconos de redes *****/
function cyc_post_ticker() {
    wp_enqueue_script( 'ticker_redes', get_stylesheet_directory_uri() . '/js/ticker-redes.js', array('jquery'), false ,true );
}
add_action( 'wp_enqueue_scripts', 'cyc_post_ticker' );

/***** xxxx *****/
/*function cyc_widget_slider() {
    wp_enqueue_script( 'ticker_redes', get_stylesheet_directory_uri() . '/js/ticker-redes.js', array('jquery'), false ,true );
}
add_action( 'wp_enqueue_scripts', 'cyc_widget_slider' );*/

/***** Opciones extra del tema *****/
function cyc_customize_register($wp_customize) {
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

/***** Cotizaciones - Clima *****/
function cyc_clima() {
    wp_enqueue_script( 'clima', get_stylesheet_directory_uri() . '/js/clima.js', array('jquery'), false ,true );
    wp_localize_script( 'clima', 'clima_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
//add_action( 'wp_enqueue_scripts', 'cyc_clima' );

/***** Slider *****/
function cyc_slider() {
    wp_enqueue_script( 'slider', get_stylesheet_directory_uri() . '/js/slider.js', array('jquery'), '201809264' ,true );
    wp_enqueue_script( 'owl_carousel_script', get_stylesheet_directory_uri() . '/js/owl.carousel.min.js', array('jquery', 'slider'), false ,true );
    wp_enqueue_style( 'owl_carousel_style', get_stylesheet_directory_uri() . '/css/owl.carousel.css' );
    wp_enqueue_style( 'owl_carousel_theme_style', get_stylesheet_directory_uri() . '/css/owl.theme.css' );
}
add_action( 'wp_enqueue_scripts', 'cyc_slider' );

/***** SlickNav Responsive Mobile Menu *****/
function cyc_slicknav() {
    wp_enqueue_script( 'slick_nav', get_stylesheet_directory_uri() . '/js/slick-nav.js', array('jquery'), false ,true );
}
add_action( 'wp_enqueue_scripts', 'cyc_slicknav' );

function cyc_clima_cotiz_crons() {
    //crea un cronjob para obtener datos del clima
    if ( ! wp_next_scheduled( 'cyc_get_wheather_data_hourly' ) ) {
            wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'cyc_get_wheather_data_hourly');
            //wp_schedule_event( time(), 'hourly', 'cyc_get_wheather_data_hourly');
    }
    //
    //cyc_get_wheather_data();
    //
    
    //crea un cronjob para obtener datos de las cotizaciones
    /*if ( ! wp_next_scheduled( 'mh_newsdesk_lite_get_currency_data_hourly' ) ) {
            wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'cyc_get_currency_data_hourly');
    }*/
}
//add_action( 'admin_menu', 'cyc_clima_cotiz_crons' );
//add_action( 'after_switch_theme', 'cyc_clima_cotiz_crons' );

function cyc_get_wheather_data() {
    $date = date('j/n/Y - G:i:s');
    update_option('date_weather_cron', $date);
    
    $wug_api_key = "e3b66b7074010837";
    $wug_url  = "http://api.wunderground.com/api/" . $wug_api_key;
    $wug_url .= "/astronomy/conditions/forecast/lang:SP/q/Uruguay/SUMU.json";
    $url = $wug_url;
    $response = wp_remote_get( $url );
    
    $body = wp_remote_retrieve_body($response);
    $weather_data = json_decode( wp_remote_retrieve_body( $response ), true );
    update_option( 'weather_data', $weather_data );
    
    /*if ( $json_string = file_get_contents($url) ) {
        $weather_data = json_decode( $json_string, true );
        update_option( 'weather_data', $weather_data );
    }*/
}
//add_action( 'cyc_get_wheather_data_hourly', 'cyc_get_wheather_data' );

function cyc_get_wheather_local_data() {
    $weather_data = get_option("weather_data");
    if ( $weather_data != '' ) {
            //$weather_icon = str_replace('i/c/k', 'i/c/i', $weather_data['current_observation']['icon_url']);
            //$weather_icon = $weather_data['current_observation']['icon_url'];
            $weather_icon = str_replace(
                    array( 'i/c/k', 'http:' ),
                    array( 'i/c/i', 'https:' ),
                    $weather_data['current_observation']['icon_url']
            );
            $weather_cond = $weather_data['current_observation']['weather'];
            $weather_temp = (int) $weather_data['current_observation']['temp_c'];
            $weather_temp_max = $weather_data['forecast']['simpleforecast']['forecastday'][0][high][celsius];
            $weather_temp_min = $weather_data['forecast']['simpleforecast']['forecastday'][0][low][celsius];
            $response = array(
                'weather_icon' => $weather_icon,
                'weather_cond' => $weather_cond,
                'weather_temp' => $weather_temp,
                'weather_temp_max' => $weather_temp_max,
                'weather_temp_min' => $weather_temp_min,
            );
            echo json_encode($response);
        }
        die();
}
//add_action( 'wp_ajax_nopriv_cyc_get_wheather_local_data', 'cyc_get_wheather_local_data');
//add_action( 'wp_ajax_cyc_get_wheather_local_data', 'cyc_get_wheather_local_data');

/***** Mega-Menu *****/
class cyc_mega_walker extends Walker_Nav_Menu {
    var $db_fields = array(
        'parent' => 'menu_item_parent', 
        'id'     => 'db_id' 
    );
    public function start_lvl( &$output,  $depth = 0, $args = array() ) {
        $output .= '<ul class="sub-menu">';
        
    }

    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= '</ul>';
    }

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $output .= sprintf( '<li class="menu-item"><a href="%s"%s>%s</a>',
            $item->url,
            ( $item->object_id === get_the_ID() ) ? ' class="current"' : '',
            $item->title
        );
        //$output .= "<li>".esc_attr($item->label);
        if ( $depth == 0 && $item->object == 'category' && $item->title !== 'Caras y Caretas TV' )  {
            $megacat_full = '';
            global $post;
            $post_args = array( 'posts_per_page' => 4, 'offset'=> 0, 'cat' => $item->object_id );
            $menuposts = get_posts( $post_args );
            $output .= '<div class="megamenu">';
            $output .= '<div class="row clearfix">';
            foreach( $menuposts as $post ) : setup_postdata( $post );
                $output .= '<article class="mh-col mh-1-4">';
                        $output .= '<div class="content-grid-thumb">';
                                $output .= '<a href="' . get_permalink() .'" title="' . get_the_title() . '">';
                                if ( has_post_thumbnail() ) :
                                    $output .= get_the_post_thumbnail( $post->ID, 'content-list', array( 'class' => 'no-lazy' )  );
                                endif;
                                $output .= '</a>';
                        $output .= '</div>';
                        $output .= '<h3 class="content-grid-title">';
                        $output .= '<a href="' . get_permalink() .'" title="' . get_the_title() . '">';
                        $output .= get_the_title();
                        $output .= '</a>';
                        $output .= '</h3>';
                $output .= '</article>';
            endforeach;
            wp_reset_postdata();
            $output .= '</div>';
            $output .= '</div>';
        }
    }
    
    public function end_el( &$output, $object, $depth = 0, $args = array() ) {
        $output .= "</li>";
    }

}

/***** Opciones de los artículos *****/

/***** Colgado *****/
function add_colgado_meta_box() {
    add_meta_box(
        'colgado_meta_box',
        esc_html__( 'Colgado', 'mh-newsdesk-lite' ),
        'show_colgado_meta_box',
        'post',
        'normal'
    );
}
add_action( 'add_meta_boxes', 'add_colgado_meta_box' );

function show_colgado_meta_box() {
    global $post;
    $colgado = get_post_meta( $post->ID, 'COLGADO', true );
    wp_nonce_field( basename( __FILE__ ), 'colgado_nonce' );
    ?>
    <p>
        <input type="text" name="colgado" id="colgado" value="<?php echo $colgado ?>" style="width: 100%;" />
    </p>
    <?php
}

function save_colgado( $post_id ) {
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'colgado_nonce' ] ) && wp_verify_nonce( $_POST[ 'colgado_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
    if( isset( $_POST[ 'colgado' ] ) ) {
        update_post_meta( $post_id, 'COLGADO', sanitize_text_field( $_POST[ 'colgado' ] ) );
    }
}
add_action( 'save_post', 'save_colgado' );

//Formato de las notas
function cyc_add_post_formats() {
    add_theme_support( 'post-formats', array( 'gallery', 'video' ) );
}
add_action( 'after_setup_theme', 'cyc_add_post_formats', 20 );

function cyc_video_mb() {
    add_meta_box(
        'featuredVideo_id', 
        'Video destacado',
        'cyc_video_show_mb',
        'post',
        'normal',
        'core'
    );
}

function cyc_video_show_mb( $post ) {
    $featuredVideoURL = get_post_meta( $post->ID, '_featuredVideoURL', true );
    
    wp_nonce_field( plugin_basename( __FILE__ ), 'featuredVideo_nonce' );
    ?>
        <p>
            <label for=""><?php _e( 'Video URL', 'mh-magazine-lite' )?></label>
            <input class="widefat" type="text" name="featured_video_url" id="local_evento" value="<?php echo $featuredVideoURL ?>" />
        </p>
    <?php
}

function cyc_video_save_postdata( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
 
    if ( !wp_verify_nonce( $_POST['featuredVideo_nonce'], plugin_basename( __FILE__ ) ) ) return;
 
    update_post_meta( $post_id, '_featuredVideoURL', htmlspecialchars( filter_input(INPUT_POST, 'featured_video_url') ) );
}
add_action( 'add_meta_boxes', 'cyc_video_mb' );
add_action( 'save_post', 'cyc_video_save_postdata' );

/***** Quita la galería del contenido del post *****/
function strip_shortcode_gallery( $content ) {
    preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );

    if ( ! empty( $matches ) ) {
        foreach ( $matches as $shortcode ) {
            if ( 'gallery' === $shortcode[2] ) {
                $pos = strpos( $content, $shortcode[0] );
                if( false !== $pos ) {
                    return substr_replace( $content, '', $pos, strlen( $shortcode[0] ) );
                }
            }
        }
    }

    return $content;
}
function cyc_get_attachment_id_from_url( $attachment_url = '' ) {
    global $wpdb;
    $attachment_id = false;

    // If there is no url, return.
    if ( '' == $attachment_url )
            return;

    // Get the upload directory paths
    $upload_dir_paths = wp_upload_dir();

    // Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
    if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

            // If this is the URL of an auto-generated thumbnail, get the URL of the original image
            $attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

            // Remove the upload path base directory from the attachment URL
            $attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

            // Finally, run a custom database query to get the attachment ID from the modified attachment URL
            $attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );

    }

    return $attachment_id;
}

/***** Funciones de las Revistas impresas y las Suscripciones *****/
//Agrega los suscriptores digitales como copia de los suscriptores
function cyc_subscriptor_setup() {
    $subscriber = get_role('subscriber');
    add_role( 'subscriber_d', 'Suscriptor Digital', $subscriber->capabilities );
}
add_action( 'after_setup_theme', 'cyc_subscriptor_setup' );

function cyc_hide_admin_bar() {
    if ( !current_user_can('edit_posts') ) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'cyc_hide_admin_bar');

//creación de los productos
function cyc_create_productos() {
    register_taxonomy(
        'productos',
        'revistas',
        array(
            'labels' => array(
                'name' => 'Productos',
                'add_new_item' => 'Agregar Producto',
                'new_item_name' => "Nuevo Producto"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}
add_action( 'init', 'cyc_create_productos' );

// definicion de las 'Revistas'
function cyc_revistas_init() {
    $labels = array(
        'name' => 'Revistas',
        'singular_name' => 'Revista',
        'add_new' => 'Añadir nueva',
        'add_new_item' => 'Añadir nueva Revista',
        'edit_item' => 'Editar Revista',
        'new_item' => 'Nueva Revista',
        'all_items' => 'Todas las Revistas',
        'view_item' => 'Ver Revista',
        'search_items' => 'Buscar Revista',
        'not_found' =>  'No se han encontrado Revistas',
        'not_found_in_trash' => 'No se han encontrado Revistas en la papelera',
    );
    $args = array( 
        'labels' => $labels,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-media-document',
        'has_archive' => true,
        'taxonomies' => array('productos'),
        'public' => true,
        'exclude_from_search' => true,
        'capabilities' => array(
            'edit_post' => 'update_core',
            'read_post' => 'update_core',
            'delete_post' => 'update_core',
            'edit_posts' => 'update_core',
            'edit_others_posts' => 'update_core',
            'publish_posts' => 'update_core',
            'read_private_posts' => 'update_core',
        ),
        'show_in_rest' => true,
        'hierarchical' => false,
        'supports' => array( 'title', 'thumbnail' ),
    );
    register_post_type( 'revistas', $args );
}
add_action( 'init', 'cyc_revistas_init' );

//Campo personalizado 'Issuu'
function cyc_issuu_meta_box(){
    add_meta_box(
        'issuu_meta_box',
        'Issuu',
        'show_issuu_meta_box',
        'revistas',
        'normal',
        'low'
    );
}
add_action( 'add_meta_boxes', 'cyc_issuu_meta_box' );

function show_issuu_meta_box( $post ){
    $issuu = get_post_meta( $post->ID, '_issuu', true );
    wp_nonce_field( basename( __FILE__ ), 'issuu_nonce' );
    ?>
    <p>
        <input class="widefat" type="text" name="issuu" id="issuu" value="<?php echo $issuu ?>" />
    </p>
    <?php
}

function cyc_save_issuu_field( $post_id ){
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'issuu_nonce' ] ) && wp_verify_nonce( $_POST[ 'issuu_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
    if( isset( $_POST[ 'issuu' ] ) ) {
        update_post_meta( $post_id, '_issuu', esc_html(filter_input(INPUT_POST, 'issuu') ) );
    }
}
add_action( 'save_post', 'cyc_save_issuu_field' );

//Campo personalizado 'dFlip'
function cyc_dflip_meta_box(){
    add_meta_box(
        'dflip_meta_box',
        'dFlip',
        'show_dflip_meta_box',
        'revistas',
        'normal',
        'low'
    );
}
add_action( 'add_meta_boxes', 'cyc_dflip_meta_box' );

function show_dflip_meta_box( $post ){
    $dflip = get_post_meta( $post->ID, '_dflip', true );
    wp_nonce_field( basename( __FILE__ ), 'dflip_nonce' );
    ?>
    <p>
        <input class="widefat" type="text" name="dflip" id="dflip" value="<?php echo $dflip ?>" />
    </p>
    <?php
}

function cyc_save_dflip_field( $post_id ){
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'dflip_nonce' ] ) && wp_verify_nonce( $_POST[ 'dflip_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
    if( isset( $_POST[ 'dflip' ] ) ) {
        update_post_meta( $post_id, '_dflip', esc_html(filter_input(INPUT_POST, 'dflip') ) );
    }
}
add_action( 'save_post', 'cyc_save_dflip_field' );


//Funciones para las suscripciones
function show_subscriptions_fields( $user ) { 
    if ( !current_user_can( 'edit_posts', $user->ID ) ) { return false; }
    $max_reading = ( get_the_author_meta( 'max_reading', $user->ID ) == '' ) ? '5' : get_the_author_meta( 'max_reading', $user->ID );
    $last_read_meta = esc_attr( get_the_author_meta( 'last_read', $user->ID ) );
    $last_read = date( 'd/m/Y', $last_read_meta );
    $reading = esc_attr( get_the_author_meta( 'reading', $user->ID ) );
    $subscription1_meta = ( get_the_author_meta( 'subscription1', $user->ID ) == '' ) ? '1/1/2015' : get_the_author_meta( 'subscription1', $user->ID );
    $subscription1 = date( 'd/m/Y', $subscription1_meta );
    $subscription2_meta = ( get_the_author_meta( 'subscription2', $user->ID ) == '' ) ? '1/1/2015' : get_the_author_meta( 'subscription2', $user->ID );
    $subscription2 = date( 'd/m/Y', $subscription2_meta );
    ?>
        <h3>Informaci&oacute;n de suscripciones</h3>
        <table class="form-table">
            <tr>
                <th><label for="subscriptions">Inicio / fin de las suscripciones <br />(dd / mm / aaaa)</label></th>
                <td>
                    <input type="text" name="subscription1" id="subscription1" value="<?php echo $subscription1; ?>" class="regular-text" />
                    <span class="description">Fecha del inicio.</span><br />
                    <input type="text" name="subscription2" id="subscription2" value="<?php echo $subscription2; ?>" class="regular-text" />
                    <span class="description">Fecha del fin.</span>
                </td>
            </tr>
            <tr>
                <th><label for="max_reading">Número máximo de lecturas</label></th>
                <td><input type="text" name="max_reading" value="<?php echo $max_reading; ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="last_read">Fecha del último accesso</label></th>
                <td><input type="text" name="last_read" readonly="true" value="<?php echo $last_read; ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="last_read">Número de lecturas en el día</label></th>
                <td><input type="text" name="last_read" readonly="true" value="<?php echo $reading; ?>" class="regular-text" /></td>
            </tr>
        </table>
<?php 
}
add_action( 'show_user_profile', 'show_subscriptions_fields' );
add_action( 'edit_user_profile', 'show_subscriptions_fields' );

function save_subscriptions_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    $subscription1  = strtotime( str_replace('/', '-', filter_input(INPUT_POST, 'subscription1')) );
    $subscription2  = strtotime( str_replace('/', '-', filter_input(INPUT_POST, 'subscription2')) );
    $max_reading = filter_input(INPUT_POST, 'max_reading');
    if ( !$subscription2 || !$subscription2 ) 
        return false;
    if ( $subscription2 < $subscription1 )
        return false;
    if ( !is_numeric($max_reading) ) 
        return false;
    update_usermeta( $user_id, 'subscription1', $subscription1 );
    update_usermeta( $user_id, 'subscription2', $subscription2 );
    update_usermeta( $user_id, 'max_reading', $max_reading );
}
add_action( 'personal_options_update', 'save_subscriptions_fields' );
add_action( 'edit_user_profile_update', 'save_subscriptions_fields' );

//determina si la suscripción del usuario (si existe) abarca la fecha dada
function has_the_subscription($user_id, $date){
    $subscription1 = get_user_meta( $user_id, 'subscription1', true );
    $subscription2 = get_user_meta( $user_id, 'subscription2', true );
    if ( $subscription1 && $subscription2 ) {
        //$the_date = strtotime( str_replace('/', '-', $date) );
        if ( ($subscription1 < $date) && ($date < $subscription2) ) {
        //if ( ($subscription1 < $the_date) && ($the_date < $subscription2) ) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//parametro usado para la página de edición impresa
function add_custom_query_var( $vars ){
    $new_vars = array( 'action', 'user_name', 'user', 'user_key', 'user_mail', 'ed', 'pag', 'page' );
    $vars = array_merge( $vars, $new_vars );
    return $vars;
}
add_filter( 'query_vars', 'add_custom_query_var' );

/***** Definición de los columnistas *****/

function cyc_create_columnistas() {
    global $wp_roles;
    if (!isset($wp_roles))
        $wp_roles = new WP_Roles();
    $auth = $wp_roles->get_role('author');
    $wp_roles->add_role('Columnista', 'Columnista', $auth->capabilities);
}
add_action('admin_init', 'cyc_create_columnistas');

// function to count views.
function cyc_enqueue_post_views(){
        if ( is_single() ) {
                wp_enqueue_script( 'load_post_views_script', get_stylesheet_directory_uri() . '/js/load_post_views.js', array( 'jquery' ) );
                wp_localize_script( 'load_post_views_script', 'ajax_post_views_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'post_id' => get_the_ID() ) );
        }
}
add_action( 'wp_enqueue_scripts', 'cyc_enqueue_post_views' );

function set_post_views() {
        $post_id = filter_input(INPUT_POST, 'post_id') ;
        $count_key = 'post_views_count';
        
        $count = get_post_meta( $post_id, $count_key, true );
        if ( $count == '' ) {
                $count = 0;
                delete_post_meta( $post_id, $count_key );
                add_post_meta( $post_id, $count_key, '0' );
        } else {
                $count++;
                update_post_meta( $post_id, $count_key, $count );
        }
        die();
}
add_action( 'wp_ajax_nopriv_set_post_views', 'set_post_views');
add_action( 'wp_ajax_set_post_views', 'set_post_views');

// Add it to a column in WP-Admin
if (!function_exists('posts_column_views')) {
	function posts_column_views($defaults){
		$defaults['post_views'] = 'Número de Visitas';
		return $defaults;
	}
}
function cyc_posts_custom_column_views( $column_name ){
        if($column_name === 'post_views'){
                echo cyc_getPostViews(get_the_ID());
        }
}
add_filter('manage_posts_columns', 'posts_column_views');
add_action('manage_posts_custom_column', 'cyc_posts_custom_column_views',5);

// function to display number of posts.
function cyc_getPostViews( $postID ){
        $count_key = 'post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        if($count==''){
                delete_post_meta($postID, $count_key);
                add_post_meta($postID, $count_key, '0');
                return '0  Visitas';
        }
        return number_format_i18n($count).' Visitas';
}

//Agregar div de compartir en footer
function cyc_compartir_en_footer() {
    if ( !is_single() ) return;
    
    wp_enqueue_style( 'fixed_bar_styles', get_stylesheet_directory_uri() . '/css/fixed.bar.css' );
    wp_enqueue_script( 'fixed_bar_script', get_stylesheet_directory_uri() . '/js/fixed.bar.js', array('jquery'), false ,true );
    
    $url = urlencode( esc_url( get_permalink( get_the_ID() ) ) );
    $title = urlencode( esc_attr( get_the_title( get_the_ID() ) ) );
    $mail_title = rawurlencode( 'Caras&Caretas - ' . get_the_title( get_the_ID() ) );

    $fb_link = 'http://www.facebook.com/share.php?u=' . $url;
    $tw_link = 'http://twitter.com/home?status=' . $title . '+' . $url;
    $wh_link = 'whatsapp://send?text=' . $title . ' – ' . $url . '" data-action="share/whatsapp/share';
    $gp_link = 'https://plus.google.com/share?url=' . $url;
    $gp_link = 'https://plus.google.com/share?url=' . $url;
    $ml_link = 'mailto:?subject=' . $mail_title . '&body=' . $url;
    ?>
        <div class="fixedBar">
            <div class="boxfloat">
                    <span>Compartir:</span>
                    <ul id="tips">
                        <li class="fb"><a href="<?php echo $fb_link ?>" title="Compartir en Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li class="tw"><a href="<?php echo $tw_link ?>" title="Compartir en Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li class="wh"><a href="<?php echo $wh_link ?>" title="Compartir en WhatsApp"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></li>
                        <li class="gp"><a href="<?php echo $gp_link ?>" title="Compartir en Google+"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <li class="pl"><a onclick="window.print()" title="Imprimir"><i class="fa fa-print" aria-hidden="true"></i></a></li>
                        <li class="ml"><a href="<?php echo $ml_link ?>" title="Compartir por mail"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></li>
                        <li class="top"><a href="#"><i class="fa fa-chevron-up" aria-hidden="true"></i></a></li>
                    </ul>
            </div>
        </div>
    <?php
}
add_action( 'wp_footer', 'cyc_compartir_en_footer' );

//Zocalo Suscripciones
function cyc_zocalo_suscripciones( ){
    if ( !is_single() ) return;
    
    wp_enqueue_style( 'zocalo_suscripciones_styles', get_stylesheet_directory_uri() . '/css/zocalo.suscripciones.css' );
    wp_enqueue_script( 'zocalo_suscripciones_script', get_stylesheet_directory_uri() . '/js/zocalo.suscripciones.js', array('jquery'), false ,true );
    $link_suscripciones = get_page_link( get_page_by_path( 'suscribirse' ) );
    ?>
        <div class="zocalo_suscripciones">
            <span class="close"><i class="fa fa-times" aria-hidden="true"></i></span>
            <a href="<?php echo $link_suscripciones; ?>">
                <div>
                    <h3>Suscribíte ahora y ponéle el hombro a la verdad.</h3>
                    <h3>Tratá de mirar en donde habitualmente no se ve.</h3>
                    <p>Si llegaste hasta aquí es porque no te alcanza con leer los titulares y buscás tener la
    otra cara de la noticia.</p>
                    <p>Necesitamos tu aporte para seguir luchando por el derecho a estar informado</p>
                    <p><em>Caras y Caretas</em> viene construyendo ese camino desde hace 17 años y tu apoyo es vital
    para sostenerlo y seguir creciendo.</p>
                    <p>Si vós necesitás a Caras y Caretas, Caras y Careta te necesita a vós</p>
                    <p>Completa el formulario y elegí entre las diferentes opciones de suscripción.</p>
                </div>
            </a>
        </div>
    <?php
}
//add_action( 'wp_footer', 'cyc_zocalo_suscripciones' );

function mh_newsdesk_lite_postnav() {
    
}

// Load translation files from your child theme instead of the parent theme
add_action( 'after_setup_theme', 'cyc_theme_locale' );
function cyc_theme_locale() {
    
        load_child_theme_textdomain( 'mh-newsdesk-lite', get_stylesheet_directory() . '/languages' );
}

function mh_newsdesk_lite_post_meta() {
        echo '<div class="entry-meta">' . "\n";

                if (has_category() && !is_single()) {
                        echo '<span class="entry-meta-cats">' . get_the_category_list(', ', '') . '</span>' . "\n";
                }
                if (is_single()) {
                    $opciones_entrada = (array) get_post_meta(get_the_ID(), '_opciones_entrada', true );
                    if ( $opciones_entrada['mostrar_autor'] == '1' ) :
                    ?>
                        <span class="entry-meta-author vcard author">
                            Por <a class="fn" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))) ?>"><?php the_author_meta( 'display_name' ); ?></a>
                        </span>
                    <?php
                    endif;
                    if ( $opciones_entrada['mostrar_comentarios'] == '1' ) :
                    ?>
                        <span class="entry-meta-comentarios updated"><?php comments_number( 'Sin comentarios', '1 comentarios', '% comentarios' ); ?></span>
                    <?php
                    endif;
                }
                echo '<span class="entry-meta-date updated">' . get_the_date() . '</span>' . "\n";
                
                if (is_single()) {
                    //if ( $opciones_entrada['compartir_en_redes'] == '1' ) {
                        if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) { 
                            ADDTOANY_SHARE_SAVE_KIT();
                        }
                    //}
                    
                }
        echo '</div>' . "\n";
}

/***** Opciones de la entrada *****/
function cyc_add_opciones_entrada_meta_box() 
{
    add_meta_box (
            'opciones_entrada_meta_box',
            'Opciones de la Entrada',
            'cyc_show_opciones_entrada_meta_box',
            'post',
            'side'
    );
}
add_action( 'add_meta_boxes', 'cyc_add_opciones_entrada_meta_box' );

function cyc_show_opciones_entrada_meta_box() 
{
    global $post;
    $opciones_entrada = (array) get_post_meta( $post->ID, '_opciones_entrada', true );
    
    $mostrar_autor = $opciones_entrada['mostrar_autor'];
    $mostrar_comentarios = $opciones_entrada['mostrar_comentarios'];
    $compartir_en_redes = $opciones_entrada['compartir_en_redes'] == '' ? '1' : $opciones_entrada['compartir_en_redes'];
    
    wp_nonce_field( basename( __FILE__ ), 'opciones_entrada_nonce' );
    ?>
    <p>
        <label for="mostrar_autor">
            <input type="checkbox" name="mostrar_autor" value="1" <?php if ( isset ( $mostrar_autor ) ) checked( $mostrar_autor, '1' ); ?>/>
            Mostrar Autor
        </label>
    </p>
    <p>
        <label for="mostrar_comentarios">
            <input type="checkbox" name="mostrar_comentarios" value="1" <?php if ( isset ( $mostrar_comentarios ) ) checked( $mostrar_comentarios, '1' ); ?>/>
            Mostrar Comentarios
        </label>
    </p>    
    <p>
        <label for="compartir_en_redes">
            <input type="checkbox" name="compartir_en_redes" value="1" <?php if ( isset ( $compartir_en_redes ) ) checked( $compartir_en_redes, '1' ); ?>/>
            Compartir en redes
        </label>
    </p>    
    <?php
}

function cyc_save_opciones_entrada ( $post_id ) 
{
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'opciones_entrada_nonce' ] ) && wp_verify_nonce( $_POST[ 'opciones_entrada_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
    if( isset( $_POST[ 'mostrar_autor' ] ) ) {
        $opciones_entrada['mostrar_autor'] = '1';
    } else {
        $opciones_entrada['mostrar_autor'] = '0';
    }
    if( isset( $_POST[ 'mostrar_comentarios' ] ) ) {
        $opciones_entrada['mostrar_comentarios'] = '1';
    } else {
        $opciones_entrada['mostrar_comentarios'] = '0';
    }
    $opciones_entrada['compartir_en_redes'] = $_POST[ 'compartir_en_redes' ] == null ? '1' : $_POST[ 'compartir_en_redes' ];
    
    update_post_meta( $post_id, '_opciones_entrada', $opciones_entrada );
    
}
add_action( 'save_post', 'cyc_save_opciones_entrada' );

/***** Google Analytics *****/

function cyc_add_googleanalytics() { 
    $cyc_options = mh_newsdesk_lite_theme_options();
    $google_analytics_code = $cyc_options['analytics_code'];
    if ( '' != $google_analytics_code ) :
?>
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '<?php echo $google_analytics_code ?>']);
        _gaq.push(['_trackPageview']);
        (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>
<?php
    endif;
    }
add_action('wp_footer', 'cyc_add_googleanalytics');

/***** Ajax Login Form *****/
function cyc_enqueue_login_script() {
    add_thickbox();
    
    wp_enqueue_script( 'login_script', get_stylesheet_directory_uri() . '/js/login-script.js', array( 'jquery' ) );
    wp_localize_script( 'login_script', 'cyc_login_script_vars', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'redirecturl' => get_page_link( get_page_by_path( 'edicion-impresa' )->ID ),
            'is_logged_in' => is_user_logged_in(),
            'login_url' => '#TB_inline?height=370&amp;width=350&amp;inlineId=cyc-login-popup',
            'logout_url' => wp_logout_url( home_url() )
        )
    );
}
add_action( 'wp_enqueue_scripts', 'cyc_enqueue_login_script' );

function login_user() {
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;
    $user_signon = wp_signon( $info, false );
    if ( is_wp_error($user_signon) ){
        echo json_encode(array( 'loggedin'=>false, 'mensaje' => 'Usuario o clave incorrecta.' ));
    } else {
        echo json_encode(array( 'result'=>1, 'mensaje' => 'Ingreso correcto, redirigiendo...' ));
    }
    die();
}
add_action( 'wp_ajax_nopriv_login_user', 'login_user');
add_action( 'wp_ajax_login_user', 'login_user');


function cyc_mail_new_user( $parms ) {
    $logo_url = $parms['logo_url'];
    $home_url = $parms['home_url'];
    $user_name = $parms['user_name'];
    $user_key = $parms['user_key'];
    $user_mail = $parms['user_mail'];
    $terminos_y_condiciones = $parms['terminos_y_condiciones'];
    
        
    //$_POST['parms'] = $parms;
    //include( get_stylesheet_directory_uri().'/mail-templates/new_user.php?logo_url=' . $logo_url . '&home_url=' . $home_url . '&user_name=' . $user_name . '&user_key=' . $user_key . '&user_mail=' . $user_mail . '&terminos_y_condiciones=' . $terminos_y_condiciones );
    //require_once ( get_stylesheet_directory_uri().'/mail-templates/new_user.php' );
    ob_start();
    ?>
        <html>
            <body>
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                    <tr>
                        <td style="text-align: center; ">
                            <img src="<?php echo $logo_url ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; margin-bottom: 30px; ">
                            <h3>
                                Gracias por registrarse.
                            </h3>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; ">
                            <p style="text-align: center; margin-bottom: 30px;">
                                Su registro está prácticamente completo.<br /><br />
                                Haga click en el siguiente enlace para confirmar su suscripción.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">
                            <a href="<?php echo $home_url ?>?action=confirmar_usuario&user_name=<?php echo $user_name ?>&user_key=<?php echo $user_key ?>&user_mail=<?php echo $user_mail ?>" 
                                style="width:60%; padding: 13px 100px; text-decoration: none; border-radius: 4px; color: #fff; background: #e96656; display: inline-block;">ACTIVAR REGISTRO</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="text-align: center; font-size: 0.7em;">
                                Al confirmar el registro Ud. manifiesta que está de acuerdo con nuestros <a href="<?php //echo $terminos_y_condiciones ?>">términos y condiciones</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </body>
        </html>
    <?php 
    $content = ob_get_clean();

    return $content;
}

function cyc_mail_confirm_user( $parms ) {
    $logo_url = $parms['logo_url'];
    $user_name = $parms['user_name'];
    $user_pass = $parms['user_pass'];
    
    ob_start();
    ?>
        <html>
            <body>
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                    <tr>
                        <td style="text-align: center; ">
                            <img src="<?php echo $logo_url ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; ">
                            <h2 style="text-align: center">Felicitaciones <?php echo $user_name ?></h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="text-align: center">
                                Ud. ya está registrado. Puede ingresar al sitio con su nombre de usuario.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4 style="text-align: center">La contraseña asignada es:</h4>
                            <h4 style="text-align: center"><?php echo $user_pass ?></h4>
                            <p style="text-align: center">

                            </p>
                        </td>
                    </tr>
                </table>
            </body>
        </html>
    <?php
    $content = ob_get_clean();

    return $content;
}

function cyc_confirmar_usuario () {
    global $wp_query;
    $user_name = $wp_query->query['user_name'];
    $user_mail = $wp_query->query['user_mail'];
    $user_key = $wp_query->query['user_key'];
    $action = $wp_query->query['action'];
    
    //$user_name = get_query_var('user_name');
    //$user_mail = get_query_var('user_mail');
    //$user_key = get_query_var('user_key');
    //$action = get_query_var('action');
    
    if( !isset( $action ) ) { return; }
    //if( !isset( $action ) ) { return $template; }

    if( $action=='confirmar_usuario' && isset( $user_key ) && isset( $user_name ) && isset( $user_mail ) ){
    //if( $wp_query->query['action']=='confirmar_usuario' && isset( $user_key ) && isset( $user_name ) && isset( $user_mail ) ){
        
        $usuarios_temporales = get_option( 'usuarios_temporales' );
        if ( $usuarios_temporales  ) {
            $user_found = array_search( $user_name, array_column($usuarios_temporales, 'user_name'));
            if ( is_int( $user_found ) &&
                $usuarios_temporales[$user_found][user_name] == $user_name &&
                $usuarios_temporales[$user_found][user_mail] == $user_mail && 
                $usuarios_temporales[$user_found][user_key] == $user_key  
            ) {
                unset( $usuarios_temporales[ $user_found ] );
                update_option( 'usuarios_temporales', $usuarios_temporales);

                $user_pass = wp_generate_password(20, false);
                /*$userdata = array(
                    'user_email'    =>  $user_mail,
                    'user_login'    =>  $user_name,
                    'user_pass'     =>  $user_pass,
                    'role'          =>  'subscriber'
                );*/
                $user_id = wp_insert_user( array(
                    'user_email'    =>  $user_mail,
                    'user_login'    =>  $user_name,
                    'user_pass'     =>  $user_pass,
                    'role'          =>  'subscriber'
                ));

                //enviar mail
                $logo_url = get_header_image();
                $parms = array(
                    'logo_url' => $logo_url,
                    'user_name' => $user_name,
                    'user_pass' => $user_pass,
                );
                $mail_data = cyc_mail_confirm_user( $parms );
                //$mail_data = get_mail_confirm_user ( $logo_url, $user_name, $user_pass );

                $headers = array('Content-Type: text/html; charset=UTF-8');
                $subject = 'Registro confirmado en Caras y Caretas';
                $result = wp_mail( $user_mail, $subject, $mail_data, $headers );
            }
        }
    }
    wp_redirect( home_url( '/registro-confirmado/?user=' . $user_id ) );
    //return $template;
}
add_action('template_redirect', 'cyc_confirmar_usuario');

function cyc_sidebar_before( $sidebar_id ) {
        
    if ( is_user_logged_in() && $sidebar_id == 'sidebar' ) :
        $user = wp_get_current_user();
        $link_perfil = get_page_link( get_page_by_path( 'perfil' )->ID );
        $link_cerrar_sesion = wp_logout_url( home_url() );
        ?>
        <div class="sb-widget mh-clearfix">
            <h4 class="widget-title"><span>Perfil</span></h4>
            <h4>Hola, <a href="<?php echo $link_perfil ?>"><?php echo $user->display_name ?></a></h4>
            <p>
                <a href="<?php echo $link_perfil ?>" class="button" style="display: inline-block">
                    <span>Editar Perfil</span>
                </a>
                <a href="<?php echo $link_cerrar_sesion ?>" class="button" style="display: inline-block">
                    <span>Cerrar Sesión</span>
                </a>
            </p>
        </div>
        <?php
    endif;
}
add_action( 'dynamic_sidebar_before', 'cyc_sidebar_before' );

function get_mail_lost_password ( $logo_url, $user_name, $new_pass ) {
    $mail_template = '
        <html>
            <body>
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                    <tr>
                        <td style="text-align: center; ">
                            <img src="'.$logo_url.'" />
                        </td>
                    </tr>
                    <tr>
                            <td style="text-align: center; ">
                                <h2 style="text-align: center">
                                    Hola '.$user_name.'
                                </h2>
                            </td>
                        </tr>
                    <tr>
                        <td style="text-align: center; ">
                            <h4 style="text-align: center">
                                Una nueva contraseña le ha sido asignada:
                            </h4>
                            <h4 style="text-align: center">'.$new_pass.'</h4>
                        </td>
                    </tr>
                </table>
            </body>
        </html>
    ';
    return $mail_template;
}

function cyc_custom_pagination( $pages = '' ) {
        global $paged;
                
        $prev_arrow = '&lsaquo; Anterior';
        $next_arrow = 'Siguiente &rsaquo;';

        if(empty($paged)) $paged = 1;
        if($pages == '')
        {
                global $wp_query;
                $pages = $wp_query->max_num_pages;
                if(!$pages)
                {
                        $pages = 1;
                }
        }   
        $big = 999999999; // need an unlikely integer
        if( $pages > 1 )  {
                echo "<div class='pagination'>";
                 if( !$current_page = get_query_var('paged') )
                         $current_page = 1;
                 if( get_option('permalink_structure') ) {
                         $format = 'page/%#%/';
                 } else {
                         $format = '&paged=%#%';
                 }
                echo paginate_links(array(
                        'base'			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                        'format'		=> $format,
                        'current'		=> $paged,
                        'total' 		=> $pages,
                        'mid_size'		=> 2,
                        'prev_text'		=> $prev_arrow,
                        'next_text'		=> $next_arrow,
                 ) );
                 echo "<span class='pagi-desc deskview'>";
                 printf( 'Página %1$s de %2$s', $paged, $pages );
                 echo "</span>";
                 echo "</div>";
        }
}


//rss
add_action('init', 'customRSS');
function customRSS(){
        add_feed('feedname', 'customRSSFunc');
}

function customRSSFunc(){
        get_template_part('rss', 'feedname');
}

//ajustes adicionales


add_action( 'admin_menu', 'cyc_add_custom_settings_menu_page' );
function cyc_add_custom_settings_menu_page() {
    add_theme_page(
        "Ajustes adicionales", 
        "Ajustes adicionales", 
        "manage_options", 
        "cyc-custom-theme-options", 
        "cyc_custom_option_page", 
        null, 
        99
    );
}

function cyc_custom_option_page() {
    ?>
        <div class="wrap">
            <h1>Ajustes adicionales del tema</h1>
            <?php settings_errors(); ?>
            <form method="post" action="options.php">
            <?php
                settings_fields( 'cyc-custom-theme-options-grp' );
                do_settings_sections( 'cyc-custom-theme-options' );
                submit_button();
                ?>
            </form>
        </div>
    <?php
}



function display_cyc_tv_title_element() {
    $cyc_featured_video_title = get_option( 'cyc_featured_video_title' );
    ?>
    <input type='text' name='cyc_featured_video_title'  class='large-text code' value='<?php echo $cyc_featured_video_title ?>' />
    <?php
}

function display_cyc_tv_video_element(){
    //https://youtu.be/BHQRZWNkfcs
    $cyc_featured_video_url = ('' == get_option( 'cyc_featured_video_url' ) ) ? '': 'https://youtu.be/' . get_option( 'cyc_featured_video_url' ); 
    ?>
        <input type='text' name='cyc_featured_video_url' class='large-text code' value='<?php echo $cyc_featured_video_url; ?>' />
    <?php if( '' != get_option( 'cyc_featured_video_url' ) ) : ?>
        <p style='margin: 20px 0'>
            <img src='https://img.youtube.com/vi/<?php echo get_option( 'cyc_featured_video_url' )?>/mqdefault.jpg' />
        </p>
    <?php
    endif;
}

function display_cyc_tv_video_element_2(){
    $cyc_featured_video_url_2 = ('' == get_option( 'cyc_featured_video_url_2' ) ) ? '': 'https://youtu.be/' . get_option( 'cyc_featured_video_url_2' ); 
    ?>
        <input type='text' name='cyc_featured_video_url_2' class='large-text code' value='<?php echo $cyc_featured_video_url_2; ?>' />
    <?php if( '' != get_option( 'cyc_featured_video_url_2' ) ) : ?>
        <p style='margin: 20px 0'>
            <img src='https://img.youtube.com/vi/<?php echo get_option( 'cyc_featured_video_url_2' )?>/mqdefault.jpg' />
        </p>
    <?php
    endif;
}

function display_cyc_tv_video_element_3(){
    $cyc_featured_video_url_3 = ('' == get_option( 'cyc_featured_video_url_3' ) ) ? '': 'https://youtu.be/' . get_option( 'cyc_featured_video_url_3' ); 
    ?>
        <input type='text' name='cyc_featured_video_url_3' class='large-text code' value='<?php echo $cyc_featured_video_url_3; ?>' />
    <?php if( '' != get_option( 'cyc_featured_video_url_3' ) ) : ?>
        <p style='margin: 20px 0'>
            <img src='https://img.youtube.com/vi/<?php echo get_option( 'cyc_featured_video_url_3' )?>/mqdefault.jpg' />
        </p>
    <?php
    endif;
}

function display_cyc_tv_video_element_4(){
    $cyc_featured_video_url_4 = ('' == get_option( 'cyc_featured_video_url_4' ) ) ? '': 'https://youtu.be/' . get_option( 'cyc_featured_video_url_4' ); 
    ?>
        <input type='text' name='cyc_featured_video_url_4' class='large-text code' value='<?php echo $cyc_featured_video_url_4; ?>' />
    <?php if( '' != get_option( 'cyc_featured_video_url_4' ) ) : ?>
        <p style='margin: 20px 0'>
            <img src='https://img.youtube.com/vi/<?php echo get_option( 'cyc_featured_video_url_4' )?>/mqdefault.jpg' />
        </p>
    <?php
    endif;
}

function display_cyc_home_image_gallery_title_element() {
    $cyc_home_image_gallery_title = get_option( 'cyc_home_image_gallery_title' );
    ?>
    <input type='text' name='cyc_home_image_gallery_title'  class='large-text code' value='<?php echo $cyc_home_image_gallery_title ?>' />
    <?php
}


function display_cyc_home_image_gallery(){
    $cyc_home_gallery_images = get_option( 'cyc_home_image_gallery' );
    ?>
    <p>
        <input type="button" class="button open-media-button" id="open-media-modal" value="Abrir la biblioteca multimedia" />
        <span class="description">Elija una o más imagenes.</span>
    </p>
    <div id="cyc_home_gallery_images">
        <?php if ($cyc_home_gallery_images) : foreach ($cyc_home_gallery_images as $id_image) : ?>
        <?php $attachment = wp_get_attachment_image_src( $id_image ) ?>
        <div class="img_item" id="img_item_<?php echo $id_image ?>">
            <input type="hidden" name="cyc_home_image_gallery[]" value="<?php echo $id_image ?>" />
            <img src="<?php echo $attachment[0] ?>" width="100" height="100" />
            <span class="remove_item dashicons dashicons-no"></span>
        </div>
        <?php endforeach; endif; ?>
    </div>
    <?php
}


function cyc_home_gallery_admin_scripts() {
    if ( 'appearance_page_cyc-custom-theme-options' == get_current_screen() -> id ) {
        wp_enqueue_media();
        
        wp_enqueue_script(
            'cyc_gallery_images_scripts',
            get_stylesheet_directory_uri() .'/js/gallery-images-upload.js', 
            array( 'media-views' ) 
        );
    }

}
add_action('admin_print_scripts', 'cyc_home_gallery_admin_scripts');

function cyc_home_gallery_admin_styles() {
    if ( 'appearance_page_cyc-custom-theme-options' == get_current_screen() -> id ) {
        wp_enqueue_style(
                'cyc_gallery_images_styles',
                get_stylesheet_directory_uri() .'/css/gallery-images-upload.css',
                array( 'media-views' )
        );
    }
}
add_action('admin_print_styles', 'cyc_home_gallery_admin_styles');

function display_cyc_home_popup_enable() {
    $cyc_home_popup_enable = get_option( 'cyc_home_popup_enable' );
    ?>
    <input type="checkbox" name="cyc_home_popup_enable" value="1" <?php echo checked( 1, $cyc_home_popup_enable, false ) ?>/>
    <?php
}

function display_cyc_home_popup_content() {
    $cyc_home_popup_content = get_option( 'cyc_home_popup_content' );
    ?>
    <textarea name="cyc_home_popup_content" rows="10" cols="50" class="large-text code"><?php echo esc_html($cyc_home_popup_content) ?></textarea>
    <?php
}

function validate_featured_video_url( $input ){
    preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $input, $video_parts);

    if( $input == '' ) {
        return;
    } else if ( ! isset($video_parts[1]) ) {
    
        add_settings_error(
            'requiredTextFieldEmpty',
            'empty',
            'Url de Youtube no válida',
            'error'
        );
        //return get_option( 'cyc_featured_video_url' );
        return $input;

    } else {
        return $video_parts[1];
    }
}

function cyc_custom_theme_settings(){
    add_option( 'first_field_option', 1 );// add theme option to database
    add_settings_section ( 
        'first_section', 
        NULL,
        NULL,
        'cyc-custom-theme-options'
    );

    
    
    add_settings_field(
        'cyc_featured_video_title', 
        'Video de C&C TV (Título)', 
        'display_cyc_tv_title_element', 
        'cyc-custom-theme-options', 
        'first_section'
    );
    register_setting( 
        'cyc-custom-theme-options-grp', 
        'cyc_featured_video_title'
    );
    
    add_settings_field(
        'cyc_featured_video_url', 
        'Video de C&C TV (url)', 
        'display_cyc_tv_video_element', 
        'cyc-custom-theme-options', 
        'first_section'
    );
    register_setting( 
        'cyc-custom-theme-options-grp', 
        'cyc_featured_video_url',
        'validate_featured_video_url'
    );
    
    
    add_settings_field(
        'cyc_featured_video_url_2', 
        'Video de C&C 2 (url)', 
        'display_cyc_tv_video_element_2', 
        'cyc-custom-theme-options', 
        'first_section'
    );
    register_setting( 
        'cyc-custom-theme-options-grp', 
        'cyc_featured_video_url_2',
        'validate_featured_video_url'
    );
    
    
    add_settings_field(
        'cyc_featured_video_url_3', 
        'Video de C&C 3 (url)', 
        'display_cyc_tv_video_element_3', 
        'cyc-custom-theme-options', 
        'first_section'
    );
    register_setting( 
        'cyc-custom-theme-options-grp', 
        'cyc_featured_video_url_3',
        'validate_featured_video_url'
    );
    
    
    add_settings_field(
        'cyc_featured_video_url_4', 
        'Video de C&C 4 (url)', 
        'display_cyc_tv_video_element_4', 
        'cyc-custom-theme-options', 
        'first_section'
    );
    register_setting( 
        'cyc-custom-theme-options-grp', 
        'cyc_featured_video_url_4',
        'validate_featured_video_url'
    );
    
    
    
    add_settings_field(
        'cyc_home_image_gallery_title', 
        'Galería de imagenes (Título)', 
        'display_cyc_home_image_gallery_title_element', 
        'cyc-custom-theme-options', 
        'first_section'
    );
    register_setting( 
        'cyc-custom-theme-options-grp', 
        'cyc_home_image_gallery_title'
    );
    
    add_settings_field(
        'cyc_home_image_gallery', 
        'Galería de imagenes', 
        'display_cyc_home_image_gallery', 
        'cyc-custom-theme-options', 
        'first_section'
    );
    register_setting( 
        'cyc-custom-theme-options-grp', 
        'cyc_home_image_gallery'
    );
    
    add_settings_field(
        'cyc_home_popup_enable', 
        'Popup en home habilitado', 
        'display_cyc_home_popup_enable', 
        'cyc-custom-theme-options', 
        'first_section' 
    );
    register_setting(
        'cyc-custom-theme-options-grp', 
        'cyc_home_popup_enable'
    );
    
    add_settings_field(
        'cyc_home_popup_content', 
        'Contenido del Popup', 
        'display_cyc_home_popup_content', 
        'cyc-custom-theme-options', 
        'first_section' 
    );
    register_setting(
        'cyc-custom-theme-options-grp', 
        'cyc_home_popup_content'
    );
}
add_action( 'admin_init', 'cyc_custom_theme_settings' );

//Mensaje invitando a integrar la comunidad
add_filter ('the_content', 'cyc_insert_mensaje_comunidad');
function cyc_insert_mensaje_comunidad($content) {
   if(is_single()) {
      $content.= '<div class="boton-comunidad">';
      $content.= '<a href="' . get_permalink(get_page_by_path('comunidad') ) . '"><h4>';
      $content.= 'En menos de un minuto podés unirte a la comunidad de Caras y Caretas. Es gratuito y te permite recibir gacetillas con información y artículos de opinión a tu correo electrónico, participar de eventos, promociones, actividades exclusivas para nuestros lectores y recibir obsequios de forma periódica. Es rápido, no te cuesta nada y es una forma de apoyar nuestra mirada y mantenernos en contacto.';
      $content.= '</h4></a>';
      $content.= '<a class="boton" href="' . get_permalink(get_page_by_path('comunidad') ) . '">Unite a la comunidad de Caras y Caretas</a>';
      $content.= '</div>';
   }
   return $content;
}

//Boton de login en el formulario de comentarios
add_action( 'comment_form_must_log_in_after', 'cyc_comment_form_before');
function cyc_comment_form_before() {
    $link_login = '#TB_inline?height=370&amp;width=350&amp;inlineId=cyc-login-popup';
    ?>
    <a href="<?php echo $link_login; ?>" class="thickbox" style="display: inline-block;margin-top: 10px; font-size: 12px; color: #fff; padding: 5px 10px; background: #6288a5;">
        LOG IN
    </a>
    <?php
}