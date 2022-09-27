<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private string $model;
    private string $endpoint;

    public function setUp(): void
    {
        parent::setUp();
        $this->model = '';
        $this->endpoint = 'api/';
    }

    public function modelStructure()
    {
        return [];
    }
}
