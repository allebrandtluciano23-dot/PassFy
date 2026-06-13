<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('events:update-status')->everyMinute();