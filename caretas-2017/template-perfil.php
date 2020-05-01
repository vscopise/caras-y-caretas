<?php
/* Template Name: Perfil */
global $current_user, $wp_roles;

if ( is_user_logged_in() ) {
    $current_user = wp_get_current_user();
    $user_name = $current_user->user_login;

    $current_user_mail = $current_user->user_email;
    $current_first_name = $current_user->user_firstname;
    $current_last_name = $current_user->user_lastname;
    
    $current_phone1 = get_the_author_meta( 'phone1', $current_user->ID );

    $nonce = esc_attr(filter_input(INPUT_POST, 'user_perfil_nonce'));
    $user_mail = filter_input( INPUT_POST, 'user_mail', FILTER_VALIDATE_EMAIL);
    $first_name = esc_attr(filter_input(INPUT_POST, 'first_name'));
    $last_name = esc_attr(filter_input(INPUT_POST, 'last_name'));
    
    $phone1 = esc_attr(filter_input(INPUT_POST, 'phone1'));

    $user_pass1 = esc_attr(filter_input(INPUT_POST, 'user_pass1'));
    $user_pass2 = esc_attr(filter_input(INPUT_POST, 'user_pass2'));

    $user_id = esc_attr(filter_input(INPUT_POST, 'user_id'));

    if( $user_id != NULL && isset( $nonce ) && wp_verify_nonce( $nonce, 'perfil' ) ) {

        $error = FALSE;
        $mensaje = '';

        if ( $user_pass1 != NULL || $user_pass2 != NULL ) {
            if ( $user_pass1 != $user_pass2 ) {
                $error = TRUE;
                $mensaje = 'Las contraseñas no coinciden';
            } else {
                $user_id = $current_user->ID;
                $username = get_userdata($user_id)->user_login;
                wp_update_user( array(
                    'ID' => $user_id, 
                    'user_pass' => $user_pass1,
                ));

                wp_cache_delete($user_id, 'users');
                wp_cache_delete($username, 'userlogins');
                wp_logout();
                wp_signon( array( 'user_login' => $username, 'user_password' => $user_pass1 ) );

                $mensaje = 'Contraseñas modificadas.';
            }
        }

        if ( $error == FALSE ) {
            if ( !$user_mail ) {
                $error = TRUE;
                $mensaje .= 'E-mail incorrecto';
            } elseif ( email_exists( $user_mail ) && ( $user_mail != $current_user_mail ) ) {
                $error = TRUE;
                $mensaje = 'Este e-mail ya está registrado';
            } else {
                wp_update_user( array(
                    'ID' => $current_user->ID, 
                    'user_email' => $user_mail,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                ));

                update_user_meta( $current_user->ID, 'phone1', $phone1 );

                $current_user_mail = $user_mail;
                $current_first_name = $first_name;
                $current_last_name = $last_name;

                $current_phone1 = $phone1;
                $current_phone2 = $phone2;

                $mensaje = 'Datos actualizados correctamente.';
            }
        }     
    }
    
}

get_header(); 

?>
<div class="mh-section mh-group">
	<div id="main-content" class="mh-content">
            <article>
            <?php mh_newsdesk_lite_before_page_content(); ?>
		<?php while (have_posts()) : the_post(); ?>
                    <header class="entry-header"><?php mh_newsdesk_lite_page_title(); ?></header>
                        <?php if ( is_user_logged_in() ) : ?>
                            <div class="entry-content clearfix">  
                                <?php if ( $mensaje != '' ) : ?>
                                    <?php if ( $error == TRUE ) : ?>
                                        <blockquote><p><?php echo $mensaje ?></p></blockquote>
                                    <?php else : ?>
                                        <blockquote><p><?php echo $mensaje ?></p></blockquote>
                                    <?php endif; ?>

                                <?php endif; ?>
                                <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" class="w3-container">
                                    <div style="background: #efefef; padding: 2%;">
                                        <?php wp_nonce_field('perfil','user_perfil_nonce', true, true ); ?>
                                        <input type="hidden" name="user_id" value="<?php echo $current_user->ID ?>" />
                                        <p>
                                            <label for="username">Usuario <strong>*</strong></label><br />
                                            <input type="text" name="username" disabled="disabled" value="<?php echo $user_name ?>" style="width: 98%" />
                                        </p>
                                        <p>
                                            <label for="user_mail">Mail</label><br />
                                            <input type="text" name="user_mail" value="<?php echo $current_user_mail ?>" style="width: 98%" />
                                        </p>
                                        <p>
                                            <label for="first_name">Nombre</label><br />
                                            <input type="text" name="first_name" value="<?php echo $current_first_name ?>" style="width: 98%" />
                                        </p>
                                        <p>
                                            <label for="last_name">Apellido</label><br />
                                            <input type="text" name="last_name" value="<?php echo $current_last_name ?>" style="width: 98%" />
                                        </p>
                                        <p>
                                            <label for="last_name">Teléfono</label><br />
                                            <input type="text" name="phone1" value="<?php echo $current_phone1 ?>" style="width: 98%" />
                                        </p>

                                        <p>
                                            <label for="password1">Nueva Contraseña</label><br />
                                            <input type="password" name="user_pass1" value="" style="width: 98%" />
                                        </p>
                                        <p>
                                            <label for="password2">Confirmar Contraseña</label><br />
                                            <input type="password" name="user_pass2" value="" style="width: 98%" />
                                        </p>
                                        <p>
                                            <input type="submit" name="submit" value="ACTUALIZAR PERFIL"/>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        <?php else : ?>
                                <blockquote><p>Debe iniciar sesión para poder editar su perfil.</p></blockquote>
                            <?php $link_login = '#TB_inline?height=300&amp;width=350&amp;inlineId=cyc-login-popup' ?>
                                <div class="clearfix">
                                    <h4 class="content-list-title">
                                        <a href="<?php echo $link_login?>" class="thickbox">
                                            ¿ Ya est&aacute; registrado ? Ingrese aquÍ
                                        </a>
                                    </h4>
                                    <?php $link_suscripciones = get_page_link( get_page_by_path( 'suscripciones' )->ID ); ?>
                                    <h4 class="content-list-title">
                                        <a href="<?php echo $link_suscripciones ?>">
                                            ¿No lo está? Entonces suscríbase aquí.
                                        </a>
                                    </h4>
                                </div>
                        <?php endif; ?>
		<?php endwhile; ?>
            </article>
	</div>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>