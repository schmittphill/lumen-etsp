<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceModel extends Model
{
    //
    use HasFactory;

    public $table = "invoices";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     * 
     */
    
    protected $fillable = [
        'ref',
        'customer_id',
        'customer_name',
        'customer_phone',
        'business_type',
        'item_details',
        'amount',
        'currency',
        'payment_status',
        'issued_by',
        'issue_date',
        'due_date',
        'location',
        'status'
    ];
}
