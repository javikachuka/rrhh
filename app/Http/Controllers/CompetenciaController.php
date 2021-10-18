<?php

namespace App\Http\Controllers;

use App\Competencia;
use Exception;
use Illuminate\Http\Request;

class CompetenciaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            
            $competencias = Competencia::orderBy('nombre', 'desc')->paginate(3);
        }
        else{
           
            $competencias = Competencia::where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('nombre', 'desc')->paginate(3);
        }
         
        return [
            'pagination' => [
                'total'        => $competencias->total(),
                'current_page' => $competencias->currentPage(),
                'per_page'     => $competencias->perPage(),
                'last_page'    => $competencias->lastPage(),
                'from'         => $competencias->firstItem(),
                'to'           => $competencias->lastItem(),
            ],
            'competencias' => $competencias
        ];
    }
    
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
           $rules = [
                  'nombre' => 'required|unique:competencias|max:150'  
            ];
            $messages = [
                'nombre.unique' => 'Ya se registro una competencia con el :attribute que ingresó.',
            ];
            $this->validate($request, $rules, $messages);            
                try{
                    if (!$request->ajax()) return redirect('/');
                    $competencia = new Competencia();
                    $competencia->nombre = $request->nombre;
                    $competencia->save();
                } catch (Exception $e){
                    //return redirect()->withErrors('Error'); 
                }        
    }
    
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
            $rules = [
                'nombre' => 'required|max:150'
                
            ];
            $messages = [
                'nombre.required' => 'Debe ingresar el :attribute .',
            
            ];
            $this->validate($request, $rules, $messages);
            try{
                
            $competencia = Competencia::findOrFail($request->id);
            $competencia->nombre = $request->nombre;
            $competencia->save();
            
            
        } catch (Exception $e){
            //return redirect()->withErrors('Error');
        }
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $competencia = Competencia::findOrFail($request->id);
        $competencia->condicion = '0';
        $competencia->save();
    }
 
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $competencia = Competencia::findOrFail($request->id);
        $competencia->condicion = '1';
        $competencia->save();
    }

    public function selectCompetencia(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $filtro = $request->filtro;
        if ($filtro==''){
            $competencias = Competencia::where('condicion', '=', 1)
            ->select('id','nombre')
            ->orderBy('nombre', 'asc')->get();
            
        } else {
            $competencias = Competencia::where('nombre', 'like', '%'. $filtro . '%')
            ->where('condicion', '=', 1)
            ->select('id','nombre')
            ->orderBy('nombre', 'asc')->get();
        }
        
        return ['competencias' => $competencias];
    }
}
