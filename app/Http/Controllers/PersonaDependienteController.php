<?php

namespace App\Http\Controllers;

use App\PersonaDependiente;
use Exception;
use Illuminate\Http\Request;
use App\User;

class PersonaDependienteController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $rol = \Auth::user()->rol_id;
        if ($rol==1){


        if ($buscar=='') {
            $personaDependientes = PersonaDependiente::join('empleados','empleados.id','=','persona_dependientes.empleado_id')
            ->select('persona_dependientes.*','empleados.apellido as apellidoEmpleado','empleados.nombre as nombreEmpleado')
            ->orderBy('apellido', 'desc')->paginate(3);
        } else {
            $personaDependientes = PersonaDependiente::join('empleados','empleados.id','=','persona_dependientes.empleado_id')
            ->select('persona_dependientes.*','empleados.apellido as apellidoEmpleado','empleados.nombre as nombreEmpleado')
            ->where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('apellido', 'desc')->paginate(3);
        }
        } else {
            $iduser = \Auth::user()->id;
            $solicitante = $this->ObtenerUsuario($iduser);
            if ($buscar=='') {
                $personaDependientes = PersonaDependiente::join('empleados','empleados.id','=','persona_dependientes.empleado_id')
                ->select('persona_dependientes.*','empleados.apellido as apellidoEmpleado','empleados.nombre as nombreEmpleado')
                ->where('empleados.id', $solicitante)
                ->orderBy('apellido', 'desc')->paginate(3);
            } else {
                $personaDependientes = PersonaDependiente::join('empleados','empleados.id','=','persona_dependientes.empleado_id')
                ->select('persona_dependientes.*','empleados.apellido as apellidoEmpleado','empleados.nombre as nombreEmpleado')
                ->where($criterio, 'like', '%'. $buscar . '%')
                ->where('empleados.id', $solicitante)
                ->orderBy('apellido', 'desc')->paginate(3);
            }
        }
        return [
            'pagination' => [
                'total'        => $personaDependientes->total(),
                'current_page' => $personaDependientes->currentPage(),
                'per_page'     => $personaDependientes->perPage(),
                'last_page'    => $personaDependientes->lastPage(),
                'from'         => $personaDependientes->firstItem(),
                'to'           => $personaDependientes->lastItem(),
            ],
            'personaDependientes' => $personaDependientes
        ];
    }
    
    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $rules = [
                  'dni' => 'required|unique:persona_dependientes|max:50'
            ];
        $messages = [
                'dni.unique' => 'Ya se registro una persona dependiente con el dni :attribute.',
            ];
        $this->validate($request, $rules, $messages);
        try {
            if (!$request->ajax()) {
                return redirect('/');
            }
            $personaDependiente = new PersonaDependiente();
            $personaDependiente->nombre = $request->nombre;
            $personaDependiente->apellido = $request->apellido;
            $personaDependiente->relacion = $request->relacion;
            $personaDependiente->necesidad = $request->necesidad;
            $personaDependiente->dni = $request->dni;
            $personaDependiente->empleado_id = $request->empleado_id;
            $personaDependiente->save();
        } catch (Exception $e) {
            return redirect()->withErrors('Error');
        }
    }
    public function selectPersonaDependiente(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
 
        $filtro = $request->filtro;
        if ($filtro=='') {
            $personaDependientes = PersonaDependiente::where('condicion', '=', 1)
            ->select('*')
            ->orderBy('apellido', 'asc')->get();
        } else {
            $personaDependientes = PersonaDependiente::where('nombre', 'like', '%'. $filtro . '%')
            ->where('condicion', '=', 1)
            ->select('*')
            ->orderBy('apellido', 'asc')->get();
        }
        
        return ['personaDependientes' => $personaDependientes];
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $rules = [
            'dni' => 'required|max:50'
      ];
        $messages = [
          'dni.unique' => 'Ya se registro una persona dependiente con el dni :attribute.',
      ];
        $this->validate($request, $rules, $messages);
        try {
            $personaDependiente = PersonaDependiente::findOrFail($request->id);
            $personaDependiente->nombre = $request->nombre;
            $personaDependiente->apellido = $request->apellido;
            $personaDependiente->relacion = $request->relacion;
            $personaDependiente->necesidad = $request->necesidad;
            $personaDependiente->dni = $request->dni;
            $personaDependiente->empleado_id = $request->empleado_id;
            $personaDependiente->save();
        } catch (Exception $e) {
            //return redirect()->withErrors('Error');
        }
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $personaDependiente = PersonaDependiente::findOrFail($request->id);
        $personaDependiente->condicion = '0';
        $personaDependiente->save();
    }
 
    public function activar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $personaDependiente = PersonaDependiente::findOrFail($request->id);
        $personaDependiente->condicion = '1';
        $personaDependiente->save();
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
