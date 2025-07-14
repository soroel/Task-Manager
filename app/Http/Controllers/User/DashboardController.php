<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())->latest()->get();

        return Inertia::render('Dashboard', [
            'tasks' => $tasks,
        ]);
    }
}

