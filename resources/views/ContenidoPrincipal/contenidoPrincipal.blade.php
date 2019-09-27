@extends('principal')

@section('contenidoPrincipal')

    @if(Auth::check())
        @if(Auth::user()->rol_id==1)
            <template v-if="menu==0">
                <categoria></categoria>
            </template>
            <template v-if="menu==1">
                <producto></producto>
            </template>
            <template v-if="menu==5">
                <usuario></usuario>
            </template>

        @elseif(Auth::user()->rol_id==2)


        @elseif(Auth::user()->rol_id==3)

        @else

        @endif
    @endif

@endsection
