<?php

namespace App\Http\Controllers;

use App\Empresa;
use Exception;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar=='') {
            $empresas = Empresa::orderBy('empresas.nombre', 'desc')->paginate(3);
        } else {
            $empresas = Empresa::where('empresas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('empresas.nombre', 'desc')->paginate(3);
        }
         
        return [
            'pagination' => [
                'total'        => $empresas->total(),
                'current_page' => $empresas->currentPage(),
                'per_page'     => $empresas->perPage(),
                'last_page'    => $empresas->lastPage(),
                'from'         => $empresas->firstItem(),
                'to'           => $empresas->lastItem(),
            ],
            'empresas' => $empresas
        ];
        
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $rules = [
                  'nombre' => 'required|unique:empresas|max:50'
            ];
        $messages = [
                'nombre.unique' => 'Ya se registro  con el :attribute que ingresó.',
            ];
        $this->validate($request, $rules, $messages);
        try {
            if (!$request->ajax()) {
                return redirect('/');
            }
           

            $empresa = new Empresa();
            $empresa->nombre = $request->nombre;
            $empresa->razonSocial = $request->razonSocial;
            $empresa->cuit = $request->cuit;
            $empresa->direccion=($request->direccion);
            $empresa->inicioActividad=($request->inicioActividad);
            $empresa->save();

        } catch (Exception $e) {
            return redirect()->withErrors('Error');
        }
    }

    public function selectEmpresa(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
 
        $filtro = $request->filtro;
        if ($filtro=='') {
            $empresas = Empresa::where('condicion', '=', 1)
            ->orderBy('nombre', 'asc')->get();
        } else {
            $empresas = Empresa::where('nombre', 'like', '%'. $filtro . '%')
            ->where('condicion', '=', 1)
            ->orderBy('nombre', 'asc')->get();
        }
       
        
        return ['empresas' => $empresas];
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
            $empresa = Empresa::findOrFail($request->id);
            $empresa->nombre = $request->nombre;
            $empresa->razonSocial = $request->razonSocial;
            $empresa->cuit = $request->cuit;
            $empresa->direccion=($request->direccion);
            $empresa->inicioActividad=($request->inicioActividad);
            $empresa->save();

        } catch (Exception $e) {
            //return redirect()->withErrors('Error');
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $empresa = Empresa::findOrFail($request->id);
        $empresa->condicion = '0';
        $empresa->save();
    }
 
    public function activar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $empresa = Empresa::findOrFail($request->id);
        $empresa->condicion = '1';
        $empresa->save();
    }
}
