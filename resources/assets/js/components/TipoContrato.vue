<template>
  <main class="main">
    <!-- Breadcrumb -->
   
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card mt-2 rounded">
        <div class="card-header rounded">
          <div class="row justify-content-between rounded">
            <h3>Tipos de Contratos </h3>
            <button
              type="button"
              @click="abrirModal('tipoContrato', 'registrar')"
              class="btn btn-secondary btn-sm"
            >
              <i class="icon-plus"></i>&nbsp;Nuevo
            </button>
          </div>
        </div>
        <div class="card-body rounded">
          <!-- <form action="/tipoContrato/pdf" method="get">
            <div class="form-group row">
              <div class="col-6">
                <div class="input-group">
                  <select
                    name="criterio"
                    class="form-control col-md-3"
                    v-model="criterio"
                  >
                    <option value="nombre">Nombre</option>
                    <option value="activo">Activo</option>
                    <option value="desactivado">Desativado</option>
                  </select>
                  <input
                    name="buscar"
                    type="text"
                    v-model="buscar"
                    @keyup.enter="listarTipoContrato(1, buscar, criterio)"
                    class="form-control"
                    placeholder="Texto a buscar"
                  />
                  <button
                    type="button"
                    @click="listarTipoContrato(1, buscar, criterio)"
                    class="btn btn-primary"
                  >
                    <i class="fa fa-search"></i> Buscar
                  </button>
                </div>
              </div>
              <div class="col">
                <button
                  type="submit"
                  @click="listarTipoContrato(1, buscar, criterio)"
                  class="btn btn-warning"
                >
                  <i class="fa fa-search"></i> Reporte
                </button>
              </div>
            </div>
          </form> -->
          <table class="table table-bordered table-striped table-sm rounded">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Dias Minimos</th>
                <th>Dias Maximo</th>

                <th>Estado</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="tipoContrato in arrayTipoContrato"
                :key="tipoContrato.id"
              >
                <td v-text="tipoContrato.nombre"></td>
                <td class="text-right" v-text="tipoContrato.diasMinimo"></td>
                <td class="text-right" v-text="tipoContrato.diasMaximo"></td>

                <td>
                  <div v-if="tipoContrato.condicion">
                    <span class="badge badge-success">Activo</span>
                  </div>
                  <div v-else>
                    <span class="badge badge-danger">Inactivo</span>
                  </div>
                </td>
                <td>
                  <button
                    type="button"
                    @click="
                      abrirModal('tipoContrato', 'actualizar', tipoContrato)
                    "
                    class="btn btn-dark btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button>
                  &nbsp;
                  <template v-if="tipoContrato.condicion">
                    <button
                      type="button"
                      class="btn btn-dark btn-sm"
                      @click="desactivarTipoContrato(tipoContrato.id)"
                    >
                      <i class="icon-trash"></i>
                    </button>
                  </template>
                  <template v-else>
                    <button
                      type="button"
                      class="btn btn-dark btn-sm"
                      @click="activarTipoContrato(tipoContrato.id)"
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
                  >Nombre (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre"
                    class="form-control rounded"
                    placeholder="Nombre"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Dias Minimos (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="number"
                    v-model="diasMinimo"
                    class="form-control rounded"
                    placeholder="Ingrese cantidad de dias minimos del contrato"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Dias Maximos (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="number"
                    v-model="diasMaximo"
                    class="form-control rounded"
                    placeholder="La cantidad de dias maximos del contrato"
                  />
                </div>
              </div>
              <div class="form-group row rounded">
                <input class="align-center ml-3 rounded" type="checkbox"  v-model="indeterminado" />
                <label >Es contrato Indeterminado</label>
              </div>
              <div v-show="errorCompetencia" class="form-group row div-error">
                <div class="text-center text-error">
                  <div
                    v-for="error in errorMostrarMsjCompetencia"
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
              @click="registrarCompetencia()"
            >
              Guardar
            </button>
            <button
              type="button"
              v-if="tipoAccion == 2"
              class="btn btn-primary"
              @click="actualizarCompetencia()"
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
      _id: 0,
      nombre: "",
      diasMaximo: 0,
      diasMinimo: 0,
      indeterminado: 0,
      arrayTipoContrato: [],
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorCompetencia: 0,
      errorMostrarMsjCompetencia: [],
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
    listarTipoContrato(page, buscar, criterio) {
      let me = this;
      var url =
        "/tipoContrato?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayTipoContrato = respuesta.tipoContratos.data;
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
      me.listarTipoContrato(page, buscar, criterio);
    },
    registrarCompetencia() {
      let me = this;
      if (this.validarCompetencia()) {
        return;
      }
      axios
        .post("/tipoContrato/registrar", {
          nombre: this.nombre,
          diasMaximo: this.diasMaximo,
          diasMinimo: this.diasMinimo,
          indeterminado: this.indeterminado,
        })
        .then(function (response) {
          me.cerrarModal();
          me.listarTipoContrato(1, "", "nombre");
          toastr.success("Se ha registrado", "Registrado", { timeOut: 5000 });
        })
        .catch(function (error) {
          console.log(error);
          toastr.error("Ha ocurrido un error", "Error", { timeOut: 5000 });
        });
    },
    actualizarCompetencia() {
      console.log("prueba");
      if (this.validarCompetencia()) {
        return;
      }

      let me = this;
      axios
        .put("/tipoContrato/actualizar", {
          nombre: this.nombre,
          diasMaximo: this.diasMaximo,
          diasMinimo: this.diasMinimo,
          id: this._id,
        })
        .then(function (response) {
          me.cerrarModal();
          me.listarTipoContrato(1, "", "nombre");
          toastr.success("Se ha actualizado con exito", "Actualizado", {
            timeOut: 5000,
          });
        })
        .catch(function (error) {
          console.log(error);
          toastr.error("Ha ocurrido un error", "Error", { timeOut: 5000 });
        });
    },
    desactivarTipoContrato(id) {
      swal({
        title: "Esta seguro de desactivarlo?",
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
            .put("/tipoContrato/desactivar", {
              id: id,
            })
            .then(function (response) {
              me.listarTipoContrato(1, "", "nombre");
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
    activarTipoContrato(id) {
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
            .put("/tipoContrato/activar", {
              id: id,
            })
            .then(function (response) {
              me.listarTipoContrato(1, "", "nombre");
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

    validarCompetencia() {
      this.errorCompetencia = 0;
      this.errorMostrarMsjCompetencia = [];

      if (!this.nombre)
        this.errorMostrarMsjCompetencia.push(
          "Debe ingresar el nombre del tipo de contrato"
        );
      if (this.diasMaximo <= 0)
        this.errorMostrarMsjCompetencia.push(
          "Debe ingresar un dia Maximo mayor a 0"
        );
      if (this.diasMinimo <= 0)
        this.errorMostrarMsjCompetencia.push(
          "Debe ingresar un dia Minimo mayor a 0"
        );
      if (this.diasMinimo >= this.diasMaximo)
        this.errorMostrarMsjCompetencia.push(
          "El dias minimos debe ser menor a dias maximos"
        );

      if (this.errorMostrarMsjCompetencia.length) this.errorCompetencia = 1;
      return this.errorCompetencia;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.nombre = "";
    },

    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "tipoContrato": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Tipo de Contrato";
              this.nombre = "";

              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar Tipo de Contrato";
              this.tipoAccion = 2;
              this._id = data["id"];
              this.nombre = data["nombre"];
              break;
            }
          }
        }
      }
    },
  },
  mounted() {
    this.listarTipoContrato(1, this.buscar, this.criterio);
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
