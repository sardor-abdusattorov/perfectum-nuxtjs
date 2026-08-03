<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('filament-logger:prune')->dailyAt('03:00');
