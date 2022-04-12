<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetcourseUser extends Model
{
  use HasFactory;

  protected $table = 'getcourse_users';

  protected $primaryKey = 'id';

  protected $fillable = [
    'id_getcourse',
    'name',
    'phone',
    'email',
    'city',
    'country'
  ];
}
