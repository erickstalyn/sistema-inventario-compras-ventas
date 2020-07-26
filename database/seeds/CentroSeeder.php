<?php

use Illuminate\Database\Seeder;
use App\Centro;
use Carbon\Carbon;

class CentroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now('America/Lima')->toDateTimeString();

        $empresa_id = 1;
        Centro::create([
            'id' => 1,
            'empresa_id' => $empresa_id,
            'numero_serie' => '0001',
            'tipo' => 'P',
            'numeros_correlativos' => [
                'factura' => 1, 'boleta_venta' => 1, 'guia de remision' => 1, 'proforma' => 1
            ],
            'nombre' => 'SUCURSAL DE SAN JUAN DE LURIGANCHO',
            'tipo_anexo' => 'SUCURSAL',
            'ubigeo' => 'LIMA LIMA SAN JUAN DE LURIGANCHO',
            'domicilio' => 'URB. PARCELACION C AV. EL BOSQUE Mz C Lote 2A',
            'created_at' => $now,
        ], [
            'id' => 2,
            'empresa_id' => $empresa_id,
            'numero_serie' => '0002',
            'tipo' => 'P',
            'numeros_correlativos' => [
                'factura' => 1, 'boleta_venta' => 1, 'guia de remision' => 1, 'proforma' => 1
            ],
            'nombre' => 'LOCAL COMERCIAL DE LIMA',
            'tipo_anexo' => 'LOCAL COMERCIAL',
            'ubigeo' => 'LIMA LIMA LIMA',
            'domicilio' => 'CAMPO FERIAL AMAZONAS JR. AMAZONAS 299 Int 10',
            'created_at' => $now,
        ], [
            'id' => 3,
            'empresa_id' => $empresa_id,
            'numero_serie' => '0003',
            'tipo' => 'P',
            'numeros_correlativos' => [
                'factura' => 1, 'boleta_venta' => 1, 'guia de remision' => 1, 'proforma' => 1
            ],
            'nombre' => 'SUCURSAL DE NUEVA CAJAMARCA',
            'tipo_anexo' => 'SUCURSAL',
            'ubigeo' => 'SAN MARTIN RIOJA NUEVA CAJAMARCA',
            'domicilio' => 'MERCADO CENTRAL NUEVA CAJAMARCA AV. MARGINAL SN Int 159',
            'created_at' => $now,
        ]);

        $empresa_id = 2;
        Centro::create([
            'id' => 4,
            'empresa_id' => $empresa_id,
            'numero_serie' => '0001',
            'tipo' => 'A',
            'numeros_correlativos' => [
                'factura' => 1, 'boleta_venta' => 1, 'guia de remision' => 1, 'proforma' => 1
            ],
            'nombre' => 'SUCURSAL DE CHICLAYO',
            'tipo_anexo' => 'SUCURSAL',
            'ubigeo' => 'LAMBAYEQUE CHICLAYO CHICLAYO',
            'domicilio' => 'MERCADO MODELO SECCION PLATAFORMA ARICA 341A',
            'created_at' => $now,
        ], [
            'id' => 5,
            'empresa_id' => $empresa_id,
            'numero_serie' => '0002',
            'tipo' => 'A',
            'numeros_correlativos' => [
                'factura' => 1, 'boleta_venta' => 1, 'guia de remision' => 1, 'proforma' => 1
            ],
            'nombre' => 'LOCAL COMERCIAL DE LIMA',
            'tipo_anexo' => 'LOCAL COMERCIAL',
            'ubigeo' => 'LIMA LIMA LIMA',
            'domicilio' => 'U.V. PUESTO 1187 CERCADO DE LIMA JR. ANDAHUAYLAS 158',
            'created_at' => $now,
        ]);
    }
}
