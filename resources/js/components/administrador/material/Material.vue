<template>
  <main>
    <!-- Interfaz Principal -->
    <div class="container-small">
      <!-- Encabezado principal -->
      <div class="row form-group">
        <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
        <span class="h3 mb-0 text-gray-900">Materiales&nbsp;</span>
        <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
          <i class="fas fa-hammer"></i>&nbsp; Nuevo
        </button>&nbsp;
        <button type="button" class="btn btn-danger" @click="generatePdf()">
          <i class="far fa-file-pdf"></i>&nbsp; PDF
        </button>
      </div>

      <!-- Inputs de busqueda -->
      <div class="row form-group">
        <div class="col-md-8">
          <div class="input-group">
            <!-- <select class="col-md-3 custom-select text-gray-900" v-model="Busqueda.estado" @change="listar()">
                            <option value="2">Todos</option>
                            <option value="1">Activados</option>
                            <option value="0">Desactivados</option>
            </select>-->
            <input
              type="search"
              class="form-control"
              v-model="Busqueda.texto"
              placeholder="Buscar por NOMBRE"
              @keyup.enter="listar()"
            />
            <button type="button" class="btn btn-primary" @click="listar()">
              <i class="fa fa-search"></i>&nbsp; Buscar
            </button>
          </div>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-1" align="right">
          <label>N° filas:</label>
        </div>
        <div class="col-md-1">
          <select
            class="custom-select custom-select-sm text-gray-900"
            v-model="Busqueda.filas"
            @change="listar()"
          >
            <option
              v-for="item in [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17]"
              :key="item"
              :value="item"
              v-text="item"
            ></option>
          </select>
        </div>
      </div>

      <!-- Listado -->
      <div v-if="ListaMaterial.length" class="table-responsive">
        <!-- Tabla -->
        <div class="ec-table overflow-auto">
          <table class="table table-borderless table-sm text-gray-900">
            <thead>
              <tr class="table-info">
                <th
                  v-for="head in ['Nombre', 'Unid. Medida', 'Costo Unit.', 'Opciones']"
                  :key="head"
                  v-text="head"
                ></th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(material, i) in ListaMaterial"
                :key="i"
                is="material-row"
                :material="material"
                @edit-material="abrirModalEditar"
              ></tr>
            </tbody>
          </table>
        </div>
        <!-- Barra de navegacion -->
        <pagination-bar @list="listar" :paginacion="Paginacion"></pagination-bar>
      </div>
      <div v-else>
        <h5>No se han encontrado resultados</h5>
      </div>
    </div>

    <!-- Modales -->
    <my-modal @list="listar" :the-modal.sync="Modal" :the-material="Material"></my-modal>
    
  </main>
</template>

<script>
/** Components */
import MaterialRow from "./subcomponents/Material-row";
import PaginationBar from "./subcomponents/Pagination-bar";
import myModal from "./subcomponents/modal";

export default {
  components: {
    MaterialRow,
    PaginationBar,
    myModal
  },
  data() {
    return {
      //datos generales
      ListaMaterial: [],
      Material: {
        id: 0,
        nombre: "",
        subtipo: "",
        unidad: "",
        costo: ""
      },
      MaterialOrigen: {
        id: 0,
        nombre: "",
        subtipo: "",
        unidad: "",
        costo: "",
      },
      SelectUnidad: [],
      SelectTipoFiltrado: [],
      YaIngrese: 0,
      //datos de busqueda y filtracion
      Busqueda: {
        texto: "",
        estado: 2,
        filas: 5,
      },

      //datos de modales
      Modal: {
        numero: 0,
        estado: 0,
        titulo: "",
        accion: "",
      },
      //datos de paginacion
      Paginacion: {
        total: 0,
        currentPage: 0,
        lastPage: 0,
        perPage: 0,
        firstItem: 0,
        lastItem: 0,
      },
      //datos de errores
      // Error: {
      //   estado: 0,
      //   mensaje: [],
      // },
      Ruta: {
        material: "/material",
        data: "/data",
        serverPhp: "http://127.0.0.1:8000",
      },
    };
  },
  computed: {
    selectUnidadFiltrado: function () {
      // console.log('Soy el computado selectUnidadFiltrado');
      let selectUnidadFiltrado = [];
      // console.log('tamaño de SelectUnidad: ' + this.SelectUnidad.length);
      if (this.SelectUnidad.length) {
        this.SelectUnidad.forEach((unidad) => {
          if (unidad.subtipo == this.Material.subtipo) {
            selectUnidadFiltrado.push(unidad);
          }
        });

        if (this.Modal.numero == 1 || this.YaIngrese) {
          //Es moda nuevo o ya ingrese
          this.Material.unidad = "";
        } else {
          this.YaIngrese = 1;
        }
        return selectUnidadFiltrado;
      }
    },
  },
  methods: {
    listar(page = 1) {
      this.Paginacion.currentPage = page;
      const url =
        this.Ruta.material +
        "?page=" +
        this.Paginacion.currentPage +
        "&estado=" +
        this.Busqueda.estado +
        "&texto=" +
        this.Busqueda.texto +
        "&filas=" +
        this.Busqueda.filas;
      const me = this;
      axios
        .get(url)
        .then(function (response) {
          me.ListaMaterial = response.data.materiales.data;
          me.Paginacion = response.data.paginacion;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    editar() {
      if (this.validar()) return;

      var me = this;
      axios
        .put("/material/editar", {
          id: this.Material.id,
          nombre: this.Material.nombre,
          subtipo: this.Material.subtipo,
          unidad: this.Material.unidad,
          costo: this.Material.costo,
        })
        .then(function (response) {
          if (response.data.estado) {
            me.cerrarModal();
            me.listar();
            Swal.fire({
              position: "top-end",
              toast: true,
              type: "success",
              title: "El Material se ha EDITADO correctamente",
              showConfirmButton: false,
              timer: 4500,
              animation: false,
              customClass: {
                popup: "animated bounceIn fast",
              },
            });
          } else {
            me.Error.mensaje.push("Este material ya está registrado");
            me.Error.estado = 1;
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    activar(material = []) {
      this.Material.id = material["id"];
      this.Material.nombre = material["nombre"];

      Swal.fire({
        title:
          '¿Esta seguro de ACTIVAR el material "' + this.Material.nombre + '"?',
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
        // reverseButtons: true,

        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-secondary",
        },
        buttonsStyling: false,
      }).then((result) => {
        if (result.value) {
          var me = this;

          axios
            .put("/material/activar", {
              id: me.Material.id,
            })
            .then(function (response) {
              me.listar();
              Swal.fire({
                position: "top-end",
                toast: true,
                type: "success",
                title: "El material se ha ACTIVADO correctamente",
                showConfirmButton: false,
                timer: 4500,
                animation: false,
                customClass: {
                  popup: "animated bounceIn fast",
                },
              });
            })
            .catch(function (error) {
              console.log(error);
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
        }
      });
    },
    desactivar(material = []) {
      this.Material.id = material["id"];
      this.Material.nombre = material["nombre"];

      Swal.fire({
        title:
          '¿Esta seguro de DESACTIVAR el material "' +
          this.Material.nombre +
          '"?',
        type: "error",
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
        // reverseButtons: true,
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-secondary",
        },
        buttonsStyling: false,
      }).then((result) => {
        if (result.value) {
          var me = this;

          axios
            .put("/material/desactivar", {
              id: me.Material.id,
            })
            .then(function (response) {
              me.listar();
              Swal.fire({
                position: "top-end",
                toast: true,
                type: "success",
                title: "El material se ha DESACTIVADO correctamente",
                showConfirmButton: false,
                timer: 4500,
                animation: false,
                customClass: {
                  popup: "animated bounceIn fast",
                },
              });
            })
            .catch(function (error) {
              console.log(error);
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
        }
      });
    },
    abrirModalAgregar() {
      this.abrirModal(1, "Nuevo Material", "Agregar");

      //Verifico si el arreglo SelectUnidad esta vacia
      // if (!this.SelectUnidad.length) this.selectUnidad();
      //Verifico si el arreglo SelectTipoFiltro esta vacia
      // if(!this.SelectTipoFiltrado.length) this.selectTipoFiltrado();
      // this.selectTipoFiltrado();
    },
    abrirModalEditar(data = []) {
      this.abrirModal(2, "Editar Material", "Editar");

      this.Material.id = data["id"];
      this.Material.nombre = data["nombre"];
      this.Material.subtipo = data["subtipo"];
      this.Material.unidad = data["unidad"];
      this.Material.costo = data["costo"];

      //Lleno los campos de mi Material Original
      this.MaterialOrigen.id = data["id"];
      this.MaterialOrigen.nombre = data["nombre"];
      this.MaterialOrigen.subtipo = data["subtipo"];
      this.MaterialOrigen.unidad = data["unidad"];
      this.MaterialOrigen.costo = data["costo"];

      //Verifico si el arreglo SelectUnidad esta vacia
      // if (!this.SelectUnidad.length) this.selectUnidad();
      //Verifico si el arreglo SelectTipoFiltro esta vacia
      // if(!this.SelectTipoFiltrado.length) this.selectTipoFiltrado();
    },
    abrirModal(numero, titulo, accion) {
      this.Modal.estado = 1;
      this.Modal.numero = numero;
      this.Modal.titulo = titulo;
      this.Modal.accion = accion;
    },
    // cerrarModal() {
    //   this.Modal.numero = 0;
    //   this.Modal.estado = 0;
    //   this.Modal.mensaje = [];

    //   this.Error.estado = 0;
    //   this.Error.mensaje = [];

    //   this.Material.id = 0;
    //   this.Material.nombre = "";
    //   this.Material.unidad = "";
    //   this.Material.subtipo = "";
    //   this.Material.costo = "";

    //   this.Button.press = false;

    //   this.YaIngrese = 0;
    // },
    // accionar(accion) {
    //   switch (accion) {
    //     case "Agregar": {
    //       this.agregar();
    //       break;
    //     }
    //     case "Editar": {
    //       this.editar();
    //       break;
    //     }
    //     case "Activar": {
    //       this.activar();
    //       break;
    //     }
    //     case "Desactivar": {
    //       this.desactivar();
    //       break;
    //     }
    //   }
    // },
    // validar() {
    //   this.Error.estado = 0;
    //   this.Error.mensaje = [];

    //   //Recorrere la lista de Material
    //   if (this.Modal.numero == 1) {
    //     //Modal agregar
    //     if (!this.Material.nombre)
    //       this.Error.mensaje.push("Debe ingresar un nombre");
    //     if (!this.Material.unidad)
    //       this.Error.mensaje.push("Debe seleccionar una Unid. Medida");
    //     if (this.Material.costo == 0 || this.Material.costo < 0)
    //       this.Error.mensaje.push("Debe ingresar un costo válido");
    //   } else {
    //     //Modal editar
    //     if (
    //       this.Material.nombre == this.MaterialOrigen.nombre &&
    //       this.Material.subtipo == this.MaterialOrigen.subtipo &&
    //       this.Material.unidad == this.MaterialOrigen.unidad &&
    //       this.Material.costo == this.MaterialOrigen.costo
    //     ) {
    //       this.Error.mensaje.push("Ningun cambio realizado");
    //     } else {
    //       if (!this.Material.nombre)
    //         this.Error.mensaje.push("Debe ingresar un nombre");
    //       if (!this.Material.unidad)
    //         this.Error.mensaje.push("Debe seleccionar una Unid. Medida");
    //       if (this.Material.costo == 0 || this.Material.costo < 0)
    //         this.Error.mensaje.push("Debe ingresar un costo válido");
    //     }
    //   }

    //   if (this.Error.mensaje.length) {
    //     this.Error.estado = 1;
    //     this.Button.press = false;
    //   }
    //   return this.Error.estado;
    // },
    // cambiarPagina(page) {
    //   if (page >= 1 && page <= this.Paginacion.lastPage) {
    //     this.listar(page);
    //   }
    // },
    // selectUnidad() {
    //   const me = this;
    //   const url = this.Ruta.data + "/selectUnidad";
    //   axios
    //     .get(url)
    //     .then(function (response) {
    //       me.SelectUnidad = response.data;
    //     })
    //     .then(function () {
    //       me.selectTipoFiltrado();
    //     })
    //     .catch(function (error) {
    //       console.log(error);
    //     });
    // },
    // selectTipoFiltrado() {
    //   // let selectTipoFiltrado = [];
    //   // console.log("Ingrese al metodo selectTipoFiltrado");
    //   // console.log("tamaño del SelectUnidad " + this.SelectUnidad.length);
    //   this.SelectUnidad.forEach((unidad) => {
    //     // console.log('Ingrese el foreach');
    //     if (!this.SelectTipoFiltrado.includes(unidad.subtipo)) {
    //       // console.log('ingrese al if del metodo selectTipoFiltrado');
    //       this.SelectTipoFiltrado.push(unidad.subtipo);
    //     }
    //   });
    //   // return selectTipoFiltrado;
    // },
    generatePdf() {
      window.open(this.Ruta.serverPhp + "/material/generatePdf", "_blank");
      this.Button.press = false;
    },
  },
  mounted() {
    this.listar();
  },
};
</script>

<style>
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}
.ec-cursor {
  cursor: pointer;
}
.ec-table {
  overflow: scroll;
  height: 20rem;
}
.ec-th {
  background-color: skyblue;
}
</style>

