<?php

namespace App\Services;
use App\Repositories\Users;

/**
 * Class StatsService
 *
 * @package App\Services
 */
/**
 * Class StatsService
 * @package App\Services
 */
class StatsService
{
    /**
     * @var Users
     */
    public $users;

    /**
     * @var DateService
     */
    protected $dateService;

    /**
     * StatsService constructor.
     *
     * @param Users $users
     * @param DateService $dateService
     */
    public function __construct(Users $users, DateService $dateService)
    {
        $this->users = $users;
        $this->dateService = $dateService;
    }

    /**
     * Returns the retention curves data.
     *
     * @return array
     */
    public function getRetentionCurvesData()
    {
        return $this->getWeeklyCohorts($this->users->groupedByWeek());
    }

    /**
     * Loop through the weeks/cohorts to create the required structure for the frontend.
     *
     * @param $usersByWeek
     * @return array
     */
    public function getWeeklyCohorts($usersByWeek)
    {
        $data = [];

        foreach ($usersByWeek as $weekUsers) {
            $data[] = $this->getSingleCohort($weekUsers);
        }

        return $data;
    }

    /**
     * Builds the data for a single cohort.
     *
     * @param $weekUsers
     * @return array
     */
    public function getSingleCohort($weekUsers)
    {
        return [
            'name' => $this->getCohortName($weekUsers->first()->created_at),
            'data' => $this->mapPercentageOfUsersToAllOnboardingPercentages($weekUsers)
        ];
    }

    /**
     * The cohort date (in the graph legend) should always be a Monday, since we can't expect
     * that the given data provides registrations from every single day.
     *
     * @param string $date
     * @return string
     */
    private function getCohortName($date)
    {
        return resolve('App\Services\DateService')->getMondayDateOfTheWeekForDate($date);
    }

    /**
     * Calculates the percentage of users that are at (or beyond) the given onboarding percentage.
     *
     * @param $usersInCohort
     * @return array
     */
    public function mapPercentageOfUsersToAllOnboardingPercentages($usersInCohort)
    {
        $onboardingPercentagesOfUsers = $usersInCohort->pluck('onboarding_percentage')->all();
        $onboardingRange = range(0, 100);

        $mapOnboardingPercentageToUserPercentage = function($onboardingPercentage) use ($usersInCohort, $onboardingPercentagesOfUsers) {
            $userCount = $this->getUserCountOfSingleOnboardingPercentage($onboardingPercentage, $onboardingPercentagesOfUsers);

            return $userCount / count($usersInCohort) * 100;
        };

        return array_map($mapOnboardingPercentageToUserPercentage, $onboardingRange);
    }

    /**
     * Get the amount of users that are at (or beyond) the given onboarding percentage.
     *
     * @param $onboardingPercentage
     * @param $onboardingPercentagesOfUsers
     * @return int
     */
    public function getUserCountOfSingleOnboardingPercentage($onboardingPercentage, $onboardingPercentagesOfUsers)
    {
        return count(array_filter($onboardingPercentagesOfUsers, function($onboardingPercentageOfUser) use ($onboardingPercentage) {
            return $onboardingPercentageOfUser >= $onboardingPercentage;
        }));
    }
}
