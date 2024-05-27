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
                        <p class="letra"> Su pedido <b> N° {{ $orderFind->code }} </b> ya esta completo. </p>

                        <p style="text-align: center;margin: 0 auto;widows: 100%;">
                            <img src="http://sptstudio.com/cakestore/order/3.jpg" alt="" />
                        </p>

                        <br>

                        <p class="letra">Ya estamos despachando tu pedido..</p>

                        <p class="letra">Si usted selecciono el tipo de entrega
                            como <b>Tienda</b>, deberá acercarse a nuestro local que ha elegido, puede revisar
                            <a href="http://cakestore-upc.sptstudio.com/client/account#history" style="color: #b91e1e;text-decoration: underline;cursor: pointer;font-weight: bold;">aquí</a>,
                            el detalle de su pedido.
                        </p>

                        <p class="letra">Si usted selecciono de tipo de entrega
                            como <b>Delivery</b>, en breve le estaremos notificando el repartidor asignado que se encargará
                            de entregarle el pedido en sus manos.</p>

                        <p class="letra">Recuerde los pedidos se procesan de Lunes a Viernes de 9 a 18 horas .</p>

                        <p class="letra">Puede ver el seguimiento de sus pedidos
                            <a href="http://cakestore-upc.sptstudio.com/client/account#history" style="color: #b91e1e;text-decoration: underline;cursor: pointer;font-weight: bold;">aquí</a></p>

                        <p></p> <br>

                        <p class="letra">Tienda CakeStore.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</html>