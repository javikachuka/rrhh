<template>
  <main class="main">
    <!-- Breadcrumb -->
    
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card mt-2 rounded">
        <div class="card-header rounded">
          <div class="row justify-content-between rounded">
            <h3>Contratos </h3>
            <button
              type="button"
              @click="abrirModal('contrato', 'registrar')"
              class="btn btn-secondary btn-sm"
            >
              <i class="icon-plus"></i>&nbsp;Nuevo
            </button>
          </div>
        </div>
        <div class="card-body rounded">
          <form action="/contrato/pdf" method="get">
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
                    <option value="desactivado">Inactivo</option>
                    <option value="vigente">Vigente</option>
                    <option value="terminado">Terminado</option>
                    <option value="tipoContrato">Tipo de Contrato</option>
                    <option value="empleado_id">ID Empleado</option>
                  </select>
                  <input
                    v-if="criterio != 'tipoContrato'"
                    name="buscar"
                    type="text"
                    v-model="buscar"
                    @keyup.enter="listarTabla(1, buscar, criterio)"
                    class="form-control"
                    placeholder="Texto a buscar"
                  />
                  <select
                  name="tipoContrato_id_filtro"
                    class="form-control col-md-3"
                    v-model="tipoContrato_id_filtro"
                    v-if="criterio == 'tipoContrato'"
                  >
                    <option value="0" disabled>Seleccione</option>
                    <option
                      v-for="tipoContrato in arrayTipoContrato"
                      :key="tipoContrato.id"
                      :value="tipoContrato.id"
                      v-text="
                        tipoContrato.nombre
                      "
                    ></option>
                  </select>
                  <button
                    type="button"
                    @click="listarTabla(1, buscar, criterio)"
                    class="btn btn-primary"
                  >
                    <i class="fa fa-search"></i>
                  </button>
                </div>
              </div>
              <div class="col">
                <button
                  type="submit"
                  @click="listarTabla(1, buscar, criterio)"
                  class="btn btn-danger float-right"
                >
                  Generar <i class="fa fa-file"></i>
                </button>
              </div>
            </div>
          </form>
          <table class="table table-bordered table-striped table-sm rounded">
            <thead>
              <tr>
                <th>Empleado (ID)</th>
                <th>Nombre</th>
                <th>Tipo Contrato</th>
                <!-- <th>Contrato</th> -->
                <th>Puesto</th>
                <th>Horas Laborales</th>
                <th>Salario</th>
                <th>Inicio Laboral</th>
                <th>Fin Laboral</th>

                <th>Condición</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="contrato in arrayContrato" :key="contrato.id">
                <td
                  v-text="
                    contrato.apellidoEmpleado +
                    ' ' +
                    contrato.nombreEmpleado +
                    ' (' +
                    contrato.empleado_id +
                    ')'
                  "
                ></td>
                <td v-text="contrato.nombre"></td>
                <td v-text="contrato.nombreTipoContrato"></td>
                <!-- <td><a v-bind:href="contrato.url">Ver Contrato</a></td> -->
                <td v-text="contrato.nombrePuesto"></td>
                <td
                  class="text-right"
                  v-text="contrato.cantidadHorasDiarias"
                ></td>
                <td class="text-right" v-text="contrato.salario"></td>
                <td class="text-right" v-text="contrato.inicioLaboral"></td>
                <td class="text-right" v-text="contrato.finLaboral"></td>

                <td>
                  <div v-if="contrato.condicion">
                    <span class="badge badge-success">Activo</span>
                  </div>
                  <div v-else>
                    <span class="badge badge-danger">Inactivo</span>
                  </div>
                </td>
                <td>
                  <button
                    type="button"
                    @click="abrirModal('contrato', 'actualizar', contrato)"
                    class="btn btn-dark btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button>
                  &nbsp;
                  <template v-if="contrato.condicion">
                    <button
                      type="button"
                      class="btn btn-dark btn-sm"
                      @click="desactivarContrato(contrato.id)"
                    >
                      <i class="icon-trash"></i>
                    </button>
                  </template>
                  <template v-else>
                    <button
                      type="button"
                      class="btn btn-info btn-sm"
                      @click="activarContrato(contrato.id)"
                    >
                      <i class="icon-check"></i>
                    </button>
                  </template>
                    <button
                      type="button"
                      class="btn btn-dark btn-sm"
                      @click="abrirModal2('evaluacion', '', contrato)"
                    >
                      <i class="icon-list"></i>
                    </button>
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
      <div class="modal-dialog modal-lg rounded" role="document">
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
          <div class="modal-body rounded">
            <form
              id="modal-form"
              action=""
              method="post"
              enctype="multipart/form-data"
              class="form-horizontal"
            >
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Empleados (*)</label
                >
                <div class="col-md-9">
                  <select class="form-control rounded" v-model="idempleado">
                    <option value="0" disabled>Seleccione</option>
                    <option
                      v-for="empleado in arrayEmpleado"
                      :key="empleado.id"
                      :value="empleado.id"
                      v-text="empleado.apellido + ' ' + empleado.nombre"
                    ></option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Tipos de Contratos (*)</label
                >
                <div class="col-md-9">
                  <select class="form-control rounded" v-model="idTipoContrato">
                    <option value="0" disabled>Seleccione</option>
                    <option
                      v-for="tipoContrato in arrayTipoContrato"
                      :key="tipoContrato.id"
                      :value="tipoContrato.id"
                      v-text="
                        tipoContrato.nombre +
                        ' (' +
                        tipoContrato.diasMinimo +
                        ' - ' +
                        tipoContrato.diasMaximo +
                        ') Dias'
                      "
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
                    class="form-control rounded"
                    placeholder="Nombre del contrato"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Descripcion (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="descripcion"
                    class="form-control rounded"
                    placeholder="Descripcion del contrato"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Puestos (*)</label
                >
                <div class="col-md-9">
                  <select class="form-control rounded" v-model="idpuesto">
                    <option value="0" disabled>Seleccione</option>
                    <option
                      v-for="puesto in arrayPuesto"
                      :key="puesto.id"
                      :value="puesto.id"
                      v-text="puesto.nombre"
                    ></option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Horas Laborales (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="number"
                    v-model="cantidadHorasDiarias"
                    class="form-control rounded"
                    placeholder="Cantidad de horas laborales"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Salario (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="number"
                    v-model="salario"
                    class="form-control rounded"
                    placeholder="Salario laboral"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3">Fecha Inicio Laboral (*)</label>
                <div class="col-md-9">
                  <input
                    type="date"
                    class="form-control rounded"
                    v-model="inicioLaboral"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3">Fecha final del contrato (*)</label>
                <div class="col-md-9">
                  <input
                    type="date"
                    class="form-control rounded"
                    v-model="finLaboral"
                  />
                </div>
              </div>
              <div class="form-group row div-error">
                <div class="col-md-3">
                  <button
                    type="button"
                    class="btn btn-secondary"
                    @click="calculadorDias()"
                  >
                    Calcular Dias del contrato
                  </button>
                </div>
                <div class="col-md-9 text-center text-error">
                  <div
                    v-for="otro in otroMensaje"
                    :key="otro"
                    v-text="otro"
                  ></div>
                </div>
              </div>

              <div class="form-group row">
                <label
                  class="col-md-3"
                  for="data"
                  form
                  action="/data"
                  method="POST"
                  enctype="multipart/form-data"
                  >Contrato (*)
                </label>
                <div class="col-md-9">
                  <input
                    class="form-control rounded"
                    type="file"
                    accept="application/pdf"
                    @change="getImage"
                    id="data"
                    name="data"
                  />
                  <b><a v-if="contrato" :href="contrato">Ver Contrato</a></b>
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
              @click="registrarContrato()"
            >
              Guardar
            </button>
            <button
              type="button"
              v-if="tipoAccion == 2"
              class="btn btn-primary"
              @click="actualizarContrato()"
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
    <!--Inicio del modal agregar/actualizar evaluation-->
    <div id="nico"  class="modal rounded" tabindex="-2" :class="{mostrartwo: modaltwo}" role="dialog" aria-labelledby="myModalLabel2" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content rounded">
                <div class="modal-header rounded">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body rounded">
                    <form id="modal-form2" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Detalle (*)</label>
                            <div class="col-md-9">
                                <textarea type="text" v-model="evaluacion" class="form-control rounded" placeholder="Detalle" required>
                                </textarea>
                                
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="evaluacion" class="btn btn-primary" @click="updateEvaluation()">Guardar</button>
                    <button type="button" v-if="!evaluacion" class="btn btn-primary" @click="updateEvaluation()">Actualizar</button>
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
      contrato_id: 0,
      nombre: "",
      descripcion: "",
      arrayPuesto: [],
      arrayEmpleado: [],
      arrayContrato: [],
      arrayTipoContrato: [],
      tipoContrato_id_filtro:0,
      cantidadHorasDiarias: 0,
      salario: 0.0,
      inicioLaboral: "",
      finLaboral: "",
      contrato: "",
      idpuesto: 0,
      idempleado: 0,
      idTipoContrato: 0,
      modal: 0,
      modaltwo: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorComponente: 0,
      errorMostrarMsjForm: [],
      otroMensaje: [],
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
      this.listarContrato(page, buscar, criterio);
    },
    listarContrato(page, buscar, criterio) {
      let me = this;
      var url =
        "/contrato?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio +
        "&tipoContrato_id_filtro="+
        me.tipoContrato_id_filtro;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;

          me.arrayContrato = respuesta.contratos.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getImage(event) {
      //Asignamos la imagen a  nuestra data
      //this.imagen = event.target.files[0];
      var fileReader = new FileReader();
      fileReader.readAsDataURL(event.target.files[0]);
      fileReader.onload = (event) => {
        this.contrato = event.target.result;
      };
      //console.log(this.curriculum);
    },
    selectPuesto() {
      let me = this;
      //loading(true)

      var url = "/puesto/selectPuesto";
      axios
        .get(url)
        .then(function (response) {
          let respuesta = response.data;
          //q: search
          me.arrayPuesto = respuesta.puestos;
          console.log("select Puesto");
          //loading(false)
        })
        .catch(function (error) {
          console.log(error);
        });
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
    selectTipoContrato() {
      let me = this;
      //loading(true)
      var url = "/tipoContrato/selectTipoContrato";
      axios
        .get(url)
        .then(function (response) {
          let respuesta = response.data;
          //q: search
          me.arrayTipoContrato = respuesta.tipoContratos;
          console.log("select Empleado");
          //loading(false)
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
    calculadorDias() {
      let me = this;
      me.otroMensaje = [];
      if(me.idTipoContrato==0){
      toastr.error("Debe seleccionar el tipo de contrato", "Error", { timeOut: 5000 });
      return 'error';
      }
      if (this.inicioLaboral != "" && this.finLaboral != "") {
        if(this.inicioLaboral > this.finLaboral){
          me.otroMensaje=[];
          me.otroMensaje.push("La fecha de inicio tiene que ser menor a la fecha de finalizacion de contrato");
          return;
        }
        axios
          .post("/contrato/calculadorDias", {
            inicioLaboral: me.inicioLaboral,
            finLaboral: me.finLaboral,
            tipoContrato_id: me.idTipoContrato,
          })
          .then(function (response) {
            console.log(response);
            me.otroMensaje.push(response.data);
            me.listarTabla(1, "", "nombre");
          })
          .catch(function (error) {
            console.log(error);
            toastr.error("Error al calcular los dias", "Error", {
              timeOut: 5000,
            });
          });
      } else {
        me.otroMensaje.push("Seleccione la fecha de inicio y fin laboral");
      }
    },
    registrarContrato() {
      let me = this;
      if (this.validarForm()) {
        return;
      }
      axios
        .post("/contrato/registrar", {
          nombre: this.nombre,
          descripcion: this.descripcion,
          idpuesto: this.idpuesto,
          idempleado: this.idempleado,
          idtipocontrato: this.idTipoContrato,
          cantidadHorasDiarias: this.cantidadHorasDiarias,
          salario: this.salario,
          inicioLaboral: this.inicioLaboral,
          finLaboral: this.finLaboral,
          contrato: this.contrato,
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
    actualizarContrato() {
      console.log("prueba");
      if (this.validarForm()) {
        return;
      }

      let me = this;
      axios
        .put("/contrato/actualizar", {
          nombre: this.nombre,
          descripcion: this.descripcion,
          idpuesto: this.idpuesto,
          idempleado: this.idempleado,
          idtipocontrato: this.idTipoContrato,
          cantidadHorasDiarias: this.cantidadHorasDiarias,
          salario: this.salario,
          inicioLaboral: this.inicioLaboral,
          finLaboral: this.finLaboral,
          contrato: this.contrato,
          id: this.contrato_id,
        })
        .then(function (response) {
          console.log(response);
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
        title: "Esta seguro de desactivar este Contrato?",
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
            .put("/contrato/desactivar", {
              id: id,
            })
            .then(function (response) {
              me.listarTabla(1, "", "nombre");
              swal(
                "Desactivado!",
                "El contrato se ha desactivado con éxito.",
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
    updateEvaluation() {
      console.log("prueba");

      let me = this;
      axios
        .post("/contrato/evaluation", {
          evaluacion: this.evaluacion,
          id: this.id,
        })
        .then(function (response) {
          console.log(response);
          me.cerrarModal();
          me.listarTabla(1, "", "nombre");
          toastr.success("Se ha agregado la evaluación", "Actualizado", {
            timeOut: 5000,
          });
        })
        .catch(function (error) {
          console.log(error);
          toastr.error("Ha ocurrido un error", "Error", { timeOut: 5000 });
        });
    },
    activarContrato(id) {
      swal({
        title: "Esta seguro de activar este contrato?",
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
            .put("/contrato/activar", {
              id: id,
            })
            .then(function (response) {
              me.listarTabla(1, "", "nombre");
              swal(
                "Activado!",
                "El Contrato se ha activado con éxito.",
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
        this.errorMostrarMsjForm.push("Debe ingresar el nombre del Contrato");
      if (!this.cantidadHorasDiarias)
        this.errorMostrarMsjForm.push(
          "Debe ingresar una cantidad de horas de trabajo"
        );
      if (!this.salario)
        this.errorMostrarMsjForm.push("Debe ingresar un salario mayor a 0.0");
      if (!this.idpuesto)
        this.errorMostrarMsjForm.push("Debe seleccionar un puesto");
      if (!this.idempleado)
        this.errorMostrarMsjForm.push("Debe seleccionar un empleado");
      if (!this.inicioLaboral)
        this.errorMostrarMsjForm.push("Debe seleccionar una fecha inicial");
      if (!this.finLaboral)
        this.errorMostrarMsjForm.push(
          "Debe seleccionar una fecha final del contrato"
        );
      if(this.inicioLaboral > this.finLaboral){
          this.errorMostrarMsjForm.push("La fecha de inicio tiene que ser menor a la fecha de finalizacion de contrato");
          //return;
        }  
        var dia = new Date();
        var comparar = new Date(this.inicioLaboral);
        if(comparar < dia){
          this.errorMostrarMsjForm.push("La fecha de inicio tiene que ser mayor a la fecha actual");        
        }  
      if (!this.contrato)
        this.errorMostrarMsjForm.push("Debe subir el archivo del contrato");
      if (this.finLaboral < this.inicioLaboral)
        this.errorMostrarMsjForm.push(
          "El inicio laboral debe ser igual o mas grande que el final del contrato"
        );
      if (this.errorMostrarMsjForm.length) this.errorComponente = 1;
      return this.errorComponente;
    },
    cerrarModal() {
      this.modal = 0;
      this.modaltwo = 0;
      this.tituloModal = "";
      this.nombre = "";
      this.descripcion = "";
      this.idpuesto = 0;
      this.idempleado = 0;
      this.idTipoContrato = 0;
      this.cantidadHorasDiarias = 0;
      this.salario = 0.0;
      this.inicioLaboral = "";
      this.finLaboral = "";
      this.contrato = "";
    },

    abrirModal(modelo, accion, data = []) {
      this.otroMensaje=[];
      switch (modelo) {
        case "contrato": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Contrato";
              this.contrato_id = "";
              this.nombre = "";
              this.descripcion = "";
              this.idpuesto = 0;
              this.idempleado = 0;
              this.idTipoContrato = 0;
              this.cantidadHorasDiarias = 0;
              this.salario = 0.0;
              this.inicioLaboral = "";
              this.finLaboral = "";
              this.contrato = "";
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar contrato";
              this.tipoAccion = 2;

              this.contrato_id = data["id"];
              this.nombre = data["nombre"];
              this.descripcion = data["descripcion"];
              this.idpuesto = data["puesto_id"];
              this.idempleado = data["empleado_id"];
              this.idTipoContrato = data["tipoContrato_id"];
              this.cantidadHorasDiarias = data["cantidadHorasDiarias"];
              this.salario = data["salario"];
              this.inicioLaboral = data["inicioLaboral"];
              this.finLaboral = data["finLaboral"];
              this.contrato = data["contrato"];

              break;
            }
          }
        }
      }
    },
    abrirModal2(modelo, accion, data = []) {
      this.otroMensaje=[];
      switch (modelo) {
        case "evaluacion": {
          this.modaltwo = 1;
          this.tituloModal = "Evaluacion";
          this.evaluacion = data["evaluacion"];
          this.id = data["id"];
          break;
        }
      }
    }
  },
  mounted() {
    this.listarTabla(1, this.buscar, this.criterio);
    this.selectPuesto();
    this.selectEmpleado();
    this.selectTipoContrato();
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
.mostrartwo {
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
