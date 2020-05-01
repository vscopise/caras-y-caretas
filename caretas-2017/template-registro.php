<?php 
/* Template Name: Registro */
get_header(); 

$user_name = filter_input( INPUT_POST, 'user_name');
$user_mail = filter_input( INPUT_POST, 'user_mail', FILTER_VALIDATE_EMAIL);

$user_key = wp_generate_password( 20, false );
$expiry_time = date( 'Y-m-d', strtotime('+7 days') );

$nonce = esc_attr(filter_input(INPUT_POST, 'new_user_nonce'));

$usuarios_temporales = get_option( 'usuarios_temporales' );

if ( $user_name != NULL || $user_mail != NULL ) {
    
    $error = FALSE;
    $mensaje = '';
    
    if( !isset( $nonce ) || !wp_verify_nonce( $nonce, 'new_user' ) ) {
            $error = TRUE;
            $mensaje = 'Error.';
    } else {
        if ( $user_name == NULL || $user_mail == NULL ) {
                $error = TRUE;
                $mensaje = 'Por favor, completa correctamente los datos requeridos.';
        } else {
                if ( !$user_mail ) {
                        $error = TRUE;
                        $mensaje = 'E-mail incorrecto';
                } elseif ( email_exists( $user_mail ) ) {
                        $error = TRUE;
                        $mensaje = 'Este e-mail ya está registrado';
                } elseif ( $usuarios_temporales && is_int( array_search( $user_mail, array_column($usuarios_temporales, 'user_mail') ) ) ) {
                        $error = TRUE;
                        $mensaje = 'Este Email ya está registrado';
                } elseif ( $usuarios_temporales && is_int( array_search( $user_name, array_column($usuarios_temporales, 'user_name') ) ) ) {
                        $error = TRUE;
                        $mensaje = 'Este Usuario ya está registrado';
                } elseif ( username_exists( $user_name ) ) {
                        $error = TRUE;
                        $mensaje = 'Este Usuario ya está registrado';
                } else {
                        $new_user = array(
                            'user_name' => $user_name,
                            'user_mail' => $user_mail,
                            'user_key' => $user_key,
                            'expiry_time' => $expiry_time,
                        );
                        if ( ! $usuarios_temporales ) {
                            $usuarios_temporales = array();
                        }
                        array_push( $usuarios_temporales, $new_user );
                        update_option( 'usuarios_temporales', $usuarios_temporales );

                        //enviar mail
                        //$logo_url = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ) , 'full' );
                        $logo_url = get_header_image();
                        $home_url = home_url('/');
                        $terminos_y_condiciones = get_permalink( get_page_by_path( 'terminos-y-condiciones' ) -> ID );

                        $parms = array(
                            'logo_url' => $logo_url,
                            'home_url' => $home_url,
                            'user_name' => $user_name,
                            'user_key' => $user_key,
                            'user_mail' => $user_mail,
                            'terminos_y_condiciones' => $terminos_y_condiciones
                        );

                        $mail_data = cyc_mail_new_user( $parms );
                        //$mail_data = get_mail_new_user( $logo_url, $home_url, $user_name, $user_key, $user_mail, $terminos_y_condiciones );

                        $headers = array('Content-Type: text/html; charset=UTF-8');
                        $subject = 'Bienvenido a Caras y Caretas';
                        $result = wp_mail( $user_mail, $subject, $mail_data, $headers );

                        $error = FALSE;

                        $mensaje = 'Mensaje enviado correctamente, ahora revise su mail';

                }

        }
    }
} 
?>
<div class="mh-section mh-group">
	<div id="main-content" class="mh-content">
            
            <?php while (have_posts()) : the_post(); ?>
            
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                                <?php mh_newsdesk_lite_page_title(); ?>
                        </header>
                        <div class="entry-content mh-clearfix">
                            
                                <?php if ( $mensaje != '' ) : ?>
                                    <?php if ( $error == TRUE ) : ?>
                                    <div class="alert alert-danger"><?php echo $mensaje ?></div>
                                    <?php else : ?>
                                    <div class="alert alert-success"><?php echo $mensaje ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if ( $mensaje == '' && $error == FALSE ) : ?>
                                            
                                    <?php the_content() ?>

                                    <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">

                                        <?php wp_nonce_field('new_user', 'new_user_nonce', true, true ); ?>

                                        <div class="row">
                                            <div class="col-lg-12 col-sm-12">
                                                <label for="user_name">Usuario <strong>*</strong></label>
                                                <input type="text" name="user_name" value="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-sm-12 form_field_wrap">
                                                <label for="user_mail">Mail <strong>*</strong></label>
                                                <input type="text" name="user_mail" value="">
                                            </div>
                                        </div>

                                        <input type="submit" name="submit" value="Enviar !"/>

                                    </form>

                                <?php endif; ?>
                        </div>
                </article>
            
            <?php endwhile; ?>
            
	</div>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>