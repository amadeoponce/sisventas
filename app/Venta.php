<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta'; 

    protected $primaryKey = 'id';

    protected $fillable = [

		'id_cliente',
		'tipo_comprobante',
		'serie_comprobante',
		'num_comprobante',
    	'impuesto',
        
        'estado'

    ];
}
