<template>
  <main class="main">
    <!-- Breadcrumb -->
   
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Dias No Laborales
          <button
            type="button"
            @click="abrirModal('diaNoLaboral', 'registrar')"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
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
          </div>
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Dia</th>
                <th>Empresa</th>
                <th>Condición</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="diaNoLaboral in arraydianolaboral" :key="diaNoLaboral.id">
                <td v-text="diaNoLaboral.dia"></td>
                <td v-text="diaNoLaboral.nombreEmpresa"></td>
                <td>
                  <div v-if="diaNoLaboral.condicion">
                    <span class="badge badge-success">Activo</span>
                  </div>
                  <div v-else>
                    <span class="badge badge-danger">Desactivado</span>
                  </div>
                </td>
                <td>
                  <button
                    type="button"
                    @click="abrirModal('diaNoLaboral', 'actualizar', diaNoLaboral)"
                    class="btn btn-warning btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button>
                  &nbsp;
                  <template v-if="diaNoLaboral.condicion">
                    <button
                      type="button"
                      class="btn btn-danger btn-sm"
                      @click="desactivarDiaNoLaboral(diaNoLaboral.id)"
                    >
                      <i class="icon-trash"></i>
                    </button>
                  </template>
                  <template v-else>
                    <button
                      type="button"
                      class="btn btn-info btn-sm"
                      @click="activarDiaNoLaboral(diaNoLaboral.id)"
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
      <div class="modal-dialog modal-primary modal-lg" role="document">
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
                  >Empresa (*)</label
                >
                <div class="col-md-9">

                <select class="form-control" v-model="empresa_id">
                  <option value="0" disabled>Seleccione</option>
                  <option
                    v-for="empresa in arrayEmpresa"
                    :key="empresa.id"
                    :value="empresa.id"
                    v-text="empresa.nombre"
                  ></option>
                </select>
                </div>
              </div>
                <div class="form-group row">
                  <label class="col-md-3">Dia No Laborable (*)</label>
                  <div class="col-md-9">

                  <input
                    type="date"
                    class="form-control"
                    v-model="dia"
                  />
                  </div>
                </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Descripcion (*)</label>

                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="descripcion"
                    class="form-control"
                    placeholder="Descripcion del dia no laboral"
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
              @click="registrarDiaNoLaboral()"
            >
              Guardar
            </button>
            <button
              type="button"
              v-if="tipoAccion == 2"
              class="btn btn-primary"
              @click="actualizarDiaNoLaboral()"
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
      arraydianolaboral: [],
      arrayEmpresa:[],
      id: 0,
      //datetime
      dia: "",
      descripcion: "",
      empresa_id: 0,
      

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
      this.listardiaNoLaboral(page, buscar, criterio);
    },
    listardiaNoLaboral(page, buscar, criterio) {
      let me = this;
      var url =
        "/diaNoLaboral?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function (response) {
          console.log(response);
          var respuesta = response.data;
          me.arraydianolaboral = respuesta.diaNoLaborales.data;
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
    registrarDiaNoLaboral() {
      let me = this;
      if (this.validarForm()) {
        return;
      }
      axios
      .post("/diaNoLaboral/registrar", {
      dia: this.dia,
      descripcion: this.descripcion,
      empresa_id: this.empresa_id,
      
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
    actualizarDiaNoLaboral() {
      console.log("prueba");
      if (this.validarForm()) {
        return;
      }

      let me = this;
      axios
        .put("/diaNoLaboral/actualizar", {
        id: this.id,
        dia: this.dia,
        descripcion: this.descripcion,
        empresa_id: this.empresa_id,
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
    desactivarDiaNoLaboral(id) {
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
            .put("/diaNoLaboral/desactivar", {
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
    activarDiaNoLaboral(id) {
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
            .put("/diaNoLaboral/activar", {
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
      if (!this.dia)
        this.errorMostrarMsjForm.push("Debe seleccionar un dia ");
      if (!this.empresa_id)
        this.errorMostrarMsjForm.push("Debe seleccionar la empresa relacionada. ");
      if (this.errorMostrarMsjForm.length) this.errorComponente = 1;
      return this.errorComponente;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";

      this.id=0;
      this.dia = "";
      this.descripcion = "";
      this.empresa_id = 0;
    },

    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "diaNoLaboral": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar dia no laboral";

               this.id=0;
              this.dia = "";
              this.descripcion = "";
              this.empresa_id = 0;

              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar dia no laboral";
              this.tipoAccion = 2;


              this.id = data["id"];
              this.dia = data["dia"];
              this.descripcion = data["descripcion"];
              this.empresa_id = data["empresa_id"];

              break;
            }
          }
        }
      }
    },
    selectEmpresa(){
                let me=this;
                //loading(true)
                var url= '/empresa/selectEmpresa';
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    //q: search
                    me.arrayEmpresa=respuesta.empresas;
                    //loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
     }
  },
  mounted() {
    this.listarTabla(1, this.buscar, this.criterio);
    this.selectEmpresa();
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
