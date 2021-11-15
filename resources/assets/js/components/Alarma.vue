<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">Alarmas</div>
        <div class="card-body">
          <div class="row">
            <div class="col">
              <div class="card">
                <div class="card-header">Solicitudes de Inasistencias</div>
                <div class="card-body">
                  <div class="form-group row">
                    <div class="col">
                      <div class="input-group">
                        <select
                          class="form-control col"
                          v-model="criInasistencia"
                        >
                          <option value="empleado">Empleado</option>
                          <option value="todos">Todos</option>
                        </select>

                        <select
                          class="form-control col"
                          v-model="idempleado"
                          v-if="criInasistencia == 'empleado'"
                        >
                          <option value="0" disabled>Seleccione</option>
                          <option
                            v-for="empleado in arrayEmpleado"
                            :key="empleado.id"
                            :value="empleado.id"
                            v-text="empleado.apellido + ' ' + empleado.nombre"
                          ></option>
                        </select>

                        <button
                          type="submit"
                          @click="
                            listarInasistencia(
                              1,
                              buInasistencia,
                              criInasistencia
                            )
                          "
                          class="btn btn-primary"
                        >
                          <i class="fa fa-search"></i> Buscar
                        </button>
                        &nbsp;
                        <button
                          type="button"
                          class="btn btn-primary"
                          data-toggle="modal"
                          data-target="#listEmpleadosModal"
                          @click.prevent="obtenerEmpleadosConLicencia()"
                        >
                          Empleados con licencias
                        </button>
                      </div>
                    </div>
                  </div>
                  <table class="table table-bordered table-striped table-sm">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Empleado(id)</th>
                        <th>Ver</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="inasistencia in arrayInasistencia"
                        :key="inasistencia.id"
                      >
                        <td v-text="inasistencia.id"></td>
                        <td v-text="inasistencia.desde2"></td>
                        <td v-text="inasistencia.hasta2"></td>
                        <td
                          v-text="
                            inasistencia.nombreEmpleado +
                            ' ' +
                            inasistencia.apellidoEmpleado +
                            ' ( ' +
                            inasistencia.empleado_id +
                            ' )'
                          "
                        ></td>
                        <td>
                          <button
                            type="button"
                            @click="verDatos(inasistencia)"
                            class="btn btn-outline-info btn-sm"
                          >
                            <i class="icon-eye"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <nav>
                    <ul class="pagination">
                      <li
                        class="page-item"
                        v-if="pagInasistencia.current_page > 1"
                      >
                        <a
                          class="page-link"
                          href="#"
                          @click.prevent="
                            cambiarPaginaInasistencia(
                              pagInasistencia.current_page - 1,
                              buInasistencia,
                              criInasistencia
                            )
                          "
                          >Ant</a
                        >
                      </li>
                      <li
                        class="page-item"
                        v-for="page in pagesNumberInasistencia"
                        :key="page"
                        :class="[page == isActivedInasistencia ? 'active' : '']"
                      >
                        <a
                          class="page-link"
                          href="#"
                          @click.prevent="
                            cambiarPaginaInasistencia(
                              page,
                              buInasistencia,
                              criInasistencia
                            )
                          "
                          v-text="page"
                        ></a>
                      </li>
                      <li
                        class="page-item"
                        v-if="
                          pagInasistencia.current_page <
                          pagInasistencia.last_page
                        "
                      >
                        <a
                          class="page-link"
                          href="#"
                          @click.prevent="
                            cambiarPaginaInasistencia(
                              pagInasistencia.current_page + 1,
                              buInasistencia,
                              criInasistencia
                            )
                          "
                          >Sig</a
                        >
                      </li>
                    </ul>
                  </nav>
                </div>
              </div>
            </div>

            <div class="col">
              <div class="card">
                <div class="card-header">Contratos a vencer</div>
                <div class="card-body">
                  <div class="form-group row">
                    <div class="col">
                      <div class="input-group">
                        <select class="form-control col" v-model="criContrato">
                          <option value="sincontrato">Sin Contrato</option>
                          <option value="avencer">Proximo vencimiento</option>
                          <option value="empleado">Empleado</option>
                        </select>
                        <select
                          v-if="criContrato == 'empleado'"
                          class="form-control col"
                          v-model="idempleadoContrato"
                        >
                          <option value="0" disabled>Seleccione</option>
                          <option
                            v-for="empleado in arrayEmpleado"
                            :key="empleado.id"
                            :value="empleado.id"
                            v-text="empleado.apellido + ' ' + empleado.nombre"
                          ></option>
                        </select>

                        <button
                          type="submit"
                          @click="listarContrato(1, buContrato, criContrato)"
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
                        <th>ID</th>
                        <th>Empleado</th>
                        <th>Inicio Contrato</th>
                        <th>Fin Contrato</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="contratito in arrayContrato"
                        :key="contratito.id"
                      >
                        <td v-text="contratito.id"></td>
                        <td
                          v-text="
                            contratito.nombreEmpleado +
                            ' ' +
                            contratito.apellidoEmpleado +
                            ' (' +
                            contratito.empleado_id +
                            ')'
                          "
                        ></td>
                        <td v-text="contratito.inicioLaboral2"></td>
                        <td v-text="contratito.finLaboral2"></td>
                      </tr>
                    </tbody>
                  </table>
                  <nav>
                    <ul class="pagination">
                      <li class="page-item" v-if="pagContrato.current_page > 1">
                        <a
                          class="page-link"
                          href="#"
                          @click.prevent="
                            cambiarPaginaContrato(
                              pagContrato.current_page - 1,
                              buContrato,
                              criContrato
                            )
                          "
                          >Ant</a
                        >
                      </li>
                      <li
                        class="page-item"
                        v-for="page in pagesNumberContrato"
                        :key="page"
                        :class="[page == isActivedContrato ? 'active' : '']"
                      >
                        <a
                          class="page-link"
                          href="#"
                          @click.prevent="
                            cambiarPaginaContrato(page, buContrato, criContrato)
                          "
                          v-text="page"
                        ></a>
                      </li>
                      <li
                        class="page-item"
                        v-if="pagContrato.current_page < pagContrato.last_page"
                      >
                        <a
                          class="page-link"
                          href="#"
                          @click.prevent="
                            cambiarPaginaContrato(
                              pagContrato.current_page + 1,
                              buContrato,
                              criContrato
                            )
                          "
                          >Sig</a
                        >
                      </li>
                    </ul>
                  </nav>
                </div>
              </div>
            </div>
          </div>
          <!-- <div class="row">
            <div class="col">
              <div class="card">
                <div class="card-header">Dias No Laborales Proximos</div>
                <div class="card-body">
                  <div class="form-group row">
                    <div class="col">
                      <div class="input-group">
                        <select
                          class="form-control col"
                          v-model="criDiaNoLaboral"
                        >
                          <option value="dia">Dia</option>
                        </select>
                        <input
                          type="text"
                          v-model="buDiaNoLaboral"
                          @keyup.enter="
                            listarDiaNoLaboral(
                              1,
                              buDiaNoLaboral,
                              criDiaNoLaboral
                            )
                          "
                          class="form-control"
                          placeholder="Buscar dia"
                        />
                        <button
                          type="submit"
                          @click="
                            listarDiaNoLaboral(
                              1,
                              buDiaNoLaboral,
                              criDiaNoLaboral
                            )
                          "
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
                        <th>ID</th>
                        <th>Dia</th>
                        <th>Empresa(id)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="diaNoLaboral in arrayDiaNoLaboral"
                        :key="diaNoLaboral.id"
                      >
                        <td v-text="diaNoLaboral.id"></td>
                        <td v-text="diaNoLaboral.dia2"></td>
                        <td
                          v-text="
                            diaNoLaboral.nombreEmpresa +
                            ' ( ' +
                            diaNoLaboral.empresa_id +
                            ' )'
                          "
                        ></td>
                      </tr>
                    </tbody>
                  </table>
                  <nav>
                    <ul class="pagination">
                      <li
                        class="page-item"
                        v-if="pagDiaNoLaboral.current_page > 1"
                      >
                        <a
                          class="page-link"
                          href="#"
                          @click.prevent="
                            cambiarPaginaDiaNoLaboral(
                              pagDiaNoLaboral.current_page - 1,
                              buDiaNoLaboral,
                              criDiaNoLaboral
                            )
                          "
                          >Ant</a
                        >
                      </li>
                      <li
                        class="page-item"
                        v-for="page in pagesNumberDiaNoLaboral"
                        :key="page"
                        :class="[page == isActivedDiaNoLaboral ? 'active' : '']"
                      >
                        <a
                          class="page-link"
                          href="#"
                          @click.prevent="
                            cambiarPaginaDiaNoLaboral(
                              page,
                              buDiaNoLaboral,
                              criDiaNoLaboral
                            )
                          "
                          v-text="page"
                        ></a>
                      </li>
                      <li
                        class="page-item"
                        v-if="
                          pagDiaNoLaboral.current_page <
                          pagDiaNoLaboral.last_page
                        "
                      >
                        <a
                          class="page-link"
                          href="#"
                          @click.prevent="
                            cambiarPaginaDiaNoLaboral(
                              pagDiaNoLaboral.current_page + 1,
                              buDiaNoLaboral,
                              criDiaNoLaboral
                            )
                          "
                          >Sig</a
                        >
                      </li>
                    </ul>
                  </nav>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card">
                <div class="card-header">Eventos Proximos</div>
                <div class="card-body">
                  <div class="form-group row">
                    <div class="col">
                      <div class="input-group">
                        <select class="form-control col" v-model="criEvento">
                          <option value="titulo">Título</option>
                        </select>
                        <input
                          type="text"
                          v-model="buEvento"
                          @keyup.enter="listarEvento(1, buEvento, criEvento)"
                          class="form-control"
                          placeholder="Texto a buEvento"
                        />
                        <button
                          type="submit"
                          @click="listarEvento(1, buEvento, criEvento)"
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
                        <th>ID</th>
                        <th>Título</th>
                        <th>Fecha</th>
                        <th>Departamento(id)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="evento in arrayEvento" :key="evento.id">
                        <td v-text="evento.id"></td>
                        <td v-text="evento.titulo"></td>
                        <td v-text="evento.fecha2"></td>
                        <td
                          v-text="
                            evento.nombreDepartamento +
                            ' ( ' +
                            evento.departamento_id +
                            ' )'
                          "
                        ></td>
                      </tr>
                    </tbody>
                  </table>
                  <nav>
                    <ul class="pagination">
                      <li class="page-item" v-if="pagEvento.current_page > 1">
                        <a
                          class="page-link"
                          href="#"
                          @click.prevent="
                            cambiarPaginaEvento(
                              pagEvento.current_page - 1,
                              buEvento,
                              criEvento
                            )
                          "
                          >Ant</a
                        >
                      </li>
                      <li
                        class="page-item"
                        v-for="page in pagesNumberEvento"
                        :key="page"
                        :class="[page == isActivedEvento ? 'active' : '']"
                      >
                        <a
                          class="page-link"
                          href="#"
                          @click.prevent="
                            cambiarPaginaEvento(page, buEvento, criEvento)
                          "
                          v-text="page"
                        ></a>
                      </li>
                      <li
                        class="page-item"
                        v-if="pagEvento.current_page < pagEvento.last_page"
                      >
                        <a
                          class="page-link"
                          href="#"
                          @click.prevent="
                            cambiarPaginaEvento(
                              pagEvento.current_page + 1,
                              buEvento,
                              criEvento
                            )
                          "
                          >Sig</a
                        >
                      </li>
                    </ul>
                  </nav>
                </div>
              </div>
            </div>
          </div> -->
        </div>
      </div>

      <!-- Ejemplo de tabla Listado -->

      <!-- Fin ejemplo de tabla Listado -->
    </div>
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
            <h4 class="modal-title">Datos del Empleado</h4>
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
            <div class="form-group text-center" v-if="empleado.id">
              <div class="form-group">
                <label class="form-control-label" for="text-input"
                  ><b>Empleado: </b>
                </label>

                {{ empleado.nombre + " " + empleado.apellido }}
              </div>
              <div class="form-group">
                <label class="form-control-label" for="text-input"
                  ><b>Antiguedad: </b>
                </label>

                {{ empleado.antiguedadCalculada }}
              </div>
              <div class="form-group">
                <label class="form-control-label" for="text-input"
                  ><b>Motivo: </b>
                </label>
                <br />
                {{ solicitudInasistenciaSelect.motivo }}
              </div>

              <hr />
              <h3>Personas Dependientes</h3>

              <ul>
                <li
                  v-for="perDep in empleado.persona_dependientes"
                  :key="perDep.id"
                >
                  <div class="form-group">
                    <label class="col-md-3 form-control-label" for="text-input"
                      ><b>Nombre</b>
                    </label>
                    <br />
                    {{ " " + perDep.nombre + " " + perDep.apellido }}
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 form-control-label" for="text-input"
                      ><b>Relación</b>
                    </label>
                    <br />
                    {{ " " + perDep.relacion }}
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 form-control-label" for="text-input"
                      ><b>Necesidad</b>
                    </label>
                    <br />
                    {{ " " + perDep.necesidad }}
                  </div>
                </li>
              </ul>

              <hr />
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              @click="aprobarSolicitud(solicitudInasistenciaSelect, 1)"
              class="btn btn-outline-success btn-sm"
            >
              Aprobar
            </button>
            &nbsp;
            <button
              type="button"
              @click="aprobarSolicitud(solicitudInasistenciaSelect, 0)"
              class="btn btn-outline-danger btn-sm"
            >
              Desaprobar
            </button>
            &nbsp;
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
              Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import toastr from "toastr";
export default {
  data() {
    return {
      arrayEmpresas: [],
      arrayareas: [],
      arrayEvento: [],
      arrayInasistencia: [],
      arrayDiaNoLaboral: [],
      arrayContrato: [],
      arrayEmpleadosLicencia: [],
      arrayEmpleado: [],
      idempleado: 0,
      idempleadoContrato: 0,

      solicitudInasistenciaSelect: {},
      empleado: { id: 0 },
      id: 0,
      nombre: "",
      descripcion: "",
      empresa_id: 0,

      modal: 0,
      modal2: 0,
      modalListarEmpleadoLicencia: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorComponente: 0,
      errorMostrarMsjForm: [],

      pagEvento: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      pagInasistencia: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      pagDiaNoLaboral: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      pagContrato: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      offset: 3,
      criEvento: "titulo",
      buEvento: "",
      criInasistencia: "todos",
      buInasistencia: "",
      criDiaNoLaboral: "dia",
      buDiaNoLaboral: "",
      criContrato: "avencer",
      buContrato: "",
    };
  },
  computed: {
    isActivedContrato: function () {
      return this.pagEvento.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumberContrato: function () {
      if (!this.pagEvento.to) {
        return [];
      }

      var from = this.pagEvento.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagEvento.last_page) {
        to = this.pagEvento.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
    isActivedEvento: function () {
      return this.pagEvento.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumberEvento: function () {
      if (!this.pagEvento.to) {
        return [];
      }

      var from = this.pagEvento.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagEvento.last_page) {
        to = this.pagEvento.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
    isActivedInasistencia: function () {
      return this.pagInasistencia.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumberInasistencia: function () {
      if (!this.pagInasistencia.to) {
        return [];
      }

      var from = this.pagInasistencia.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagInasistencia.last_page) {
        to = this.pagInasistencia.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
    isActivedDiaNoLaboral: function () {
      return this.pagDiaNoLaboral.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumberDiaNoLaboral: function () {
      if (!this.pagDiaNoLaboral.to) {
        return [];
      }

      var from = this.pagDiaNoLaboral.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagDiaNoLaboral.last_page) {
        to = this.pagDiaNoLaboral.last_page;
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
              me.listarInasistencia(1, "", "todos");
              me.modal2 = 0;
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
    verDatos(solicitudInasistencia) {
      this.modal2 = 1;
      let me = this;
      var url = "/getEmpleado?id=" + solicitudInasistencia.empleado_id;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.empleado = respuesta.empleado;
          me.solicitudInasistenciaSelect = solicitudInasistencia;
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
    selectEmpleado() {
      let me = this;
      //loading(true)
      var url = "/empleado/selectEmpleado";
      axios
        .get(url)
        .then(function (response) {
          let respuesta = response.data;
          //q: search
          me.arrayEmpleado = respuesta.empleados;
          console.log("select Empleado");
          //loading(false)
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    listarEvento(page, buEvento, criEvento) {
      let me = this;
      var url =
        "/evento/alarma?page=" +
        page +
        "&buscar=" +
        buEvento +
        "&criterio=" +
        criEvento;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayEvento = respuesta.eventos.data;
          me.pagEvento = respuesta.pagination;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    cambiarPaginaEvento(page, buEvento, criEvento) {
      let me = this;
      //Actualiza la página actual
      me.pagEvento.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarEvento(page, buEvento, criEvento);
    },
    listarInasistencia(page, buInasistencia, criInasistencia) {
      let me = this;
      var url =
        "/solicitudInasistencia/alarma?page=" +
        page +
        "&buscar=" +
        buInasistencia +
        "&criterio=" +
        criInasistencia +
        "&idempleado=" +
        this.idempleado;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayInasistencia = respuesta.solicitudInasistencias.data;
          me.pagInasistencia = respuesta.pagination;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    cambiarPaginaInasistencia(page, buInasistencia, criInasistencia) {
      let me = this;
      //Actualiza la página actual
      me.pagInasistencia.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarInasistencia(page, buInasistencia, criInasistencia);
    },
    listarDiaNoLaboral(page, buDiaNoLaboral, criDiaNoLaboral) {
      let me = this;
      var url =
        "/diaNoLaboral/alarmaDiaNoLaboral?page=" +
        page +
        "&buscar=" +
        buDiaNoLaboral +
        "&criterio=" +
        criDiaNoLaboral;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayDiaNoLaboral = respuesta.diaNoLaborales.data;
          me.pagDiaNoLaboral = respuesta.pagination;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    cambiarPaginaDiaNoLaboral(page, buDiaNoLaboral, criDiaNoLaboral) {
      let me = this;
      //Actualiza la página actual
      me.pagDiaNoLaboral.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarDiaNoLaboral(page, buDiaNoLaboral, criDiaNoLaboral);
    },
    listarContrato(page, buContrato, criContrato) {
      let me = this;
      var url =
        "/contrato/alarma?page=" +
        page +
        "&buscar=" +
        buContrato +
        "&criterio=" +
        criContrato +
        "&idempleado=" +
        me.idempleadoContrato;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayContrato = respuesta.contratos.data;
          me.pagContrato = respuesta.pagination;
          console.log(response.data);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    cambiarPaginaContrato(page, buContrato, criContrato) {
      let me = this;
      //Actualiza la página actual
      me.pagContrato.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarContrato(page, buContrato, criContrato);
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
        case "area": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar area";

              this.id = 0;
              this.nombre = "";
              this.descripcion = "";
              this.empresa_id = 0;

              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar area";
              this.tipoAccion = 2;

              this.id = data["id"];
              this.nombre = data["nombre"];
              this.descripcion = data["descripcion"];
              this.empresa_id = data["empresa_id"];

              break;
            }
          }
        }
      }
    },
  },
  mounted() {
    this.listarEvento(1, this.buEvento, this.criEvento);
    this.listarInasistencia(1, this.buInasistencia, this.criInasistencia);
    this.listarDiaNoLaboral(1, this.buDiaNoLaboral, this.criDiaNoLaboral);
    this.listarContrato(1, this.buContrato, this.criContrato);
    this.selectEmpleado();
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
