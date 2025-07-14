Hello {{ $task->assignedUser->name }},

You have been assigned a new task:

Title: {{ $task->title }}
Deadline: {{ $task->deadline }}

Please log in to the system to view and manage it.