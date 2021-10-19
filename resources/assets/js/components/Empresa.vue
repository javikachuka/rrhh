<template>
  <main class="main">
    <!-- Breadcrumb -->

    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card mt-2">
        <div class="card-header">
           <div class="row justify-content-between"> 
             <h3>
               Empresas
              </h3>
              <button
                type="button"
                @click="abrirModal('empresa', 'registrar')"
                class="btn btn-secondary btn-sm"
              >
                <i class="icon-plus"></i>&nbsp;Nuevo
              </button>
           </div>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <!-- <div class="input-group">
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
              </div> -->
            </div>
          </div>
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Razon Social</th>
                <th>CUIT</th>
                <th>Dirección</th>
                <th>Inicio Actividad</th>
                <th>Estado</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="empresa in arrayempresas" :key="empresa.id">
                <td v-text="empresa.nombre"></td>
                <td v-text="empresa.razonSocial"></td>
                <td v-text="empresa.cuit"></td>
                <td v-text="empresa.direccion"></td>
                <td v-text="empresa.inicioActividad"></td>
                <td>
                  <div v-if="empresa.condicion">
                    <span class="badge badge-success">Activo</span>
                  </div>
                  <div v-else>
                    <span class="badge badge-danger">Desactivado</span>
                  </div>
                </td>
                <td>
                  <button
                    type="button"
                    @click="abrirModal('empresa', 'actualizar', empresa)"
                    class="btn btn-dark btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button>
                  &nbsp;
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
                  >Nombre (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre"
                    class="form-control"
                    placeholder="Nombre de la empresa"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Razon Social (*)
                </label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="razonSocial"
                    class="form-control"
                    placeholder="Razon Social de la empresa"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >CUIT (*)
                </label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="cuit"
                    class="form-control"
                    placeholder="CUIT"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Direccion (*)
                </label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="direccion"
                    class="form-control"
                    placeholder="Direccion"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3">Inicio de Actividad (*)</label>
                <div class="col-md-9">
                  <input
                    type="date"
                    class="form-control"
                    v-model="inicioActividad"
                  />
                </div>
              </div>

              <div v-show="errorComponente" class="form-group row div-error">
                <ul class="text-center text-error">
                  <li
                    v-for="error in errorMostrarMsjForm"
                    :key="error"
                    v-text="error"
                  ></li>
                </ul>
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
              @click="registrarEmpresa()"
            >
              Guardar
            </button>
            <button
              type="button"
              v-if="tipoAccion == 2"
              class="btn btn-primary"
              @click="actualizarEmpresa()"
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
      arrayEmpresas: [],
      arrayempresas: [],

      id: 0,
      nombre: "",
      razonSocial: "",
      cuit: "",
      direccion: "",
      inicioActividad: "",

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
      this.listarEmpresa(page, buscar, criterio);
    },
    listarEmpresa(page, buscar, criterio) {
      let me = this;
      var url =
        "/empresa?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayempresas = respuesta.empresas.data;
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
    registrarEmpresa() {
      let me = this;
      if (this.validarForm()) {
        return;
      }
      axios
        .post("/empresa/registrar", {
          nombre: this.nombre,
          razonSocial: this.razonSocial,
          cuit: this.cuit,
          direccion: this.direccion,
          inicioActividad: this.inicioActividad,
        })
        .then(function (response) {
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
    actualizarEmpresa() {
      console.log("prueba");
      if (this.validarForm()) {
        return;
      }

      let me = this;
      axios
        .put("/empresa/actualizar", {
          id: this.id,
          nombre: this.nombre,
          razonSocial: this.razonSocial,
          cuit: this.cuit,
          direccion: this.direccion,
          inicioActividad: this.inicioActividad,
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
    desactivarEmpresa(id) {
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
            .put("/empresa/desactivar", {
              id: id,
            })
            .then(function (response) {
              me.listarTabla(1, "", "nombre");
              swal("Desactivado!", "Se ha desactivado con éxito.", "success");
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
    activarEmpresa(id) {
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
            .put("/empresa/activar", {
              id: id,
            })
            .then(function (response) {
              me.listarTabla(1, "", "nombre");
              swal("Activado!", "Se ha activado con éxito.", "success");
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
      if (!this.razonSocial)
        this.errorMostrarMsjForm.push("Debe ingresar una razon social");
      if (!this.cuit) this.errorMostrarMsjForm.push("Debe ingresar un CUIT");
      if (!this.direccion)
        this.errorMostrarMsjForm.push("Debe ingresar una direccion");
      if (!this.inicioActividad)
        this.errorMostrarMsjForm.push("Debe seleccionar una fecha");
      if (this.errorMostrarMsjForm.length) this.errorComponente = 1;
      return this.errorComponente;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";

      this.nombre = "";
      this.descripcion = "";
      this.empresa_id = 0;
    },

    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "empresa": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar empresa";

              this.id = 0;
              this.nombre = "";
              this.razonSocial = "";
              this.cuit = "";
              this.inicioActividad = "";
              this.direccion = "";

              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar empresa";
              this.tipoAccion = 2;

              this.id = data["id"];
              this.nombre = data["nombre"];
              this.razonSocial = data["razonSocial"];
              this.cuit = data["cuit"];
              this.inicioActividad = data["inicioActividad"];
              this.direccion = data["direccion"];

              break;
            }
          }
        }
      }
    },
  },
  mounted() {
    this.listarTabla(1, this.buscar, this.criterio);
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
