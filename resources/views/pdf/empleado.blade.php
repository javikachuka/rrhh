<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Auditorias</title>
    <style>
        body {
        /*position: relative;*/
        /*width: 16cm;  */
        /*height: 29.7cm; */
        /*margin: 0 auto; */
        /*color: #555555;*/
        /*background: #FFFFFF; */
        font-family: Arial, sans-serif; 
        font-size: 14px;
        /*font-family: SourceSansPro;*/
        }

        #logo{
        float: left;
        margin-top: 1%;
        margin-left: 2%;
        margin-right: 2%;
        }

        #imagen{
        width: 100px;
        }

        #datos{
        float: left;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        /*text-align: justify;*/
        }

        #encabezado{
        text-align: center;
        margin-left: 10%;
        margin-right: 35%;
        font-size: 15px;
        }

        #fact{
        /*position: relative;*/
        float: right;
        margin-top: 2%;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 20px;
        }

        section{
        clear: left;
        }

        #cliente{
        text-align: left;
        }

        #facliente{
        width: 40%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #fac, #fv, #fa{
        color: #FFFFFF;
        font-size: 15px;
        }

        #facliente thead{
        padding: 20px;
        background: #2183E3;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facvendedor{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facvendedor thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facarticulo{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facarticulo thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #gracias{
        text-align: center; 
        }
    </style>
    <body>
        <header>
       
            <div id="logo">
                <img src="img/logo.png" alt="incanatoIT" id="imagen">
            </div>
            <div id="datos">
                <p id="encabezado">
                    <b>Sistema de Produccion</b><br>José Simon 1368, Puerto Rico - Misiones, Argentina<br>Telefono:(+54)3764414563<br>Email:simon.cia@gmail.com
                </p>
            </div>
        
            <div id="fact">
                <p>Fecha<br>
                {{ \Carbon\Carbon::parse($now)->format('d/m/Y')}}</p>
            </div>
           
        </header>
        <br>
        <section>
            <div>
                <table id="facliente">
                    <thead>                        
                        <tr>
                            <th id="fac">Auditorias</th>
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
        <section>
            <div>
                <table id="facarticulo">
                    <thead>
                        <tr id="fa">
                        <th>Tabla</th>
                        <th>Accion</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Num Movimiento</th>
                           
                           
                        </tr>
                    </thead>
                   
                    <tbody>
                    
                        @foreach ($auditorias as $aud)
                        <tr>
                        <td>{{$aud->tabla}}</td>
                            <td>{{$aud->accion}}</td>
                            <td>{{$aud->fecha_hora}}</td>
                            <td>{{$aud->user}}</td>
                            <td>{{$aud->id}}</td>
                            
                            
                            
                            
                        </tr>
                        @endforeach
                    </tbody>
                    <tbody>
                    </tbody>
                
                </table>
            </div>
        </section>
        <br>
        <br>
        <div class="izquierda">
        <p><strong>Total de registros: </strong>{{$cont}}</p>
    </div>    
    </body>
</html>