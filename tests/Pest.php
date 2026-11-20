<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)->in('Feature', 'Unit/Auth');
uses(RefreshDatabase::class)->in('Unit/Auth');
