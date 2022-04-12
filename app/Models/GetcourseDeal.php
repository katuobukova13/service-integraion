<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetcourseDeal extends Model
{
  use HasFactory;

  protected $table = 'getcourse_deals';

  protected $primaryKey = 'id';

  protected $fillable = [
    'number',
    'title',
    'status',
    'user_id',
    'sum',
    'paid',
    'paid_at'
  ];
}
