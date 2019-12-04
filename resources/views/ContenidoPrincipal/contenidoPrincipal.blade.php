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
            <template v-if="menu==3">
                <produccion></produccion>
            </template>
            <template v-if="menu==6">
                <usuario></usuario>
            </template>

        @elseif(Auth::user()->rol=='P')


        @elseif(Auth::user()->rol=='A')
            <template v-if="menu==1">
                <produccion></produccion>
            </template>
        @else

        @endif
    @endif

@endsection
