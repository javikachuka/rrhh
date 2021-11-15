<template>
  <main class="main">
    <!-- Breadcrumb -->
   
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Personas Dependientes
          <button
            type="button"
            @click="abrirModal('personaDependiente', 'registrar')"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nueva
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                  <option value="nombre">Nombre</option>
                  <option value="apellido">Apellido</option>
                  <option value="dni">DNI</option>
                  <option value="empleado_id">ID Empleado</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  @keyup.enter="listarPersonasDependientes(1, buscar, criterio)"
                  class="form-control"
                  placeholder="Texto a buscar"
                />
                <button
                  type="submit"
                  @click="listarPersonasDependientes(1, buscar, criterio)"
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
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Relacion</th>
                <th>DNI</th>
                <th>Necesidad</th>
                <th>Tutor o Empleado (ID)</th>
                <th>Estado</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="personaDependiente in arrayPersonaDependientes"
                :key="personaDependiente.id"
              >
                <td v-text="personaDependiente.apellido"></td>
                <td v-text="personaDependiente.nombre"></td>
                <td v-text="personaDependiente.relacion"></td>
                <td v-text="personaDependiente.dni"></td>
                <td v-text="personaDependiente.necesidad"></td>
                <td v-text="personaDependiente.apellidoEmpleado +' '+personaDependiente.nombreEmpleado+ ' ('+personaDependiente.empleado_id+')' "></td>

                <td>
                  <div v-if="personaDependiente.condicion">
                    <span class="badge badge-success">Activo</span>
                  </div>
                  <div v-else>
                    <span class="badge badge-danger">Desactivado</span>
                  </div>
                </td>
                <td>
                  <button
                    type="button"
                    @click="
                      abrirModal(
                        'personaDependiente',
                        'actualizar',
                        personaDependiente
                      )
                    "
                    class="btn btn-warning btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button>
                  &nbsp;
                  <template v-if="personaDependiente.condicion">
                    <button
                      type="button"
                      class="btn btn-danger btn-sm"
                      @click="
                        desactivarPersonaDependiente(personaDependiente.id)
                      "
                    >
                      <i class="icon-trash"></i>
                    </button>
                  </template>
                  <template v-else>
                    <button
                      type="button"
                      class="btn btn-info btn-sm"
                      @click="activarPersonaDependiente(personaDependiente.id)"
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
                  >Nombre (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre"
                    class="form-control"
                    placeholder="Nombre de la persona dependiente"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Apellido (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="apellido"
                    class="form-control"
                    placeholder="Apellido de la persona dependiente"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >DNI (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="dni"
                    class="form-control"
                    placeholder="DNI de la persona dependiente"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Relacion (*)</label
                >
                <div class="col-md-9">
                  <select  v-model="relacion"  class="form-control">
                  <option disabled value="">Relación de la persona dependiente</option>
                  <option value="Hijo/a" >Hijo/a</option>
                  <option value="Esposo/a" >Esposo/a</option>
                  <option value="Hermano/a">Hermano/a</option>
                </select>
                </div>
              </div>
                
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Necesidad (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="necesidad"
                    class="form-control"
                    placeholder="Necesidad de la persona dependiente"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Empleado (*)</label
                >
                <div class="col-md-9">
                  <select class="form-control" v-model="empleado_id">
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
              <div
                v-show="errorPersonaDependiente"
                class="form-group row div-error"
              >
                <div class="text-center text-error">
                  <div
                    v-for="error in errorMostrarMsj"
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
              @click="registrarPersonaDependiente()"
            >
              Guardar
            </button>
            <button
              type="button"
              v-if="tipoAccion == 2"
              class="btn btn-primary"
              @click="actualizarPersonaDependiente()"
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
import toastr from 'toastr'; 
    export default {
        data (){
            return {
                _id: 0,
                nombre:'',
                apellido:'',
                relacion:'',
                necesidad:'',
                dni:'',
                empleado_id:'',
                arrayEmpleado:[],
                arrayPersonaDependientes : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorPersonaDependiente : 0,
                errorMostrarMsj : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : ''
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            listarPersonasDependientes (page,buscar,criterio){
                let me=this;
                var url= '/personaDependiente?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayPersonaDependientes = respuesta.personaDependientes.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarPersonasDependientes(page,buscar,criterio);
            },
            registrarPersonaDependiente(){
                let me = this;
                if (this.validarForm()){
                    return;
                }
                axios.post('/personaDependiente/registrar',{
                    nombre:this.nombre,
                    apellido:this.apellido,
                    relacion:this.relacion,
                    necesidad:this.necesidad,
                    dni:this.dni,
                    empleado_id:this.empleado_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarPersonasDependientes(1,'','nombre');
                    toastr.success('Se ha registrado la personaDependiente', 'Actualizado', {timeOut: 5000})
                }).catch(function (error) {
                    console.log(error);
                    toastr.error('Ha ocurrido un error', 'Error', {timeOut: 5000})
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
            actualizarPersonaDependiente(){

                console.log('prueba');
               if (this.validarForm()){
                    return;
                }
                
                let me = this;
                axios.put('/personaDependiente/actualizar',{
                    nombre:this.nombre,
                    apellido:this.apellido,
                    relacion:this.relacion,
                    necesidad:this.necesidad,
                    dni:this.dni,
                    empleado_id:this.empleado_id,
                    id: this._id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarPersonasDependientes(1,'','nombre');
                    toastr.success('Se ha actualizado con exito', 'Actualizado', {timeOut: 5000})
                }).catch(function (error) {
                    console.log(error);
                    toastr.error('Ha ocurrido un error', 'Error', {timeOut: 5000})
                }); 
            },
            desactivarPersonaDependiente(id){
               swal({
                title: 'Esta seguro de desactivar ',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/personaDependiente/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarPersonasDependientes(1,'','nombre');
                        swal(
                        'Desactivado!',
                        'La Persona Dependiente se ha desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            activarPersonaDependiente(id){
               swal({
                title: 'Esta seguro de activarlo?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/personaDependiente/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarPersonasDependientes(1,'','nombre');
                        swal(
                        'Activado!',
                        'Se ha activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            
            
            validarForm(){
                this.errorPersonaDependiente=0;
                this.errorMostrarMsj =[];

                if (!this.nombre) this.errorMostrarMsj.push("Debe ingresar el nombre de la persona");
                if (!this.apellido) this.errorMostrarMsj.push("Debe ingresar el apellido de la persona");
                if (!this.relacion) this.errorMostrarMsj.push("Debe ingresar la relacion del empleado con esta persona");
                if (!this.necesidad) this.errorMostrarMsj.push("Debe ingresar que necesita la persona dependiente");
                if (!this.dni) this.errorMostrarMsj.push("Debe ingresar el dni de la persona dependiente");
                if (!this.empleado_id) this.errorMostrarMsj.push("debe seleccionar el empleado responsable de esta persona");
                if (this.errorMostrarMsj.length) this.errorPersonaDependiente = 1;
                return this.errorPersonaDependiente;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.apellido='';
                this.relacion='';
                this.necesidad='';
                this.dni='';
                this.empleado_id='';
            },
            
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "personaDependiente":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar persona dependiente';
                                this.nombre='';
                                this.apellido='';
                                this.relacion='';
                                this.necesidad='';
                                this.dni='';
                                this.empleado_id='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar persona dependiente';
                                this.tipoAccion=2;
                                this._id=data['id'];
                                this.nombre = data['nombre'];
                                this.apellido = data['apellido'];
                                this.relacion = data['relacion'];
                                this.necesidad = data['necesidad'];
                                this.dni = data['dni'];
                                this.empleado_id = data['empleado_id'];
                               
                                break;
                            }
                        }
                    }
                }
            }
            
        },
        mounted() {
            this.listarPersonasDependientes(1,this.buscar,this.criterio);
            this.selectEmpleado();
        }
    }
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
