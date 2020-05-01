</div>
<footer class="mh-footer">
	<div class="wrapper-inner">
            <p class="copyright">&copy; <?php echo date('Y') ?> Caras y Caretas  - Paraguay 1478 piso 2 - Tel. 2903 3188 - <a href="/terminos-y-condiciones">Términos y Condiciones</a> - <a href="/contacto">Contacto</a></p>
	</div>
</footer>

<div id="cyc-login-popup" style="display:none">
    <h2>Iniciar sesión</h2>
    <form id="cyc-login-form">
        <div class="input">
            <label>Usuario</label>
            <input type="text" id="mh_username" name="username" />
        </div>
        <div class="input">
            <label>Contraseña</label>
            <input type="password" id="mh_password" name="password" />
        </div>
        <div class="input">
            <?php wp_nonce_field('mh_login','mh_login_nonce', true, true ); ?>
            <input type="submit" value="Enviar" />
            <div id="login_message"></div>
        </div>
    </form>
</div>
<?php wp_footer(); ?>
</body>
</html>