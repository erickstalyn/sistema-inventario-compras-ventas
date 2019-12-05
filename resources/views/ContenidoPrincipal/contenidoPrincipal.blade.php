@extends('principal')

@section('contenidoPrincipal')

    @if(Auth::check())
        @if(Auth::user()->rol=='M')
            <template v-if="menu==0">
                <material></material>
            </template>
            <template v-if="menu==1">
                <superproducto></superproducto>
            </template>
            <template v-if="menu==2">
                <producto></producto>
            </template>
            <template v-if="menu==5">
                <usuario></usuario>
            </template>

        @elseif(Auth::user()->rol=='P')
            <template v-if="menu==0">
                <producto></producto>
            </template>

        @elseif(Auth::user()->rol=='A')
            <template v-if="menu==0">
                <producto></producto>
            </template>
            <template v-if="menu==1">
                <produccion></produccion>
            </template>
        @else

        @endif
    @endif

@endsection
