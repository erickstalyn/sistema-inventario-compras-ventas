@extends('principal')

@section('contenidoPrincipal')

    @if(Auth::check())
        @if(Auth::user()->rol=='M')
            <template v-if="menu==0">
                <adm-material></adm-material>
            </template>
            <template v-if="menu==1">
                <adm-superproducto></adm-superproducto>
            </template>
            <template v-if="menu==2">
                <adm-producto></adm-producto>
            </template>
            <template v-if="menu==3">
                <adm-abasto></adm-abasto>
            </template>
            <template v-if="menu==5">
                <adm-usuario></adm-usuario>
            </template>

        @elseif(Auth::user()->rol=='P')
            <template v-if="menu==0">
                <pue-producto></pue-producto>
            </template>

        @elseif(Auth::user()->rol=='A')
            <template v-if="menu==0">
                <alm-producto></alm-producto>
            </template>
            <template v-if="menu==1">
                <alm-produccion></alm-produccion>
            </template>
        @else

        @endif
    @endif

@endsection
