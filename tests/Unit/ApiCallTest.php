<?php

declare(strict_types=1);

namespace Tests\Unit;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class ApiCallTest extends TestCase
{
    private ?Client $http;

    public function setUp(): void
    {
        $this->http = new Client(['base_url' => 'http://localhost/ras/']);
    }

    public function tearDown(): void
    {
        $this->http = null;
    }

    public function test_get_call(): void
    {
        $response = $this->http->request('GET', 'user-agent');

        $this->assertEquals(404, $response->getStatusCode());
    }
}