jQuery(document).ready(function ($) {
    $('#cyc-login-form').submit( function(event) {
            if (event.preventDefault) {
                event.preventDefault();
            } else {
                event.returnValue = false;
            }
            $('#login_message').html('');
            $('#login_message').addClass('loading');
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: cyc_login_script_vars.ajaxurl,
                data: {
                    'action': 'login_user',
                    'nonce': $('#mh_login_nonce').val(),
                    'username': $('#mh_username').val(),
                    'password': $('#mh_password').val()
                },
                success: function(response){
                    $('#login_message').removeClass('loading');
                    $('#login_message').html( response.mensaje);
                    if( response.result === 1 ) {
                        document.location.href = cyc_login_script_vars.redirecturl;
                    }
                }
            });
        });
        var login_button_content = cyc_login_script_vars.is_logged_in == '1' ? 'DESCONECTAR' : 'INGRESAR';
        var login_button_url = cyc_login_script_vars.is_logged_in == '1' ? cyc_login_script_vars.logout_url : cyc_login_script_vars.login_url;
        var login_button_class = cyc_login_script_vars.is_logged_in == '1' ? '' : 'thickbox';
        $('#login-logout a').html( login_button_content );
        $('#login-logout a').attr('href', login_button_url );
        $('#login-logout a').attr('class', login_button_class );
        
});