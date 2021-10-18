<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Incidencia;
use Exception;

class IncidenciaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            
            $incidencias = Incidencia::orderBy('nombre', 'desc')->paginate(3);
        }
        else{
           
            $incidencias = Incidencia::where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('nombre', 'desc')->paginate(3);
        }
         
        return [
            'pagination' => [
                'total'        => $incidencias->total(),
                'current_page' => $incidencias->currentPage(),
                'per_page'     => $incidencias->perPage(),
                'last_page'    => $incidencias->lastPage(),
                'from'         => $incidencias->firstItem(),
                'to'           => $incidencias->lastItem(),
            ],
            'incidencias' => $incidencias
        ];
    }
    
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
           $rules = [
                  'nombre' => 'required|unique:incidencias|max:50'  
            ];
            $messages = [
                'nombre.unique' => 'Ya se registro una incidencia con el :attribute que ingresó.',
            ];
            $this->validate($request, $rules, $messages);            
                try{
                    if (!$request->ajax()) return redirect('/');
                    $incidencia = new incidencia();
                    $incidencia->nombre = $request->nombre;
                    $incidencia->diasMaximo = $request->diasMaximo;
                    $incidencia->diasMinimo = $request->diasMinimo;
                    $incidencia->save();
                } catch (Exception $e){
                    return redirect()->withErrors('Error'); 
                }        
    }
    public function selectIncidencia(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $filtro = $request->filtro;
        if ($filtro==''){
            $incidencias = Incidencia::where('condicion', '=', 1)
            ->select('*')
            ->orderBy('nombre', 'asc')->get();
            
        } else {
            $incidencias = Incidencia::where('nombre', 'like', '%'. $filtro . '%')
            ->where('condicion', '=', 1)
            ->select('*')
            ->orderBy('nombre', 'asc')->get();
        }
        
        return ['incidencias' => $incidencias];
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
            $rules = [
                'nombre' => 'required|max:50'
                
            ];
            $messages = [
                'nombre.required' => 'Debe ingresar el :attribute .',
            
            ];
            $this->validate($request, $rules, $messages);
            try{
                
             $incidencia = Incidencia::findOrFail($request->id);
             $incidencia->nombre = $request->nombre;
            $incidencia->diasMinimo = $request->diasMinimo;
            $incidencia->diasMaximo = $request->diasMaximo;
            $incidencia->save();
            
            
        } catch (Exception $e){
            //return redirect()->withErrors('Error');
        }
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $incidencia = Incidencia::findOrFail($request->id);
        $incidencia->condicion = '0';
        $incidencia->save();
    }
 
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $incidencia = Incidencia::findOrFail($request->id);
        $incidencia->condicion = '1';
        $incidencia->save();
    }
}
