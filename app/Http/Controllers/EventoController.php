<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evento;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar=='') {
            $eventos = Evento::join('departamentos','eventos.departamento_id','=','departamentos.id')
            ->select( 'eventos.*', 'departamentos.nombre as nombreDepartamento')
            ->orderBy('titulo', 'desc')->paginate(3);
            
        } else {
            $eventos = Evento::join('departamentos','eventos.departamento_id','=','departamentos.id')
            ->select( 'eventos.*', 'departamentos.nombre as nombreDepartamento')
            ->where('eventos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('titulo', 'desc')->paginate(3);
        }
       
        return [
           'pagination' => [
                'total'        => $eventos->total(),
                'current_page' => $eventos->currentPage(),
                'per_page'     => $eventos->perPage(),
                'last_page'    => $eventos->lastPage(),
                'from'         => $eventos->firstItem(),
                'to'           => $eventos->lastItem(),
            ],
            'eventos' => $eventos
        ];
    }

    public function eventoHome()
    {
        
        $eventos = Evento::select( 'id', 'fechaFin as end', 'titulo as title', 'fecha as start', 'descripcion')
        ->orderBy('id', 'desc')->paginate(3);
        return [
           'pagination' => [
                'total'        => $eventos->total(),
                'current_page' => $eventos->currentPage(),
                'per_page'     => $eventos->perPage(),
                'last_page'    => $eventos->lastPage(),
                'from'         => $eventos->firstItem(),
                'to'           => $eventos->lastItem(),
            ],
            'eventos' => $eventos
        ];
    }

    public function alarmaEvento(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar=='') {
            $eventos = Evento::join('departamentos','eventos.departamento_id','=','departamentos.id')
            ->select( 'eventos.*',DB::raw("DATE_FORMAT(eventos.fecha, '%d/%m/%Y') as fecha2"), 'departamentos.nombre as nombreDepartamento')
            ->where('eventos.fecha','>',Carbon::now())
            ->orderBy('fecha', 'asc')->paginate(3);
            
        } else {
            $eventos = Evento::join('departamentos','eventos.departamento_id','=','departamentos.id')
            ->select( 'eventos.*',DB::raw("DATE_FORMAT(eventos.fecha, '%d/%m/%Y') as fecha2"), 'departamentos.nombre as nombreDepartamento')
            ->where('eventos.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('eventos.fecha','>',Carbon::now())
            ->orderBy('fecha', 'asc')->paginate(3);
        }
         
        return [
            'pagination' => [
                'total'        => $eventos->total(),
                'current_page' => $eventos->currentPage(),
                'per_page'     => $eventos->perPage(),
                'last_page'    => $eventos->lastPage(),
                'from'         => $eventos->firstItem(),
                'to'           => $eventos->lastItem(),
            ],
            'eventos' => $eventos
        ];
    }
    
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
           $rules = [
                  'titulo' => 'required|max:100'  
            ];
            $messages = [
                'titulo.max' => 'La longitud máxima del :attribute es 100 caracteres.',
            ];
            $this->validate($request, $rules, $messages);            
                try{
                    if (!$request->ajax()) return redirect('/');
                    $input = $request->fecha.$request->hora;
                    $input2 = $request->fechaFin.$request->horaFin;
                    $evento = new Evento();
                    $evento->titulo = $request->titulo;
                    $evento->descripcion = $request->descripcion;
                    $evento->fecha = Carbon::parse($input);
                    $evento->fechaFin = Carbon::parse($input2);
                    $evento->departamento_id=$request->departamento_id;
                    //$evento->empresa_id=$request->idempresa;
                    $evento->save();
                } catch (Exception $e){
                    return redirect()->withErrors('Error'); 
                }        
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $rules = [
            'titulo' => 'required|max:100'  
        ];
        $messages = [
            'titulo.max' => 'La longitud maxima del :attribute es 100 caracteres.',
        ];
            $this->validate($request, $rules, $messages);
            try{
            $input = $request->fecha.$request->hora;  
            $input2 = $request->fechaFin.$request->horaFin;  
            $evento = Evento::findOrFail($request->id);
            $evento->titulo = $request->titulo;
            $evento->descripcion = $request->descripcion;
            $evento->fecha = Carbon::parse($input);
            $evento->fechaFin = Carbon::parse($input2);
            $evento->departamento_id=$request->departamento_id;
            //$evento->empresa_id=$request->idempresa;
            $evento->save();
            
            
        } catch (Exception $e){
            //return redirect()->withErrors('Error');
        }
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $evento = Evento::findOrFail($request->id);
        $evento->condicion = '0';
        $evento->save();
    }
 
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $evento = Evento::findOrFail($request->id);
        $evento->condicion = '1';
        $evento->save();
    }
}
