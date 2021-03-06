<?php

namespace sisventas\Http\Controllers;

use Illuminate\Http\Request;
use sisventas\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use sisventas\Http\Requests\ArticuloFormRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisventas\Articulo;
use DB;

class ArticuloController extends Controller
{ 
   public function __construct()
    {
	$this->middleware('auth');
    }

    public function index(Request $request)
    {
    	if($request)
    	{
    		$query=trim($request->get('searchText'));
    		$articulos=DB::table('articulo as a')
    		->join ('categoria as c','a.idcategoria','=','c.idcategoria')
			->select('a.idarticulo','a.nombre', 'a.codigo', 'a.stock', 'c.nombre as categoria','a.descripccion', 'a.imagen', 'a.estado', 'a.impuesto' )
    		->where('a.nombre','LIKE','%'.$query.'%')
    		->orwhere('a.codigo','LIKE','%'.$query.'%')
    		->orderBy('a.idarticulo','desc')
    		->paginate(10);
    		return view('almacen.articulo.index',["articulos"=>$articulos,"searchText"=>$query]);
    	}

    }
    public function create()
    {
    	$impuestos=DB::table('impuesto')->where('Estado','=','A')->get();
		$categorias=DB::table('categoria')->where('condicion','=','1')->get();
		return view("almacen.articulo.create",["categorias"=>$categorias, "impuestos"=>$impuestos]);
    }

    public function store (ArticuloFormRequest $request)
    {

		$articulo=new articulo;
		$articulo->idcategoria=$request->get('idcategoria');
		$articulo->codigo=$request->get('codigo');
		$articulo->nombre=$request->get('nombre');
		$articulo->stock=$request->get('stock');
		$articulo->impuesto=(float)$request->get('impuesto');
		$articulo->descripccion=$request->get('descripccion');
		$articulo->estado='Activo';

	if(Input::hasFile('imagen')){
		$file=Input::file('imagen');
		$file->move(public_path().'/imagenes/articulos/',$file->getClientOriginalName());
		$articulo->imagen=$file->getClientOriginalName();
	}
		$articulo->save();
		return Redirect::to('almacen/articulo');
	//}	
}

	public function edit($id)
	{
		$articulo = Articulo::findOrFail($id);
		$impuestos=DB::table('impuesto')->where('Estado','=','A')->get();
		$categorias=DB::table('categoria')->where('condicion','=','1')->get();
		return view("almacen.articulo.edit",["articulo"=>$articulo,"categoria"=>$categorias,"impuestos"=>$impuestos]);
	}

  public function update(ArticuloFormRequest $request, $id)
	{
		$articulo =Articulo::findOrFail($id);
		$articulo->idcategoria=$request->get('idcategoria');
		$articulo->nombre=$request->get('nombre');
		$articulo->codigo=$request->get('codigo');
		$articulo->impuesto=(float)$request->get('impuesto'); 
		$articulo->stock=$request->get('stock');
		$articulo->descripccion=$request->get('descripccion');

		if(Input::hasFile('imagen'))
		{
		$file=Input::file('imagen');
		$file->move(public_path().'/imagenes/articulos/',$file->getClientOriginalName());
		
		$articulo->imagen=$file->getClientOriginalName();
		}
		$articulo->update();
		return Redirect::to('almacen/articulo');
}



    public function show($id)
    {
		return view("almacen.articulo.show",["articulo"=>Articulo::findOrFail($id)]);
    }


	public function destroy($id)
	{
	$articulo=Articulo::findOrFail($id);
	$articulo->estado='Inactivo';
	$articulo->delete();
	return Redirect::to('almacen/articulo');
	}
	
}
