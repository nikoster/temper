<?php

namespace App\Http\Controllers;

use App\Services\StatsService;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index(StatsService $statsService)
    {
        return view('stats')->with('stats', $statsService->gatherData());
    }
}
