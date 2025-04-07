<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'representative',
        'client_id',
        'invoice_type',
        'discount',
        'discount_value',
        'tax',
        'final_total',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'return_invoice_id');
    }
}
