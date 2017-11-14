@extends('layouts.admin')
@section('contenido')
	<div class="row">
		<div class="col-lg-12 col-md-6 col-dm-12 col-xs-12">
			<div class="form-group">
				<label for="nombre">Nombre Proveedor</label>
				<p>{{$pedido->nombre}}</p>
				</select>
			</div>
		</div>

		<div class="col-lg-3 col-md-4 col-dm-12 col-xs-12">
			<div class="form-group">
				<label>Fecha hora</label>
				<p>{{$pedido->fecha_hora}}</p>
			</div>
		</div>



		<div class="col-lg-3 col-md-4 col-dm-12 col-xs-12">
			<div class="form-group">
				<label for="numero_comprobante">Numero de Comprobante</label>
				<p>{{$pedido->num_comprobante}}</p>
			</div>
		</div>

		<div class="col-lg-3 col-md-4 col-dm-12 col-xs-12">
			<div class="form-group">
				<label for="anticipo">Condiciones</label>
				<p>{{$pedido->condiciones}}</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="panel panel-primary">
			<div class="panel-body">

				<div class="col-lg-12 col-md-12 col-dm-12 col-xs-12">
					<div class="table-responsive">
						<table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
							<thead style="background-color:#caf5a9">
							<th>Articulo</th>
							<th>Cantidad</th>
							<th>Precio Compra</th>



							</thead>
							<tfoot>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th>Total Compra<h4 id="total">{{$pedido->total_venta}}</h4></th>
							</tfoot>
							<tbody>
							@foreach($detalles as $det)
								<tr>

									<td>{{$det->articulo}}</td>
									<td>{{$det->cantidad}}</td>

									<td>{{$det->precio_venta}}</td>

								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

