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
            <table class="letra" width="100%" border="0" align="center" cellpadding="8" cellspacing="0" bordercolor="#dddddd" id="bmail">
                <p style="text-align: center;margin:0; font-size: 32px; color: #000;position: relative;left: 35px; top: 24px;
                font-weight: bold;">ComprandoPe</p>
                <tr style="color:#696868;">
                    <td>
                        <p class="letra">Hola, Administrador. </p>
                        <p class="letra">Te llegó una orden desde ComprandoPe. A continuacion le mostramos los datos del Pedido.</p>

                        <p> <b>Orden :</b> {{ $code }} <br />
                            <b>Cliente :</b> {{ $client }} | <b>Teléfono :</b> {{ $phone }} <br />
                            <b>Negocio :</b> {{ $negocio }} <br />
                            <b>Productos :</b> {{ $products }} <br />
                            <b>Costo :</b> S/. {{ $total }} <br />
                            <b>Comision :</b> S/. {{ $comision }} </p>

                        <p class="letra">ComprandoPe.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</html>