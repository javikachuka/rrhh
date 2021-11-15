<template>
<main class="main">
    <!-- Breadcrumb -->
    
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                
            </div>
            <div class="car-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Areas</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="areas">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p>Cantidad de Empleados por areas.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Puestos</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="puestos">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p>Empleados por puestos.</p>
                            </div>
                        </div>
                    </div>
                     <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Departamentos</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="departamentos">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p>Empleados por departamentos.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Activos</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="activos">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p>Cantidad de Empleados Activos.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
</template>
<script>
    export default {
        data (){
            return {
                varArea:null,
                charArea:null,
                areas:[],
                varCantidadArea:[],
                varNombreArea:[],
                
                varDepartamento:null,
                charDepartamento:null,
                departamentos:[],
                varCantidadDepartamento:[],
                varNombreDepartamento:[],

                varActivos:null,
                charActivos:null,
                activos:[],
                activosIn:[],
                varCantidadActivos:[],
                varNombreActivos:[],
                
                varPuesto:null,
                charPuesto:null,
                puestos:[],
                varCantidadPuesto:[],
                varNombrePuesto:[],
            }
        },
        methods : {
            getAreas(){
                let me=this;
                var url= '/empleado/reporte';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.areas = respuesta.empleadosArea;
                    
                    //cargamos los datos del chart
                    me.loadArea();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getPuestos(){
                let me=this;
                var url= '/empleado/reporte';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.puestos = respuesta.empleadosPuesto;
                    console.log(me.puestos);
                    //cargamos los datos del chart
                    me.loadPuestos();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             getDepartamentos(){
                let me=this;
                var url= '/empleado/reporte';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.departamentos = respuesta.empleadosDepartamento;
                    console.log(me.departamentos);
                    //cargamos los datos del chart
                    me.loadDepartamentos();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             getActivos(){
                let me=this;
                var url= '/empleado/reporte';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                   me.activos = respuesta.activos;
                    me.activosIn = respuesta.activosIn;
                    //cargamos los datos del chart
                    me.loadActivos();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            loadArea(){
                let me=this;
                
                me.areas.map(function(x){
                    me.varNombreArea.push(x.nombre);
                    me.varCantidadArea.push(x.cantidad);
                });
                
                me.varArea=document.getElementById('areas').getContext('2d');

                me.charArea = new Chart(me.varArea, {
                    type: 'bar',
                    data: {
                        labels: me.varNombreArea,
                        datasets: [{
                            label: 'areas',
                            data: me.varCantidadArea,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1,
                            //barThickness: 100,
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
            loadDepartamentos(){
                let me=this;
                me.departamentos.map(function(x){
                    me.varNombreDepartamento.push(x.nombre);
                    me.varCantidadDepartamento.push(x.cantidad);
                });
                me.varDepartamento=document.getElementById('departamentos').getContext('2d');

                me.charDepartamento = new Chart(me.varDepartamento, {
                    type: 'bar',
                    data: {
                        labels: me.varNombreDepartamento,
                        datasets: [{
                            label: 'departamentos',
                            data: me.varCantidadDepartamento,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
            
            loadActivos(){
                let me=this;
                /*me.areas.map(function(x){
                    me.varNombreActivos.push(x.nombre);
                    me.varCantidadActivos.push(x.cantidad);
                });*/
                me.varNombreActivos = ["Activos","Activos con Licencias"];
                me.varCantidadActivos = [me.activos, me.activosIn];
                me.varActivos=document.getElementById('activos').getContext('2d');
                console.log(me.varNombreActivos);
                me.charActivo = new Chart(me.varActivos, {
                    type: 'bar',
                    data: {
                        labels: me.varNombreActivos,
                        datasets: [{
                            label: 'activos',
                            data: me.varCantidadActivos,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
            loadPuestos(){
                let me=this;
                me.puestos.map(function(x){
                    me.varNombrePuesto.push(x.nombre);
                    me.varCantidadPuesto.push(x.cantidad);
                });
                me.varPuesto=document.getElementById('puestos').getContext('2d');

                me.charPuesto = new Chart(me.varPuesto, {
                    type: 'bar',
                    data: {
                        labels: me.varNombrePuesto,
                        datasets: [{
                            label: 'puestos',
                            data: me.varCantidadPuesto,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            }
        },
        mounted() {
            this.getAreas();
            this.getPuestos();
            this.getActivos();
             this.getDepartamentos();
        }
    }
</script>
