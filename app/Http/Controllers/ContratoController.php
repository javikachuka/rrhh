<?php

namespace App\Http\Controllers;

use App\Contrato;
use App\Calendar;
use App\Empleado;
use App\TipoContrato;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

class ContratoController extends Controller
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
        $contratos = Contrato::join('empleados', 'contratos.empleado_id', '=', 'empleados.id')
            ->join('puestos', 'contratos.puesto_id', '=', 'puestos.id')
            ->join('tipo_contratos', 'contratos.tipoContrato_id', '=', 'tipo_contratos.id')
            ->select(
                'contratos.*',
                'puestos.nombre as nombrePuesto',
                'empleados.nombre as nombreEmpleado',
                'empleados.apellido as apellidoEmpleado',
                'tipo_contratos.nombre as nombreTipoContrato',
                DB::raw('CONCAT("'. public_path().'/'.'", contratos.contrato) as url')
            );
            
        
        if ($criterio =='activo') {
            $contratos->where('contratos.condicion', 1);
        } elseif ($criterio =='desactivado') {
            $contratos->where('contratos.condicion', 0);
        } elseif ($criterio =='vigente') {//contrato en curso
            $contratos->where('contratos.actual', 1);
        } elseif ($criterio =='terminado') {
            $contratos->where('contratos.finLaboral', '<', Carbon::now());
        } elseif ($criterio =='tipoContrato') {
            $contratos->where('tipo_Contratos.id', $request->tipoContrato_id_filtro);
        } elseif ($buscar!='') {
            $contratos->where('contratos.'.$criterio, 'like', '%'. $buscar . '%');
        }
         
        $contratos= $contratos->orderBy('contratos.nombre', 'desc')->paginate(3);
         
        return [
            'pagination' => [
                'total'        => $contratos->total(),
                'current_page' => $contratos->currentPage(),
                'per_page'     => $contratos->perPage(),
                'last_page'    => $contratos->lastPage(),
                'from'         => $contratos->firstItem(),
                'to'           => $contratos->lastItem(),
            ],
            'contratos' => $contratos
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
        //   return $request;
        if (!$request->ajax()) {
            return redirect('/');
        }
        $rules = [
                  'nombre' => 'required|unique:contratos|max:50'
            ];
        $messages = [
                'nombre.unique' => 'Ya se registro  con el :attribute que ingresó.',
            ];
        $this->validate($request, $rules, $messages);
        try {
            if (!$request->ajax()) {
                return redirect('/');
            }
            //A continiacion se calcula si la cantidad de dias seleccionado respeta la cantidad de dias
            //definido en el TIPO de CONTRATO
            //********************************** */
            $fechaEmision = Carbon::parse($request->input('inicioLaboral'));
            $fechaExpiracion = Carbon::parse($request->input('finLaboral'));
               
            
            $cantidadDiasRealTrabajo = $fechaExpiracion->diffInDays($fechaEmision)+ 1;
            //cada 7 dias 1 no es Habil y sin contar los feriados
            $decimales = explode('.', $cantidadDiasRealTrabajo/7);
            $cantidadDiasRealTrabajo-= $decimales[0] ;
            $diasNoLaborales= Calendar::where('start_date', '>=', $request->inicioLaboral)->where('start_date', '<=', $request->finLaboral)->get();
            $contarFeriados=count($diasNoLaborales);
            if ($contarFeriados>0) {
                $cantidadDiasRealTrabajo-=$contarFeriados;
            }
            
            $tipoContrato=  TipoContrato::findOrFail($request->idtipocontrato);

            $fechaFinContrato= null;
            if (($tipoContrato->diasMaximo ==0) && ($tipoContrato->diasMinimo > 0)) {
                //es un contrato indeterminado
            } else {
                $fechaFinContrato=$request->finLaboral;

                if (($tipoContrato->diasMaximo < $cantidadDiasRealTrabajo) || ($cantidadDiasRealTrabajo < $tipoContrato->diasMinimo)) {
                    return ['Error','Los dias de los Tipos de contrato '. strtoupper($tipoContrato->nombre) .' tienen que ser mayor a '.$tipoContrato->diasMinimo.' dias y menor a '.$tipoContrato->diasMaximo.' dias'];
                }
            }
            //****************************************************************** */
            

            $exploded = explode(',', $request->contrato);
            $decoded = base64_decode($exploded[1]);
            if (str_contains($exploded[0], 'pdf')) {
                $extension = 'pdf';
            } else {
                $extension = 'pdf';
            }
            $fileName = str_random().'.'.$extension;
            $path = public_path().'/'.$fileName;
            file_put_contents($path, $decoded);
            $empleado    = Empleado::find($request->idempleado);
            $contratoViejo=$empleado->contratos->where('actual', 1)->first();
            if ($contratoViejo) {
                $contratoViejo->actual=0;
                $contratoViejo->condicion=0;
                $contratoViejo->update();
            }
                
            $contrato = new Contrato();
            $contrato->nombre = $request->nombre;
         
            $contrato->descripcion = $request->descripcion ;
            $contrato->inicioLaboral= $request->inicioLaboral;
            $contrato->finLaboral= $fechaFinContrato;
            // $contrato->inicioLaboral= Carbon::now();
            // $contrato->finLaboral= Carbon::now();
            $contrato->cantidadHorasDiarias= intval($request->cantidadHorasDiarias);
            $contrato->salario= floatval($request->salario);
            //$contrato->contrato = '';
            $contrato->contrato=$fileName;
            $contrato->puesto_id=($request->idpuesto);
            $contrato->empleado_id=($request->idempleado);
            $contrato->tipoContrato_id=($request->idtipocontrato);
                
            $contrato->save();
            return 0;
        } catch (Exception $e) {
            return redirect()->withErrors('Error');
        }
    }

    public function alarma(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        $contratos = Contrato::join('empleados', 'empleados.id', '=', 'contratos.empleado_id')->where('actual', 1);


        if ($criterio =='sincontrato') {
            $contratos =  $contratos->where('contratos.finLaboral', '<', Carbon::now());
        } elseif ($criterio == 'avencer') {
            // $contratos =  $contratos->where('contratos.finLaboral','>',Carbon::now())
            // ->where('contratos.finLaboral','<',Carbon::now()->addMonth());
            $contratos =  $contratos->where('contratos.finLaboral', '>=', Carbon::now())
            ->where('contratos.finLaboral', '<', Carbon::now()->addMonth()->format('Y-m-d'));
        } elseif ($criterio == 'empleado') {
            $contratos =  $contratos->where('contratos.empleado_id', $request->idempleado);
        } elseif ($buscar!='') {
            $contratos =  $contratos->where('contratos.finLaboral', '>=', Carbon::now())
            ->where('contratos.finLaboral', '<', Carbon::now()->addMonth()->format('Y-m-d'));
            $contratos= $contratos->where('contratos.'.$criterio, 'like', '%'. $buscar . '%');
        }
        // $contratos=$contratos->select('empleados.*',
        // DB::raw("DATE_FORMAT(contratos.finLaboral, '%d/%m/%Y') as finLaboral2"),
        // DB::raw("DATE_FORMAT(contratos.inicioLaboral, '%d/%m/%Y') as inicioLaboral2"))->paginate(3);
        // $contratos=$contratos->select('empleados.*')->groupBy('empleados.id')->paginate(3);
        $contratos=$contratos->select(
            'contratos.*',
            DB::raw("DATE_FORMAT(contratos.inicioLaboral, '%d/%m/%Y') as inicioLaboral2"),
            DB::raw("DATE_FORMAT(contratos.finLaboral, '%d/%m/%Y') as finLaboral2"),
            'empleados.nombre as nombreEmpleado',
            'empleados.apellido as apellidoEmpleado'
        )->paginate(3);
         
      
        return [
            'pagination' => [
                'total'        => $contratos->total(),
                'current_page' => $contratos->currentPage(),
                'per_page'     => $contratos->perPage(),
                'last_page'    => $contratos->lastPage(),
                'from'         => $contratos->firstItem(),
                'to'           => $contratos->lastItem(),
            ],
            'contratos' => $contratos
        ];
    }

    public function selectContrato(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
 
        $filtro = $request->filtro;
        if ($filtro=='') {
            $contratos = Contrato::where('condicion', '=', 1)
            ->orderBy('nombre', 'asc')->get();
        } else {
            $contratos = Contrato::where('nombre', 'like', '%'. $filtro . '%')
            ->where('condicion', '=', 1)
            ->orderBy('nombre', 'asc')->get();
        }
       
        
        return ['contratos' => $contratos];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function show(Contrato $contrato)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function edit(Contrato $contrato)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contrato  $contrato
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
            
             //A continiacion se calcula si la cantidad de dias seleccionado respeta la cantidad de dias
            //definido en el TIPO de CONTRATO
            //********************************** */
            $fechaEmision = Carbon::parse($request->input('inicioLaboral'));
            $fechaExpiracion = Carbon::parse($request->input('finLaboral'));
               
            
            $cantidadDiasRealTrabajo = $fechaExpiracion->diffInDays($fechaEmision)+ 1;
            //cada 7 dias 1 no es Habil y sin contar los feriados
            $decimales = explode('.', $cantidadDiasRealTrabajo/7);
            $cantidadDiasRealTrabajo-= $decimales[0] ;
            $diasNoLaborales= Calendar::where('start_date', '>=', $request->inicioLaboral)->where('start_date', '<=', $request->finLaboral)->get();
            $contarFeriados=count($diasNoLaborales);
            if ($contarFeriados>0) {
                $cantidadDiasRealTrabajo-=$contarFeriados;
            }
            
            $tipoContrato=  TipoContrato::findOrFail($request->idtipocontrato);

            $fechaFinContrato= null;
            if (($tipoContrato->diasMaximo ==0) && ($tipoContrato->diasMinimo > 0)) {
                //es un contrato indeterminado
            } else {
                $fechaFinContrato=$request->finLaboral;
            }
            //****************************************************************** */
           

            $contrato = Contrato::findOrFail($request->id);
            //return $request->id;
            
            $empleado    = Empleado::find($request->idempleado);
            $contratoViejo=$empleado->contratos->where('actual', 1)->first();
            if ($contratoViejo) {
                $contratoViejo->actual=0;
                $contratoViejo->condicion=0;
                $contratoViejo->save();
            }

            if ($request->hasFile($request->contrato)) {
                $exploded = explode(',', $request->contrato);
                $decoded = base64_decode($exploded[1]);
                if (str_contains($exploded[0], 'pdf')) {
                    $extension = 'pdf';
                } else {
                    $extension = 'pdf';
                }
                $fileName = str_random().'.'.$extension;
                $path = public_path().'/'.$fileName;
                file_put_contents($path, $decoded);
            } else {
                $fileName= $contrato->contrato;
            }
            $contrato->nombre = $request->nombre;
            $contrato->descripcion = $request->descripcion;
            $contrato->puesto_id=($request->idpuesto);
            $contrato->empleado_id=($request->idempleado);
            //$contrato->tipoContrato_id=($request->idTipoContrato);
            $contrato->cantidadHorasDiarias=intval($request->cantidadHorasDiarias);
            $contrato->salario=floatval($request->salario);
            $contrato->inicioLaboral= $request->inicioLaboral;
            // $contrato->finLaboral= $request->finLaboral;
            $contrato->finLaboral= $fechaFinContrato;
            $contrato->contrato=$fileName;
            $contrato->descripcion = $request->descripcion ;
            $contrato->tipoContrato_id=($request->idtipocontrato);
            // return 0;
            $contrato->save();
        } catch (PDOException $e) {
            return redirect()->withErrors('Error', [$e]);
            // return 'error' + $e;
        }
    }
    
    public function calculadorDias(Request $request)
    {
        //********************************** */
        $fechaEmision = Carbon::parse($request->inicioLaboral);
        $fechaExpiracion = Carbon::parse($request->finLaboral);
        $tipoContrato = TipoContrato::findOrFail($request->tipoContrato_id);

        if ($tipoContrato->diasMaximo == 0 && $tipoContrato->diasMinimo > 0) {
            return 'contrato indeterminado';
        }
        $cantidadDiasRealTrabajo = $fechaExpiracion->diffInDays($fechaEmision)+ 1;

        //cada 7 dias 1 no es Habil y sin contar los feriados
        $decimales = explode('.', $cantidadDiasRealTrabajo/7);
        $cantidadDiasRealTrabajo-= $decimales[0] ;
        $diasNoLaborales= Calendar::all()->where('start_date', '>=', $request->inicioLaboral)->where('start_date', '<=', $request->finLaboral);

        $contarFeriados=count($diasNoLaborales);
        if ($contarFeriados>0) {
            $cantidadDiasRealTrabajo-=$contarFeriados;
        }
        return $cantidadDiasRealTrabajo;
        //****************************************************************** */
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $contrato = Contrato::findOrFail($request->id);
        $empleado    = Empleado::find($contrato->empleado_id);
        $contratoViejo=$empleado->contratos->where('actual', 1)->first();
        if ($contratoViejo) {
            $contratoViejo->actual=0;
            $contratoViejo->condicion=0;
            $contratoViejo->save();
        }

        $contrato->condicion = '0';
        $contrato->save();
    }
 
    public function activar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $contrato = Contrato::findOrFail($request->id);
        $empleado    = Empleado::find($contrato->empleado_id);
        $contratoViejo=$empleado->contratos->where('actual', 1)->first();
        if ($contratoViejo) {
            $contratoViejo->actual=0;
            $contratoViejo->condicion=0;
            $contratoViejo->save();
        }
        $contrato->actual=1;
        $contrato->condicion = '1';
        $contrato->save();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contrato $contrato)
    {
        //
    }

    public function pdfContrato(Request $request)
    {
        $contratos = Contrato::join('tipo_contratos', 'contratos.tipoContrato_id', '=', 'tipo_contratos.id')
        ->join('empleados', 'contratos.empleado_id', '=', 'empleados.id')
        ->select('contratos.*', 'tipo_contratos.nombre as nombreTipoContrato', 'empleados.nombre as nombreEmpleado', 'empleados.apellido as apellidoEmpleado');
            
        
        //anido las consultas segun los filtros
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($criterio =='activo') {
            $contratos->where('contratos.condicion', 1);
        } elseif ($criterio =='desactivado') {
            $contratos->where('contratos.condicion', 0);
        } elseif ($criterio =='vigente') {//contrato en curso
            $contratos->where('contratos.actual', 1);
        } elseif ($criterio =='terminado') {
            $contratos->where('contratos.finLaboral', '<', Carbon::now());
        } elseif ($criterio =='tipoContrato') {
            $criterio='Tipo de Contrato';
            $contratos->where('tipo_Contratos.id', $request->tipoContrato_id_filtro);
        } elseif ($buscar!='') {
            $contratos->where('contratos.'.$criterio, 'like', '%'. $buscar . '%');
        }
         
         
        $contratos= $contratos->orderBy('nombre', 'desc')->get();
        $buscar= $buscar ? ucfirst($buscar): 'Sin Busqueda';
        $criterio= $criterio ? ucfirst($criterio): 'Sin Criterio';
       
        $count = count($contratos);
        // $count = 1;
        $now= Carbon::now();
        
        $pdf = PDF::loadView('pdf.contrato', ['contratos' => $contratos, 'buscar' => $buscar, 'criterio' => $criterio, 'now' => $now, 'count' => $count]);
        
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $y = $canvas->get_height() - 35;
        $pdf->getDomPDF()->get_canvas()->page_text(500, $y, "Pagina {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream();
    }
}
