<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Comprobante de vale</title>
    </head>
    <style>
        body {
        /*position: relative;*/
        /*width: 16cm;  */
        /*height: 29.7cm; */
        /*margin: 0 auto; */
        /*color: #555555;*/
        /*background: #FFFFFF; */
        font-family: Arial, sans-serif; 
        font-size: 14px;
        /*font-family: SourceSansPro;*/
        }

        #logo{
        float: left;
        margin-top: 1%;
        margin-left: 2%;
        margin-right: 2%;
        }

        #imagen{
        width: 100px;
        }

        #datos{
        float: left;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        /*text-align: justify;*/
        }

        #encabezado{
        text-align: center;
        margin-left: 10%;
        margin-right: 35%;
        font-size: 15px;
        }

        #fact{
        /*position: relative;*/
        float: right;
        margin-top: 2%;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 20px;
        }

        section{
        clear: left;
        }

        #cliente{
        text-align: left;
        }

        #facliente{
        /* width: 100%; */
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        .fac, .fv, .fa{
        color: #FFFFFF;
        font-size: 15px;
        }

        #facliente thead{
        padding: 20px;
        /* background: #00b894; */
        background: #000000;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facvendedor{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facvendedor thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        .facarticulo{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        .facarticulo thead{
        padding: 5px;
        background: #000000;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;  
        }

        .pagos{
        width: 50%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        .pagos thead{
        padding: 5px;
        background: #000000;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;  
        }

        #gracias{
        text-align: center; 
        }
    </style>
    <body>
        <header>
            <div id="logo">
                <img src="img/logo2.png" alt="incanatoIT" id="imagen">
            </div>
            <div id="datos">
                <p id="encabezado">
                    <b>EMPRESA SILMAR</b>
                    <br>José Gálvez 1368, Lambayeque - Chiclayo, Perú
                    <br>Telefono:(+51)931742904<br>
                    Email: empresa_silmar@gmail.com<br><br>
                    <u><i>Comprobante de vale</i></u>
                </p>
            </div>
            <div id="fact">
                <p style="font-size: 15px;">
                    @php
                        $date = new DateTime($venta['created_at']);
                        echo $date->format('d/m/Y');
                    @endphp
                </p>
            </div>
        </header>
        <br>
        <section>
            <div>
                <table id="facliente">
                    <thead>                        
                        <tr class="fa">
                            <th style="width: 17rem">A nombre</th>
                            <th style="width: 5rem">Monto</th>
                            <th style="width: 9rem">Fecha generado</th>
                            <th style="width: 6rem">Venta que la generó</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                @if($cliente['nombres'])
                                    <p >Sr(a). {{$cliente['nombres'] . ' ' . $cliente['apellidos']}}<br>
                                    DNI: {{$cliente['dni']}}<br>
                                    </p>
                                @elseif($cliente['razon_social'])
                                    <p >{{$cliente['razon_social']}}<br>
                                    RUC: {{$cliente['ruc']}}<br>
                                    </p>
                                @else
                                    ---
                                @endif
                            </td>
                            <td>
                                S/. {{$vale['monto']}}
                            </td>
                            @php
                                $date = new DateTime($vale['fecha']);
                            @endphp
                            <td>
                                Fecha: {{$date->format('d/m/Y')}}<br>
                                Hora: {{$date->format('h:i a')}}
                            </td>
                            <td>
                                Código: {{$venta['codigo']}}<br>
                                @php
                                    $date = new DateTime($venta['created_at']);
                                @endphp
                                Fecha: {{$date->format('d/m/Y h:i a')}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <hr>
    </body>
</html>