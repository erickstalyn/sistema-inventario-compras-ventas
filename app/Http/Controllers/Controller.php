<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\DetalleMovimiento;
use DateTime;
use DB;
use App\Detalle_abasto;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test()
    {
        $fecha_inicial = '2020-06-01';
        $fecha_final = '2020-10-31';
        $detalle_producto_id = 1;

        $fecha = 'CONCAT(YEAR(fecha), "-", LPAD(MONTH(fecha), 2, "0"), "-01")';

        $list = DetalleMovimiento::select(DB::raw("$fecha fecha"), 'descripcion', DB::raw('SUM(ingreso) ingreso_total'), DB::raw('SUM(egreso) egreso_total'))
                                    ->where(DB::raw($fecha), '>=', $fecha_inicial)
                                    ->where(DB::raw($fecha), '<=', $fecha_final)
                                    // ->where(DB::raw('YEAR(fecha)'), '>=', intval((new DateTime($fecha_inicial))->format('n')))
                                    // ->where(DB::raw('MONTH(fecha)'), '>=', intval((new DateTime($fecha_inicial))->format('n')))
                                    // ->where(DB::raw('YEAR(fecha)'), '<=', intval((new DateTime($fecha_final))->format('n')))
                                    // ->where(DB::raw('MONTH(fecha)'), '<=', intval((new DateTime($fecha_final))->format('n')))
                                    ->where('detalle_producto_id', $detalle_producto_id)
                                    // ->groupBy(DB::raw('YEAR(fecha)'), DB::raw('MONTH(fecha)'), 'descripcion', 'tipo')
                                    ->groupBy(DB::raw($fecha), 'descripcion')
                                    ->orderBy(DB::raw($fecha), 'asc')->get();


        // $detalle = Detalle_abasto::join('abasto AS A', 'A.id', '=', 'detalle_abasto.abasto_id')
        //                             ->join('envio AS E', 'E.abasto_id', '=', 'A.id')
        //                             ->join('detalle_producto AS DP', 'DP.producto_id', '=', 'detalle_abasto.producto_id')
        //                             ->select('DP.id AS detalle_producto_id', 'detalle_abasto.cantidad', 'E.updated_at', 'DP.substock')
        //                             ->whereRaw('E.updated_at IS NOT NULL AND DP.centro_id = E.centro_to_id')
        //                             ->where('detalle_abasto.id', '=', 1)->first();


        dd($list);
    }
}
