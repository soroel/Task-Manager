<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function build()
    {
        return $this->subject('New Task Assigned')
            ->html("
                <p>Hello {$this->task->assignedUser->name},</p>
                <p>You have been assigned a new task: <strong>{$this->task->title}</strong>.</p>
                <p>Deadline: {$this->task->deadline}</p>
            ");
    }
}
