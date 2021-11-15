<template>
            <main class="main">
            <!-- Breadcrumb -->
            
            <div class="container-fluid rounded">
                <!-- Ejemplo de tabla Listado -->
                <div class="card rounded mt-2">
                    <div class="card-header rounded">
                        <div class="row justify-content-between rounded">
                            <h3>Incidencias</h3>
                            <button type="button" @click="abrirModal('incidencia','registrar')" class="btn btn-secondary btn-sm">
                                <i class="icon-plus"></i>&nbsp;Nueva
                            </button>
                        </div>
                    </div>
                    <div class="card-body rounded">
                        <!-- <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                    
                                    <option value="nombre">Nombre</option>
                                    <option value="diasMinimo">Dias Minimos</option>
                                    <option value="diasMaximo">Dias Maximos</option>
                                   
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarIncidencia(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarIncidencia(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div> -->
                        <table class="table table-bordered table-striped table-sm rounded">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Días Mínimo</th>
                                    <th>Días máximo</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="incidencia in arrayIncidencia" :key="incidencia.id">
                                   
                                    <td v-text="incidencia.nombre"></td>
                                    <td v-text="incidencia.diasMinimo"></td>
                                    <td v-text="incidencia.diasMaximo"></td>
                                 
                                    <td>
                                        <div v-if="incidencia.condicion">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Inactivo</span>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" @click="abrirModal('incidencia','actualizar',incidencia)" class="btn btn-dark btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="incidencia.condicion">
                                            <button type="button" class="btn btn-dark btn-sm" @click="desactivarIncidencia(incidencia.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-dark btn-sm" @click="activarIncidencia(incidencia.id)">
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
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="modal-form" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la incidencia">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Días Mínimo (*)</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="diasMinimo" class="form-control" placeholder="Ingrese la cantidad de días mínimos que  incidencia">
                                    </div>
                                </div>
                               
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Días Máximo (*)</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="diasMaximo" class="form-control" placeholder="Ingrese la cantidad de días máximo que puede tener la incidencia">
                                    </div>
                                </div>
                               
                                
                                <!-- <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="data" form action="/data" method="POST" enctype="multipart/form-data">Curriculum (*) </label>
                                        <input type="file" @change="getImage" id="data" name="data" >            
                                    </div>
                                </div> -->
                                <div v-show="errorIncidencia" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjIncidencia" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarIncidencia()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarIncidencia()">Actualizar</button>
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
                incidencia_id: 0,
                nombre : '',
                diasMinimo : '',
                diasMaximo : '',
                arrayIncidencia : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorIncidencia : 0,
                errorMostrarMsjIncidencia : [],
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
            listarIncidencia (page,buscar,criterio){
                let me=this;
                var url= '/incidencia?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayIncidencia = respuesta.incidencias.data;
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
                me.listarIncidencia(page,buscar,criterio);
            },
            registrarIncidencia(){
                let me = this;
                if (this.validarIncidencia()){
                    return;
                }
                axios.post('/incidencia/registrar',{
                    'nombre': this.nombre,
                    'diasMinimo': this.diasMinimo,
                    'diasMaximo': this.diasMaximo
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarIncidencia(1,'','nombre');
                    toastr.success('Se ha registrado la incidencia', 'Actualizado', {timeOut: 5000})
                }).catch(function (error) {
                    console.log(error);
                    toastr.error('Ha ocurrido un error', 'Error', {timeOut: 5000})
                });
            },
            actualizarIncidencia(){

                console.log('prueba');
               if (this.validarIncidencia()){
                    return;
                }
                
                let me = this;
                axios.put('/incidencia/actualizar',{
                    'nombre': this.nombre,
                    'diasMinimo': this.diasMinimo,
                    'diasMaximo': this.diasMaximo,
                    'id': this.incidencia_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarIncidencia(1,'','nombre');
                    toastr.success('Se ha actualizado con exito', 'Actualizado', {timeOut: 5000})
                }).catch(function (error) {
                    console.log(error);
                    toastr.error('Ha ocurrido un error', 'Error', {timeOut: 5000})
                }); 
            },
            desactivarIncidencia(id){
               swal({
                title: 'Esta seguro de desactivar esta incidencia?',
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

                    axios.put('/incidencia/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarIncidencia(1,'','nombre');
                        swal(
                        'Desactivado!',
                        'La incidencia se ha desactivado con éxito.',
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
            activarIncidencia(id){
               swal({
                title: 'Esta seguro de activar este incidencia?',
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

                    axios.put('/incidencia/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarIncidencia(1,'','nombre');
                        swal(
                        'Activado!',
                        'La incidencia se ha activado con éxito.',
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
            
            
            validarIncidencia(){
                this.errorIncidencia=0;
                this.errorMostrarMsjIncidencia =[];

                if (!this.nombre) this.errorMostrarMsjIncidencia.push("Debe ingresar el nombre de la incidencia");
                if (!this.diasMinimo) this.errorMostrarMsjIncidencia.push("Debe ingresar la cantidad de días mínimos");
                if (!this.diasMaximo) this.errorMostrarMsjIncidencia.push("Debe ingresar la cantidad de días máximos");
                if (this.errorMostrarMsjIncidencia.length) this.errorIncidencia = 1;
                return this.errorIncidencia;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.diasMinimo='';
                this.diasMaximo='';
            },
            
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "incidencia":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar incidencia';
                                this.nombre= '';
                                this.diasMinimo='';
                                this.diasMaximo='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar incidencia';
                                this.tipoAccion=2;
                                this.incidencia_id=data['id'];
                                this.nombre = data['nombre'];
                                this.diasMinimo= data['diasMinimo'];
                                this.diasMaximo= data['diasMaximo'];
                                break;
                            }
                        }
                    }
                }
            }
            
        },
        mounted() {
            this.listarIncidencia(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
