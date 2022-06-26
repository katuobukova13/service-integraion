<?php

namespace App\Modules\Integration\Domain\Amocrm;

use JetBrains\PhpStorm\ArrayShape;

final class AmocrmSamplingClause
{
  public function __construct(
    public readonly array  $with = [],
    public readonly int    $page = 1,
    public readonly int    $limit = 50,
    public readonly string $query = '',
    public readonly array  $filter = [],
    public readonly array  $order = [],
  )
  {
  }

  #[ArrayShape([
    'with' => "array",
    'page' => "int",
    'limit' => "int",
    'query' => "string",
    'filter' => "array",
    'order' => "array"
  ])]
  public function toArray(): array
  {
    return [
      'with' => $this->with,
      'page' => $this->page,
      'limit' => $this->limit,
      'query' => $this->query,
      'filter' => $this->filter,
      'order' => $this->order,
    ];
  }
}
