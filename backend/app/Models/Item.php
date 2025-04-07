<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'price',
        'sales_invoice_id',
        'return_invoice_id',
    ];
}
