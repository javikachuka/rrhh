<template>
            <main class="main">
            <!-- Breadcrumb -->
           
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Eventos
                        <button type="button" @click="abrirModal('evento','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                    
                                    <option value="titulo">Título</option>
                                   
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarEvento(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarEvento(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Descripción</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="evento in arrayEvento" :key="evento.id">
                                   
                                    <td v-text="evento.titulo"></td>
                                    <td v-text="evento.descripcion"></td>
                                    <td v-text="evento.fecha"></td>
                                 
                                    <td>
                                        <div v-if="evento.condicion">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" @click="abrirModal('evento','actualizar',evento)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="evento.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarEvento(evento.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarEvento(evento.id)">
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
                <div class="modal-dialog modal-primary modal-lg" role="document">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Título (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="titulo" class="form-control" placeholder="Ingrese el título del evento">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="descripcion" class="form-control" placeholder="Ingrese una descripción en caso de que sea necesario">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ingrese la fecha del evento (*)</label>
                                     <div class="col-md-9">
                                        <input type="date" class="form-control" v-model="fecha" >
                                    </div>
                                </div>
  
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ingrese la hora del evento (*)</label>
                                     <div class="col-md-9">
                                        <input type="time" class="form-control" v-model="hora" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ingrese la fecha en que finaliza el evento (*)</label>
                                     <div class="col-md-9">
                                        <input type="date" class="form-control" v-model="fechaFin" >
                                    </div>
                                </div>
  
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ingrese la hora en que finaliza el evento (*)</label>
                                     <div class="col-md-9">
                                        <input type="time" class="form-control" v-model="horaFin" >
                                    </div>
                                </div>
  
                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ingrese el departamento</label>
                                    <div class="col-md-9">
                                       <select class="form-control" v-model="departamento_id">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="departamento in arrayDepartamento" :key="departamento.id" :value="departamento.id" v-text="departamento.nombre"></option>
                                        </select>  
                                                                  
                                    </div>
                                </div>
                                
                                <!-- <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="data" form action="/data" method="POST" enctype="multipart/form-data">Curriculum (*) </label>
                                        <input type="file" @change="getImage" id="data" name="data" >            
                                    </div>
                                </div> -->
                                <div v-show="errorEvento" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjEvento" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEvento()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEvento()">Actualizar</button>
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
import DatePicker from 'vue2-datepicker';
    export default {
        data (){
            return {
                evento_id: 0,
                nombre : '',
                titulo : '',
                fecha : new Date(),
                fechaFin : new Date(),
                descripcion : '',
                hora: '',
                 horaFin: '',
                departamento_id : '',
                diasMaximo : '',
                arrayEvento : [],
                arrayDepartamento : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorEvento : 0,
                errorMostrarMsjEvento : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'titulo',
                buscar : ''
            }
        },
        components: {
            DatePicker
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
            listarEvento (page,buscar,criterio){
                let me=this;
                var url= '/evento?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayEvento = respuesta.eventos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
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
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarEvento(page,buscar,criterio);
            },
            registrarEvento(){
                let me = this;
                if (this.validarEvento()){
                    return;
                }
                axios.post('/evento/registrar',{
                    'titulo': this.titulo,
                    'descripcion': this.descripcion,
                    'fecha': this.fecha,
                    'hora': this.hora,
                    'fechaFin': this.fechaFin,
                    'horaFin': this.horaFin,
                     'departamento_id': this.departamento_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarEvento(1,'','nombre');
                    toastr.success('Se ha registrado el evento', 'Actualizado', {timeOut: 5000})
                }).catch(function (error) {
                    console.log(error);
                    toastr.error('Ha ocurrido un error', 'Error', {timeOut: 5000})
                });
            },
            actualizarEvento(){

                
               if (this.validarEvento()){
                    return;
                }
                
                let me = this;
                console.log(me.id);
                axios.put('/evento/actualizar',{
                    'titulo': me.titulo,
                    'descripcion': me.descripcion,
                    'fecha': me.fecha,
                     'hora': me.hora,
                     'fechaFin': me.fechaFin,
                     'horaFin': me.horaFin,
                     'departamento_id': me.departamento_id,
                    'id': me.id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarEvento(1,'','nombre');
                    toastr.success('Se ha actualizado con exito', 'Actualizado', {timeOut: 5000})
                }).catch(function (error) {
                    console.log(error);
                    toastr.error('Ha ocurrido un error', 'Error', {timeOut: 5000})
                }); 
            },
            desactivarEvento(id){
               swal({
                title: 'Esta seguro de desactivar este evento?',
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

                    axios.put('/evento/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarEvento(1,'','nombre');
                        swal(
                        'Desactivado!',
                        'El evento se ha desactivado con éxito.',
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
            activarEvento(id){
               swal({
                title: 'Esta seguro de activar este evento?',
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

                    axios.put('/evento/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarEvento(1,'','nombre');
                        swal(
                        'Activado!',
                        'El evento se ha activado con éxito.',
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
            
            
            validarEvento(){
                this.errorEvento=0;
                this.errorMostrarMsjEvento =[];

                if (!this.titulo) this.errorMostrarMsjEvento.push("Debe ingresar el titulo del evento");
                if (!this.fecha) this.errorMostrarMsjEvento.push("Debe ingresar la fecha del evento");
                if (!this.fechaFin) this.errorMostrarMsjEvento.push("Debe ingresar la fecha en que finaliza el evento");
                if (!this.hora) this.errorMostrarMsjEvento.push("Debe ingresar la hora del evento");
                if (!this.horaFin) this.errorMostrarMsjEvento.push("Debe ingresar la hora en que finaliza el evento");
                if(this.fecha > this.fechaFin){
                    this.errorMostrarMsjEvento.push("La fecha de inicio  del evento tiene que ser menor a la fecha de finalizacion");
                    //return;
                    }  
                var dia = new Date();
                    var comparar = new Date(this.fecha);
                    if(comparar < dia){
                    this.errorMostrarMsjEvento.push("La fecha de inicio del evento tiene que ser mayor a la fecha actual");        
                    }  
                if (this.errorMostrarMsjEvento.length) this.errorEvento = 1;
                return this.errorEvento;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.titulo='';
                this.descripcion='';
                this.departamento_id='';
                this.fecha='';
                this.hora='';
                 this.fechaFin='';
                this.horaFin='';
            },
            
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "evento":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar evento';
                                this.titulo= '';
                                this.descripcion='';
                                this.fecha='';
                                this.hora='';
                                 this.fechaFin='';
                                this.horaFin='';
                                this.empleado_id= '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar evento';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.titulo = data['titulo'];
                                this.descripcion= data['descripcion'];
                                this.fecha= data['fecha'];
                                 this.fechaFin=data['fechaFin'];
                                var datetime = this.fecha;
                                var date =datetime.slice(0,10);
                                var time = datetime.slice(-8);
                                var datetime2 = this.fechaFin;
                                var date2 =datetime2.slice(0,10);
                                var time2 = datetime2.slice(-8);
                                this.fecha = date;
                                this.hora = time;
                               this.fechaFin = date2;
                                this.horaFin = time2;
                                
                                 this.departamento_id= data['departamento_id'];
                                break;
                            }
                        }
                    }
                }
            }
            
        },
        mounted() {
            this.listarEvento(1,this.buscar,this.criterio);
            this.selectDepartamentos();
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
