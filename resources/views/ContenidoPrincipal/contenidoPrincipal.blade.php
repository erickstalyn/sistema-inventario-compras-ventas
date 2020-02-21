@extends('principal')

@section('contenidoPrincipal')

    @if(Auth::check())
        @if(Auth::user()->getRol->id===1)
            <template v-if="menu==1">
                <adm_material></adm_material>
            </template>
            <template v-if="menu==2">
                <adm_superproducto></adm_superproducto>
            </template>
            <template v-if="menu==3">
                <adm_producto></adm_producto>
            </template>
            <template v-if="menu==4">
                <adm_abasto></adm_abasto>
            </template>
            <template v-if="menu==5">
                <adm_verpuesto></adm_verpuesto>
            </template>
            <template v-if="menu==6">
                <adm_veralmacen></adm_veralmacen>
            </template>
            <template v-if="menu==7">
                <adm_cliente></adm_cliente>
            </template>
            <template v-if="menu==10">
                <adm_usuario></adm_usuario>
            </template>
            <template v-if="menu==11">
                <adm_estadistica></adm_estadistica>
                {{-- <dashboard :num_mostrar="1"></dashboard> --}}
            </template>

        @elseif(Auth::user()->getRol->id===2)
            <template v-if="menu==1">
                <pue_producto></pue_producto>
            </template>
            <template v-if="menu==2">
                <pue_venta></pue_venta>
            </template>
            <template v-if="menu==3">
                <vale></vale>
            </template>
            <template v-if="menu==4">
                <enviorealizado></enviorealizado>
            </template>
            <template v-if="menu==5">
                <enviorecibido></enviorecibido>
            </template>

        @elseif(Auth::user()->getRol->id===3)
            <template v-if="menu==1">
                <alm_producto></alm_producto>
            </template>
            <template v-if="menu==2">
                <alm_produccion></alm_produccion>
            </template>
            <template v-if="menu==3">
                <enviorealizado></enviorealizado>
            </template>
            <template v-if="menu==4">
                <enviorecibido></enviorecibido>
            </template>
            <template v-if="menu==5">
                <pue_venta></pue_venta>
            </template>
            <template v-if="menu==6">
                <vale></vale>
            </template>
        @else

        @endif
    @endif

@endsection
