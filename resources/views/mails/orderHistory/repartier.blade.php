<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<style type="text/css">
    .letra{
        color:#696868;
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
                        <p class="letra">Hola, {{ $orderFind->clientName. ' '.$orderFind->clientLastName }}.</p>
                        <p class="letra"> Su pedido <b> N° {{ $orderFind->code }} </b> ya esta en camino, a continuación
                        le dejamos los datos que identificarán al repartidor encargado de atenderlo.</p>
                    </td>
                </tr>

                 <tr>
                 <td>
                     <table width="90%" border="0" align="center" cellpadding="5" cellspacing="0" id="data"
                            style="font-family:Verdana; font-size:12px; border:solid #dddddd 1px;">
                         <tbody>
                         <tr class="letra">
                             <th width="41%" align="right" style="background:#eeeeee;color:#000">Nombres :</th>
                             <td width="59%">{{ $repartierName }}</td>
                         </tr>
                         <tr class="letra">
                             <th width="41%" align="right" style="background:#eeeeee;color:#000">Celular :</th>
                             <td width="59%">{{ $repartierPhone }}</td>
                         </tr>
                         <tr class="letra">
                             <th width="41%" align="right" style="background:#eeeeee;color:#000">E-mail :</th>
                             <td width="59%">{{ $repartierEmail }}</td>
                         </tr>
                         </tbody>
                     </table>
                 </td>
                 </tr>

                 <tr>
                     <td>
                        <p class="letra" style="color:#696868;">Recuerde que el tiempo de envio demora entre 1 hora a 2 horas.</p>
                        <p class="letra" style="color:#696868;">Así mismo, informale que este portal le informará sobre los
                            servicios de compra o modificaciones personales que vaya realizando.
                            Además contará con un historial de sus pedidos e información necesaria para que evite todos los
                            inconvenientes que pueda presentar.</p>
                        <p class="letra" style="color:#696868;">Sin más que agregar, nos despedimos de usted.</p>
                        <p> </p>
                        <p> </p> <br>
                        <p class="letra" style="color:#696868;">Tienda CakeStore.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p></p> <span>Por favor, no responder a este correo.</span>

</html>