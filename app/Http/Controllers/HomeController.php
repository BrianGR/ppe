<?php

namespace sisventas\Http\Controllers;

use Illuminate\Http\Request;
use sisventas\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisventas\Http\Requests\VentaFormRequest;
use sisventas\venta;
use sisventas\detalledeventa;
use sisventas\articulo;

use DB;
use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request)
        {
            $query=trim($request->GET('searchText'));

            $ventas=DB::table('venta as v')
                ->join('persona as p','v.idcliente','=','p.idpersona')
                ->join('detalledeventa as dv','v.idventa','=','dv.idventa')
                ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.anticipo','v.total_venta')
                ->where('v.num_comprobante','LIKE','%'.$query.'%')
                ->orderBy('v.idventa','desc')
                ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado')
                ->paginate(15);
            return view('home',["ventas"=>$ventas,"searchText"=>$query]);
        }
    }
}
