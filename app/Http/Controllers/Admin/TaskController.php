<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskAssigned;

class TaskController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    public function index()
    {
        $tasks = Task::with('assignedUser')->latest()->paginate(10); // ← Add pagination

        return Inertia::render('Admin/Tasks/Index', [
            'tasks' => $tasks,
        ]);
    }


    public function create()
    {
        $users = User::where('role', 'user')->get(['id', 'name']);
        return Inertia::render('Admin/Tasks/Create', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'deadline' => 'required|date|after_or_equal:today',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'deadline' => $request->deadline,
            'status' => 'Pending',
        ]);


        // Email notification
        Mail::to($task->assignedUser->email)->send(new TaskAssigned($task));

        return redirect()->route('admin.tasks.index')->with('success', 'Task assigned successfully.');
    }


    public function myTasks()
    {
        $tasks = Task::where('assigned_to', auth()->id())->get();
        return Inertia::render('Tasks/MyTasks', ['tasks' => $tasks]);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);

        $task->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Task status updated.');
    }
    public function edit(Task $task)
    {
        $users = User::where('role', 'user')->get(['id', 'name']);

        return Inertia::render('Admin/Tasks/Edit', [
            'task' => $task,
            'users' => $users,
        ]);
    }
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'deadline' => 'required|date|after_or_equal:today',
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'deadline' => $request->deadline,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully.');
    }
    public function destroy(Task $task)
    {
        // $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully.');
    }



}
