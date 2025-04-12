<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCambio extends Model
{
    /** @use HasFactory<\Database\Factories\TipoCambioFactory> */
    use HasFactory;

    protected $table = 'tipo_cambios';
    protected $fillable = [
        'compra',
        'venta',
        'moneda',
        'fecha'
    ];
}
