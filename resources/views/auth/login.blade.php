@extends('auth.contenido')

@section('login')
<div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card-group mb-0">
        <div class="card p-4">
          
        <form class="form-horizontal was-validated" action="{{route('login_post')}}" method="post" autocomplete="off">
          {{ csrf_field()}}
          <div class="card-body">
            <!-- Prueba-->
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
              <div class="alert-text">
                  @foreach($errors->all() as $error)
                    <span>{{ $error }}</span>
                  @endforeach
              </div>
              </div>
            @endif
          <!-- Prueba-->
            <h1 class="text-dark">Acceder</h1>
            <p class="text-muted">Control de acceso al sistema</p>
            
            <div class="form-group mb-3">
              <span class="input-group-addon"><i class="icon-user"></i></span>
              <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Usuario" value="{{old('usuario')}}" autofocus>
            </div>
            
            <div class="form-group mb-4">
              <span class="input-group-addon"><i class="icon-lock"></i></span>
              <input type="password" name="password" id="clave" class="form-control" placeholder="Clave">
            </div>
            <div class="row">
              <div class="col-6">
                <button type="submit" class="btn btn-primary px-4">Acceder</button>
              </div>
            </div>
          </div>
        </form>
        </div>
        <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
          <div class="card-body text-center">
            <div>
              <h3>Universidad Nacional Pedro Ruiz Gallo</h3>
              <h4 class="mb-4">Kardex | Grupo 04</h4>
              <ul class="">
                <li class="mb-1">Baldera Cardozo Jhordy Benjamín</li>
                <li class="mb-1">Céspedes Días José Anderson</li>
                <li class="mb-1">Chapoñan Villalobos Areliz Verania</li>
                <li class="mb-1">Pacherrez Puyén Erick Stalyn</li>
                <li class="mb-1">Purihuaman Nicolás Mercy Jadira</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

