<?php

namespace App\Services;

use App\Repositories\Users;
use Carbon\Carbon;

class StatsService
{
    /**
     * Entrance method that will be responsible for gathering all the needed data
     *
     * @return array
     */
    public function gatherData()
    {
        // Get the users
        $userRepository = new Users();
        $users = $userRepository->groupedByWeek();

        // Initialize return object
        $data = [];

        // Loop through the weeks/cohorts to create the required structure for the frontend
        foreach ($users as $week => $weekUsers) {
            $firstUserDate = $weekUsers->first()->created_at;
            $cohort = [
                'name' => $this->getCohortDate($firstUserDate),
                'data' => $this->calculateOnboardingPercentages($weekUsers)
            ];

            $data[] = $cohort;
        }

        return $data;
    }

    /**
     * The cohort date (in the graph legend) should always be a Monday. We can't expect
     * that the given data provides registrations from every single day, so this method
     * helps to calculate the first day of the week of any given date.
     *
     * @param string $date
     * @return string|static
     */
    private function getCohortDate($date)
    {
        // Get the numeral representation of the date
        $dayOfWeek = Carbon::parse($date)->format('w');

        // Special cases
        if ($dayOfWeek === 1) return $date; // Already at Monday
        if ($dayOfWeek === 0) return Carbon::parse($date)->subDays(6); // Sunday

        // Return the new date
        return $cohortDate = Carbon::parse($date)->subDays($dayOfWeek - 1)->format('Y-m-d');
    }

    /**
     * Calculates the percentage of users that are at (or beyond) the given onboarding percentage.
     *
     * @param $users
     * @return array
     */
    private function calculateOnboardingPercentages($users)
    {
        // Get the amount of users this cohort has
        $userCount = count($users);

        // Flatten user data to array with the onboarding percentages only
        $percentages = $users->pluck('onboarding_percentage');

        // Create an array from 1 till 100 that calculates the percentages of users that are at (or passed) that percentage
        $cohortData = [];
        for ($i = 0; $i <= 100; $i++) {
            $count = 0;
            foreach ($percentages as $percentage) {
                if ($percentage >= $i) $count++;
            }

            $cohortData[$i] = ($count / $userCount) * 100;
        }

        return $cohortData;
    }
}
