<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<style>
    .letra{
        font-size: 13px;font-family: arial,sans-serif;
    }
</style>

<table width="580" border="0" cellpadding="8" cellspacing="0" style="background: #e8e3e333;
    border-radius: 15px;border: 1px solid #752424;">
    <tr>
        <td>
            <p style="padding-right: 29px"> <img src="https://cakestore.decomonky.com/favicon.ico" class="logo" alt="CakeStore" style="float: right;
             object-fit: contain; margin-bottom: 20px;position: relative;border-radius: 75px;width: 74px;right: 29px;margin:0;" /> </p>
            <table class="letra" width="100%" border="0" align="center" cellpadding="8" cellspacing="0" bordercolor="#dddddd" id="bmail">
                <p style="text-align: center;margin:0; font-size: 32px; color: #925848;text-transform: uppercase; position: relative;left: 35px; top: 24px;
                font-weight: bold;">Cake<span style="color: #c83a3a;font-weight: bold;">Store</span></p>
                <tr style="color:#696868;">
                    <td>
                        <p class="letra">Hola, {{ $orderFind->clientName. ' '.$orderFind->clientLastName }}. </p>
                        <p class="letra"> Su pedido <b> N° {{ $orderFind->code }} </b> ha sido entregado de manera
                            exitosa. </p>

                          <p class="letra">Agradecemos su confiabilidad en nuestra compañia, y esperamos volver
                          a realizarle un nuevo servicio, con la misma calidad que nos caracteriza.</p>
                        <p></p> <br>

                        <p class="letra">Tienda CakeStore.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p></p> <span>Por favor, no responder a este correo.</span>

</html>