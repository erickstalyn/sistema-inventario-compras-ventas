<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reporte de materiales</title>
        <style>
            body {
                margin: 0;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                font-size: 0.875rem;
                font-weight: normal;
                line-height: 1.5;
                color: #151b1e;           
            }
            .table {
                display: table;
                width: 100%;
                max-width: 100%;
                margin-bottom: 1rem;
                background-color: transparent;
                border-collapse: collapse;
            }
            .table-bordered {
                border: 1px solid #c2cfd6;
            }
            thead {
                display: table-header-group;
                vertical-align: middle;
                border-color: inherit;
            }
            tr {
                display: table-row;
                vertical-align: inherit;
                border-color: inherit;
            }
            .table th, .table td {
                padding: 0.75rem;
                vertical-align: top;
                border-top: 1px solid #c2cfd6;
            }
            .table thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #c2cfd6;
            }
            .table-bordered thead th, .table-bordered thead td {
                border-bottom-width: 2px;
            }
            .table-bordered th, .table-bordered td {
                border: 1px solid #c2cfd6;
            }
            th, td {
                display: table-cell;
                vertical-align: inherit;
            }
            th {
                font-weight: bold;
                text-align: -internal-center;
                text-align: left;
            }
            tbody {
                display: table-row-group;
                vertical-align: middle;
                border-color: inherit;
            }
            tr {
                display: table-row;
                vertical-align: inherit;
                border-color: inherit;
            }
            .table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(0, 0, 0, 0.05);
            }
            .izquierda{
                float:left;
            }
            .derecha{
                float:right;
            }
        </style>
    </head>
    <body>
        <div>
        <h3>Lista de Abastecimientos <span class="derecha" id="fecha">{{date("d/m/Y")}}</span></h3>
        </div>
        <div>
            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>Proveedor</th>
                        <th >Almacén de destino</th>
                        <th>Fecha de envío</th>
                        <th>Costo total</th>
                        <th>Tipo</th>
                        <th>Estado de envío</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($abasto as $aba)
                    <tr>
                        <td >
                            @if($aba->proveedor_persona)
                                {{$aba->proveedor_persona}}
                            @else
                                {{$aba->proveedor_empresa}}
                            @endif
                        </td>
                        <td >
                            {{$aba->nombre_centro}}
                        </td>
                        <td >
                            @php
                                $date = new DateTime($aba->fecha_envio);
                                echo $date->format('d-m-Y');
                            @endphp
                        </td>
                        <td >
                            {{$aba->total}}
                        </td>
                        <td >
                            @if($aba->tipo_abasto)
                                <span >Crédito</span>
                            @else
                                <span >Contado</span>
                            @endif
                        </td>
                        <td >
                            @if($aba->estado_envio == 0)
                                <span >Enviado</span>
                            @elseif($aba->estado_envio == 1)
                                <span >Aceptado</span>
                            @else
                                <span >Rechazado</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach                                
                </tbody>
            </table>
        </div>
        <div class="izquierda">
            <p><strong>Total de registros: </strong>{{$cont}}</p>
        </div>
    </body>
</html>