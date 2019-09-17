@extends('principal')

@section('contenidoPrincipal')
    <template v-if="menu==0">
        <example-component></example-component>
    </template>
    <template v-if="menu==1">
        <h1>Contenido de productos</h1>
    </template>
        

@endsection
