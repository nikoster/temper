<?php

namespace Tests\Unit;

use App\Services\StatsService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StatsServiceTest extends TestCase
{
    /** @var  StatsService */
    protected $statsService;

    protected $usersGroupedByWeek;

    public function setUp()
    {
        parent:: setUp();

        $this->statsService = $this->app->make('stats');
        $this->usersGroupedByWeek = $this->statsService->users->groupedByWeek();
    }

    public function testUserCountOfSingleOnboardingPercentageTest()
    {
        $onboardingPercentage = 40;
        $onboardingPercentagesOfUsers = [0, 10, 30, 40, 80, 99, 100];

        $this->assertEquals(4, $this->statsService->getUserCountOfSingleOnboardingPercentage($onboardingPercentage, $onboardingPercentagesOfUsers));
    }

    public function testPercentageMappingHasKeysFrom0To100()
    {
        $usersInCohort = $this->usersGroupedByWeek->first();
        $mappedPercentages = $this->statsService->mapPercentageOfUsersToAllOnboardingPercentages($usersInCohort);

        $this->assertEquals(range(0, 100), array_keys($mappedPercentages));
    }

    public function testPercentageMappingHasValidPercentageValues()
    {
        $mappedPercentages = $this->statsService->mapPercentageOfUsersToAllOnboardingPercentages($this->usersGroupedByWeek->first());

        foreach ($mappedPercentages as $onboardingPercentage => $usersPercentage) {
            // Beginning (0% onboarding) should always be 100%
            if ($onboardingPercentage === 0) {
                $this->assertEquals(100, $usersPercentage);
            }

            // Check the remaining percentages, no more special cases left
            $this->assertGreaterThanOrEqual(0, $usersPercentage);
            $this->assertLessThanOrEqual(100, $usersPercentage);
        }
    }

    public function testSingleCohortTest()
    {
        $cohort = $this->statsService->getSingleCohort($this->usersGroupedByWeek->first());

        $this->assertEquals(2, count($cohort));
        $this->assertArrayHasKey('name', $cohort);
        $this->assertArrayHasKey('data', $cohort);

        $this->assertStringMatchesFormat('%s', $cohort['name']);
        $this->assertEquals(101, count($cohort['data']));
    }

    public function testGetRetentionCurvesTest()
    {
        $retentionCurves = $this->statsService->getRetentionCurvesData();

        $this->assertGreaterThan(0, count($retentionCurves));
    }
}
