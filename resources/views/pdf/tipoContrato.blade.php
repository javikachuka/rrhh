@extends('pdf.layouts')
@section('content')
<br/>
<h3 id="facarticulo" align="left"> <u> Reporte de Tipo Contratos</u></h3>
<section>
    <div>
        <table id="" style="border-style:none;" >
            <thead style="border: 1px solid white;">                        
                <tr  style="border: 1px solid white;">
                    <th id=""  style="border: 1px solid white;"></th>
                </tr>
            </thead>
            <tbody style="border: 1px solid white;">
          
                <tr  style="border: 1px solid white;">
                    
                    <th  style="border: 1px solid white;"><p id="cliente"> Criterio: {{$criterio}}<br>
                   
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