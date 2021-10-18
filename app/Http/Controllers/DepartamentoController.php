<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Departamento;
use Exception;

class DepartamentoController extends Controller
{
    public function index(Request $request)
    {
    if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar=='') {
        
            $departamentos = Departamento::join('areas', 'areas.id', '=', 'departamentos.area_id')
            ->select(
                'departamentos.*',
                'areas.nombre as nombreArea'
            )->orderBy('departamentos.nombre', 'desc')->paginate(3);
        } else {
            $departamentos = Departamento::join('areas', 'areas.id', '=', 'departamentos.area_id')
            ->select(
                'departamentos.*',
                'areas.nombre as nombreArea')
            ->where('departamentos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('departamentos.nombre', 'desc')->paginate(3);
        }
         
        return [
            'pagination' => [
                'total'        => $departamentos->total(),
                'current_page' => $departamentos->currentPage(),
                'per_page'     => $departamentos->perPage(),
                'last_page'    => $departamentos->lastPage(),
                'from'         => $departamentos->firstItem(),
                'to'           => $departamentos->lastItem(),
            ],
            'departamentos' => $departamentos
        ];
    }
    
    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $rules = [
                  'nombre' => 'required|unique:departamentos|max:50'
            ];
        $messages = [
                'nombre.unique' => 'Ya se registro  con el :attribute que ingresó.',
            ];
        $this->validate($request, $rules, $messages);
        try {
            if (!$request->ajax()) {
                return redirect('/');
            }
            
            $departamento = new Departamento();
            $departamento->nombre = $request->nombre;
            $departamento->descripcion = $request->descripcion;
            $departamento->area_id=(int) ($request->area_id);
            $departamento->save();

        } catch (Exception $e) {
            return redirect()->withErrors('Error');
        }
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
            $departamento = Departamento::findOrFail($request->id);
            $departamento->nombre = $request->nombre;
            $departamento->descripcion = $request->descripcion;
            $departamento->area_id=(int) ($request->area_id);

            $departamento->save();
        } catch (Exception $e) {
            //return redirect()->withErrors('Error');
        }
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $departamento = Departamento::findOrFail($request->id);
        $departamento->condicion = '0';
        $departamento->save();
    }
 
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $departamento = Departamento::findOrFail($request->id);
        $departamento->condicion = '1';
        $departamento->save();
    }

    public function selectDepartamento(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $filtro = $request->filtro;
        if ($filtro==''){
            $departamentos = Departamento::where('condicion', '=', 1)
            ->select('id','nombre')
            ->orderBy('nombre', 'asc')->get();
            
        } else {
            $departamentos = Departamento::where('nombre', 'like', '%'. $filtro . '%')
            ->where('condicion', '=', 1)
            ->select('id','nombre')
            ->orderBy('nombre', 'asc')->get();
        }
        
        return ['departamentos' => $departamentos];
    }
}
