<?php

namespace App\Http\Controllers;

use App\TipoContrato;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoContratoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $tipoContratos = TipoContrato::select('*');    
      
        
            if ($criterio =='activo') {   
                $tipoContratos->where('tipo_contratos.condicion', 1);
            } elseif ($criterio =='desactivado') {
                $tipoContratos->where('tipo_contratos.condicion', 0);
            }elseif ($buscar!=''){
                $tipoContratos->where('tipo_contratos.'.$criterio, 'like', '%'. $buscar . '%');
            }
            
            $tipoContrato= $tipoContratos->orderBy('nombre', 'desc')->paginate(3);
         
        return [
            'pagination' => [
                'total'        => $tipoContrato->total(),
                'current_page' => $tipoContrato->currentPage(),
                'per_page'     => $tipoContrato->perPage(),
                'last_page'    => $tipoContrato->lastPage(),
                'from'         => $tipoContrato->firstItem(),
                'to'           => $tipoContrato->lastItem(),
            ],
            'tipoContratos' => $tipoContrato
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
                    $tipoContrato = new TipoContrato();
                    $tipoContrato->diasMaximo = $request->diasMaximo;
                    if($request->indeterminado){
                        $tipoContrato->diasMaximo=0;
                    }
                    $tipoContrato->diasMinimo = $request->diasMinimo;
                    $tipoContrato->nombre = $request->nombre;
                    $tipoContrato->save();
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
                
            $tipoContrato = TipoContrato::findOrFail($request->id);
            $tipoContrato->nombre = $request->nombre;
            $tipoContrato->diasMaximo = $request->diasMaximo;
            $tipoContrato->diasMinimo = $request->diasMinimo;
            $tipoContrato->save();
            
            
        } catch (Exception $e){
            //return redirect()->withErrors('Error');
        }
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $tipoContrato = TipoContrato::findOrFail($request->id);
        $tipoContrato->condicion = '0';
        $tipoContrato->save();
    }
 
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $tipoContrato = TipoContrato::findOrFail($request->id);
        $tipoContrato->condicion = '1';
        $tipoContrato->save();
    }

    public function selectTipoContrato(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $filtro = $request->filtro;
        if ($filtro==''){
            $tipoContrato = TipoContrato::where('condicion', '=', 1)
            ->select('*')
            ->orderBy('nombre', 'asc')->get();
            
        } else {
            $tipoContrato = TipoContrato::where('nombre', 'like', '%'. $filtro . '%')
            ->where('condicion', '=', 1)
            ->select('*')
            ->orderBy('nombre', 'asc')->get();
        }
        
        return ['tipoContratos' => $tipoContrato];
    }
    public function pdfTipoContrato(Request $request)
    {
        // $tipoContratos = TipoContrato::join('contratos','contratos.tipoContrato_id','=','tipo_contratos.id')
        $tipoContratos = TipoContrato::select('*');    
        //SELECT tipo_contratos.nombre, COUNT(contratos.id) as cantidad FROM `tipo_contratos` INNER JOIN contratos on contratos.tipoContrato_id = tipo_contratos.id  GROUP by tipo_contratos.id
        
        
        //anido las consultas segun los filtros
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        
            if ($criterio =='activo') {   
                $tipoContratos->where('tipo_contratos.condicion', 1);
            } elseif ($criterio =='desactivado') {
                $tipoContratos->where('tipo_contratos.condicion', 0);
            }elseif ($buscar!=''){
                $tipoContratos->where('tipo_contratos.'.$criterio, 'like', '%'. $buscar . '%');
            }
            
            $tipoContratos= $tipoContratos->orderBy('nombre', 'desc')->get();
            
        $buscar= $buscar ? ucfirst($buscar): 'Sin Busqueda';
        $criterio= $criterio ? ucfirst($criterio): 'Sin Criterio';
       
        $count = count($tipoContratos);
        // $count = 1;
        $now= Carbon::now();
        
        $pdf = PDF::loadView('pdf.tipoContrato', ['tipoContratos' => $tipoContratos, 'buscar' => $buscar, 'criterio' => $criterio, 'now' => $now, 'count' => $count]);
        
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $y = $canvas->get_height() - 35;
        $pdf->getDomPDF()->get_canvas()->page_text(500, $y, "Pagina {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream();
    }
}
