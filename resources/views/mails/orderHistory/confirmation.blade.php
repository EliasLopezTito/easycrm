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
                        <p class="letra"> Muchas gracias por tu compra. </p>

                        <p style="text-align: center;margin: 0 auto;widows: 100%;">
                            <img src="http://sptstudio.com/cakestore/order/1.jpg" alt="" />
                        </p>

                        <br>

                        <p class="letra">A contuniuacion le mostramos los datos necesarios a realizarse, para finalizar con su pedido.</p>

                        <p> <b>N° Pedido :</b> {{ $orderFind->code }} <br />
                            <b>N° Cuenta BCP :</b> 131-2845566-0-37 <br />
                            <b>N° Cuenta Interbank :</b> BE57 0633 9533 1498 <br />
                            <b>N° Cuenta Scotiabank :</b> 323-456789 <br />
                            <b>Monto a depositar :</b> S/. {{ $orderFind->total }}  </p>

                        <p class="letra">En cuanto se acredite el pago y procesemos tu pedido te estaremos avisando que el mismo ya esta en preparación.</p>

                        <p class="letra"><b>Pagos por depósito / transferencia : </b> <br />
                            Por favor en cuanto tengas el comprobante de pago de envialo respondiento este mail.</p>

                        <p class="letra">Puede ver el seguimiento de sus pedidos
                            <a href="http://cakestore-upc.sptstudio.com/client/account#pending" style="color: #b91e1e;text-decoration: underline;cursor: pointer;font-weight: bold;">aquí</a></p>

                        <p></p> <br>

                        <p class="letra">Tienda CakeStore.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</html>