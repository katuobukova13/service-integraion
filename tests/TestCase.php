<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

abstract class TestCase extends BaseTestCase
{
  use CreatesApplication, WithoutMiddleware;

  public function setUp(): void
  {
    parent::setUp();
  }
}
