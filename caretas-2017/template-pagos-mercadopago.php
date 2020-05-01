<?php /* Template Name: Pagos MercadoPago */ ?>
<?php get_header(); ?>
<div class="mh-section mh-group socios">
    <div class="w3-row">
        <?php 
        $token = filter_input(INPUT_POST, 'token');
        $transactionAmount = filter_input(INPUT_POST, 'transactionAmount');
        $paymentMethodId = filter_input(INPUT_POST, 'paymentMethodId');
        $paymentEmail = filter_input(INPUT_POST, 'email');
        $monto = filter_input(INPUT_POST, 'monto');
        $request_metod = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        ?>
        <?php 
            if ( $request_metod === 'POST' 
                && !empty( $token ) 
                && !empty( $transactionAmount ) 
                && !empty( $paymentMethodId )  
                && !empty( $paymentEmail )  
            ) :
        ?>
        <?php 
            $cyc_options = mh_newsdesk_lite_theme_options();
            $mp_access_token = $cyc_options['mp_access_token'];
            //$file = get_stylesheet_directory() . '/mercadopago/autoload.php';
            //echo '<p>file-' . $file . '</p>';
            require_once get_stylesheet_directory() . '/mercadopago/autoload.php';
            MercadoPago\SDK::setAccessToken( $mp_access_token );

            $payment = new MercadoPago\Payment();

            $payment->transaction_amount = 123;
            //$payment->transaction_amount = $transactionAmount;
            $payment->token = $token;
            $payment->description = "Ergonomic Silk Shirt";
            $payment->installments = 1;
            //$payment->payment_method_id = $paymentMethodId;
            $payment->issuer_id = 310;
            $payment->payer = array(
              "email" => $paymentEmail
            );

            $payment->save();

            echo $payment->status;
        ?>
        <?php 
            elseif (
                $request_metod === 'POST'
                && !empty( $paymentEmail ) 
                && !empty( $monto ) 
            ) :
        ?>
        <div class="w3-row">
            <div class="entry-content mh-clearfix">
                <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
                <script>
                    jQuery(document).ready(function($) {
                        window.Mercadopago.setPublishableKey(MercadoPagoObject.mp_public_key);
                        window.Mercadopago.getIdentificationTypes();
    

                        function getBin() {
                            return $('#cardNumber').val().substring(0,6);
                          //const cardnumber = document.getElementById("cardnumber");
                          //return cardnumber.substring(0,6);
                        }
                        $(document).bind( 'keyup', guessingPaymentMethod);
                        function guessingPaymentMethod(event) {
                            var bin = getBin();

                            if (event.type === "keyup") {
                                if (bin.length >= 6) {
                                    window.Mercadopago.getPaymentMethod({
                                        "bin": bin
                                    }, setPaymentMethodInfo);
                                }
                            } else {
                                setTimeout(function() {
                                    if (bin.length >= 6) {
                                        window.Mercadopago.getPaymentMethod({
                                            "bin": bin
                                        }, setPaymentMethodInfo);
                                    }
                                }, 100);
                            }
                        };

                        function setPaymentMethodInfo(status, response) {
                            if (status === 200) {
                                const paymentMethodElement = document.querySelector('input[name=paymentMethodId]');

                                if (paymentMethodElement) {
                                    paymentMethodElement.value = response[0].id;
                                } else {
                                    const input = document.createElement('input');
                                    input.setattribute('name', 'paymentMethodId');
                                    input.setAttribute('type', 'hidden');
                                    input.setAttribute('value', response[0].id);     

                                    $('#pay').appendChild(input);
                                }
                            } else {
                                alert(`payment method info error: ${response}`);  
                            }
                        };

                        doSubmit = false;
                        $('#pay').bind('submit', doPay);
                        //addEvent(document.querySelector('#pay'), 'submit', doPay);
                        function doPay(event){
                            event.preventDefault();
                            if ( !doSubmit ) {
                                var $form = document.querySelector('#pay');
                                window.Mercadopago.createToken($form, sdkResponseHandler);
                                return false;
                            }
                        };

                        function sdkResponseHandler(status, response) {
                            if (status !== 200 && status !== 201) {
                                alert("Complete los datos requeridos");
                            } else {
                                var form = document.querySelector('#pay');
                                var card = document.createElement('input');
                                card.setAttribute('name', 'token');
                                card.setAttribute('type', 'hidden');
                                card.setAttribute('value', response.id);
                                form.appendChild(card);
                                doSubmit=true;
                                form.submit();
                            }
                        };
                    });
                </script>
                <form action="" method="post" id="pay" name="pay" >
                    <div class='resumen'>
                        <?php $start_date = date('d/m/Y'); ?>
                        <p class="resumen-monto">$&nbsp;<?php echo $monto ?></p>
                        <p>Se te descontará esta cantidad en forma mensual a partir del <?php echo $start_date ?></p>
                    </div>
                    <h3 class="title">
                        Completa todos los datos a continuación
                        <span>
                            Pagos procesados por: 
                            <img src="https://http2.mlstatic.com/ui/navigation/4.1.4/mercadopago/logo__large@2x.png" alt="Mercad Pago" />
                        </span>
                    </h3>
                    <fieldset>
                        <input type="hidden" id="transactionAmount" name="transactionAmount" value="<?php echo $monto ?>" />
                        <input type="hidden" id="email" name="email" value="<?php echo $paymentEmail ?>" />
                        <p>
                            <span>Número de tarjeta de crédito:</span>
                            <input type="text" id="cardNumber" data-checkout="cardNumber" placeholder="4509 9535 6623 3704" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
                        </p>
                        
                        <p>
                            <span>Código de seguridad:</span>
                            <input type="text" id="securityCode" data-checkout="securityCode" placeholder="123" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
                        </p>
                        <p>
                            <span>Fecha de vencimiento:</span>
                            <select id="cardExpirationMonth" data-checkout="cardExpirationMonth">
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Setiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>/<input type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" placeholder="2015" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
                        </p>
                        <p>
                            <span>Card holder name:</span>
                            <input type="text" id="cardholderName" data-checkout="cardholderName" placeholder="APRO" />
                        </p>
                        <p>
                            <span>Documento:</span>
                            <select id="docType" data-checkout="docType"></select>
                            <input type="text" id="docNumber" data-checkout="docNumber" placeholder="12345678" />
                        </p>  
                        <input type="hidden" name="paymentMethodId" />
                        <p class="submit">
                            <input type="submit" value="Confirmar débito !" />
                        </p>
                    </fieldset>
                </form>
                <p class="terminos">
                    Al continuar manifiestas que estás de acuerdo con nuestros <a href=''>Términos y condiciones</a>
                </p>
            </div>
        </div>
            
        <?php else : ?>
        <div class="mh-section mh-group">
            <div class="mh-row mh-group" style="height: 420px">
                <div class="mh-col mh-1-2" style="color: #fff; background: #000; height: 100%;">
                    <div style="padding: 20px">
                        <h2 style="color:#fff;">Si vos necesitás a Caras y Caretas, Caras y Caretas te necesita a vos.</h2>
                        <p style="margin-bottom: .5em">A partir de ahora, con un aporte mensual podés ser socio contribuyente de la Comunidad de Caras y Caretas y participar activamente en la discusión subre su actualidad y futuro.</p>
                        <p style="margin-bottom: .5em">Defendé la otra mirada. Defendé tu voz. Unite a Caras y Caretas.</p>
                        <p style="margin-bottom: .5em">Tu contribución no es indispensable para que continúes recibiendo Caras y Caretas Diario, que seguirá siendo gratuita para todos los miembros de la Comunidad, pero será una forma de colaborar con el financiamiento de este proyecto y de las actividades públicas que organiza la Comunidad.</p>
                    </div>
                </div>
                <div class="mh-col mh-1-2">
                    <form action="" method="post" >
                        <div class="mh-section mh-group">
                            <h2 style="text-align: center;">Unite a Caras y Caretas y accedé a:</h2>
                            <div class="w3-row">
                                <div class="mh-col mh-1-2">
                                    <p><i class="fa fa-check" style="margin-right: 5px"></i>Caras y Caretas Diario</p>
                                    <p><i class="fa fa-check" style="margin-right: 5px"></i>Entrevistas con público</p>
                                    <p><i class="fa fa-check" style="margin-right: 5px"></i>Entradas al Cine y al Teatro</p>
                                </div>
                                <div class="mh-col mh-1-2">
                                    <p><i class="fa fa-check" style="margin-right: 5px"></i>Videoconferencias interactivas</p>
                                    <p><i class="fa fa-check" style="margin-right: 5px"></i>Promociones y Beneficios exclusivos</p>
                                </div>
                            </div>
                        </div>
                        <h4 style="font-weigt: 400;">Elegí con cuánto querés contribuir</h4>
                        <input type="hidden" id="monto" name="monto" />
                        <div class="mh-section mh-group">
                            <div class="mh-col mh-1-3">
                                <div class="monto" data-value="300">$300</div>
                            </div>
                            <div class="mh-col mh-1-3">
                                <div class="monto" data-value="450">$450</div>
                            </div>
                            <div class="mh-col mh-1-3">
                                <div class="monto" data-value="1000">$1000</div>
                            </div>
                        </div>
                        <div class="mh-section mh-group">
                            <h4>Ingresá tu email:</h4>
                            <input type="email" name="email" />
                        </div>
                        <div class="mh-section mh-group">
                            <input type="submit" name="Enviar" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="mh-section mh-group"  style="padding: 30px; background: #ddd; font-style: italic; font-family: 'PT Serif',Georgia,Times,'Times New Roman',serif">
            <div class="mh-row">
                <div class="mh-col mh-1-2">
                    <p>
                        Somos una propuesta alternativa a la prensa dominante, defendemos otra mirada y queremos que nos ayudes a seguir existiendo.
                    </p>
                </div>
                <div class="mh-col mh-1-2">
                    <p>
                        Hacete socio de la Comunidad de Caras y Caretas y si tenés alguna pregunta o sugerencia, no dudes en escribirnos a comunidad@carasycaretas.com.uy
                    </p>
                    
                </div>
            </div>
        </div>
        <?php endif; ?>
        
    </div>
</div>
<?php get_footer(); ?>