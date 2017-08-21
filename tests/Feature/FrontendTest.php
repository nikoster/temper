<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TemperTest extends TestCase
{
    /**
     * Test the redirect to /stats.
     *
     * @return void
     */
    public function testRedirectTest()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    /**
     * Test /stats response code.
     *
     * @return void
     */
    public function testStatsResponseTest()
    {
        $response = $this->get('/stats');

        $response->assertStatus(200);
    }

    /**
     * Test rendering of retention curve chart.
     *
     * @return void
     */
    public function testRetentionCurveRenderTest()
    {
        $response = $this->get('/stats');

        $response->assertSee('Weekly Retention Curves, Temper');
    }
}
