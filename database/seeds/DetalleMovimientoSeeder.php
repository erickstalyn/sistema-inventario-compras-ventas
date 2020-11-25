<?php

use Illuminate\Database\Seeder;
use App\Detalle_abasto;
use App\DetalleMovimiento;
use App\Detalle_producto;
use App\Envio;

class DetalleMovimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $detalles_abasto = Detalle_abasto::join('abasto AS A', 'A.id', '=', 'detalle_abasto.abasto_id')
                                        ->join('envio AS E', 'E.abasto_id', '=', 'A.id')
                                        ->select('detalle_abasto.id')
                                        ->whereRaw('E.updated_at IS NOT NULL')
                                        ->orderBy('E.updated_at', 'asc')->get();

        foreach ($detalles_abasto as $detalle_abasto) {
            $detalle = Detalle_abasto::join('abasto AS A', 'A.id', '=', 'detalle_abasto.abasto_id')
                                    ->join('envio AS E', 'E.abasto_id', '=', 'A.id')
                                    ->join('detalle_producto AS DP', 'DP.producto_id', '=', 'detalle_abasto.producto_id')
                                    ->select('DP.id AS detalle_producto_id', 'detalle_abasto.cantidad', 'E.updated_at', 'DP.substock')
                                    ->whereRaw('E.updated_at IS NOT NULL AND DP.centro_id = E.centro_to_id')
                                    ->where('detalle_abasto.id', '=', $detalle_abasto->id)->first();

            DetalleMovimiento::create([
                'detalle_producto_id' => $detalle->detalle_producto_id,
                'descripcion' => 'Compra al proveedor',
                'stock_old' => $detalle->substock,
                'ingreso' => $detalle->cantidad,
                'stock_new' => $detalle->cantidad + $detalle->substock,
                'fecha' => $detalle->updated_at
            ]);
        }
    }
}
