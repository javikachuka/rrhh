<?php

namespace App\Http\Controllers;

use App\DiaNoLaboral;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiaNoLaboralController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar=='') {
            $diaNoLaborales = DiaNoLaboral::join('empresas', 'dias_no_laborales.empresa_id', '=', 'empresas.id')
            ->select(
                'dias_no_laborales.*',
                'empresas.nombre as nombreEmpresa'
            )
            ->paginate(3);
        } else {
            $diaNoLaborales = DiaNoLaboral::join('empresas', 'dias_no_laborales.empresa_id', '=', 'empresas.id')
            ->select(
                'dias_no_laborales.*',
                'empresas.nombre as nombreEmpresa'
            )->where('dias_no_laborales.'.$criterio, 'like', '%'. $buscar . '%')
            ->paginate(3);
        }
         
        return [
            'pagination' => [
                'total'        => $diaNoLaborales->total(),
                'current_page' => $diaNoLaborales->currentPage(),
                'per_page'     => $diaNoLaborales->perPage(),
                'last_page'    => $diaNoLaborales->lastPage(),
                'from'         => $diaNoLaborales->firstItem(),
                'to'           => $diaNoLaborales->lastItem(),
            ],
            'diaNoLaborales' => $diaNoLaborales
        ];
    }
    public function alarmaDiaNoLaboral(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar=='') {
            $diaNoLaborales = DiaNoLaboral::join('empresas', 'dias_no_laborales.empresa_id', '=', 'empresas.id')
            ->select(
                'dias_no_laborales.*',
                'empresas.nombre as nombreEmpresa',
                DB::raw("DATE_FORMAT(dias_no_laborales.dia, '%d/%m/%Y') as dia2")
            )
            ->where('dia', '>', Carbon::now())
            ->orderBy('dia', 'asc')
            ->paginate(3);
        } else {
            $diaNoLaborales = DiaNoLaboral::join('empresas', 'dias_no_laborales.empresa_id', '=', 'empresas.id')
            ->select(
                'dias_no_laborales.*',
                'empresas.nombre as nombreEmpresa',
                DB::raw("DATE_FORMAT(dias_no_laborales.dia, '%d/%m/%Y') as dia2")
            )->where('dias_no_laborales.'.$criterio, 'like', '%'. $buscar . '%')
                ->where('dia', '>', Carbon::now())
                ->orderBy('dia', 'asc')
            ->paginate(3);
        }
         
        return [
            'pagination' => [
                'total'        => $diaNoLaborales->total(),
                'current_page' => $diaNoLaborales->currentPage(),
                'per_page'     => $diaNoLaborales->perPage(),
                'last_page'    => $diaNoLaborales->lastPage(),
                'from'         => $diaNoLaborales->firstItem(),
                'to'           => $diaNoLaborales->lastItem(),
            ],
            'diaNoLaborales' => $diaNoLaborales
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        // $rules = [
        //           'nombre' => 'required|unique:areas|max:50'
        //     ];
        // $messages = [
        //         'nombre.unique' => 'Ya se registro  con el :attribute que ingresó.',
        //     ];
        // $this->validate($request, $rules, $messages);
        try {
            if (!$request->ajax()) {
                return redirect('/');
            }
            
            $diaNoLaboral = new DiaNoLaboral();
            $diaNoLaboral->dia = $request->dia;
            $diaNoLaboral->descripcion = $request->descripcion;
            $diaNoLaboral->empresa_id=(int) ($request->empresa_id);
            $diaNoLaboral->save();
        } catch (Exception $e) {
           // return redirect()->withErrors('Error');
        }
    }

    public function selectArea(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
 
        $filtro = $request->filtro;
        if ($filtro=='') {
            $diaNoLaborales = DiaNoLaboral::where('condicion', '=', 1)
            ->orderBy('nombre', 'asc')->get();
        } else {
            $diaNoLaborales = DiaNoLaboral::where('nombre', 'like', '%'. $filtro . '%')
            ->where('condicion', '=', 1)
            ->orderBy('nombre', 'asc')->get();
        }
       
        
        return ['diaNoLaborales' => $diaNoLaborales];
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        // $rules = [
        //     'nombre' => 'required|max:50'
            
        // ];
        // $messages = [
        //     'nombre.required' => 'Debe ingresar el :attribute .',
        
        // ];
        // $this->validate($request, $rules, $messages);
        try {
            $diaNoLaboral = DiaNoLaboral::findOrFail($request->id);
            $diaNoLaboral->dia = $request->dia;
            $diaNoLaboral->descripcion = $request->descripcion;
            $diaNoLaboral->empresa_id=(int) ($request->empresa_id);

            $diaNoLaboral->save();
        } catch (Exception $e) {
            return redirect()->withErrors('Error');
        }
    }
    
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $diaNoLaboral = DiaNoLaboral::findOrFail($request->id);
        $diaNoLaboral->condicion = '0';
        $diaNoLaboral->save();
    }
 
    public function activar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $diaNoLaboral = DiaNoLaboral::findOrFail($request->id);
        $diaNoLaboral->condicion = '1';
        $diaNoLaboral->save();
    }
}
