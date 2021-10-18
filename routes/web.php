<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware'=>['guest']],function(){
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});
Route::group(['middleware'=>['auth']],function(){
    
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/main', function () {
    return view('contenido/contenido');
})->name('main');

Route::group(['middleware' => ['Administrador']], function () {
//Empleado
Route::get('/empleado', 'EmpleadoController@index');
Route::post('/empleado/registrar', 'EmpleadoController@store');
Route::put('/empleado/actualizar', 'EmpleadoController@update');
Route::put('/empleado/desactivar', 'EmpleadoController@desactivar');
Route::put('/empleado/activar', 'EmpleadoController@activar');
Route::get('/empleado/selectEmpleado', 'EmpleadoController@selectEmpleado');
Route::get('/empleado/findCompetencias', 'EmpleadoController@findCompetencias');
Route::get('/empleado/findContactos', 'EmpleadoController@findContactos');
Route::get('/empleado/reporte', 'EmpleadoController@__invoke');
Route::get('/empleado/pdf/{buscar}/{criterio}/{incidencia}', 'EmpleadoController@pdf')->name('empleado_pdf');

//Contrato
Route::get('/contrato', 'ContratoController@index');
Route::post('/contrato/registrar', 'ContratoController@store');
Route::put('/contrato/actualizar', 'ContratoController@update');
Route::put('/contrato/desactivar', 'ContratoController@desactivar');
Route::put('/contrato/activar', 'ContratoController@activar');
Route::get('/contrato/selectContrato', 'ContratoController@selectContrato');
Route::get('/contrato/pdf', 'ContratoController@pdfContrato')->name('contrato_pdf');
Route::post('/contrato/calculadorDias', 'ContratoController@calculadorDias');
Route::get('/contrato/alarma', 'ContratoController@alarma');


//Empresa
Route::get('/empresa', 'EmpresaController@index');
Route::post('/empresa/registrar', 'EmpresaController@store');
Route::put('/empresa/actualizar', 'EmpresaController@update');
Route::put('/empresa/desactivar', 'EmpresaController@desactivar');
Route::put('/empresa/activar', 'EmpresaController@activar');
Route::get('/empresa/selectEmpresa', 'EmpresaController@selectEmpresa');

//Area
Route::get('/area', 'AreaController@index');
Route::post('/area/registrar', 'AreaController@store');
Route::put('/area/actualizar', 'AreaController@update');
Route::put('/area/desactivar', 'AreaController@desactivar');
Route::put('/area/activar', 'AreaController@activar');
Route::get('/area/selectArea', 'AreaController@selectArea');

//Puesto
Route::get('/puesto', 'PuestoController@index');
Route::post('/puesto/registrar', 'PuestoController@store');
Route::put('/puesto/actualizar', 'PuestoController@update');
Route::put('/puesto/desactivar', 'PuestoController@desactivar');
Route::put('/puesto/activar', 'PuestoController@activar');
Route::get('/puesto/selectPuesto', 'PuestoController@selectPuesto');

//Incidencia
Route::get('/incidencia', 'IncidenciaController@index');
Route::post('/incidencia/registrar', 'IncidenciaController@store');
Route::put('/incidencia/actualizar', 'IncidenciaController@update');
Route::put('/incidencia/desactivar', 'IncidenciaController@desactivar');
Route::put('/incidencia/activar', 'IncidenciaController@activar');
Route::get('/incidencia/selectIncidencia', 'IncidenciaController@selectIncidencia');

//Incidencia
Route::get('/personaDependiente', 'PersonaDependienteController@index');
Route::post('/personaDependiente/registrar', 'PersonaDependienteController@store');
Route::put('/personaDependiente/actualizar', 'PersonaDependienteController@update');
Route::put('/personaDependiente/desactivar', 'PersonaDependienteController@desactivar');
Route::put('/personaDependiente/activar', 'PersonaDependienteController@activar');
Route::get('/personaDependiente/selectPersonaDependiente', 'PersonaDependienteController@selectPersonaDependiente');

//Evento
Route::get('/evento', 'EventoController@index');
Route::post('/evento/registrar', 'EventoController@store');
Route::put('/evento/actualizar', 'EventoController@update');
Route::put('/evento/desactivar', 'EventoController@desactivar');
Route::put('/evento/activar', 'EventoController@activar');
Route::get('/evento/alarma', 'EventoController@alarmaEvento');
Route::get('/evento/home', 'EventoController@eventoHome');

//Departamento
Route::get('/departamento', 'DepartamentoController@index');
Route::post('/departamento/registrar', 'DepartamentoController@store');
Route::put('/departamento/actualizar', 'DepartamentoController@update');
Route::put('/departamento/desactivar', 'DepartamentoController@desactivar');
Route::put('/departamento/activar', 'DepartamentoController@activar');
Route::get('/departamento/selectDepartamento', 'DepartamentoController@selectDepartamento');

//Competencia
Route::get('/competencia', 'CompetenciaController@index');
Route::post('/competencia/registrar', 'CompetenciaController@store');
Route::put('/competencia/actualizar', 'CompetenciaController@update');
Route::put('/competencia/desactivar', 'CompetenciaController@desactivar');
Route::put('/competencia/activar', 'CompetenciaController@activar');
Route::get('/competencia/selectCompetencia', 'CompetenciaController@selectCompetencia');

//DiaNoLaboral
Route::get('/diaNoLaboral', 'DiaNoLaboralController@index');
Route::post('/diaNoLaboral/registrar', 'DiaNoLaboralController@store');
Route::put('/diaNoLaboral/actualizar', 'DiaNoLaboralController@update');
Route::put('/diaNoLaboral/desactivar', 'DiaNoLaboralController@desactivar');
Route::put('/diaNoLaboral/activar', 'DiaNoLaboralController@activar');
Route::get('/diaNoLaboral/selectDiaNoLaboral', 'DiaNoLaboralController@selectDiaNoLaboral');
Route::get('/diaNoLaboral/alarmaDiaNoLaboral', 'DiaNoLaboralController@alarmaDiaNoLaboral');

//Solicitudes
Route::get('/solicitudInasistencia', 'SolicitudInasistenciaController@index');
Route::post('/solicitudInasistencia/registrar', 'SolicitudInasistenciaController@store');
Route::put('/solicitudInasistencia/actualizar', 'SolicitudInasistenciaController@update');
Route::put('/solicitudInasistencia/desactivar', 'SolicitudInasistenciaController@desactivar');
Route::put('/solicitudInasistencia/activar', 'SolicitudInasistenciaController@activar');
Route::get('/solicitudInasistencia/selectSolicitudInasistencia', 'SolicitudInasistenciaController@selectSolicitudInasistencia');
Route::get('/solicitudInasistencia/alarma', 'SolicitudInasistenciaController@alarmaInasistencia');

//TipoContrato
Route::get('/tipoContrato', 'TipoContratoController@index');
Route::post('/tipoContrato/registrar', 'TipoContratoController@store');
Route::put('/tipoContrato/actualizar', 'TipoContratoController@update');
Route::put('/tipoContrato/desactivar', 'TipoContratoController@desactivar');
Route::put('/tipoContrato/activar', 'TipoContratoController@activar');
Route::get('/tipoContrato/selectTipoContrato', 'TipoContratoController@selectTipoContrato');
Route::get('/tipoContrato/pdf', 'TipoContratoController@pdfTipoContrato')->name('tipoContrato_pdf');

//User
Route::get('/usuario', 'UserController@index');
Route::post('/usuario/registrar', 'UserController@store');
Route::put('/usuario/actualizar', 'UserController@update');
Route::put('/usuario/desactivar', 'UserController@desactivar');
Route::put('/usuario/activar', 'UserController@activar');

//Rol
Route::get('/rol', 'UserController@indexRol');
/*Route::post('/usuario/registrar', 'UserController@store');
Route::put('/usuario/actualizar', 'UserController@update');
Route::put('/usuario/desactivar', 'UserController@desactivar');
Route::put('/usuario/activar', 'UserController@activar');*/

});

Route::group(['middleware' => ['Empleado']], function () {
    //Empleado
    Route::get('/empleado', 'EmpleadoController@index');
    Route::get('/getEmpleado', 'EmpleadoController@getEmpleado');
    Route::post('/empleado/registrar', 'EmpleadoController@store');
    Route::put('/empleado/actualizar', 'EmpleadoController@update');
    Route::put('/empleado/desactivar', 'EmpleadoController@desactivar');
    Route::put('/empleado/activar', 'EmpleadoController@activar');
    Route::get('/empleado/selectEmpleado', 'EmpleadoController@selectEmpleado');
    Route::get('/empleado/findCompetencias', 'EmpleadoController@findCompetencias');
    Route::get('/empleado/findContactos', 'EmpleadoController@findContactos');
    Route::get('/empleado/enLicencia', 'EmpleadoController@enLicencia');
    Route::get('/empleado/reporte', 'EmpleadoController@__invoke');
    Route::get('/empleado/pdf/{buscar}/{criterio}/{incidencia}', 'EmpleadoController@pdf')->name('empleado_pdf');
    //Solicitudes
Route::get('/solicitudInasistencia', 'SolicitudInasistenciaController@index');
Route::post('/solicitudInasistencia/registrar', 'SolicitudInasistenciaController@store');
Route::put('/solicitudInasistencia/aprobar', 'SolicitudInasistenciaController@aprobar');
Route::put('/solicitudInasistencia/actualizar', 'SolicitudInasistenciaController@update');
Route::put('/solicitudInasistencia/desactivar', 'SolicitudInasistenciaController@desactivar');
Route::put('/solicitudInasistencia/activar', 'SolicitudInasistenciaController@activar');
Route::get('/solicitudInasistencia/selectSolicitudInasistencia', 'SolicitudInasistenciaController@selectSolicitudInasistencia');
Route::get('/solicitudInasistencia/alarma', 'SolicitudInasistenciaController@alarmaInasistencia');
});


//Auth::routes();

//Route::get('/', 'Auth\LoginController@showLoginForm');
//Route::post('/login', 'Auth\LoginController@login')->name('login');
//Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

//Route::get('/home', 'HomeController@index')->name('home');

});