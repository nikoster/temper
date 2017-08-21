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

    /**
     * Test if the name/label of the cohort is actually a Monday
     *
     * @return void
     */
    public function testCohortNameIsMondayTest()
    {
        // Resolve StatsService
        $data = $this->app->make('stats')->gatherData();

        foreach ($data as $cohort) {
            $dayOfCohortName = Carbon::parse($cohort['name'])->format('w');

            // 1 stands for Monday
            $this->assertEquals(1, $dayOfCohortName);
        }
    }

    /**
     * Test if the cohort values are valid percentages
     *
     * @return void
     */
    public function testCohortPercentageValuesTest()
    {
        // Resolve StatsService
        $data = $this->app->make('stats')->gatherData();

        foreach ($data as $cohort) {
            foreach ($cohort['data'] as $onboardingPercentage => $usersPercentage) {
                // Beginning (0% onboarding) should always be 100%
                if ($onboardingPercentage === 0) {
                    $this->assertEquals(100, $usersPercentage);
                }

                // Check the remaining percentages, no more special cases left
                $this->assertGreaterThanOrEqual(0, $usersPercentage);
                $this->assertLessThanOrEqual(100, $usersPercentage);
            }
        }
    }
}
