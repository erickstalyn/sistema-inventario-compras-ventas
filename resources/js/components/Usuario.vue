<template>
    <div class="container-fluid">
        <div class="card">

            <div class="card-header">
                <i class="fa fa-align-justify"></i> Usuarios
                <button type="button" class="btn btn-success">
                    <i class="icon-plus"></i>&nbsp; Nuevo
                </button>
                <button type="button" class="btn btn-danger">
                    <i class="icon-picture"></i>&nbsp; PDF
                </button>
            </div>

            <div class="card-body">
                <div class="row form-group">
                    <div class="col-md-8">
                        <div class="input-group">
                            <select class="col-md-3 form-control">
                                <option value="A">Activados</option>
                                <option value="D">Desactivados</option>
                                <option value="T">Todos</option>
                            </select>
                            <input type="text" class="form-control">
                            <button type="button" class="btn btn-primary">
                                <i class="fa fa-search"></i>&nbsp; Buscar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Direccion</th>
                                <th>Usuario</th>
                                <th>Fecha de creacion</th>
                                <th>Fecha de actualizacion</th>
                                <th>Fecha de eliminacion</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <nav>
                    <ul class="pagination">
                        <li class="page-item">
                            <a href="#" class="page-link">Anterior</a>
                        </li>
                        <li class="page-item">
                            <a href="#" class="page-link"></a>
                        </li>
                        <li class="page-item">
                            <a href="#" class="page-link">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                'usuario': {
                    'id': 0,
                    'nombre': '',
                    'dni': '',
                    'direccion': '',
                    'telefono': '',
                    'email': '',
                    'birthday': '',
                    'tipo': '',
                }
            }
        },
        mounted() {
        }
    }
</script>

