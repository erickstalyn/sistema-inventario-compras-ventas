<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de abasto</title>
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

        #fac, #fv, #fa{
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

        #facarticulo{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facarticulo thead{
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
        @foreach($abasto as $aba)
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
                    <u><i>Reporte de abastecimiento</i></u>
                </p>
            </div>
            <div id="fact">
                <p style="font-size: 15px;">
                    {{-- @php
                        $date = new DateTime($aba->fecha_envio);
                        echo $date->format('d/m/Y');
                    @endphp --}}
                    {{date("d/m/Y")}}
                </p>
            </div>
        </header>
        <br>
        <section>
            <div>
                <table id="facliente">
                    <thead>                        
                        <tr id="fa">
                            <th style="width: 18rem">Proveedor</th>
                            <th style="width: 8rem">Alm. Dest.</th>
                            <th style="width: 8rem">Fec. Envío</th>
                            <th style="width: 8rem">Est. Envio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                @if($aba->dni)
                                    <p >Sr(a). {{$aba->proveedor_persona}}<br>
                                    DNI: {{$aba->dni}}<br>
                                    </p>
                                @else
                                    <p >Empresa: {{$aba->proveedor_empresa}}<br>
                                    RUC: {{$aba->ruc}}<br>
                                    </p>
                                @endif
                            </td>
                            <td>
                                {{$aba->nombre_centro}}
                            </td>
                            <td>
                                @php
                                    $date = new DateTime($aba->fecha_envio);
                                    echo $date->format('d/m/Y');
                                @endphp
                            </td>
                            <td>
                                @if($aba->estado_envio == 0)
                                    Enviado
                                @elseif($aba->estado_envio == 1)
                                    Aceptado
                                @else
                                    Rechazado
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                {{-- <p style="font-size: 15px;">Reporte de abastecimiento
                    <br>
                        @php
                            $date = new DateTime($aba->fecha_envio);
                            echo $date->format('d/m/Y');
                        @endphp
                    <br>
                </p> --}}
            </div>
        </section>
        {{-- <section>
            <div>
                <p style="font-size: 15px;">Reporte de abastecimiento
                    <br>
                        @php
                            $date = new DateTime($aba->fecha_envio);
                            echo $date->format('d/m/Y');
                        @endphp
                    <br>
                </p>
            </div>
        </section> --}}
        @endForeach
        <br>
        <section>
            <div>
                <table id="facarticulo">
                    <thead>
                        <tr id="fa">
                            <th>Nombre</th>
                            <th>Cant.</th>
                            <th>P. unit.</th>
                            <th style="padding-left: 23px">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detalles as $det)
                        <tr>
                            <td >{{$det->nombre_producto}}</td>
                            <td >{{$det->cantidad}}</td>
                            <td >{{$det->costo_abasto}}</td>
                            <td style="padding-left: 15px">{{$det->subtotal}}</td>
                        </tr>
                        @endForeach
                    </tbody>
                    <tfoot>
                        @foreach($abasto as $aba)
                        <tr>
                            <th></th>
                            <th></th>
                            <th>TOTAL</th>
                            <td>s/ {{$aba->total}}</td>
                        </tr>
                        @endForeach
                    </tfoot>
                </table>
            </div>
        </section>
        {{-- <br>
        <br>
        <footer>
            <div id="gracias">
                <p><b>Gracias por su compra!</b></p>
            </div>
        </footer> --}}
    </body>
</html>