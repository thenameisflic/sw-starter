<?php

use App\Jobs\ComputeQueryStatistics;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new ComputeQueryStatistics)->everyFiveMinutes();
