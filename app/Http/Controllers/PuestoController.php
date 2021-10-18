<?php

namespace App\Http\Controllers;

use App\Puesto;
use Exception;
use Illuminate\Http\Request;
use PDOException;

class PuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar=='') {
            $puestos = Puesto::join('departamentos','puestos.departamento_id','=','departamentos.id')
            ->select('puestos.*', 'departamentos.nombre as nombreDepartamento')
              ->orderBy('puestos.nombre', 'desc')->paginate(3);
           
        } else {
            $puestos = Puesto::join('departamentos','puestos.departamento_id','=','departamentos.id')
            ->select('puestos.*', 'departamentos.nombre as nombreDepartamento')
             ->where('puestos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('puestos.nombre', 'desc')->paginate(3);
        }
         
        return [
            'pagination' => [
                'total'        => $puestos->total(),
                'current_page' => $puestos->currentPage(),
                'per_page'     => $puestos->perPage(),
                'last_page'    => $puestos->lastPage(),
                'from'         => $puestos->firstItem(),
                'to'           => $puestos->lastItem(),
            ],
            'puestos' => $puestos
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        if (!$request->ajax()) {
            return redirect('/');
        }
        $rules = [
                  'nombre' => 'required|unique:puestos|max:50'
            ];
        $messages = [
                'nombre.unique' => 'Ya se registro  con el :attribute que ingresó.',
            ];
        $this->validate($request, $rules, $messages);
        try {
            if (!$request->ajax()) {
                return redirect('/');
            }
            $puesto = new Puesto();
            $puesto->nombre = $request->nombre;
            $puesto->descripcion = $request->descripcion;
            $puesto->sueldoBasico = floatval($request->sueldoBasico);
            $puesto->departamento_id = $request->departamento_id;
            
            $puesto->save();
        } catch (Exception $e) {
            return redirect()->withErrors('Error');
        }
    }

    public function selectPuesto(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
 
        $filtro = $request->filtro;
        if ($filtro=='') {
            $puestos = Puesto::where('condicion', '=', 1)
            ->orderBy('nombre', 'asc')->get();
        } else {
            $puestos = Puesto::where('nombre', 'like', '%'. $filtro . '%')
            ->where('condicion', '=', 1)
            ->orderBy('nombre', 'asc')->get();
        }
       
        
        return ['puestos' => $puestos];
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Puesto  $puesto
     * @return \Illuminate\Http\Response
     */
    public function show(Puesto $puesto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Puesto  $puesto
     * @return \Illuminate\Http\Response
     */
    public function edit(Puesto $puesto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Puesto  $puesto
     * @return \Illuminate\Http\Response
     */
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
            $puesto = Puesto::findOrFail($request->id);
            $puesto->nombre = $request->nombre;
            $puesto->descripcion = $request->descripcion;
            $puesto->sueldoBasico = $request->sueldoBasico;
            $puesto->departamento_id = $request->departamento_id;
            $puesto->save();
        } catch (PDOException $e) {
            //return redirect()->withErrors('Error');
            return 'error' + $e;
        }
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $puesto = Puesto::findOrFail($request->id);
        $puesto->condicion = '0';
        $puesto->save();
    }
 
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $puesto = Puesto::findOrFail($request->id);
        $puesto->condicion = '1';
        $puesto->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Puesto  $puesto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Puesto $puesto)
    {
        //
    }
}
