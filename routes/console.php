<?php

use Illuminate\Support\Facades\Schedule;
Schedule::command('app:game-result-calculator')->everyMinute();