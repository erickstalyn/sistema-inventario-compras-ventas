@extends('principal')

@section('contenidoPrincipal')

    @if(Auth::check())
        @if(Auth::user()->rol=='M')
            <template v-if="menu==0">
                <material></material>
            </template>
            <template v-if="menu==1">
                <categoria></categoria>
            </template>
            <template v-if="menu==2">
                <producto></producto>
            </template>
            <template v-if="menu==5">
                <usuario></usuario>
            </template>

        @elseif(Auth::user()->rol=='P')


        @elseif(Auth::user()->rol=='A')

        @else

        @endif
    @endif

@endsection
