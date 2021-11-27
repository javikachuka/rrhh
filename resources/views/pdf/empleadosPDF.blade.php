@extends('pdf.layouts')
@section('content')
<br/>
<h3 id="facarticulo" align="left"> <u> Reporte de Empleados</u></h3>
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
                <th>Nombre</th>
                <th>Apellido</th>
                <th>CUIL</th>
                <th>Direccion</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody style="background-color:white ; color:black;">
            @if ($count>0)

            @foreach ($empleados as $emple)
            <tr>
                <td>{{$emple->nombre}}</td>
                <td>{{$emple->apellido}}</td>
                <td>{{$emple->cuil}}</td>
                <td>{{$emple->direccion}}</td>
                <td>{{$emple->condicion == 1 ? 'Activo' : 'Inactivo'}}</td>
                
            </tr>
            @endforeach
            @endif
        </tbody>


    </table>
</div>
@endsection