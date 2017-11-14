<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Factura de Venta # {{$venta->idventa}}</title>
    <link rel="stylesheet" href="css\style.css" media="all" />
   </head>
  <body>
    <header class="clearfix">
        <div>
            <img src="imagenes\logo.png" width="125px" height="125px">
        </div>
      <div>
      <h1>Factura de Venta # {{$venta->num_comprobante}}</h1>
          <h2>Fecha: {{$venta->fecha_hora}}</h2>
      <div id="pago">
      <p><h3>klkldkl</h3>
      <h3>balalal</h3></p>
      </div>
      <div id="alcance"><b>Alcance: </b>{{$venta->descripccion}}
      </div>
        <div id="DatosCliente">
        <p><b>Cliente:</b> {{$venta->nombre}} <b>Identificación:</b> {{$venta->tipo_documento}} <b>Numero:</b> {{$venta->num_documento}} <b>Nombre del Cliente:</b> {{$venta->nombrecontacto}}</p>
            <p><b>Telefono:</b> {{$venta->telefono}} <b>Correo:</b> {{$venta->email}} <b>Dirección:</b>
        {{$venta->direccion}}</p>
      </div>
     </header>
<table>
   <thead>
       <tr>
            <th>Codigo</th>
            <th>Imagen</th>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Total</th>
          </tr>
        </thead>
   <tbody>
@foreach($detalle as $det)
      <tr>
        <td class="item">{{$det->codigo}}</td>
        <td> <img src="imagenes\articulos\{{$det->imagen}}"> </td>
        <td class="item">{{$det->descripccion}}</td>
        <td class="number">{{$det->cantidad}}</td>
        <td class="item"><output>{{$det->precio_venta}}</output></td>
        <td class="item"><output>{{($det->cantidad*$det->precio_venta)}}</output></td>
      </tr>
         @endforeach
     </tbody>
</table>    
    <footer>
    <table> 
    <thead>
       <tr>
            <th>---------------------------------</th>
            <th>-------------------------------------------------------</th>
            <th>-----------------------------------------------------</th>
            <th>----------------------------------------------------</th>
            <th>---------------------------------------------------</th>
            <th>---------------------------------------------------</th>
          </tr>
        </thead>
   
        <tbody>
            <tr>
            <td colspan="6" class="descripccion"> Subtotal </td>
            <td class="descripccion">${{$venta->total_venta}}</td>
          </tr>
          <tr>
            <td colspan="6" class="descripccion">Impuestos</td>
            <td class="descripccion">$0</td>
          </tr>
          <tr>
            <td colspan="6" class="descripccion">Descuento</td>
            <td class="descripccion">$0</td>
          </tr>
          <tr>
            <td colspan="6" class="descripccion">Anticipo</td>
            <td class="descripccion">${{$venta->anticipo}}</td>
          </tr>s
          <tr>
            <td colspan="6" class="descripccion">Valor Total</td>
            <td class="descripccion" id="ptotal">{{$venta->total_venta}}</td>
          </tr>
          </tbody>
    </table>
      <div>Condiciones del servicio:</div>
      <textarea cols="30" rows="5" id="comment">{{$venta->condiciones}}</textarea>
      <div>PPE ltda - Factura creada por Sistema de ventas PPE ltda

      </div>
    </footer>
</body>
</html>