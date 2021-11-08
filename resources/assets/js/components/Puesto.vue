<template>
  <main class="main">
    <!-- Breadcrumb -->
    
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <div class="row justify-content-between">
          <h3>Puestos </h3>
          <button
            type="button"
            @click="abrirModal('puesto', 'registrar')"
            class="btn btn-secondary btn-sm"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
          </div>
        </div>
        <div class="card-body">
          <!-- <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                  <option value="nombre">Nombre</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  @keyup.enter="listarTabla(1, buscar, criterio)"
                  class="form-control"
                  placeholder="Texto a buscar"
                />
                <button
                  type="submit"
                  @click="listarTabla(1, buscar, criterio)"
                  class="btn btn-primary"
                >
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div> -->
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Sueldo Basico</th>
                <th>Departamento</th>
                <th>Condición</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="puesto in arrayPuestos" :key="puesto.id">
               
                <td v-text="puesto.nombre"></td>
                <td v-text="puesto.sueldoBasico"></td>
                <td v-text="puesto.nombreDepartamento"></td>
                <td>
                  <div v-if="puesto.condicion">
                    <span class="badge badge-success">Activo</span>
                  </div>
                  <div v-else>
                    <span class="badge badge-danger">Inactivo</span>
                  </div>
                </td>
                <td>
                  <button
                    type="button"
                    @click="abrirModal('puesto', 'actualizar', puesto)"
                    class="btn btn-dark btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button>
                  &nbsp;
                  <template v-if="puesto.condicion">
                    <button
                      type="button"
                      class="btn btn-dark btn-sm"
                      @click="desactivarPuesto(puesto.id)"
                    >
                      <i class="icon-trash"></i>
                    </button>
                  </template>
                  <template v-else>
                    <button
                      type="button"
                      class="btn btn-dark btn-sm"
                      @click="activarPuesto(puesto.id)"
                    >
                      <i class="icon-check"></i>
                    </button>
                  </template>
                </td>
              </tr>
            </tbody>
          </table>
          <nav>
            <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page > 1">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="
                    cambiarPagina(pagination.current_page - 1, buscar, criterio)
                  "
                  >Ant</a
                >
              </li>
              <li
                class="page-item"
                v-for="page in pagesNumber"
                :key="page"
                :class="[page == isActived ? 'active' : '']"
              >
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(page, buscar, criterio)"
                  v-text="page"
                ></a>
              </li>
              <li
                class="page-item"
                v-if="pagination.current_page < pagination.last_page"
              >
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="
                    cambiarPagina(pagination.current_page + 1, buscar, criterio)
                  "
                  >Sig</a
                >
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div
      class="modal fade"
      tabindex="-1"
      :class="{ mostrar: modal }"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button
              type="button"
              class="close"
              @click="cerrarModal()"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form
              id="modal-form"
              action=""
              method="post"
              enctype="multipart/form-data"
              class="form-horizontal"
            >
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Departamentos (*)</label
                >
                <div class="col-md-9">
                <select class="form-control" v-model="departamento_id">
                  <option value="0" disabled>Seleccione</option>
                  <option
                    v-for="departamento in arrayDepartamento"
                    :key="departamento.id"
                    :value="departamento.id"
                    v-text="departamento.nombre"
                  ></option>
                </select>
              </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Nombre (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre"
                    class="form-control"
                    placeholder="Nombre del departamento"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Descripcion </label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="descripcion"
                    class="form-control"
                    placeholder="Descripcion del departamento"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Salario Basico (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="sueldoBasico"
                    class="form-control"
                    placeholder="Salario laboral"
                  />
                </div>
              </div>

              <div v-show="errorComponente" class="form-group row div-error">
                <div class="text-center text-error">
                  <div
                    v-for="error in errorMostrarMsjForm"
                    :key="error"
                    v-text="error"
                  ></div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="cerrarModal()"
            >
              Cerrar
            </button>
            <button
              type="button"
              v-if="tipoAccion == 1"
              class="btn btn-primary"
              @click="registrarPuesto()"
            >
              Guardar
            </button>
            <button
              type="button"
              v-if="tipoAccion == 2"
              class="btn btn-primary"
              @click="actualizarPuesto()"
            >
              Actualizar
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </main>
</template>

<script>
import toastr from "toastr";
export default {
  data() {
    return {
      arrayPuestos: [],
      arrayDepartamento: [],

      puesto_id: 0,
      nombre: "",
      descripcion: "",
      sueldoBasico: 0.0,
      departamento_id:0,
      

      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorComponente: 0,
      errorMostrarMsjForm: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      offset: 3,
      criterio: "nombre",
      buscar: "",
    };
  },
  computed: {
    isActived: function () {
      return this.pagination.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumber: function () {
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
  },
  methods: {
    listarTabla(page, buscar, criterio) {
      this.listarPuestos(page, buscar, criterio);
    },
    listarPuestos(page, buscar, criterio) {
      let me = this;
      var url =
        "/puesto?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayPuestos = respuesta.puestos.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarTabla(page, buscar, criterio);
    },
    registrarPuesto() {
      let me = this;
      if (this.validarForm()) {
        return;
      }
      axios
        .post("/puesto/registrar", {
          "nombre": this.nombre,
          "descripcion": this.descripcion,
          "departamento_id": this.departamento_id,
          "sueldoBasico": parseFloat(this.sueldoBasico)
        })
        .then(function (response) {
          console.log(response);
          me.cerrarModal();
          me.listarTabla(1, "", "nombre");
          toastr.success("Se ha registrado con exito", "Registrado", {
            timeOut: 5000,
          });
        })
        .catch(function (error) {
          console.log(error);
          toastr.error("Ha ocurrido un error", "Error", { timeOut: 5000 });
        });
    },
    actualizarPuesto() {
      console.log("prueba");
      if (this.validarForm()) {
        return;
      }

      let me = this;
      axios
        .put("/puesto/actualizar", {
          "id": this.puesto_id,
          "nombre": this.nombre,
          "descripcion": this.descripcion,
          "departamento_id": this.departamento_id,
          "sueldoBasico": this.sueldoBasico
        })
        .then(function (response) {
          me.cerrarModal();
          me.listarTabla(1, "", "nombre");
          toastr.success("Se ha actualizado con exito", "Actualizado", {
            timeOut: 5000,
          });
        })
        .catch(function (error) {
          console.log(error);
          toastr.error("Ha ocurrido un error", "Error", { timeOut: 5000 });
        });
    },
    desactivarContrato(id) {
      swal({
        title: "Esta seguro de desactivarlo ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false,
        reverseButtons: true,
      }).then((result) => {
        if (result.value) {
          let me = this;

          axios
            .put("/puesto/desactivar", {
              id: id,
            })
            .then(function (response) {
              me.listarTabla(1, "", "nombre");
              swal(
                "Desactivado!",
                "Se ha desactivado con éxito.",
                "success"
              );
            })
            .catch(function (error) {
              console.log(error);
            });
        } else if (
          // Read more about handling dismissals
          result.dismiss === swal.DismissReason.cancel
        ) {
        }
      });
    },
    activarContrato(id) {
      swal({
        title: "Esta seguro de activarlo?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false,
        reverseButtons: true,
      }).then((result) => {
        if (result.value) {
          let me = this;

          axios
            .put("/puesto/activar", {
              id: id,
            })
            .then(function (response) {
              me.listarTabla(1, "", "nombre");
              swal(
                "Activado!",
                "Se ha activado con éxito.",
                "success"
              );
            })
            .catch(function (error) {
              console.log(error);
            });
        } else if (
          // Read more about handling dismissals
          result.dismiss === swal.DismissReason.cancel
        ) {
        }
      });
    },

    validarForm() {
      this.errorComponente = 0;
      this.errorMostrarMsjForm = [];
      if (!this.nombre)
        this.errorMostrarMsjForm.push("Debe ingresar el nombre");
      if (!this.departamento_id)
        this.errorMostrarMsjForm.push("Debe seleccionar un departamento");
      if (parseFloat(this.sueldoBasico) <= 0.0 )
        this.errorMostrarMsjForm.push("Debe ingresar un sueldo basico mayor a 0");
      if (this.errorMostrarMsjForm.length) this.errorComponente = 1;
      return this.errorComponente;
    },
     selectDepartamentos(){
                let me=this;
                //loading(true)
                var url= '/departamento/selectDepartamento';
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    //q: search
                    me.arrayDepartamento=respuesta.departamentos;
                    //loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
     },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
   
      this.nombre = "";
      this.descripcion = '';
      this.departamento_id = 0;
      this.sueldoBasico = 0.0;
    },

    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "puesto": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Puesto";

              this.nombre = "";
              this.descripcion = '';
              this.departamento_id = 0;
              this.sueldoBasico = 0.0;

              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar contrato";
              this.tipoAccion = 2;
            this.puesto_id = data["id"];
              this.nombre = data["nombre"];
              this.descripcion = data["descripcion"];
              this.departamento_id = data["departamento_id"];
              this.sueldoBasico = data["sueldoBasico"];
              
              break;
            }
          }
        }
      }
    },
  },
  mounted() {
    this.listarTabla(1, this.buscar, this.criterio);
    this.selectDepartamentos();
  },
};
</script>
<style>
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-weight: bold;
}
</style>
