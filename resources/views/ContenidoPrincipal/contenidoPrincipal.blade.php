@extends('principal')

@section('contenidoPrincipal')

    @if(Auth::check())
        @if(Auth::user()->rol=='M')
            <template v-if="menu==0">
                <adm_material></adm_material>
            </template>
            <template v-if="menu==1">
                <adm_superproducto></adm_superproducto>
            </template>
            <template v-if="menu==2">
                <adm_producto></adm_producto>
            </template>
            <template v-if="menu==3">
                <adm_abasto></adm_abasto>
            </template>
            <template v-if="menu==5">
                <adm_verpuesto></adm_verpuesto>
            </template>
            <template v-if="menu==6">
                <adm_veralmacen></adm_veralmacen>
            </template>
            <template v-if="menu==10">
                <adm_usuario></adm_usuario>
            </template>

        @elseif(Auth::user()->rol=='P')
            <template v-if="menu==0">
                <pue_producto></pue_producto>
            </template>
            <template v-if="menu==1">
                <pue_venta></pue_venta>
            </template>
            <template v-if="menu==2">
                <enviorealizado></enviorealizado>
            </template>
            <template v-if="menu==3">
                <enviorecibido></enviorecibido>
            </template>

        @elseif(Auth::user()->rol=='A')
            <template v-if="menu==0">
                <alm_producto></alm_producto>
            </template>
            <template v-if="menu==1">
                <alm_produccion></alm_produccion>
            </template>
            <template v-if="menu==2">
                <enviorealizado></enviorealizado>
            </template>
            <template v-if="menu==3">
                <enviorecibido></enviorecibido>
            </template>
            <template v-if="menu==4">
                <pue_venta></pue_venta>
            </template>
        @else

        @endif
    @endif

@endsection
