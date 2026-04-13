<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class AuditLogController extends Controller
{
    public function index()
    {
        $activities = Activity::with('causer', 'subject')
            ->latest()
            ->paginate(50);

        return view('admin.audit.index', compact('activities'));
    }
}
