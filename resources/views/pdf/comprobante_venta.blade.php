<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comprobante de venta</title>
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
        @foreach($venta as $ven)
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
                    {{date("d/m/Y")}}
                </p>
                <span style="font-size: 15px;">
                    {{-- @if(substr($ven->tipo, 0, 1) == '1')
                        Tipo: Contado
                    @else
                        Tipo: Crédito
                    @endif --}}
                </span>
                {{-- <span style="font-size: 15px;">
                    @if(substr($ven->tipo, 1) == '1')
                        Tipo entrega: Postpago
                    @else
                        Tipo entrega: Prepago
                    @endif
                </span> --}}
            </div>
        </header>
        <span>Codigo: {{$ven->codigo}}</span>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span>
            @if(substr($ven->tipo, 0, 1) == '1')
                Tipo pago: Contado
            @else
                Tipo pago: Crédito
            @endif
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            @if(substr($ven->tipo, 1, 1) == '1')
                Tipo entrega: Prepago
            @else
                Tipo entrega: Postpago
            @endif
        </span>
        <br>
        <br>
        <section>
            <div>
                <table id="facliente">
                    <thead>                        
                        <tr class="fa">
                            <th style="width: 18rem">Cliente</th>
                            <th style="width: 8rem">Beneficio</th>
                            <th style="width: 8rem">Descuentos</th>
                            <th style="width: 8rem">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                @if($ven->nombres)
                                    <p >Sr(a). {{$ven->nombres . '' . $ven->apellidos}}<br>
                                    DNI: {{$ven->dni}}<br>
                                    </p>
                                @elseif($ven->razon_social)
                                    <p >{{$ven->razon_social}}<br>
                                    RUC: {{$ven->ruc}}<br>
                                    </p>
                                @else
                                    ------
                                @endif
                            </td>
                            <td style="font-size:13px;">
                                -- Vale Generado -- <br>
                                @php
                                    $date = new DateTime($ven->vale_generada_created_at);
                                @endphp
                                Monto: s/. {{$ven->vale_generada_monto}} <br>
                                Fecha: {{$date->format('d/m/Y')}} <br>
                                Hora: {{$date->format('h:i a')}}
                            </td>
                            <td>
                                @if($ven->vale_usada_created_at)
                                    -- Vale Usado -- <br>
                                    @php
                                        $date = new DateTime($ven->vale_usada_created_at);
                                    @endphp
                                    Monto: s/. {{$ven->vale_usada_monto}} <br>
                                    Fecha: {{$date->format('d/m/Y')}} <br>
                                    Hora: {{$date->format('h:i a')}}
                                @endif
                            </td>
                            <td>
                                @php
                                    $date = new DateTime($ven->created_at);
                                    echo $date->format('d/m/Y h:i a');
                                @endphp
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        @endForeach
        <br>
        <section>
            <div>
                <table class="facarticulo">
                    <thead>
                        <tr class="fa">
                            <th style="width: 20rem">Nombre</th>
                            <th>Fallidos</th>
                            <th>Cantidad</th>
                            <th style="padding-right: 35px; text-align: right;">P. unit.</th>
                            <th style="padding-right: 35px; text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detalles as $det)
                        @php
                            $element = $det->detalle
                        @endphp
                        <tr>
                            <td >{{$element->nombre_producto}}</td>
                            <td >{{$element->cantidad_fallido == null ? '---': $element->cantidad_fallido}}</td>
                            <td >{{$element->cantidad}}</td>
                            <td style="padding-right: 35px; text-align: right;">{{$element->precio}}</td>
                            <td style="padding-right: 35px; text-align: right;">{{$element->subtotal}}</td>
                        </tr>
                        @endForeach
                    </tbody>
                    <tfoot>
                        @foreach($venta as $ven)
                        <tr>
                            <th></th>
                            <th></th>
                            {{-- <th></th> --}}
                            <th colspan="2" style="padding-right: 35px; text-align: right;">TOTAL VENTA</th>
                            <td style="padding-right: 35px; text-align: right;">s/ {{$ven->total_venta}}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            {{-- <th></th> --}}
                            <th colspan="2" style="padding-right: 35px; text-align: right;">TOTAL DESCUENTO</th>
                            <td style="padding-right: 35px; text-align: right;">s/ {{$ven->total_descuento ? $ven->total_descuento : 0}}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            {{-- <th></th> --}}
                            <th colspan="2" style="padding-right: 35px; text-align: right;">TOTAL</th>
                            <td style="padding-right: 35px; text-align: right;">s/ {{$ven->total}}</td>
                        </tr>
                        @endForeach
                    </tfoot>
                </table>
            </div>
        </section>
        <hr>
        <br>
        <footer>
            <div id="gracias">
                <p><b>Gracias por su compra!</b></p>
            </div>
        </footer>
        @if(substr($ven->tipo, 0, 1) == '2')
            Lista de pagos realizados
            <br><br>
            <section>
                <div>
                    @if(count($pagos))
                        <table class="pagos">
                            <thead>
                                <tr class="fa">
                                    <th style="padding-right: 10px; text-align: right;">#</th>
                                    <th style="padding-right: 20px; text-align: right;">Fecha de pago</th>
                                    <th style="padding-right: 23px; text-align: right;">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pagos as $pag)
                                    <tr>
                                        <td style="padding-right: 10px; text-align: right;">{{$loop->index + 1}}</td>
                                        <td style="padding-right: 20px; text-align: right;">
                                            @php
                                                $date = new DateTime($pag->created_at);
                                                echo $date->format('d/m/Y h:i a');
                                            @endphp
                                        </td>
                                        <td style="padding-right: 23px; text-align: right;">{{$pag->monto}}</td>
                                    </tr>
                                @endForeach
                            </tbody>
                            <tfoot>
                                @foreach($venta as $ven)
                                    @if($ven->total_faltante != 0)
                                        <tr>
                                            <th></th>
                                            <th style="padding-right: 20px; text-align: right;">Monto pagado: </th>
                                            <td style="padding-right: 23px; text-align: right;">s/ {{ number_format($ven->total - $ven->total_faltante, 2, '.', '') }}</td>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th style="padding-right: 20px; text-align: right;">Monto faltante: </th>
                                            <td style="padding-right: 23px; text-align: right;">s/ {{$ven->total_faltante}}</td>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th style="padding-right: 20px; text-align: right;">Costo total: </th>
                                            <td style="padding-right: 23px; text-align: right;">s/ {{$ven->total}}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td></td>
                                            <td style="padding-right: 23px; text-align: center;" colspan="2">-- PAGADO COMPLETAMENTE --</td>
                                        </tr>
                                    @endif
                                @endForeach
                            </tfoot>
                        </table>
                    @else
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-- Ningún pago registrado --
                    @endif
                </div>
            </section>
        @endif
    </body>
</html>