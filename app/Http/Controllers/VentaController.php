<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Venta;
use App\DetalleVenta;
class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        if ($request)
    	{
    		$query = trim($request->input('searchText'));

    		//DB::raw() permite escribir sentencias SQL en crudo
    		$ingresos = DB::table('venta as i')
    		->join('persona as p', 'p.id', '=', 'i.id_cliente')
    		->join('detalle_venta as di', 'di.id_venta', '=', 'i.id')
    		->select('p.nombre', 'i.id', 'i.tipo_comprobante', 
    			'i.serie_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado', DB::raw('sum(di.cantidad * di.precio_venta) as total'))
    		->where('i.num_comprobante', 'LIKE', "%$query%")
    		->orderBy('i.id', 'DESC')
    		->groupBy('p.nombre', 'i.id', 'i.tipo_comprobante', 
    			'i.serie_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado')
    		->paginate(5);

    		return view('ventas.venta.index', [
    					'ingresos' => $ingresos, 
    					'searchText' => $query]);
    	}
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $personas = DB::table('persona')->where('tipo_persona', '=', 'Cliente')->get();
    	$articulos = DB::table('articulo as art')
    		->select(DB::raw('CONCAT(art.codigo, " - ", art.nombre) AS articulo'), 
    						'art.id' )
    		->where('art.estado', '=', 'Activo')
    		->get(); 

    	return view('ventas.venta.create', [
    				'personas' => $personas,
    				'articulos' => $articulos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {
    	//Capturador de excepciones try
    	//Transacción para asegurar los datos
    	// dd($request);
    	try {
    		DB::beginTransaction();

    		$ingreso = new Venta;
            $ingreso->id_cliente = $request->get('id_proveedor');
    		$ingreso->tipo_comprobante = $request->get('tipo_comprobante');
    		$ingreso->serie_comprobante = $request->get('serie_comprobante');
    		$ingreso->num_comprobante = $request->get('num_comprobante');
    		$ingreso->impuesto = 18;
    		$ingreso->estado = 'A';
    		$ingreso->save();

    		//Artículos array()
    		//Tabla detalle_ingreso
    		$id_articulo = $request->get('id_articulo'); //array()
    		$cantidad = $request->get('cantidad');
    		$precio_compra = $request->get('precio_compra');
    		$precio_venta = $request->get('precio_compra');

    		dd($id_articulo);

    		//Recorre los detalles de ingreso
    		$cont = 0;

    		while($cont < count($id_articulo))
    		{
    			$detalle = new DetalleVenta;
    			//$ingreso->id del ingreso que recien se guardo 
    			$detalle->id_venta = $ingreso->id;
    			//id_articulo de la posición cero
    			$detalle->id_articulo = $id_articulo[$cont];
    			$detalle->cantidad = $cantidad[$cont];
    			$detalle->precio_comrpa = $precio_compra[$cont];
    			$detalle->precio_venta = $precio_venta[$cont];
    			$detalle->save();

    			$cont = $cont + 1;
    		}

    		DB::commit();
    	} catch (Exception $e) {
    		//Si existe algún error en la Transacción
    		DB::rollback(); //Anular los cambios en la DB
    	}

    	return redirect('ventas/venta');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
