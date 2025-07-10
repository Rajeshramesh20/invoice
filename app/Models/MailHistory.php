<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customers;

class MailHistory extends Model
{
    use HasFactory;

    protected $table = 'mail_history';
    protected $primaryKey = 'mail_id';
    protected $fillable = ['customer_id','email','content','status'];

    public function customer(){
        return $this->belongsTo(Customers::class, 'customer_id');
    }
}
