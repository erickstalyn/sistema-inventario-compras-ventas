<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getEVentas(Request $request){

        $anio = date('Y');
        $ventas = DB::table('venta as v')->select(DB::raw('MONTH(v.created_at) as mes'), DB::raw('YEAR(v.created_at) as year'), DB::raw('COUNT(*) as num_ventas'))
                    ->whereYear('v.created_at', $anio)
                    ->groupBy(DB::raw('MONTH(v.created_at)'), DB::raw('YEAR(v.created_at)'))
                    ->get();
        return ['ventas' => $ventas];
    }
}
