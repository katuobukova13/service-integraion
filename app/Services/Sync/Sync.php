<?php

namespace App\Services\Sync;

use Mockery\Matcher\Closure;

enum SyncDirection
{
  case TailToHead;
  case HeadToTail;
}

abstract class Sync
{
  public function setFieldMatch(string $headColumnName, string $tailColumnName, Closure $callback)
  {
  }

  public function __construct(string $sync, SyncDirection $direction)
  {

  }

}
