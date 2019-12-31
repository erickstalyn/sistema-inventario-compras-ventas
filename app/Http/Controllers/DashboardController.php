<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getEVentas(Request $request){

        $anio = date('Y');
        // $ingresos = DB::table('ingresos as i')->select(DB::raw('MONTH(i.fecha_hora) as mes'), DB::raw('YEAR(i.fecha_hora) as anio'), DB::raw('SUM(i.total) as total'))
        //             ->whereYear('i.fecha_hora', $anio)
        //             ->groupBy(DB::raw('MONTH(i.fecha_hora)'), DB::raw('YEAR(i.fecha_hora)'))
        //             ->get();

        $ventas = DB::table('venta as v')->select(DB::raw('MONTH(v.created_at) as mes'), DB::raw('YEAR(v.created_at) as anvo'), DB::raw('SUM(v.total) as total'))
                    ->whereYear('v.created_at', $anio)
                    ->groupBy(DB::raw('MONTH(v.created_at)'), DB::raw('YEAR(v.created_at)'))
                    ->get();
        return ['ventas' => $ventas];
    }
}
