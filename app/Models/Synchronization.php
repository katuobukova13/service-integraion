<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Synchronization extends Model
{
  use HasFactory;

  protected $fillable = ['key', 'title', 'description',
    'head_service_class', 'tail_service_class', 'entity_id',
    'category_id'];

  public function synchronzationsActivities()
  {
    return $this->belongsTo(SynchronizationsActivity::class);
  }

  public function synchronzationsEntities()
  {
    return $this->belongsTo(SynchronizationEntity::class);
  }

  public function synchronzationsCategories()
  {
    return $this->belongsTo(SynchronizationCategory::class);
  }
}
