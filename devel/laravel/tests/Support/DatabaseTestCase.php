<?php

namespace Tests\Support;

use Illuminate\Foundation\Testing\{
    TestCase as BaseTestCase,
    RefreshDatabase,
};

abstract class DatabaseTestCase extends BaseTestCase
{
    use RefreshDatabase;
}
