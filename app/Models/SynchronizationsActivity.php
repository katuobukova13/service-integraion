<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SynchronizationsActivity extends Model
{
  use HasFactory;

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */

  protected $fillable = ['sync_id', 'status'];
  protected $dates = [
    'started_at',
    'finished_at'
  ];

}
