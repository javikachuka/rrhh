<template>
  <main class="main">
    <!-- Breadcrumb -->

    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card mt-2 rounded">
        <div class="card-header rounded">
          <div class="row justify-content-between rounded">
            <h3>Empleados</h3>
            <button
              v-if="listado == 1"
              type="button"
              @click="mostrarDetalle()"
              class="btn btn-secondary btn-sm float-right"
            >
              <i class="icon-plus"></i>&nbsp;Nuevo
            </button>
          </div>
        </div>
        <!-- Listado-->
        <template v-if="listado == 1">
          <div class="card-body rounded">
            <form action="/empleado/pdf" method="get">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-3" v-model="criterio" name="criterio">
                    <option value="nombre">Nombre</option>
                    <option value="apellido">Apellido</option>
                    <option value="enlicencia">En Licencia</option>
                    <option value="trabajando">Trabajando</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                    <option value="cuil">Cuil</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscar"
                    @keyup.enter="listarEmpleado(1, buscar, criterio)"
                    class="form-control"
                    name="buscar"
                    placeholder="Texto a buscar"
                  />
                  <button
                    type="button"
                    @click="listarEmpleado(1, buscar, criterio)"
                    class="btn btn-primary"
                  >
                    <i class="fa fa-search"></i>
                  </button>
                </div>
              </div>
              <div class="col">
                <button
                  type="submit"
                  class="btn btn-danger float-right"
                >
                  Generar <i class="fa fa-file"></i>
                </button>
              </div>
            </div>
            </form>
            <div class="table-responsive rounded">
              <table class="table table-bordered table-striped table-sm rounded">
                <thead>
                  <tr>
                    <th>Cuil</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="empleado in arrayEmpleado" :key="empleado.id">
                    <td v-text="empleado.cuil"></td>
                    <td v-text="empleado.nombre"></td>
                    <td v-text="empleado.apellido"></td>
                    <td v-text="empleado.direccion"></td>
                    <td>
                      <div v-if="empleado.condicion">
                        <span class="badge badge-success">Activo</span>
                      </div>
                      <div v-else>
                        <span class="badge badge-danger">Inactivo</span>
                      </div>
                    </td>
                    <td>
                      <button
                        type="button"
                        @click="modificarEmpleado(empleado)"
                        class="btn btn-dark btn-sm"
                      >
                        <i class="icon-pencil"></i>
                      </button>
                      &nbsp;
                      <template v-if="empleado.condicion">
                        <button
                          type="button"
                          class="btn btn-dark btn-sm"
                          @click="desactivarEmpleado(empleado.id)"
                        >
                          <i class="icon-trash"></i>
                        </button>
                      </template>
                      <template v-else>
                        <button
                          type="button"
                          class="btn btn-dark btn-sm"
                          @click="activarEmpleado(empleado.id)"
                        >
                          <i class="icon-check"></i>
                        </button>
                      </template>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <nav>
              <ul class="pagination">
                <li class="page-item" v-if="pagination.current_page > 1">
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="
                      cambiarPagina(
                        pagination.current_page - 1,
                        buscar,
                        criterio
                      )
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
                      cambiarPagina(
                        pagination.current_page + 1,
                        buscar,
                        criterio
                      )
                    "
                    >Sig</a
                  >
                </li>
              </ul>
            </nav>
          </div>
        </template>
        <!--Fin Listado-->
        <!-- Detalle-->
        <template v-else-if="listado == 0">
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-9">
                <!-- <div class="form-group">
                                    <label for="">Prueba(*)</label>
                                    <v-select
                                        :on-search="listarIncidencia"
                                        label="nombre"
                                        :options="arrayIncidencia"
                                        placeholder="Buscar Incidencias..."
                                        :onChange="getDatosIncidencia"                                        
                                    >
                                    </v-select>
                                </div> -->
              </div>
              <div class="col-md-12">
                <h4>Datos del empleado</h4>
              </div>
              <div class="col-md-12">
                <div
                  v-show="errorEmpleado"
                  class="form-group row div-error text-left"
                >
                  <div class="text-left text-error">
                    <ul>
                      <li
                        v-for="error in errorMostrarMsjEmpleado"
                        :key="error"
                        v-text="error"
                      ></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <b><label>Nombre (*)</label></b>
                <input type="text" class="form-control rounded" v-model="nombre" />
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <b><label>Apellido (*)</label></b>
                  <input type="text" class="form-control rounded" v-model="apellido" />
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <b><label>Cuil (*)</label> </b>
                  <input
                    type="text"
                    id="numero"
                    @keyup="valida()"
                    v-model="cuil"
                    class="form-control rounded"
                    maxlength="11"
                  />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <b><label>Estado Civil (*)</label> </b>

                  <select v-model="estadoCivil" class="form-control rounded">
                    <option disabled value="">Estado Civil</option>
                    <option value="Soltero/a">Soltero/a</option>
                    <option value="Casado/a">Casado/a</option>
                    <option value="Divorciado/a">Divorciado/a</option>
                    <option value="Viudo/a">Viudo/a</option>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <b><label>Dirección (*)</label> </b>
                  <input type="text" class="form-control rounded" v-model="direccion" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <b><label>Fecha de Nacimiento (*)</label> </b>
                  <input
                    type="date"
                    class="form-control rounded"
                    v-model="fechaNacimiento"
                    name="fechaNacimiento"
                    id="fechaNacimiento"
                    v-on:blur="validarNacimiento"
                  />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <b><label>Fecha de de Ingreso (*)</label> </b>
                  <input type="date" class="form-control rounded" v-model="fechaAlta" />
                </div>
              </div>

              <div class="col-md-9 mt-2">
              <h4>Salud</h4>
                <div class="form-group">
                   <b><label
                    for="data"
                    form
                    action="/data"
                    method="POST"
                    enctype="multipart/form-data"
                    >Pre-ocupacional (*)
                  </label> </b>
                  <input class="form-control" accept="application/pdf" type="file" @change="getImage" id="data" name="data" />
                </div>
              </div>
              <div class="col-md-12 mt-2">
                <h4>Contacto de Emergencia</h4>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <b><label>Nombre (*)</label></b>

                  <input
                    type="text"
                    class="form-control rounded"
                    v-model="nombreContacto"
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <b><label>Telefono (*)</label></b>

                  <input
                    type="text"
                    class="form-control rounded"
                    v-model="telefono1"
                    placeholder=""
                    maxlength="10"
                  />
                </div>
              </div>
                <div class="col-md-9 mt-2">
                <h4>Habilidades</h4>
                <div class="form-group">
                 <b><label>Competencias</label> </b>

                <multiselect
                  v-model="competenciasId"
                  :options="arrayCompetencias"
                  :multiple="true"
                  label="nombre"
                  track-by="id"
                  placeholder="Seleccione una o más competencias"
                >
                </multiselect>
              </div>
              </div>
              <!-- <div> 
                <h4>Datos Contractuales</h4>
                <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Empleado (*)</label
                >
                <div class="col-md-9">
                  <select class="form-control" v-model="empleado_id">
                    <option value="0" disabled>Seleccione el Empleado</option>
                    <option
                      v-for="empleado in arrayEmpleado"
                      :key="empleado.id"
                      :value="empleado.id"
                      v-text="empleado.apellido + ' ' + empleado.nombre"
                    ></option>
                  </select>
                </div>
              </div>
              </div> -->
              <!-- <div class="col-md-6">
                <div class="form-group">
                
                  <label>Telefono 2(*)</label>
                 
                  <input
                    type="text"
                    class="form-control"
                    v-model="telefono2"
                    placeholder="376xxxxxxx"
                    maxlength="10"
                  />
                   </div>
                </div> -->
              <!-- <div class="col-md-6">
                <div class="form-group">
               
                  
                    <label>Correo(*)</label>
                     
                    <input
                      type="email"
                      class="form-control"
                      v-model="correo"
                      maxlength="100"
                    />
                  </div>
                
                </div> -->
              <!-- <div class="col-md-4">
                <div class="form-group">
                  <button
                    @click="agregarDetalle()"
                    class="btn btn-success form-control btnagregar"
                  >
                    Agregar Contacto
                    <i class="icon-plus"></i>
                  </button>
                </div>
              </div> -->

              <br />
              <!-- <div class="col-md-9">
                <div class="form-group">
                  <div class="table-responsive col-md-9">
                    <table class="table table-bordered table-striped table-sm">
                      <thead>
                        <tr>
                          <th>Nombre</th>
                          <th>Telefono 1</th>
                          <th>Telefono 2</th>
                          <th>Correo</th>
                          <th>Acción</th>
                        </tr>
                      </thead>
                      <tbody v-if="arrayDetalle.length">
                        <tr
                          v-for="(detalle, index) in arrayDetalle"
                          :key="detalle.id"
                        >
                          <td v-text="detalle.nombre"></td>

                          <td v-text="detalle.telefono1"></td>
                          <td v-text="detalle.telefono2"></td>
                          <td v-text="detalle.correo"></td>
                          <td>
                            <button
                              @click="eliminarDetalle(index)"
                              type="button"
                              class="btn btn-danger btn-sm"
                            >
                              <i class="icon-close"></i>
                            </button>
                          </td>
                        </tr>
                      </tbody>
                      <tbody v-else>
                        <tr>
                          <td colspan="5">
                            No hay contactos de emergencia cargados
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div> -->
            </div>

            <div class="form-group row">
              <div class="col-md-12">
                <button
                  type="button"
                  @click="ocultarDetalle()"
                  class="btn btn-secondary"
                >
                  Cerrar
                </button>
                <button
                  type="button"
                  v-if="modificar == 0"
                  class="btn btn-primary"
                  @click="registrarEmpleado()"
                >
                  Guardar
                </button>
                <button
                  type="button"
                  v-if="modificar == 1"
                  class="btn btn-primary"
                  @click="actualizarEmpleado()"
                >
                  Actualizar Empleado
                </button>
              </div>
            </div>
          </div>
        </template>
        <!-- Fin Detalle-->
        <!-- Ver empleado -->
        <template v-else-if="listado == 2">
          <div class="card-body">
            <div class="form-group row border">
              <div class="col-md-9">
                <div class="form-group">
                  <label for="">Nombre</label>
                  <p v-text="nombre"></p>
                </div>
              </div>
              <div class="col-md-3">
                <label for="">Apellido</label>
                <p v-text="apellido"></p>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Cuil</label>
                  <p v-text="tipo_comprobante"></p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Serie Comprobante</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Número Comprobante</label>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-12">
                <button
                  type="button"
                  @click="ocultarDetalle()"
                  class="btn btn-secondary"
                >
                  Cerrar
                </button>
              </div>
            </div>
          </div>
        </template>
        <!-- fin ver empleado -->
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
  </main>
</template>

<script>
/*import vSelect from 'vue-select';*/
import Multiselect from "vue-multiselect";
import toastr from "toastr";
// import 'vue-select/dist/vue-select.css';
//Vue.component("v-select", vSelect);
// Vue.component('v-select', VueSelect.VueSelect);
export default {
  data() {
    return {
      empleado_id: 0,
      idincidencia: 0,
      arrayCompetencias: [],
      arrayEliminar: [],
      competenciasId: [],
      nombreContacto: "",
      estadoCivil: "",
      telefono1: "",
      telefono2: "",
      nombre: "",
      correo: "",
      apellido: "",
      cuil: "",
      fechaAlta: "",
      fechaBaja: "",
      curriculum: "",
      fechaNacimiento: "",
      direccion: "",
      arrayEmpleado: [],
      arrayIncidencia: [],
      arrayDetalle: [],
      listado: 1,
      modificar: 0,
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorEmpleado: 0,
      errorMostrarMsjEmpleado: [],
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
      criterioA: "nombre",
      buscarA: "",
    };
  },
  components: {
    //vSelect,
    Multiselect,
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
    calcularTotal: function () {
      var resultado = 0.0;
      for (var i = 0; i < this.arrayDetalle.length; i++) {
        resultado =
          resultado +
          (this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad -
            this.arrayDetalle[i].descuento);
      }
      return resultado;
    },
  },
  methods: {
    listarEmpleado(page, buscar, criterio) {
      let me = this;
      var url =
        "/empleado?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayEmpleado = respuesta.empleados.data;
          //me.otroArray = respuesta.masinfo.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    selectIncidencia() {
      console.log("en select");
      let me = this;
      //loading(true)
      console.log("en select");
      var url = "/incidencia/selectIncidencia";
      axios
        .get(url)
        .then(function (response) {
          let respuesta = response.data;
          //q: search
          me.arrayIncidencia = respuesta.incidencias;
          console.log(response.data.incidencias[0].nombre);
          //loading(false)
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    selectCompetencia() {
      let me = this;
      //loading(true)
      var url = "/competencia/selectCompetencia";
      axios
        .get(url)
        .then(function (response) {
          let respuesta = response.data;
          //q: search
          me.arrayCompetencias = respuesta.competencias;
          //loading(false)
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
        this.curriculum = event.target.result;
      };
    },

    // listarIncidencia (search,loading){
    //     console.log("en select");
    //     let me=this;
    //     loading(true)
    //     var url= '/incidencia/selectIncidencia?filtro='+search;
    //     axios.get(url).then(function (response) {
    //         let respuesta = response.data;
    //         q: search
    //         me.arrayIncidencia=respuesta.incidencias;
    //         console.log(respuesta.incidencias);
    //         loading(false)
    //     })
    //     .catch(function (error) {
    //         console.log(error);
    //     });
    // },
    getDatosIncidencia(val1) {
      let me = this;
      me.loading = true;
      me.idincidencia = val1.id;
    },

    cambiarPagina(page, buscar, criterio) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarEmpleado(page, buscar, criterio);
    },

    agregarDetalle() {
      let me = this;

      if (me.nombreContacto == "" || me.telefono1 == "") {
      } else {
        me.arrayDetalle.push({
          nombre: me.nombreContacto,
          telefono1: me.telefono1,
          telefono2: me.telefono2,
          correo: me.correo,
        });
        me.nombreContacto = "";
        me.telefono1 = "";
        me.telefono2 = "";
        me.correo = "";
      }
    },
    eliminarDetalle(index) {
      let me = this;
      me.arrayEliminar.push({
        id: me.arrayDetalle[index].id,
      });
      me.arrayDetalle.splice(index, 1);
    },

    registrarEmpleado() {
      let me = this;
      if (this.validarEmpleado()) {
        return;
      }
      if (me.esCUITValida() == false) {
        me.errorMostrarMsjEmpleado.push("El cuil ingresado no es válido");
        me.errorEmpleado = 1;
        return;
      }

      let arrayContactos = [{
        nombre: this.nombreContacto,
        telefono1: this.telefono1,
        telefono2: '54564',
        correo: 'asdfas@asdf.com'
      }]
      axios
        .post("/empleado/registrar", {
          nombre: this.nombre,
          apellido: this.apellido,
          cuil: this.cuil,
          direccion: this.direccion,
          estadoCivil: this.estadoCivil,
          fechaNacimiento: this.fechaNacimiento,
          fechaAlta: this.fechaAlta,
          curriculum: this.curriculum,
          data: this.competenciasId,
          contactos: arrayContactos,
        })
        .then(function (response) {
          me.cerrarModal();
          me.listarEmpleado(1, "", "nombre");
          me.ocultarDetalle();
          toastr.success("Se ha registrado el empleado", "Registrado", {
            timeOut: 5000,
          });
        })
        .catch(function (error) {
          console.log(error.response)
          if (error.response.data) {
            if (error.response.data.error) {
              toastr.error(error.response.data.error_description, "Error", { timeOut: 5000 });
            } else {
              toastr.error("Ha ocurrido un error", "Error", { timeOut: 5000 });
            }
          }
        });
    },
    actualizarEmpleado() {
      if (this.validarEmpleado()) {
        return;
      }
      /*if (me.esCUITValida() == false) {
        me.errorMostrarMsjEmpleado.push("El cuil ingresado no es válido");
        me.errorEmpleado = 1;
        return;
      }*/

      let me = this;
      console.log(this.competenciasId);
      let arrayContactos = [{
        nombre: this.nombreContacto,
        telefono1: this.telefono1,
        telefono2: '54564',
        correo: 'asdfas@asdf.com'
      }]
      axios
        .put("/empleado/actualizar", {
          nombre: this.nombre,
          apellido: this.apellido,
          cuil: this.cuil,
          direccion: this.direccion,
          estadoCivil: this.estadoCivil,
          fechaNacimiento: this.fechaNacimiento,
          fechaAlta: this.fechaAlta,
          curriculum: this.curriculum,
          data: this.competenciasId,
          contactos: arrayContactos,
          eliminar: this.arrayEliminar,
          id: this.id,
        })
        .then(function (response) {
          me.cerrarModal();
          me.listarEmpleado(1, "", "nombre");
          me.ocultarDetalle();
          toastr.success("Se ha actualizado el empleado", "Actualizado", {
            timeOut: 5000,
          });
        })
        .catch(function (error) {
          console.log(error);
          toastr.error("Ha ocurrido un error", "Error", { timeOut: 5000 });
        });
    },
    desactivarEmpleado(id) {
      swal({
        title: "Esta seguro de desactivar este empleado?",
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
            .put("/empleado/desactivar", {
              id: id,
            })
            .then(function (response) {
              me.listarEmpleado(1, "", "nombre");
              swal(
                "Desactivado!",
                "El registro ha sido desactivado con éxito.",
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
    activarEmpleado(id) {
      swal({
        title: "Esta seguro de activar este empleado?",
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
            .put("/empleado/activar", {
              id: id,
            })
            .then(function (response) {
              me.listarEmpleado(1, "", "nombre");
              swal(
                "Activado!",
                "El registro ha sido activado con éxito.",
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
    esCUITValida() {
      console.log(this.cuil);
      this.inputString = this.cuil.toString();
      if (this.inputString.length == 11) {
        return true
      } else {
        return false;
      }
    },
    valida() {
      const campoNumerico = document.getElementById("numero");

      campoNumerico.addEventListener("keydown", function (evento) {
        const teclaPresionada = evento.key;
        const teclaPresionadaEsUnNumero = Number.isInteger(
          parseInt(teclaPresionada)
        );

        const sePresionoUnaTeclaNoAdmitida =
          teclaPresionada != "ArrowDown" &&
          teclaPresionada != "ArrowUp" &&
          teclaPresionada != "ArrowLeft" &&
          teclaPresionada != "ArrowRight" &&
          teclaPresionada != "Backspace" &&
          teclaPresionada != "Delete" &&
          teclaPresionada != "Enter" &&
          !teclaPresionadaEsUnNumero;
        const comienzaPorCero =
          campoNumerico.value.length === 0 && teclaPresionada == 0;

        if (sePresionoUnaTeclaNoAdmitida || comienzaPorCero) {
          evento.preventDefault();
        }
      });
    },
    validarEmpleado() {
      this.errorEmpleado = 0;
      this.errorMostrarMsjEmpleado = [];

      if (!this.nombre)
        this.errorMostrarMsjEmpleado.push(
          "El nombre del empleado no puede estar vacío."
        );
      if (!this.apellido)
        this.errorMostrarMsjEmpleado.push(
          "El apellido del empleado no puede estar vacío."
        );
      if (!this.cuil)
        this.errorMostrarMsjEmpleado.push(
          "El cuil del empleado no puede estar vacío."
        );
      if (!this.direccion)
        this.errorMostrarMsjEmpleado.push(
          "La dirección del empleado no puede estar vacía."
        );
      if (!this.estadoCivil)
        this.errorMostrarMsjEmpleado.push(
          "La estado civil del empleado no puede estar vacío."
        );
      if (!this.fechaNacimiento)
        this.errorMostrarMsjEmpleado.push(
          "Debe ingresar la fecha de nacimiento."
        );
      if (!this.fechaAlta)
        this.errorMostrarMsjEmpleado.push(
          "La fecha de ingreso del empleado no puede estar vacía."
        );
      if (this.fechaAlta < this.fechaNacimiento)
        this.errorMostrarMsjEmpleado.push(
          "La fecha de nacimiento debe ser menor a la fecha en que se dio de alta el empleado."
        );
      if (!this.curriculum)
        this.errorMostrarMsjEmpleado.push(
          "Debe cargar el archivo con el Pre-ocupacional del empleado."
        );
      if (this.errorMostrarMsjEmpleado.length) this.errorEmpleado = 1;

      return this.errorEmpleado;
    },
    validarTelefono() {
      if (/^([0-9]{4})+([0-9]{6})$/i.test(this.telefono1)) {
        return "1";
      } else if (/^([0-9]{4})+([0-9]{6})$/i.test(this.telefono2)) {
        return "1";
      } else {
        return "0";
      }
    },
    mostrarDetalle() {
      let me = this;
      me.listado = 0;

      this.modificar = 0;
      this.nombre = "";
      this.apellido = "";
      this.cuil = "";
      this.direccion = "";
      this.estadoCivil = "";
      this.fechaNacimiento = null;
      this.fechaAlta = null;
      this.curriculum = "";
      this.empleado_id = "";
      this.competenciasId = [];
      this.arrayDetalle = [];
      this.arrayEliminar = [];
    },
    ocultarDetalle() {
      this.listado = 1;
      this.modificar = 0;
      this.nombre = "";
      this.apellido = "";
      this.cuil = "";
      this.direccion = "";
      this.estadoCivil = "";
      this.fechaNacimiento = null;
      this.fechaAlta = null;
      this.curriculum = "";
      this.empleado_id = "";
      this.competenciasId = [];
      this.arrayDetalle = [];
      this.arrayEliminar = [];
    },
    verEmpleado(id) {
      /*let me=this;
                me.listado=2;
                
                //Obtener los datos del ingreso
                var arrayEmpleadoT=[];
                var url= '/venta/obtenerCabecera?id=' + id;
                
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    arrayEmpleadoT = respuesta.venta;
                    me.nombre = arrayEmpleadoT[0]['nombre'];
                    
                })
                .catch(function (error) {
                    console.log(error);
                });

                //Obtener los datos de los detalles 
                var urld= '/venta/obtenerDetalles?id=' + id;
                
                axios.get(urld).then(function (response) {
                    console.log(response);
                    var respuesta= response.data;
                    me.arrayDetalle = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });  */
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
    },
    modificarEmpleado(data = []) {
      let me = this;
      me.listado = 0;
      me.modificar = 1;
      me.errorMostrarMsjEmpleado = [];
      me.id = data["id"];
      me.nombre = data["nombre"];
      me.apellido = data["apellido"];
      me.cuil = data["cuil"];
      me.estadoCivil = data["estadoCivil"];
      me.direccion = data["direccion"];
      me.fechaNacimiento = data["fechaNacimiento"];
      me.fechaAlta = data["fechaAlta"];
      me.fechaBaja = data["fechaBaja"];
      this.curriculum = data["curriculum"];
      var url = "/empleado/findCompetencias?id=" + data["id"];
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.competenciasId = respuesta.competencias;
        })
        .catch(function (error) {
          console.log(error);
        });
      var url = "/empleado/findContactos?id=" + data["id"];
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          console.log(respuesta.contactos);
          me.arrayDetalle = respuesta.contactos;
          me.nombreContacto = respuesta.contactos.length > 0 ? respuesta.contactos[respuesta.contactos.length-1].nombre : ''
          me.telefono1 = respuesta.contactos.length > 0 ? respuesta.contactos[respuesta.contactos.length-1].telefono1 : ''
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    validarNacimiento() {
      return true; // sacar esto para poder validar -> JAVI
      var myDate = $("#fechaNacimiento");
      var today = new Date();
      var hoy = new Date();
      var dd = today.getDate();
      var mm = today.getMonth() + 1;
      var yyyy = today.getFullYear();
      if (dd < 10) dd = "0" + dd;

      if (mm < 10) mm = "0" + mm;
      var cumpleanos = new Date(this.fechaNacimiento);
      var edad = hoy.getFullYear() - cumpleanos.getFullYear();
      var m = hoy.getMonth() - cumpleanos.getMonth();
      today = yyyy + "-" + mm + "-" + dd;
      myDate.attr("max", today);

      if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
        edad--;
      }
      console.log(edad);
      var date = myDate.val();
      if (Date.parse(date)) {
        if (date > today) {
          toastr.error(
            "Te agradecería si nos decis como viajar en el tiempo :)",
            "Error",
            {
              timeOut: 5000,
            }
          );
          myDate.val("");
        }
        if (edad < 18) {
          toastr.error("El empleado no puede ser menor de edad", "Error", {
            timeOut: 5000,
          });
          myDate.val("");
        }
      }
    },
  },
  mounted() {
    this.listarEmpleado(1, this.buscar, this.criterio);
    this.selectIncidencia();
    this.selectCompetencia();
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
@media (min-width: 600px) {
  .btnagregar {
    margin-top: 2rem;
  }
}
</style>
