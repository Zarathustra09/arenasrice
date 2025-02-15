<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function index()
    {
        $logs = Activity::all()->map(function ($log) {
            if (class_exists($log->subject_type)) {
                $log->subject = $log->subject_type::find($log->subject_id);
            }
            if (class_exists($log->causer_type)) {
                $log->causer = $log->causer_type::find($log->causer_id);
            }
            return $log;
        });

        return view('admin.log.index', compact('logs'));
    }
}
