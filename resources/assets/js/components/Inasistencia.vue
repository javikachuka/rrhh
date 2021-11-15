<template>
  <main class="main">
    <!-- Breadcrumb -->

    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <div class="row justify-content-between rounded">
            <h3>Solicitudes de Inasistencias</h3>
            <button
              type="button"
              @click="abrirModal('solicitudInasistencia', 'registrar')"
              class="btn btn-secondary btn-sm"
            >
              <i class="icon-plus"></i>&nbsp;Nueva
            </button>
          </div>
        </div>
        <div class="card-body">
          <!-- <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                  <option value="nombre">Nombre</option>
                  <option value="aprobado">Aprobado</option>
                  <option value="desaprobado">Desaprobado</option>
                  <option value="enespera">En Espera</option>
                  <option value="enlicencia">En licencia</option>
                  <option value="proximo">Proxima Licencias</option>
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
            <div class="col" v-if="user.rol.nombre == 'Administrador'">
              <button
                type="button"
                class="btn btn-primary"
                data-toggle="modal"
                data-target="#listEmpleadosModal"
                @click.prevent="obtenerEmpleadosConLicencia()"
              >
                Ver Empleados con licencias
              </button>
            </div>
          </div> -->
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Desde</th>
                <th>Hasta</th>
                <th>Motivo</th>
                <th>Empleado</th>
                <th>Incidencia</th>
                <th>Estado</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="solicitudInasistencia in arraysolicitudinasistencia"
                :key="solicitudInasistencia.id"
              >
                <td v-text="solicitudInasistencia.desde2"></td>
                <td v-text="solicitudInasistencia.hasta2"></td>
                <td v-text="solicitudInasistencia.motivo"></td>
                <td
                  v-text="
                    solicitudInasistencia.apellidoEmpleado +
                    ' ' +
                    solicitudInasistencia.nombreEmpleado
                  "
                ></td>
                <td v-text="solicitudInasistencia.nombreIncidencia"></td>
                <td>
                  <div v-if="solicitudInasistencia.aprobado">
                    <span class="badge badge-success">Aprobado</span>
                  </div>
                  <div v-if="solicitudInasistencia.aprobado == null">
                    <span class="badge badge-warning">En Espera</span>
                  </div>
                  <div v-if="solicitudInasistencia.aprobado == false">
                    <span class="badge badge-danger">Desaprobado</span>
                  </div>
                </td>
                <!-- <td>
                  <div v-if="solicitudInasistencia.condicion">
                    <span class="badge badge-success">Activo</span>
                  </div>
                  <div v-else>
                    <span class="badge badge-danger">Desactivado</span>
                  </div>
                </td> -->
                <td>
                  <button
                    title="Editar"
                    type="button"
                    @click="
                      abrirModal(
                        'solicitudInasistencia',
                        'actualizar',
                        solicitudInasistencia
                      )
                    "
                    class="btn btn-dark btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button>
                  &nbsp;

                  <template v-if="solicitudInasistencia.condicion">
                    <button
                      title="Desactivar"
                      v-if="user.rol.nombre == 'Administrador'"
                      type="button"
                      class="btn btn-dark btn-sm"
                      @click="
                        desactivarSolicitudInasistencia(
                          solicitudInasistencia.id
                        )
                      "
                    >
                      <i class="icon-trash"></i>
                    </button>
                  </template>
                  <template v-else>
                    <button
                      title="Activar"
                      v-if="user.rol.nombre == 'Administrador'"
                      type="button"
                      class="btn btn-info btn-sm"
                      @click="
                        activarSolicitudInasistencia(solicitudInasistencia.id)
                      "
                    >
                      <i class="icon-check"></i>
                    </button>
                  </template>
                  &nbsp;
                  <!-- <template>
                    <button
                      title="Ver"
                      v-if="user.rol.nombre == 'Administrador'"
                      type="button"
                      @click="verDatos(solicitudInasistencia)"
                      class="btn btn-outline-info btn-sm"
                    >
                      <i class="icon-eye"></i>
                    </button>
                  </template> -->
                  &nbsp;
                  <template>
                    <button
                      v-if="user.rol.nombre == 'Administrador'  && solicitudInasistencia.aprobado==null || solicitudInasistencia.aprobado==true"
                      type="button"
                      title="Desaprobar"
                      @click="aprobarSolicitud(solicitudInasistencia, 0)"
                      class="btn btn-outline-info btn-sm"
                    >
                      <i class="icon-close"></i>
                    </button>
                  </template>
                  <template>
                    <button
                      v-if="user.rol.nombre == 'Administrador' && solicitudInasistencia.aprobado==null || solicitudInasistencia.aprobado==false "
                      type="button"
                      title="Aprobar"
                      @click="aprobarSolicitud(solicitudInasistencia, 1)"
                      class="btn btn-outline-info btn-sm"
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
              <div class="col form-group form-group" v-if="user.rol_id == 1">
                <label class="form-control-label" for="text-input"
                  >Empleados (*)</label
                >
                <div class="col-3"></div>
                <select class="form-control" v-model="empleado_id">
                  <option value="0" disabled>Seleccione</option>
                  <option
                    v-for="empleado in arrayEmpleados"
                    :key="empleado.id"
                    :value="empleado.id"
                    v-text="empleado.nombre"
                  ></option>
                </select>
                <div class="col-3"></div>
              </div>
              <div class="col form-group">
                <label class="form-control-label" for="text-input"
                  >Incidencias (*)</label
                >
                <div class="col-3"></div>
                <select class="form-control" v-model="incidencia_id">
                  <option value="0" disabled>Seleccione</option>
                  <option
                    v-for="incidencia in arrayIncidencias"
                    :key="incidencia.id"
                    :value="incidencia.id"
                    v-text="
                      incidencia.nombre +
                      ' (Dias Minimos:' +
                      incidencia.diasMinimo +
                      ' -  Dias Maximos:' +
                      incidencia.diasMaximo +
                      ')'
                    "
                  ></option>
                </select>
                <div class="col-3"></div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Desde (*)</label>
                  <div class="col-3"></div>
                  <input type="date" class="form-control" v-model="desde" />
                  <div class="col-3"></div>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Hasta (*)</label>
                  <div class="col-3"></div>
                  <input type="date" class="form-control" v-model="hasta" />
                  <div class="col-3"></div>
                </div>
              </div>
              <div class="form-group col">
                <label class="form-control-label" for="text-input"
                  >Motivo
                </label>
                <div class="col-3"></div>
                <input
                  type="text"
                  v-model="motivo"
                  class="form-control"
                  placeholder="Motivo de la solicitud de la inasistencia"
                />
                <div class="col-3"></div>
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
              @click="registrarSolicitudInasistencia()"
            >
              Guardar
            </button>
            <button
              type="button"
              v-if="tipoAccion == 2"
              class="btn btn-primary"
              @click="actualizarSolicitudInasistencia()"
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
    <div
      class="modal fade"
      tabindex="-1"
      :class="{ mostrar: modal2 }"
      role="dialog"
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
              @click="cerrarModalver()"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group" v-if="empleado.id">
              <div class="row">
                <label
                  >Empleado: {{ empleado.nombre + empleado.apellido }}
                </label>
              </div>
              <hr />
              <h3>Personas Dependientes</h3>
              <div class="row">
                <ul>
                  <li
                    v-for="perDep in empleado.persona_dependientes"
                    :key="perDep.id"
                  >
                    <div class="row">
                      Nombre: {{ " " + perDep.nombre + " " + perDep.apellido }}
                    </div>
                    <div class="row">Relacion:{{ " " + perDep.relacion }}</div>
                    <div class="row">
                      Necesidad:{{ " " + perDep.necesidad }}
                    </div>
                  </li>
                </ul>
              </div>
              <hr />
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="cerrarModalVer()"
            >
              Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>

    <div>
      <!-- Modal -->
      <div
        class="modal fade"
        id="listEmpleadosModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLongTitle"
        aria-hidden="true"
        :class="{ mostrar: modalListarEmpleadoLicencia }"
      >
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">
                Empleados en Licencias
              </h5>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
                @click.prevent="modalListarEmpleadoLicencia = 0"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <ul>
                <li
                  v-for="empleadoLicencia in arrayEmpleadosLicencia"
                  :key="empleadoLicencia.id"
                >
                  <h5>
                    {{
                      "ID: (" +
                      empleadoLicencia.id +
                      ")" +
                      empleadoLicencia.nombre +
                      " " +
                      empleadoLicencia.apellido +
                      " "
                    }}<span class="badge badge-success">{{
                      "Desde: " + empleadoLicencia.desde
                    }}</span
                    ><span class="badge badge-success">{{
                      "Hasta: " + empleadoLicencia.hasta
                    }}</span>
                  </h5>
                </li>
              </ul>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
                @click.prevent="modalListarEmpleadoLicencia = 0"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
let user = document.head.querySelector('meta[name="user"]');

import toastr from "toastr";
export default {
  data() {
    return {
      arrayEmpleados: [],
      arrayIncidencias: [],
      arraysolicitudinasistencia: [],
      arrayEmpleadosLicencia: [],
      id: 0,
      desde: "",
      hasta: "",
      motivo: "",
      empleado_id: 0,
      incidencia_id: 0,
      //usuario:'',
      empleado: { id: 0 },

      modal: 0,
      modal2: 0,
      modalListarEmpleadoLicencia: 0,
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
    user() {
      return JSON.parse(user.content);
      console.log(user.content);
    },
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
    obtenerEmpleadosConLicencia() {
      this.modalListarEmpleadoLicencia = 1;
      let me = this;
      var url = "/empleado/enLicencia";
      axios
        .get(url)
        .then(function (response) {
          let respuesta = response.data;
          me.arrayEmpleadosLicencia = respuesta.empleados;
          console.log(respuesta.empleados);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    aprobarSolicitud(solicitudInasistencia, valor) {
      let mensaje = "¿Desea Aprobar la Solicitud de Inasistencia?";

      if (!valor) {
        mensaje = "¿Desea Desaprobar la solicitud de Inasistencia?";
      }
      swal({
        title: mensaje,
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
            .put("/solicitudInasistencia/aprobar", {
              id: solicitudInasistencia.id,
              aprobado: parseInt(valor),
            })
            .then(function (response) {
              if (valor) {
                swal("Aprobado!", "Se ha aprobado correctamente.", "success");
              } else {
                swal("Desaprobado!", "Se desaprobo la solicitud.", "success");
              }
              me.listarTabla(1, "", "nombre");
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
    verDatos(solicitudInasistencia) {
      this.modal2 = 1;
      let me = this;
      var url = "/getEmpleado?id=" + solicitudInasistencia.empleado_id;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.empleado = respuesta.empleado;
          console.log(respuesta.empleado);
        })
        .catch(function (error) {
          me.empleado.id = 0;
          console.log(error);
        });
    },
    cerrarModalVer() {
      this.modal2 = 0;
    },
    listarTabla(page, buscar, criterio) {
      this.listarSolicitudInasistencia(page, buscar, criterio);
    },
    listarSolicitudInasistencia(page, buscar, criterio) {
      let me = this;
      var url =
        "/solicitudInasistencia?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arraysolicitudinasistencia = respuesta.solicitudInasistencias.data;
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
    registrarSolicitudInasistencia() {
      let me = this;
      if (this.validarForm()) {
        return;
      }
      axios
        .post("/solicitudInasistencia/registrar", {
          desde: this.desde,
          hasta: this.hasta,
          motivo: this.motivo,
          empleado_id: this.empleado_id,
          incidencia_id: this.incidencia_id,
        })
        .then(function (response) {
          console.log(response);
          if (response.data) {
            toastr.error(response.data[1], "Error", { timeOut: 10000 });
          } else {
            me.cerrarModal();
            me.listarTabla(1, "", "nombre");
            toastr.success("Se ha registrado con exito", "Registrado", {
              timeOut: 5000,
            });
          }
        })
        .catch(function (error) {
          console.log(error);
          toastr.error("Ha ocurrido un error", "Error", { timeOut: 5000 });
        });
    },
    actualizarSolicitudInasistencia() {
      console.log("prueba");
      if (this.validarForm()) {
        return;
      }

      let me = this;
      axios
        .put("/solicitudInasistencia/actualizar", {
          id: this.id,
          desde: this.desde,
          hasta: this.hasta,
          motivo: this.motivo,
          empleado_id: this.empleado_id,
          incidencia_id: this.incidencia_id,
        })
        .then(function (response) {
          if (response.data) {
            toastr.error(response.data[1], "Error", { timeOut: 10000 });
          } else {
            me.cerrarModal();
            me.listarTabla(1, "", "nombre");
            toastr.success("Se ha actualizado con exito", "Actualizado", {
              timeOut: 5000,
            });
          }
        })
        .catch(function (error) {
          console.log(error);
          toastr.error("Ha ocurrido un error", "Error", { timeOut: 5000 });
        });
    },
    desactivarSolicitudInasistencia(id) {
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
            .put("/solicitudInasistencia/desactivar", {
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
    activarSolicitudInasistencia(id) {
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
            .put("/solicitudInasistencia/activar", {
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
      if (!this.desde) this.errorMostrarMsjForm.push("Debe ingresar una fecha");
      if (!this.hasta) this.errorMostrarMsjForm.push("Debe ingresar una fecha");
      if(this.desde > this.hasta){
          this.errorMostrarMsjForm.push("La fecha de inicio  de la inasistencia tiene que ser menor a la fecha de finalizacion");
          //return;
        }  
      var dia = new Date();
        var comparar = new Date(this.desde);
        if(comparar < dia){
          this.errorMostrarMsjForm.push("La fecha desde tiene que ser mayor a la fecha actual");        
        }  
      if (!this.motivo)
        this.errorMostrarMsjForm.push("Debe ingresar una motivo");
      /*if (this.empleado_id <= 0)
        this.errorMostrarMsjForm.push("Debe seleccionar un empleado");*/
      if (this.incidencia_id <= 0)
        this.errorMostrarMsjForm.push("Debe seleccionar una incidencia");
      if (this.errorMostrarMsjForm.length) this.errorComponente = 1;
      return this.errorComponente;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";

      this.id = 0;
      this.desde = "";
      this.hasta = "";
      this.motivo = "";
      this.empleado_id = 0;
      this.incidencia_id = 0;
    },
    selectEmpleado() {
      let me = this;
      //loading(true)
      var url = "/empleado/selectEmpleado";
      axios
        .get(url)
        .then(function (response) {
          let respuesta = response.data;
          //q: search
          me.arrayEmpleados = respuesta.empleados;
          //loading(false)
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    selectIncidencia() {
      let me = this;
      //loading(true)
      var url = "/incidencia/selectIncidencia";
      axios
        .get(url)
        .then(function (response) {
          let respuesta = response.data;
          //q: search
          me.arrayIncidencias = respuesta.incidencias;
          //loading(false)
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "solicitudInasistencia": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar solicitud inasistencia";

              this.id = 0;
              this.desde = "";
              this.hasta = "";
              this.motivo = "";
              this.empleado_id = 0;
              this.incidencia_id = 0;

              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar solicitud inasistencia";
              this.tipoAccion = 2;

              this.id = data["id"];
              this.desde = data["desde"];
              this.hasta = data["hasta"];
              this.motivo = data["motivo"];
              this.empleado_id = data["empleado_id"];
              this.incidencia_id = data["incidencia_id"];

              break;
            }
          }
        }
      }
    },
  },
  mounted() {
    this.listarTabla(1, this.buscar, this.criterio);
    this.selectEmpleado();
    this.selectIncidencia();
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
