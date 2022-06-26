<?php

namespace App\Modules\Integration\Core\Facades;

use JetBrains\PhpStorm\ArrayShape;

final class SamplingClause
{
  public function __construct(
    public readonly array $select = [],
    public readonly array $with = [],
    public readonly array $filters = [],
    public readonly int   $limit = 50,
    public readonly int   $page = 1,
  )
  {
  }

  #[ArrayShape([
    'select' => "array",
    'with' => "array",
    'filters' => "array",
    'limit' => "int",
    'page' => "int"
  ])]
  public function toArray(): array
  {
    return [
      'select' => $this->select,
      'with' => $this->with,
      'filters' => $this->filters,
      'limit' => $this->limit,
      'page' => $this->page
    ];
  }
}
