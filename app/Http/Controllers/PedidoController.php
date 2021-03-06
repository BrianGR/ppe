<?php
namespace sisventas\Http\Controllers;

use Illuminate\Http\Request;
use sisventas\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisventas\Http\Requests\PedidoFormRequest;
use sisventas\Pedido;
use sisventas\detalledepedido;
use sisventas\articulo;
use DB;
use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;
class PedidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /*public function index(Request $request)
        {

                $query=trim($request->GET('searchText'));
            $pedido=DB::table('pedido as pe')
                    ->join('detalledepedido as dp','pe.idpedido','=','dp.idpedido')
                                    ->join('articulo as a','a.idarticulo','=','dp.idarticulo')
                                    ->join('detallecotizacion as dc','dc.idarticulo','=','a.idarticulo')
                                    ->join('cotizacion as c','c.idcotizacion','=','dc.idcotizacion')
             ->join('persona as p','c.idcliente','=','p.idpersona')

                      ->join('venta as v','v.idcliente','=','p.idpersona')
                ->select('pe.idpedido','pe.fecha_hora','p.nombre','pe.num_comprobante','v.anticipo','v.total_venta')
                ->where('pe.num_comprobante','LIKE','%'.$query.'%')
                ->orderBy('pe.idpedido','desc')
                ->groupBy('pe.idpedido','pe.fecha_hora','p.nombre','pe.num_comprobante')
                ->paginate(7);
                return view('pedidos.index',["pedido"=>$pedido,"searchText"=>$query]);

        }*/
    /*public function index(Request $request)
     {
         if($request)
         {
             $query=trim($request->GET('searchText'));
         $pedido=DB::table('pedido as pe')
          ->join('persona as p','pe.idproveedor','=','p.idpersona')
                   ->join('venta as v','v.idcliente','=','p.idpersona')
         ->join('detalledepedido as dp','pe.idpedido','=','dp.idpedido')
             ->select('pe.idpedido','pe.fecha_hora','p.nombre','pe.num_comprobante','v.anticipo','v.total_venta')
             ->where('pe.num_comprobante','LIKE','%'.$query.'%')
             ->orderBy('pe.idpedido','desc')
             ->groupBy('pe.idpedido','pe.fecha_hora','p.nombre','pe.num_comprobante')
             ->paginate(7);
             return view('pedidos.index',["pedido"=>$pedido,"searchText"=>$query]);
         }
     }*/
    public function index(Request $request)
    {
        if($request)
        {
            $query=trim($request->GET('searchText'));
            $pedido=pedido::all();
            return view('pedidos.index',["pedido"=>$pedido,"searchText"=>$query]);
        }
    }
    public function create()
    {
        $ipedido=DB::table('pedido')->max('idpedido')+1; //as incredible
        $personas=DB::table('persona')->where('tipo_persona','=','Proveedor')->get(); //si el provedor tambien es cliente, retirara el where
        $articulos=DB::table('articulo as art')
            ->join('detalle_ingreso as di','art.idarticulo','=','di.idarticulo')
            ->select(DB::raw('CONCAT(art.codigo, " ",art.nombre) AS articulo'),'art.idarticulo','art.stock', 'art.descripccion', 'di.precio_compra') //esta consulta extrae el promdio del valor de venta del producto
            ->where('art.estado','=','Activo')
            // solo muestra articulos con stock en positivo
            ->groupBy('articulo','art.idarticulo')
            ->get();
        return view('pedidos.create',["personas"=>$personas,"articulos"=>$articulos, "ipedido"=>$ipedido]);
    }
    public function show($id)
    {
        $pedido=DB::table('pedido as pe')
            ->join('persona as p','pe.idproveedor','=','p.idpersona')
            ->join('detalledepedido as dp','pe.idpedido','=','dp.idpedido')
            ->select('pe.idpedido','pe.fecha_hora','p.nombre','pe.num_comprobante','pe.total_venta','pe.condiciones')
            ->where('pe.idpedido','=',$id)
            ->first();
        $detalles=DB::table('detalledepedido as dp')
            ->join('articulo as a','dp.idarticulo','=','a.idarticulo')
            ->select('a.nombre as articulo','dp.cantidad','dp.descuento','dp.precio_venta')
            ->where('dp.idpedido',$id)
            ->get();
        return view('pedidos.show',["pedido"=>$pedido,"detalles"=>$detalles]);
    }
    public function edit($id)
    {
        $pedido=DB::table('pedido as pe')
            ->join('detalledepedido as dp','pe.idpedido','=','dp.idpedido')
            ->select('pe.total_venta','pe.idpedido','dp.iddetalledeventa')
            ->where('pe.idpedido','=',$id)
            ->first();
        $detalles=DB::table('detalledepedido as dp')
            ->join('articulo as art','art.idarticulo','=','dp.idarticulo')
            ->select(DB::raw('CONCAT(art.codigo, " ",art.nombre) AS articulo'),'dp.idarticulo','dp.cantidad','dp.descuento','dp.iddetalledeventa as id' ,'dp.precio_venta')
            ->where('dp.idpedido',$id)
            ->get();
        $articulos=DB::table('articulo as art')
            ->join('detalle_ingreso as di','art.idarticulo','=','di.idarticulo')
            ->select(DB::raw('CONCAT(art.codigo, " ",art.nombre) AS articulo'),'art.idarticulo','art.stock','art.impuesto', DB::raw('avg(di.precio_venta) as precio_promedio')) //esta consulta extrae el promdio del valor de venta del producto
            ->where('art.estado','=','Activo')
            ->where('art.stock','>','0') // solo muestra articulos con stock en positivo
            ->groupBy('articulo','art.idarticulo','art.stock')
            ->get();
        return view("pedido.edit", ["pedido"=>$pedido,"articulos"=>$articulos,"detalles"=>$detalles]);

    }
    public function destroy($id)

    {

    }
    public function store(PedidoFormRequest $request) {

        //try{
        //DB::beginTransaction();
        $pedido=new pedido();
        $pedido->idproveedor=$request->get('idproveedor');
        $pedido->num_comprobante=$request->get('num_comprobante');
        $pedido->total_venta=$request->get('total_venta');
        $mytime = Carbon::now('America/Santiago');
        $pedido->fecha_hora=$mytime->toDateTimeString();
        //$ingreso->impuesto='16';//$request->get('impuesto');//16%
        //$pedido->estado='A';
        $pedido->condiciones=$request->get('condiciones');
        //$pedido->idproyecto=$request->get('idproyecto');
        $pedido->save();
        $idarticulo=$request->get('idarticulo');
        $cantidad=$request->get('cantidad');
        $descuento=$request->get('descuento');
        $precio_venta=$request->get('precio_compra');
        $cont=0;

        While($cont < count($idarticulo))
        {
            $detalles=new detalledepedido();
            $detalles->idpedido=$pedido->idpedido;
            $detalles->idarticulo=$idarticulo[$cont];
            $detalles->cantidad=$cantidad[$cont];
            $detalles->precio_venta=$precio_venta[$cont];
            $detalles->save();
            $cont=$cont+1;
        }
        /*DB::commit();
        }
        catch(\Exception $e)
        {
        DB::rollback();
        }*/
        return Redirect::to('pedidos');
    }
    public function crear_pdf($id)
    {
        $venta=DB::table('pedido as pe')
            ->join('persona as p','pe.idproveedor','=','p.idpersona')
            ->join('detalledepedido as dp','pe.idpedido','=','dp.idpedido')
            ->select('pe.idpedido','pe.fecha_hora','p.nombre','p.idpersona','p.nombrecontacto','p.telefono','p.direccion','p.email', 'p.tipo_documento','p.num_documento','pe.num_comprobante','v.descripccion','v.total_venta','v.condiciones','dp.iddetalledeventa')
            ->where('pe.idpedido','=',$id)
            ->first();
        $detalle=DB::table('detalledepedido as dp')
            ->join('articulo as a','dp.idarticulo','=','a.idarticulo')
            ->select('a.nombre as articulo','a.codigo','a.imagen', 'a.descripccion','dp.cantidad','dp.descuento','dp.precio_venta')
            ->where('idpedido',$id)
            ->get();
        $date = date('Y-m-d');
        $pdf=  \PDF::loadview('pedido.reporte',["detalle"=>$detalle, "venta"=>$venta]) ->setPaper('letter', 'portrait');
        return $pdf->stream('reporte.pdf');
    }

}