<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function index()
    {
        return view('admin.log.index');
    }

    public function getLogs(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);

        $query = Activity::query();
        $recordsTotal = $query->count();

        // Handle search if present
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $query->where('description', 'like', "%{$searchValue}%")
                ->orWhere('event', 'like', "%{$searchValue}%");
        }

        $recordsFiltered = $query->count();

        $logs = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get()
            ->map(function ($log) {
                if (class_exists($log->subject_type)) {
                    $log->subject = $log->subject_type::find($log->subject_id);
                }
                if (class_exists($log->causer_type)) {
                    $log->causer = $log->causer_type::find($log->causer_id);
                }
                return $log;
            });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $logs
        ]);
    }
}
