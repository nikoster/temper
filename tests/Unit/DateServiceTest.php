<?php

namespace Tests\Unit;

use App\Services\DateService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DateServiceTest extends TestCase
{
    /** @var DateService */
    protected $dateService;

    public function setUp()
    {
        parent::setUp();

        $this->dateService = $this->app->make('date');
    }

    /**
     * Test the day subtract method
     *
     * @return void
     */
    public function testDaySubtractTest()
    {
        $givenDate = '1986-03-05';
        $expectedDate = new \DateTime('1986-03-02');
        $daysToSubtract = 3;

        $this->assertEquals($expectedDate, $this->dateService->subtractDaysFromDate($givenDate, $daysToSubtract));
    }

    public function testMondayDateOfTheWeekForDateTest()
    {
        $monday = '2017-08-21';
        $wednesday = '2017-08-23';
        $sunday = '2017-08-27';

        $this->assertEquals($monday, $this->dateService->getMondayDateOfTheWeekForDate($monday));
        $this->assertEquals($monday, $this->dateService->getMondayDateOfTheWeekForDate($wednesday));
        $this->assertEquals($monday, $this->dateService->getMondayDateOfTheWeekForDate($sunday));
    }
}
