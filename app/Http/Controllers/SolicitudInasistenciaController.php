<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\User;
use App\Incidencia;
use App\SolicitudInasistencia;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Include_;
use PDOException;

class SolicitudInasistenciaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $rol = \Auth::user()->rol_id;
        if ($rol==1) {
            $solicitudInasistencias = SolicitudInasistencia::join('empleados', 'empleados.id', '=', 'solicitudes_inasistencias.empleado_id')
            ->join('incidencias', 'incidencias.id', '=', 'solicitudes_inasistencias.incidencia_id')
            ->select(
                'solicitudes_inasistencias.*',
                'incidencias.nombre as nombreIncidencia',
                'empleados.nombre as nombreEmpleado',
                'empleados.apellido as apellidoEmpleado',
                'solicitudes_inasistencias.created_at',
                'solicitudes_inasistencias.updated_at',
                DB::raw("DATE_FORMAT(solicitudes_inasistencias.desde, '%d/%m/%Y') as desde2"),
                DB::raw("DATE_FORMAT(solicitudes_inasistencias.hasta, '%d/%m/%Y') as hasta2")
            );
        } else {
            $iduser = \Auth::user()->id;
            $solicitante = $this->ObtenerUsuario($iduser);
            
            $solicitudInasistencias = SolicitudInasistencia::join('empleados', 'empleados.id', '=', 'solicitudes_inasistencias.empleado_id')
            ->join('incidencias', 'incidencias.id', '=', 'solicitudes_inasistencias.incidencia_id')
            ->select(
                'solicitudes_inasistencias.*',
                'incidencias.nombre as nombreIncidencia',
                'empleados.nombre as nombreEmpleado',
                'empleados.apellido as apellidoEmpleado',
                'solicitudes_inasistencias.created_at',
                'solicitudes_inasistencias.updated_at',
                DB::raw("DATE_FORMAT(solicitudes_inasistencias.desde, '%d/%m/%Y') as desde2"),
                DB::raw("DATE_FORMAT(solicitudes_inasistencias.hasta, '%d/%m/%Y') as hasta2")
            )
            ->where('empleados.id', $solicitante);
        }
       
        
        if ($criterio=='aprobado') {
            $solicitudInasistencias= $solicitudInasistencias->where('aprobado', 1);
        } elseif ($criterio=='desaprobado') {
            $solicitudInasistencias= $solicitudInasistencias->where('aprobado', 0);
        } elseif ($criterio=='enespera') {
            $solicitudInasistencias= $solicitudInasistencias->where('aprobado', null);
        } elseif ($criterio=='enlicencia') {
            $solicitudInasistencias= $solicitudInasistencias->where('aprobado', 1)->where('desde', '<=', Carbon::now())->where('hasta', '>=', Carbon::now());
        } elseif ($criterio=='proximo') {
            $solicitudInasistencias= $solicitudInasistencias->where('aprobado', 1)->where('desde', '>=', Carbon::now());
        } elseif ($buscar!='') {
            $solicitudInasistencias= $solicitudInasistencias->where('solicitudes_inasistencias.'.$criterio, 'like', '%'. $buscar . '%');
        }
        $solicitudInasistencias=$solicitudInasistencias->paginate(3);
         
        return [
            'pagination' => [
                'total'        => $solicitudInasistencias->total(),
                'current_page' => $solicitudInasistencias->currentPage(),
                'per_page'     => $solicitudInasistencias->perPage(),
                'last_page'    => $solicitudInasistencias->lastPage(),
                'from'         => $solicitudInasistencias->firstItem(),
                'to'           => $solicitudInasistencias->lastItem(),
            ],
            'solicitudInasistencias' => $solicitudInasistencias
        ];
    }

    
    public function aprobar(Request $request)
    {
        $solicitudInasistencias = SolicitudInasistencia::findOrFail($request->id);
        if ($solicitudInasistencias->id == 0) {
            return '';
        }
        $solicitudInasistencias->aprobado=$request->aprobado;
        $solicitudInasistencias->update();
    }

    public function alarmaInasistencia(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $solicitudInasistencias = SolicitudInasistencia::join('empleados', 'empleados.id', '=', 'solicitudes_inasistencias.empleado_id')
        ->join('incidencias', 'incidencias.id', '=', 'solicitudes_inasistencias.incidencia_id')
        ->select(
            'solicitudes_inasistencias.*',
            'incidencias.nombre as nombreIncidencia',
            'empleados.nombre as nombreEmpleado',
            'empleados.apellido as apellidoEmpleado',
            DB::raw("DATE_FORMAT(solicitudes_inasistencias.desde, '%d/%m/%Y') as desde2"),
            DB::raw("DATE_FORMAT(solicitudes_inasistencias.hasta, '%d/%m/%Y') as hasta2")
        );
        
        $solicitudInasistencias= $solicitudInasistencias->where('aprobado', null);

        if ($criterio=='empleado') {
            $solicitudInasistencias= $solicitudInasistencias->where('solicitudes_inasistencias.empleado_id', $request->idempleado);
        } elseif ($criterio=='todos') {
        } elseif ($buscar!='') {
            $solicitudInasistencias= $solicitudInasistencias->where('solicitudes_inasistencias.'.$criterio, 'like', '%'. $buscar . '%');
        }
        $solicitudInasistencias=$solicitudInasistencias->paginate(3);
         
        
       

         
        return [
            'pagination' => [
                'total'        => $solicitudInasistencias->total(),
                'current_page' => $solicitudInasistencias->currentPage(),
                'per_page'     => $solicitudInasistencias->perPage(),
                'last_page'    => $solicitudInasistencias->lastPage(),
                'from'         => $solicitudInasistencias->firstItem(),
                'to'           => $solicitudInasistencias->lastItem(),
            ],
            'solicitudInasistencias' => $solicitudInasistencias
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        // $rules = [
        //           'nombre' => 'required|unique:solicitudes_inasistencias|max:50'
        //     ];
        // $messages = [
        //         'nombre.unique' => 'Ya se registro  con el :attribute que ingresó.',
        //     ];
        // $this->validate($request, $rules, $messages);
        try {
            if (!$request->ajax()) {
                return redirect('/');
            }
            $fechaEmision = Carbon::parse($request->input('desde'));
            $fechaExpiracion = Carbon::parse($request->input('hasta'));

            $diasDiferencia = $fechaExpiracion->diffInDays($fechaEmision)+ 1;
            //cada 7 dias 1 no es Habil y sin contar los feriados
            $decimales = explode('.', $diasDiferencia/7);
            $diasDiferencia-= $decimales[0] ;
            $diasNoLaborales= Calendar::where('start_date', '>=', $request->desde)->where('start_date', '<=', $request->hasta)->get();
            $contarFeriados=count($diasNoLaborales);
            if ($contarFeriados>0) {
                $diasDiferencia-=$contarFeriados;
            }

            $incidencia= Incidencia::findOrFail($request->incidencia_id);
            
            if (($incidencia->diasMaximo < $diasDiferencia) || ($diasDiferencia < $incidencia->diasMinimo)) {
                return ['Error','Los dias de licencia tiene que ser mayor a '.$incidencia->diasMinimo.' dias y menor a '.$incidencia->diasMaximo.' dias'];
            }
            
            $solicitudInasistencia = new SolicitudInasistencia();
            $solicitudInasistencia->desde = $request->desde;
            $solicitudInasistencia->hasta = $request->hasta;
            $solicitudInasistencia->motivo = $request->motivo;
            $solicitudInasistencia->incidencia_id=($request->incidencia_id);
            if ($request->empleado_id==null) {
                $iduser = \Auth::user()->id;
                $solicitante = $this->ObtenerUsuario($iduser);
                $solicitudInasistencia->empleado_id=($solicitante);
            } else {
                $solicitudInasistencia->empleado_id=($request->empleado_id);
            }
           
            $solicitudInasistencia->save();
        } catch (PDOException $e) {
            //return redirect()->withErrors('Error');
            return 'error' + $e;
        }
    }

    public function selectSolicitudInasistencia(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
 
        $filtro = $request->filtro;
        if ($filtro=='') {
            $solicitudInasistencias = SolicitudInasistencia::where('condicion', '=', 1)
            ->orderBy('nombre', 'asc')->get();
        } else {
            $solicitudInasistencias = SolicitudInasistencia::where('nombre', 'like', '%'. $filtro . '%')
            ->where('condicion', '=', 1)
            ->orderBy('nombre', 'asc')->get();
        }
       
        
        return ['solicitudInasistencias' => $solicitudInasistencias];
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $rules = [
            'desde' => 'required',
            'hasta' => 'required',
            'motivo' => 'required',
        ];
        $messages = [
            'desde.required' => 'Debe ingresar el :attribute .',
            'hasta.required' => 'Debe ingresar el :attribute .',
            'motivo.required' => 'Debe ingresar el :attribute .'
        ];
        $this->validate($request, $rules, $messages);
        try {
            $fechaEmision = Carbon::parse($request->input('desde'));
            $fechaExpiracion = Carbon::parse($request->input('hasta'));

            $diasDiferencia = $fechaExpiracion->diffInDays($fechaEmision)+ 1;
            //cada 7 dias 1 no es Habil y sin contar los feriados
            $decimales = explode('.', $diasDiferencia/7);
            $diasDiferencia-= $decimales[0] ;
            $diasNoLaborales= Calendar::where('start_date', '>=', $request->desde)->where('start_date', '<=', $request->hasta)->get();
            $contarFeriados=count($diasNoLaborales);
            if ($contarFeriados>0) {
                $diasDiferencia-=$contarFeriados;
            }
            $incidencia= Incidencia::findOrFail($request->incidencia_id);
            
            if (($incidencia->diasMaximo < $diasDiferencia) || ($diasDiferencia < $incidencia->diasMinimo)) {
                return ['Error','Los dias de licencia tiene que ser mayor a '.$incidencia->diasMinimo.' dias y menor a '.$incidencia->diasMaximo.' dias'];
            }

            $solicitudInasistencia = SolicitudInasistencia::findOrFail($request->id);
            $solicitudInasistencia->desde = $request->desde;
            $solicitudInasistencia->hasta = $request->hasta;
            $solicitudInasistencia->motivo = $request->motivo;
            $solicitudInasistencia->incidencia_id=($request->incidencia_id);
            // $solicitudInasistencia->empleado_id=($request->empleado_id);
            if ($request->empleado_id==null) {
                $iduser = \Auth::user()->id;
                $solicitante = $this->ObtenerUsuario($iduser);
                $solicitudInasistencia->empleado_id=($solicitante);
            } else {
                $solicitudInasistencia->empleado_id=($request->empleado_id);
            }
            $solicitudInasistencia->save();
        } catch (Exception $e) {
            return redirect()->withErrors('Error');
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $solicitudInasistencia = SolicitudInasistencia::findOrFail($request->id);
        $solicitudInasistencia->condicion = '0';
        $solicitudInasistencia->save();
    }
 
    public function activar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $solicitudInasistencia = SolicitudInasistencia::findOrFail($request->id);
        $solicitudInasistencia->condicion = '1';
        $solicitudInasistencia->save();
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
