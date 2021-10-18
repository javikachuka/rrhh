<?php

namespace App\Http\Controllers;

use App\Area;
use App\Empresa;
use Exception;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar=='') {
            $areas = Area::join('empresas', 'areas.empresa_id', '=', 'empresas.id')
            ->select(
                'areas.*',
                'empresas.nombre as nombreEmpresa'
            )->where('areas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('areas.nombre', 'desc')->paginate(3);
        } else {
            $areas = Area::join('empresas', 'areas.empresa_id', '=', 'empresas.id')
            ->select(
                'areas.*',
                'empresas.nombre as nombreEmpresa'
            )->where('areas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('areas.nombre', 'desc')->paginate(3);
        }
         
        return [
            'pagination' => [
                'total'        => $areas->total(),
                'current_page' => $areas->currentPage(),
                'per_page'     => $areas->perPage(),
                'last_page'    => $areas->lastPage(),
                'from'         => $areas->firstItem(),
                'to'           => $areas->lastItem(),
            ],
            'areas' => $areas
        ];
        
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $rules = [
                  'nombre' => 'required|unique:areas|max:50'
            ];
        $messages = [
                'nombre.unique' => 'Ya se registro  con el :attribute que ingresó.',
            ];
        $this->validate($request, $rules, $messages);
        try {
            if (!$request->ajax()) {
                return redirect('/');
            }
            $iduser = \Auth::user()->id;
            $empresa = $this->obtenerEmpresa($iduser);
            $area = new Area();
            $area->nombre = $request->nombre;
            $area->descripcion = $request->descripcion;
            $area->empresa_id= $empresa;
            $area->save();

        } catch (Exception $e) {
            return redirect()->withErrors('Error');
        }
    }

    public function selectArea(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
 
        $filtro = $request->filtro;
        if ($filtro=='') {
            $areas = Area::where('condicion', '=', 1)
            ->orderBy('nombre', 'asc')->get();
        } else {
            $areas = Area::where('nombre', 'like', '%'. $filtro . '%')
            ->where('condicion', '=', 1)
            ->orderBy('nombre', 'asc')->get();
        }
       
        
        return ['areas' => $areas];
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $rules = [
            'nombre' => 'required|max:50'
            
        ];
        $messages = [
            'nombre.required' => 'Debe ingresar el :attribute .',
        
        ];
        $this->validate($request, $rules, $messages);
        try {
            $area = Area::findOrFail($request->id);
            $area->nombre = $request->nombre;
            $area->descripcion = $request->descripcion;
            //$area->empresa_id=(int) ($request->empresa_id);

            $area->save();
        } catch (PDOException $e) {
            //return redirect()->withErrors('Error');
            return 'error' + $e;
        }
    }
    
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $area = Area::findOrFail($request->id);
        $area->condicion = '0';
        $area->save();
    }
 
    public function activar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $area = Area::findOrFail($request->id);
        $area->condicion = '1';
        $area->save();
    }

    public function obtenerEmpresa($iduser)
    {
        
        $empresa = Empresa::join('users','empresas.id','=','users.empresa_id')
        ->where('users.id', '=', $iduser)
        ->select('empresas.id as id')
        ->orderBy('empresas.id', 'asc')->take(1)->get();
        $empresa = $empresa[0]['id'];
        return $empresa;
    }

}
