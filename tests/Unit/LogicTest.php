<?php

namespace Tests\Unit;

use App\Repositories\Users;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogicTest extends TestCase
{
    /**
     * Test users fetch grouping by week
     *
     * @return void
     */
    public function testUserFetchByWeekTest()
    {
        $userRepository = new Users();
        $users = $userRepository->groupedByWeek()->toArray();
        $weekNumbers = array_keys($users);

        // Couldn't find an array assert method that checks the
        // array for values between 2 given values
        foreach ($weekNumbers as $weekNumber) {
            $this->assertGreaterThanOrEqual(1, $weekNumber);
            $this->assertLessThanOrEqual(52, $weekNumber);
        }
    }
}
