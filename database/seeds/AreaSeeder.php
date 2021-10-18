<?php

use App\Area;
use App\Rol;
use App\AreaEmpresa;
use App\Competencia;
use App\User;
use App\Calendar;
use App\ContactoEmergencia;
use App\Contrato;
use App\Departamento;
use App\DiaNoLaboral;
use App\Empleado;
use App\Empresa;
use App\Evento;
use App\Http\Controllers\EmpleadoController;
use App\Incidencia;
use App\PersonaDependiente;
use App\Puesto;
use App\SolicitudInasistencia;
use App\TipoContrato;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* $nuevaArea = new Area();
        $nuevaArea->nombre='Centro de Operaciones';
        $nuevaArea->descripcion='Area Principal';
        $nuevaArea->empresa_id=null;
        $nuevaArea->save(); */

        $empresa = new Empresa();
        $empresa->nombre='Angaus Producciones ';
        $empresa->razonSocial='Sociedad Anonima';
        $empresa->cuit='30707831320';
        $empresa->direccion='Avellaneda 893';
        $empresa->inicioActividad=Carbon::createFromFormat('Y-m-d','1990-02-25');
        $empresa->save();

        $nuevaArea = new Area();
        $nuevaArea->nombre='Dirección';
        $nuevaArea->descripcion='Controla todas las áreas de trabajo que se encuentran en la empresa.';
        $nuevaArea->empresa_id=$empresa->id;
        $nuevaArea->save();

        $nuevaArea2 = new Area();
        $nuevaArea2->nombre='Administración';
        $nuevaArea2->descripcion='Relacionada con el funcionamiento de la empresa';
        $nuevaArea2->empresa_id=$empresa->id;
        $nuevaArea2->save();

        $nuevaArea3 = new Area();
        $nuevaArea3->nombre='Contabilidad y Finanzas';
        $nuevaArea3->descripcion='Tendrá en cuenta todos los movimientos de dinero, tanto dentro como fuera de la empresa, que también en algunas veces pueden estar almacenadas en bancos o en una caja fuerte.';
        $nuevaArea3->empresa_id=$empresa->id;
        $nuevaArea3->save();

        $nuevaArea4 = new Area();
        $nuevaArea4->nombre='Tecnologías de la información';
        $nuevaArea4->descripcion='Es el área responsable de desarrollar la estructura tecnológica, el hardware, el software y las redes de computadoras dentro de la organización.';
        $nuevaArea4->empresa_id=$empresa->id;
        $nuevaArea4->save();
        
        $nuevaCompetencia = new Competencia();
        $nuevaCompetencia->nombre='Liderazgo';
        $nuevaCompetencia->save();

        $nuevaCompetencia = new Competencia();
        $nuevaCompetencia->nombre='Comunicación';
        $nuevaCompetencia->save();

        $nuevaCompetencia = new Competencia();
        $nuevaCompetencia->nombre='Trabajo en Equipo';
        $nuevaCompetencia->save();

        $nuevaCompetencia = new Competencia();
        $nuevaCompetencia->nombre='Creatividad';
        $nuevaCompetencia->save();
        
        $departamento = new Departamento();
        $departamento->nombre='Desarrollo de Software';
        $departamento->descripcion='Programacion, analisis, diseño, etc';
        $departamento->area_id=$nuevaArea4->id;
        $departamento->save();

        $departamento2 = new Departamento();
        $departamento2->nombre='Recursos Humanos';
        $departamento2->descripcion='Gestion administrativa del personal';
        $departamento2->area_id=$nuevaArea2->id;
        $departamento2->save();

        $departamento3 = new Departamento();
        $departamento3->nombre='Compras';
        $departamento3->descripcion='La principal función del departamento de compras es adquirir buenas materias primas, a buen precio, siempre y cuando es necesario, sin roturas de stock.';
        $departamento3->area_id=$nuevaArea3->id;
        $departamento3->save();

        $departamento4 = new Departamento();
        $departamento4->nombre='Comercial';
        $departamento4->descripcion='Debe asegurarse de que estén bien definidos los objetivos empresariales, departamentales e individuales.';
        $departamento4->area_id=$nuevaArea2->id;
        $departamento4->save();

        $departamento5 = new Departamento();
        $departamento5->nombre='Dirección General';
        $departamento5->descripcion='Direccion general';
        $departamento5->area_id=$nuevaArea->id;
        $departamento5->save();
        
        $empleado = new Empleado();
        $empleado->nombre='Ana';
        $empleado->apellido='Rafaela';
        $empleado->cuil='27236150610';
        $empleado->fechaNacimiento= Carbon::createFromFormat('Y-m-d', '1980-01-12');
        $empleado->fechaBaja= null;
        $empleado->fechaAlta= Carbon::createFromFormat('Y-m-d','2000-11-22');
        $empleado->direccion='Bolivar 6358';
        $empleado->estadoCivil='Soltero/a';
        $empleado->save();
        
        $empleado2 = new Empleado();
        $empleado2->nombre='Agus';
        $empleado2->apellido='Britez';
        $empleado2->cuil='20026162522';
        $empleado2->fechaNacimiento= Carbon::createFromFormat('Y-m-d', '1990-01-12');
        $empleado2->fechaBaja= null;
        $empleado2->fechaAlta= Carbon::createFromFormat('Y-m-d','2010-11-22');
        $empleado2->direccion='Belgrano 589';
        $empleado2->estadoCivil='Soltero/a';
        $empleado2->save();

        $empleado3 = new Empleado();
        $empleado3->nombre='Antonio';
        $empleado3->apellido='Ramirez';
        $empleado3->cuil='20163652634';
        $empleado3->fechaNacimiento= Carbon::createFromFormat('Y-m-d', '1992-05-25');
        $empleado3->fechaBaja= null;
        $empleado3->fechaAlta= Carbon::createFromFormat('Y-m-d','2002-08-01');
        $empleado3->direccion='Avenida Las Heras 85';
        $empleado3->estadoCivil='Viudo/a';
        $empleado3->save();

        $empleado4 = new Empleado();
        $empleado4->nombre='Julieta';
        $empleado4->apellido='Perez';
        $empleado4->cuil='27389229135';
        $empleado4->fechaNacimiento= Carbon::createFromFormat('Y-m-d', '1982-04-03');
        $empleado4->fechaBaja= null;
        $empleado4->fechaAlta= Carbon::createFromFormat('Y-m-d','2017-02-01');
        $empleado4->direccion='Calle Junin 1420';
        $empleado4->estadoCivil='Casado/a';
        $empleado4->save();
        

        $empleado5 = new Empleado();
        $empleado5->nombre='Rosalia';
        $empleado5->apellido='Gonzalez';
        $empleado5->cuil='27313126319';
        $empleado5->fechaNacimiento= Carbon::createFromFormat('Y-m-d', '1980-07-14');
        $empleado5->fechaBaja= null;
        $empleado5->fechaAlta= Carbon::createFromFormat('Y-m-d','2020-05-01');
        $empleado5->direccion='Calle Ayacuho 2520';
        $empleado5->estadoCivil='Casado/a';
        $empleado5->save();

        $empleado6 = new Empleado();
        $empleado6->nombre='Vanina';
        $empleado6->apellido='Benitez';
        $empleado6->cuil='27325256665';
        $empleado6->fechaNacimiento= Carbon::createFromFormat('Y-m-d', '1995-04-03');
        $empleado6->fechaBaja= null;
        $empleado6->fechaAlta= Carbon::createFromFormat('Y-m-d','2019-02-01');
        $empleado6->direccion='San Martin 1420';
        $empleado6->estadoCivil='Soltero/a';
        $empleado6->save();

        $dependiente = new PersonaDependiente();
        $dependiente->nombre='Rafael';
        $dependiente->apellido='Robert';
        $dependiente->dni='50123313';
        $dependiente->relacion='Hermano/a';
        $dependiente->necesidad='Discapacidad';
        $dependiente->empleado_id=$empleado->id;
        $dependiente->save();

        $dependiente2 = new PersonaDependiente();
        $dependiente2->nombre='Rocio';
        $dependiente2->apellido='Avellaneda';
        $dependiente2->dni='21456888';
        $dependiente2->relacion='Esposo/a';
        $dependiente2->necesidad='Obra Social';
        $dependiente2->empleado_id=$empleado3->id;
        $dependiente2->save();

        $puesto = new Puesto();
        $puesto->nombre='CEO (Chief Executive Officer)';
        $puesto->descripcion='Estos ejecutivos son los máximos responsables de la gestión y dirección administrativa de una empresa. ';
        $puesto->sueldoBasico=120000.00;
        $puesto->departamento_id=$departamento5->id;
        $puesto->save();

        $puesto2 = new Puesto();
        $puesto2->nombre='Gerente de operaciones';
        $puesto2->descripcion='se encarga de supervisar el funcionamiento del sistema de creación y distribución de los productos de la empresa';
        $puesto2->sueldoBasico=70000.00;
        $puesto2->departamento_id=$departamento4->id;
        $puesto2->save();

        $puesto3 = new Puesto();
        $puesto3->nombre='Gerente Comercial';
        $puesto3->descripcion='es referirse específicamente al responsables de las ventas de una empresa';
        $puesto3->sueldoBasico=70000.00;
        $puesto3->departamento_id=$departamento4->id;
        $puesto3->save();

        $puesto4 = new Puesto();
        $puesto4->nombre='Gerente de TI';
        $puesto4->descripcion='Los administradores de TI determinan las necesidades tecnológicas de la empresa y planifican cómo satisfacer esas necesidades: desde el desarrollo de la infraestructura hasta la coordinación de actualizaciones de software.';
        $puesto4->sueldoBasico=70000.00;
        $puesto4->departamento_id=$departamento->id;
        $puesto4->save();

        $puesto4 = new Puesto();
        $puesto4->nombre='Programador Junior Full Stack';
        $puesto4->descripcion='Desarrollador de back y frond end.';
        $puesto4->sueldoBasico=50000.00;
        $puesto4->departamento_id=$departamento->id;
        $puesto4->save();

        $puesto4 = new Puesto();
        $puesto4->nombre='Gerente de Recursos Humanos';
        $puesto4->descripcion='Los responsables de la gestión de personas determinan cuánto se paga a los empleados, cómo aumentan y se distribuyen bonos y salarios,  eligen los planes de salud y jubilación de la compañía. ';
        $puesto4->sueldoBasico=70000.00;
        $puesto4->departamento_id=$departamento2->id;
        $puesto4->save();

        $puesto4 = new Puesto();
        $puesto4->nombre='Encargado del deposito';
        $puesto4->descripcion='Control de stock ';
        $puesto4->sueldoBasico=70000.00;
        $puesto4->departamento_id=$departamento3->id;
        $puesto4->save();

        $tipoContrato = new TipoContrato();
        $tipoContrato->nombre='A Prueba';
        $tipoContrato->diasMaximo=90;
        $tipoContrato->diasMinimo=30;
        $tipoContrato->save();

        $tipoContrato2 = new TipoContrato();
        $tipoContrato2->nombre='Por Tiempo Parcial';
        $tipoContrato2->diasMaximo=180;
        $tipoContrato2->diasMinimo=30;
        $tipoContrato2->save();

        $tipoContrato3 = new TipoContrato();
        $tipoContrato3->nombre='Indeterminación del Plazo';
        $tipoContrato3->diasMaximo=0;
        $tipoContrato3->diasMinimo=1;
        $tipoContrato3->save();

        $tipoContrato4 = new TipoContrato();
        $tipoContrato4->nombre='Plazo Fijo';
        $tipoContrato4->diasMaximo=365;
        $tipoContrato4->diasMinimo=30;
        $tipoContrato4->save();


        
        $contrato = new Contrato();
        $contrato->nombre='Trabajo';
        $contrato->descripcion='Es un contrato de trabajo';
        $contrato->salario=17000.0;
        $contrato->inicioLaboral=Carbon::createFromFormat('Y-m-d','2020-11-22');
        $contrato->finLaboral=Carbon::createFromFormat('Y-m-d','2021-11-22');
        $contrato->cantidadHorasDiarias=8;
        $contrato->contrato='ubicacion';
        $contrato->puesto_id=$puesto->id;
        $contrato->empleado_id=$empleado->id;
        $contrato->tipoContrato_id=$tipoContrato4->id;
        $contrato->save();

        $contrato = new Contrato();
        $contrato->nombre='Trabajo';
        $contrato->descripcion='Es un contrato de trabajo';
        $contrato->salario=17000.0;
        $contrato->inicioLaboral=Carbon::createFromFormat('Y-m-d','2021-12-01');
        $contrato->finLaboral=Carbon::createFromFormat('Y-m-d','2021-03-01');
        $contrato->cantidadHorasDiarias=8;
        $contrato->contrato='ubicacion';
        $contrato->puesto_id=$puesto2->id;
        $contrato->empleado_id=$empleado2->id;
        $contrato->tipoContrato_id=$tipoContrato->id;
        $contrato->save();

        $contrato = new Contrato();
        $contrato->nombre='Trabajo';
        $contrato->descripcion='Es un contrato de trabajo';
        $contrato->salario=17000.0;
        $contrato->inicioLaboral=Carbon::createFromFormat('Y-m-d','2021-01-01');
        $contrato->finLaboral=Carbon::createFromFormat('Y-m-d','2021-03-31');
        $contrato->cantidadHorasDiarias=8;
        $contrato->contrato='ubicacion';
        $contrato->puesto_id=$puesto2->id;
        $contrato->empleado_id=$empleado3->id;
        $contrato->tipoContrato_id=$tipoContrato2->id;
        $contrato->save();

        $contrato = new Contrato();
        $contrato->nombre='Trabajo';
        $contrato->descripcion='Es un contrato de trabajo';
        $contrato->salario=17000.0;
        $contrato->inicioLaboral=Carbon::createFromFormat('Y-m-d','2020-05-01');
        $contrato->finLaboral=Carbon::createFromFormat('Y-m-d','2021-04-01');
        $contrato->cantidadHorasDiarias=8;
        $contrato->contrato='ubicacion';
        $contrato->puesto_id=$puesto3->id;
        $contrato->empleado_id=$empleado4->id;
        $contrato->tipoContrato_id=$tipoContrato4->id;
        $contrato->save();

        $contrato = new Contrato();
        $contrato->nombre='Trabajo';
        $contrato->descripcion='Es un contrato de trabajo';
        $contrato->salario=17000.0;
        $contrato->inicioLaboral=Carbon::createFromFormat('Y-m-d','2021-01-01');
        $contrato->finLaboral=Carbon::createFromFormat('Y-m-d','2021-12-31');
        $contrato->cantidadHorasDiarias=8;
        $contrato->contrato='ubicacion';
        $contrato->puesto_id=$puesto3->id;
        $contrato->empleado_id=$empleado5->id;
        $contrato->tipoContrato_id=$tipoContrato4->id;
        $contrato->save();
        
        $rol1 = new Rol();
        $rol1->nombre='Administrador';
        $rol1->descripcion='...';
        $rol1->save();

        $rol2 = new Rol();
        $rol2->nombre='Empleado';
        $rol2->descripcion='...';
        $rol2->save();
        
        
        

        $user = new User();
        $user->usuario='root';
        $user->email='root@root.com';
        $user->empresa_id=$empresa->id;
        $user->empleado_id=$empleado->id;
        $user->rol_id=$rol1->id;
        $user->password = bcrypt('root');
        $user->save();

        $user = new User();
        $user->usuario='empleado';
        $user->email='empleado@empleado.com';
        $user->empresa_id=$empresa->id;
        $user->empleado_id=$empleado2->id;
        $user->rol_id=$rol2->id;
        $user->password = bcrypt('empleado');
        $user->save();
        
        $diaNoLaboral = new DiaNoLaboral();
        $diaNoLaboral->dia=Carbon::createFromFormat('Y-m-d','2020-05-25');
        $diaNoLaboral->descripcion='Dia de ';
        $diaNoLaboral->empresa_id= $empresa->id;
        $diaNoLaboral->save();

        $evento = new Evento();
        $evento->titulo='Reunión Scrum';
        $evento->descripcion='Un evento para....';
        $evento->fecha=Carbon::createFromFormat('Y-m-d H','2021-02-25 14');
        $evento->fechaFin=Carbon::createFromFormat('Y-m-d H','2021-02-26 17');
        $evento->departamento_id=$departamento->id;
        $evento->empresa_id=$empresa->id;
        $evento->save();

        $evento = new Evento();
        $evento->titulo='Capacitación nuevo sistema';
        $evento->descripcion='Un evento para capacitar a los empleados....';
        $evento->fecha=Carbon::createFromFormat('Y-m-d H','2021-03-04 14');
        $evento->fechaFin=Carbon::createFromFormat('Y-m-d H','2021-03-04 16');
        $evento->departamento_id=$departamento->id;
        $evento->empresa_id=$empresa->id;
        $evento->save();


        $evento = new Calendar();
        $evento->event_name='Año Nuevo';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-01-01');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-01-01');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Carnaval';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-02-15');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-02-16');
        $evento->save();

    
        $evento = new Calendar();
        $evento->event_name='Día Internacional de la Mujer';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-03-08');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-03-08');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Día la Memoria, Verdad y Justicia';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-03-24');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-03-24');
        $evento->save();


        $evento = new Calendar();
        $evento->event_name='Jueves Santo';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-04-01');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-04-01');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Viernes Santo';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-04-02');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-04-02');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Pascuas Judías';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-04-03');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-04-04');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Día del Veterano y de los Caídos en la Guerra de Malvinas';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-04-02');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-04-02');
        $evento->save();
        
        $evento = new Calendar();
        $evento->event_name='Día del Trabajador';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-05-01');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-05-01');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Puente Turístico';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-05-24');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-05-24');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Día de la Revolucón de Mayo';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-05-25');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-05-25');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Paso a la Inmortalidad del General Manuel Belgrano';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-06-20');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-06-20');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Paso a la Inmortalidad del General Martín Miguel de Güemes';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-06-21');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-06-21');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Día de la Independencia';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-07-09');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-07-09');
        $evento->save();

       
        $evento = new Calendar();
        $evento->event_name='Día del Paso a la Inmortalidad del Gral. José de San Martín';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-08-16');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-08-16');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Día del Respeto a la Diversidad Cultural';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-10-11');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-10-11');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Puente Turístico';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-10-08');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-10-08');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Día de la Soberanía Nacional';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-11-20');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-11-20');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Puente Turístico';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-11-22');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-11-22');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Inmaculada Concepción de María';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-12-08');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-12-08');
        $evento->save();

        $evento = new Calendar();
        $evento->event_name='Navidad';
        $evento->start_date=Carbon::createFromFormat('Y-m-d','2021-12-25');
        $evento->end_date=Carbon::createFromFormat('Y-m-d','2021-12-25');
        $evento->save();


        $incidencia = new Incidencia();
        $incidencia->nombre='Vacaciones';
        $incidencia->diasMaximo=40;
        $incidencia->diasMinimo=6;
        $incidencia->save();

        $incidencia2 = new Incidencia();
        $incidencia2->nombre='Licencia Mensual';
        $incidencia2->diasMaximo=30;
        $incidencia2->diasMinimo=1;
        $incidencia2->save();
        

        $incidencia3 = new Incidencia();
        $incidencia3->nombre='Nacimiento de Hijo';
        $incidencia3->diasMaximo=2;
        $incidencia3->diasMinimo=1;
        $incidencia3->save();

        $incidencia4 = new Incidencia();
        $incidencia4->nombre='Matrimonio';
        $incidencia4->diasMaximo=10;
        $incidencia4->diasMinimo=1;
        $incidencia4->save();

        $incidencia5 = new Incidencia();
        $incidencia5->nombre='Fallecimiento familiar';
        $incidencia5->diasMaximo=3;
        $incidencia5->diasMinimo=1;
        $incidencia5->save();

        $incidencia6 = new Incidencia();
        $incidencia6->nombre='Examen';
        $incidencia6->diasMaximo=2;
        $incidencia6->diasMinimo=1;
        $incidencia6->save();


        
        $solicitudesInasistencias = new SolicitudInasistencia();
        $solicitudesInasistencias->desde=Carbon::createFromFormat('Y-m-d','2021-04-01');
        $solicitudesInasistencias->hasta=Carbon::createFromFormat('Y-m-d','2021-05-01');
        $solicitudesInasistencias->motivo='vacaciones';
        //$solicitudesInasistencias->aprobado=1;
        $solicitudesInasistencias->incidencia_id=$incidencia->id;
        $solicitudesInasistencias->empleado_id=$empleado->id;
        $solicitudesInasistencias->save();

        $solicitudesInasistencias = new SolicitudInasistencia();
        $solicitudesInasistencias->desde=Carbon::createFromFormat('Y-m-d','2021-03-21');
        $solicitudesInasistencias->hasta=Carbon::createFromFormat('Y-m-d','2021-03-05');
        $solicitudesInasistencias->motivo='vacaciones';
        $solicitudesInasistencias->aprobado=1;
        $solicitudesInasistencias->updated_at=Carbon::createFromFormat('Y-m-d','2021-02-21');
        $solicitudesInasistencias->incidencia_id=$incidencia->id;
        $solicitudesInasistencias->empleado_id=$empleado5->id;
        $solicitudesInasistencias->save();

        $solicitudesInasistencias = new SolicitudInasistencia();
        $solicitudesInasistencias->desde=Carbon::createFromFormat('Y-m-d','2021-03-10');
        $solicitudesInasistencias->hasta=Carbon::createFromFormat('Y-m-d','2021-04-10');
        $solicitudesInasistencias->motivo='vacaciones';
        $solicitudesInasistencias->aprobado=0;
        $solicitudesInasistencias->updated_at=Carbon::createFromFormat('Y-m-d','2020-02-21');
        $solicitudesInasistencias->incidencia_id=$incidencia->id;
        $solicitudesInasistencias->empleado_id=$empleado3->id;
        $solicitudesInasistencias->save();

        $solicitudesInasistencias = new SolicitudInasistencia();
        $solicitudesInasistencias->desde=Carbon::createFromFormat('Y-m-d','2021-03-01');
        $solicitudesInasistencias->hasta=Carbon::createFromFormat('Y-m-d','2021-04-01');
        $solicitudesInasistencias->motivo='cuidado a un familiar';
        $solicitudesInasistencias->aprobado=1;
        $solicitudesInasistencias->updated_at=Carbon::createFromFormat('Y-m-d','2021-02-21');
        $solicitudesInasistencias->incidencia_id=$incidencia2->id;
        $solicitudesInasistencias->empleado_id=$empleado2->id;
        $solicitudesInasistencias->save();

        $solicitudesInasistencias = new SolicitudInasistencia();
        $solicitudesInasistencias->desde=Carbon::createFromFormat('Y-m-d','2021-03-04');
        $solicitudesInasistencias->hasta=Carbon::createFromFormat('Y-m-d','2021-03-05');
        $solicitudesInasistencias->motivo='nacimiento';
        $solicitudesInasistencias->aprobado=1;
        $solicitudesInasistencias->updated_at=Carbon::createFromFormat('Y-m-d','2021-02-21');
        $solicitudesInasistencias->incidencia_id=$incidencia3->id;
        $solicitudesInasistencias->empleado_id=$empleado3->id;
        $solicitudesInasistencias->save();

        $solicitudesInasistencias = new SolicitudInasistencia();
        $solicitudesInasistencias->desde=Carbon::createFromFormat('Y-m-d','2021-05-01');
        $solicitudesInasistencias->hasta=Carbon::createFromFormat('Y-m-d','2021-05-02');
        $solicitudesInasistencias->motivo='vacaciones';
        $solicitudesInasistencias->incidencia_id=$incidencia6->id;
        $solicitudesInasistencias->empleado_id=$empleado4->id;
        $solicitudesInasistencias->save();

        //relacion many to many
        
        $contactoEmergencia = new ContactoEmergencia();
        $contactoEmergencia->nombre='Agustin';
        $contactoEmergencia->telefono1='12312412';
        $contactoEmergencia->telefono2='12312413';
        $contactoEmergencia->correo='agus@agus.com';
        $contactoEmergencia->empleado_id=$empleado->id;
        $contactoEmergencia->save();

    }
}
