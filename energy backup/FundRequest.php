<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FundRequest extends Model {
    protected $fillable = [
        'requester_name', 
        'amount', 
        'purpose', 
        'user_id', 
        'logistics', 
        'status'
    ];
}