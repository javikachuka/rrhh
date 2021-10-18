@extends('pdf.layouts')
@section('content')
<h3 id="facarticulo" align="center"> <u> Reporte de Tipo Contratos</u></h3>
<section>
    <div>
        <table id="facliente">
            <thead>                        
                <tr>
                    <th id="fac"></th>
                </tr>
            </thead>
            <tbody>
          
                <tr>
                    
                    <th><p id="cliente"> Criterio: {{$criterio}}<br>
                   
                    Busqueda: {{$buscar}}</p></th>
                   
                </tr>
        
            </tbody>
        </table>
    </div>
</section>
<br>
<section>
    <div>
  
        
        
    </div>
</section>
<br>
<div>
    <table id="facarticulo" >
        <thead class="" style="background-color:white ; color:black;">
            <tr id="">
                <th>ID Tipo</th>
                <th>Nombre</th>
                <th>Cantidad Contratos</th>
            </tr>
        </thead>
        <tbody style="background-color:white ; color:black;">
            @if ($count>0)

            @foreach ($tipoContratos as $tipoContrato)
            <tr style="text-align: right">

                <td>{{$tipoContrato->id}} </td>
                <td>{{$tipoContrato->nombre}}</td>
                <td>{{count($tipoContrato->contratos)}}</td>
            </tr>
            @endforeach
            @endif
        </tbody>


    </table>
</div>
@endsection