<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empleado;
use App\CompetenciaEmpleado;
use App\ContactoEmergencia;
use Exception;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonLocale;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Storage;
use PDOException;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $rol = \Auth::user()->rol_id;
        //return $rol;
        if ($rol=='1') {
            $empleados = Empleado::select('empleados.*');
            if ($criterio=='enlicencia') {
                $empleados= Empleado::join('solicitudes_inasistencias', 'empleados.id', '=', 'solicitudes_inasistencias.empleado_id')
                ->where('solicitudes_inasistencias.desde', '<=', Carbon::now())->where('solicitudes_inasistencias.hasta', '>=', Carbon::now())->where('aprobado', true);
            } elseif ($criterio=='trabajando') {
                $empleados= Empleado::join('solicitudes_inasistencias', 'empleados.id', '=', 'solicitudes_inasistencias.empleado_id')
                ->where('solicitudes_inasistencias.desde', '>', Carbon::now())->Where('solicitudes_inasistencias.hasta', '<', Carbon::now())->orWhere('aprobado', false);
            } elseif ($buscar!='') {
                $empleados= $empleados->where('empleados.'.$criterio, 'like', '%'. $buscar . '%');
            }
           
            $empleados=$empleados->select('empleados.*')->orderBy('empleados.nombre', 'desc')->paginate(3);
        ///return $empleados;
        } else {
            //return 'else';
            $iduser = \Auth::user()->id;
            $solicitante = $this->ObtenerUsuario($iduser);
            $empleados = Empleado::select('empleados.*')->where('empleados.id', $solicitante);
            if ($criterio=='enlicencia') {
                $empleados= Empleado::join('solicitudes_inasistencias', 'empleados.id', '=', 'solicitudes_inasistencias.empleado_id')
                ->where('solicitudes_inasistencias.desde', '<=', Carbon::now())->where('solicitudes_inasistencias.hasta', '>=', Carbon::now())->where('aprobado', true)->where('empleados.id', $solicitante);
            } elseif ($criterio=='trabajando') {
                $empleados= Empleado::join('solicitudes_inasistencias', 'empleados.id', '=', 'solicitudes_inasistencias.empleado_id')
                ->where('solicitudes_inasistencias.desde', '>', Carbon::now())->Where('solicitudes_inasistencias.hasta', '<', Carbon::now())->orWhere('aprobado', false)->where('empleados.id', $solicitante);
            } elseif ($buscar!='') {
                $empleados= $empleados->where('empleados.'.$criterio, 'like', '%'. $buscar . '%');
            }
            
            $empleados=$empleados->select('empleados.*')->orderBy('empleados.nombre', 'desc')->paginate(3);
        }
       
       
        return [
            'pagination' => [
                'total'        => $empleados->total(),
                'current_page' => $empleados->currentPage(),
                'per_page'     => $empleados->perPage(),
                'last_page'    => $empleados->lastPage(),
                'from'         => $empleados->firstItem(),
                'to'           => $empleados->lastItem(),
            ],
            'empleados' => $empleados
        ];
    }

    public function enLicencia(Request $request)
    {
        
    //    return 0;
        $empleados = Empleado::join('solicitudes_inasistencias', 'empleados.id', '=', 'solicitudes_inasistencias.empleado_id');
        $empleados= $empleados->where('solicitudes_inasistencias.desde', '<=', Carbon::now())->where('solicitudes_inasistencias.hasta', '>=', Carbon::now())->where('aprobado', true);
        $empleados=$empleados->select(
            'empleados.*',
            'solicitudes_inasistencias.motivo as motivo',
            DB::raw("DATE_FORMAT(solicitudes_inasistencias.desde, '%d/%m/%Y') as desde"),
            DB::raw("DATE_FORMAT(solicitudes_inasistencias.hasta, '%d/%m/%Y') as hasta")
        )->orderBy('nombre', 'desc')->get();
    
        return ['empleados'=>$empleados];
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
       
        $rules = [
                'apellido' => 'required|max:100',
                'nombre' => 'required|max:100',
                'cuil' => 'required|unique:empleados|max:11'
                
            ];
        $messages = [
                'cuil.unique' => 'Ya se registro un empleado con el :attribute que ingresó.',
            
            ];
        $this->validate($request, $rules, $messages);
        //$cuit = $request->cuil;
        //$this->validarCUIT($cuit);
            
        try {
            if (!$request->ajax()) {
                return redirect('/');
            }

            $fileName='';
            if($request->hasFile($request->curriculum)){
            $exploded = explode(',', $request->curriculum);
            $decoded = base64_decode($exploded[1]);
            if (str_contains($exploded[0], 'pdf')) {
                $extension = 'pdf';
            } else {
                $extension = 'pdf';
            }
            $fileName = str_random().'.'.$extension;
            $path = public_path().'/'.$fileName;
            file_put_contents($path, $decoded);

                        
            }
            //$fileName = $request->file('curriculum')->store('public');
            //$url = Storage::url($fileName);
                  

            $empleado = new Empleado();
            $empleado->nombre = $request->nombre;
            $empleado->apellido = $request->apellido;
            $empleado->cuil = $request->cuil;
            $empleado->estadoCivil = $request->estadoCivil;
            $empleado->fechaNacimiento = $request->fechaNacimiento;
            $empleado->fechaAlta = $request->fechaAlta;
            $empleado->direccion = $request->direccion;
            //$empleado->curriculum = $request->curriculum;
            $empleado->curriculum = $fileName;
            $empleado->save();
            $competencias = $request->data;//Array de competencias
                    $contactos = $request->contactos;//Array de contactos
                    //Recorro todos los elementos
        
                    foreach ($competencias as $ep=>$det) {
                        $competencia = new CompetenciaEmpleado();
                        $competencia->empleado_id = $empleado->id;
                        $competencia->competencia_id = $det['id'];
                        $competencia->save();
                    }
            foreach ($contactos as $ep=>$det) {
                $contacto = new ContactoEmergencia();
                $contacto->empleado_id = $empleado->id;
                $contacto->nombre = $det['nombre'];
                $contacto->telefono1 = $det['telefono1'];
                $contacto->telefono2 = $det['telefono2'];
                $contacto->correo = $det['correo'];
                $contacto->save();
            }
        } catch (PDOException $e) {
            //return redirect()->withErrors('Error');
            return 'error' + $e;
        }
    }

    public function selectEmpleado(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
 
        $filtro = $request->filtro;
        if ($filtro=='') {
            $empleados = Empleado::where('condicion', '=', 1)
            // ->select('id','nombre')
            ->orderBy('nombre', 'asc')->get();
        } else {
            $empleados = Empleado::where('nombre', 'like', '%'. $filtro . '%')
            ->where('condicion', '=', 1)
            // ->select('id','nombre')
            ->orderBy('nombre', 'asc')->get();
        }
        
        return ['empleados' => $empleados];
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $rules = [
                'apellido' => 'required|max:100',
                'nombre' => 'required|max:100',
                'cuil' => 'required|max:11'
                
            ];
        $messages = [
                'nombre.required' => 'Debe ingresar el :attribute .',
                'apellido.required' => 'Debe ingresar el :attribute .',
                'estadoCivil.required' => 'Debe ingresar el estado civil .',
                'curriculum.required' => 'Debe ingresar el curriculum .',
            
            ];
        $this->validate($request, $rules, $messages);
        try {
            if (!$request->ajax()) {
                return redirect('/');
            }
            DB::beginTransaction();

            $empleado = Empleado::findOrFail($request->id);
            if ($request->hasFile($request->curriculum)) {
                $exploded = explode(',', $request->curriculum);
                $decoded = base64_decode($exploded[1]);
                if (str_contains($exploded[0], 'pdf')) {
                    $extension = 'pdf';
                } else {
                    $extension = 'pdf';
                }
                $fileName = str_random().'.'.$extension;
                $path = public_path().'/'.$fileName;
                file_put_contents($path, $decoded);
            } else {
                $fileName= $empleado->curriculum;
            }
                

            $empleado->nombre = $request->nombre;
            $empleado->apellido = $request->apellido;
            //$empleado->id = $request->id;
            $empleado->fechaAlta = $request->fechaAlta;
            $empleado->fechaNacimiento = $request->fechaNacimiento;
            $empleado->direccion = $request->direccion;
            $empleado->estadoCivil = $request->estadoCivil;
            $empleado->curriculum = $fileName;
            $empleado->cuil = $request->cuil;
            $empleado->save();
            
            $competencias = $request->data;//Array de competencias
            $contactos = $request->contactos;//Array de contactos
            $eliminar = $request->eliminar;//Array de eliminar
            
            //Recorro todos los elementos
            $idempleado = $request->id;
            $this->encontrar($idempleado);
            foreach ($competencias as $ep=>$det) {
                $competencia = new CompetenciaEmpleado();
                $competencia->empleado_id = $empleado->id;
                $competencia->competencia_id = $det['id'];
                $competencia->save();
            }
            foreach ($contactos as $ep=>$det) {
                if (!empty($det['id'])) {
                    $idcontacto = $det['id'];
                    $contacto = ContactoEmergencia::findOrFail($idcontacto);
                    $contacto->nombre = $det['nombre'];
                    $contacto->telefono1 = $det['telefono1'];
                    $contacto->telefono2 = $det['telefono2'];
                    $contacto->correo = $det['correo'];
                    $contacto->save();
                } else {
                    $contacto = new ContactoEmergencia();
                    $contacto->empleado_id = $empleado->id;
                    $contacto->nombre = $det['nombre'];
                    $contacto->telefono1 = $det['telefono1'];
                    $contacto->telefono2 = $det['telefono2'];
                    $contacto->correo = $det['correo'];
                    $contacto->save();
                }
            }
            foreach ($eliminar as $ep=>$det) {
                $idcontacto = $det['id'];
                $contacto = ContactoEmergencia::findOrFail($idcontacto);
                $contacto->delete();
            }
                        
            DB::commit();
        } catch (PDOException $e) {
            //return redirect()->withErrors('Error');
            return 'error' + $e;
            DB::rollBack();
        }
    }
    public function getEmpleado(Request $request)
    {
        $empleado = Empleado::findOrFail($request->id);
        $empleado->responsabilidades;
        $empleado->personaDependientes;
        $end = Carbon::parse($empleado->fechaAlta);
        $now = Carbon::now();

        $length = $end->diffInDays($now);

        if ($length >31) {
            $length= $end->diffInMonths($now);
            if ($length > 12) {
                $length= $end->diffInYears($now);
                $length=$length . ' Años';
            } else {
                $length= $length . ' Meses';
            }
        } else {
            $length= $length . ' Dias';
        }
        $empleado->antiguedadCalculada=$length;
        return ['empleado'=>$empleado];
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $empleado = Empleado::findOrFail($request->id);
        $empleado->condicion = '0';
        $empleado->save();
    }
 
    public function activar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $empleado = Empleado::findOrFail($request->id);
        $empleado->condicion = '1';
        $empleado->save();
    }
    public function findCompetencias(Request $request)
    {
        $id = $request->id;
        $competencias = CompetenciaEmpleado::join('competencias', 'competencias_empleados.competencia_id', '=', 'competencias.id')
        ->join('empleados', 'competencias_empleados.empleado_id', '=', 'empleados.id')
        ->select('competencias.id as id', 'competencias_empleados.id as idcomp_emp', 'competencias.nombre as nombre')
        ->where('empleados.id', '=', $id)
        ->orderBy('competencias.nombre', 'asc')->get();
        
        return ['competencias' => $competencias];
    }
    public function findContactos(Request $request)
    {
        $id = $request->id;
        $contactos = ContactoEmergencia::join('empleados', 'contactos_emergencias.empleado_id', '=', 'empleados.id')
        ->select('contactos_emergencias.id as id', 'contactos_emergencias.nombre as nombre', 'telefono1', 'telefono2', 'correo')
        ->where('empleados.id', '=', $id)
        ->orderBy('contactos_emergencias.nombre', 'asc')->get();
        
        return ['contactos' => $contactos];
    }
    public function encontrar($idempleado)
    {
        $comp = CompetenciaEmpleado::where('empleado_id', '=', $idempleado)
        ->select('id')->get();
        foreach ($comp as $ep=>$det) {
            $c = CompetenciaEmpleado::findOrFail($det['id']);
            $c->delete();
        }
    }
    public function pdf($buscar, $criterio)
    {
        //$desde = $request->desde;
        //$hasta = $request->hasta;
        /*$auditorias = Auditoria::select('auditorias.id','auditorias.fecha_hora', 'auditorias.accion', 'auditorias.user', 'auditorias.tabla')
        ->whereBetween('auditorias.fecha_hora', [$desde, $hasta])->orderBy('auditorias.id', 'desc')->get();*/
        if ($buscar=='') {
            $empleados = Empleado::select('nombre', 'apellido', 'cuil', 'fechaAlta', 'id')
            ->orderBy('id', 'desc')->get();
        } else {
            $empleados = Empleado::join('contratos', 'empleados.id', '=', 'contratos.empleado_id')
            ->join('puestos', 'contratos.puesto_id', '=', 'puestos.id')
            ->join('departamentos', 'puestos.departamento_id', '=', 'departamentos.id')
            ->join('areas', 'departamentos.area_id', '=', 'areas.id')
            ->select(
                'cuil',
                'direccion',
                'empleados.nombre as nombreEmpleado',
                'empleados.apellido as apellidoEmpleado',
                'fechaAlta',
                'fechaBaja',
                'curriculum',
                'fechaNacimiento',
                'empleados.condicion as condicion',
                ''
            )
              ->orderBy('empleados.nombre', 'desc')
        
            ->where('empleados.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->get();
        }
        
        $i=0;
        while ($i<count($empleados)) {
            $originalDate = $empleados[$i]['fechaAlta'];
            $empleados[$i]['fechaAlta'] = date("d/m/Y", strtotime($originalDate));
        
            $i++;
        }
        
    
   
        $cont = count($empleados);
        $now= Carbon::now();
    
        $pdf = \pdf::loadView('pdf.empleados', ['empleados' => $empleados, 'buscar' => $buscar, 'criterio' => $criterio, 'now' => $now, 'cont' => $cont]);
    
        return $pdf->download('empleados-.pdf');
    }
 
    public function __invoke(Request $request)
    {
        $empleadosPuesto=DB::table('empleados as e')
        ->join('contratos', 'e.id', '=', 'contratos.empleado_id')
        ->join('puestos', 'contratos.puesto_id', '=', 'puestos.id')
        ->select(
            DB::raw('COUNT(e.id) as cantidad'),
        //DB::raw('e.id'),
        DB::raw('puestos.nombre')
        )
        ->groupBy(DB::raw('puestos.nombre'))
        ->get();

        $empleadosArea=DB::table('empleados as e')
        ->join('contratos', 'e.id', '=', 'contratos.empleado_id')
        ->join('puestos', 'contratos.puesto_id', '=', 'puestos.id')
        ->join('departamentos', 'puestos.departamento_id', '=', 'departamentos.id')
        ->join('areas', 'departamentos.area_id', '=', 'areas.id')
        ->select(
            DB::raw('COUNT(e.id) as cantidad'),
        //DB::raw('e.id'),
        DB::raw('areas.nombre as nombre')
        )
        ->groupBy(DB::raw('areas.nombre'))
        ->get();

        $empleadosDepartamento=DB::table('empleados as e')
        ->join('contratos', 'e.id', '=', 'contratos.empleado_id')
        ->join('puestos', 'contratos.puesto_id', '=', 'puestos.id')
        ->join('departamentos', 'puestos.departamento_id', '=', 'departamentos.id')
        //->join('areas','departamentos.area_id','=','areas.id')
        ->select(
            DB::raw('COUNT(e.id) as cantidad'),
        //DB::raw('e.id'),
        DB::raw('departamentos.nombre as nombre')
        )
        ->groupBy(DB::raw('departamentos.nombre'))
        ->get();

        /*$empleadosActivosIn=DB::table('empleados as e')
        ->join('solicitudes_inasistencias','e.id','=','solicitudes_inasistencias.empleado_id')
        ->where('e.condicion', '=', 1)
        ->select(DB::raw('COUNT(e.id) as cantidad'),
        //DB::raw('e.id'),
        DB::raw('e.nombre'))
        ->groupBy(DB::raw('cantidad'))
        ->get();*/

        /*$empleadosActivos=DB::table('empleados as e')
        ->where('e.condicion', '=', 1)
        ->select(DB::raw('COUNT(e.id) as cantidad'),
        //DB::raw('e.id'),
        DB::raw('e.nombre'))
        ->groupBy(DB::raw('e.nombre'))
        ->get();*/
        $empleados = Empleado::where('condicion', '=', 1)
        ->select('cuil')
              ->orderBy('nombre', 'desc')->get();
        $hoy = Carbon::today();
        $empleadosin = Empleado::join('solicitudes_inasistencias', 'empleados.id', '=', 'solicitudes_inasistencias.empleado_id')
              ->select('cuil')
              ->where('empleados.condicion', '=', 1)
              ->where('solicitudes_inasistencias.aprobado', '=', 1)
              ->whereDate('solicitudes_inasistencias.hasta', '>=', $hoy)
              ->whereDate('solicitudes_inasistencias.desde', '<=', $hoy)
            ->orderBy('empleados.nombre', 'desc')->get();

        $activos = count($empleados);
        $activosIn = count($empleadosin);

        return ['empleadosArea'=>$empleadosArea,'empleadosDepartamento'=>$empleadosDepartamento,
        'empleadosPuesto'=>$empleadosPuesto, 'activos'=>$activos, 'activosIn'=>$activosIn];
    }

    public function ObtenerUsuario($iduser)
    {
        $operario = User::where('id', '=', $iduser)
        ->select('id', 'usuario', 'empleado_id')
        ->orderBy('id', 'asc')->take(1)->get();
        $operario = $operario[0]['empleado_id'];
        return $operario;
    }
}
