<?php

return [
    'navigation_group' => 'Tasks',
    'navigation_label' => 'Tasks',
    'model_label' => 'Task',
    'plural_model_label' => 'Tasks',
    'fields' => [
        'title' => 'Task Title',
        'project' => 'Project',
        'assigned_to' => 'Assigned To',
        'status' => 'Status',
        'priority' => 'Priority',
        'due_date' => 'Due Date',
        'description' => 'Description',
        'created_at' => 'Created At',
    ],
    'statuses' => [
        'New' => 'New',
        'In Progress' => 'In Progress',
        'Review' => 'Review',
        'Completed' => 'Completed',
    ],
    'priorities' => [
        'High' => 'High',
        'Medium' => 'Medium',
        'Low' => 'Low',
    ],
    'filters' => [
        'status' => 'Status',
    ],
    'actions' => [
        'create' => 'Create Task',
        'edit' => 'Edit Task',
        'view' => 'View Task',
    ],
    'board' => [
        'navigation_label' => 'Task Board',
        'navigation_group' => 'Tasks',
        'title' => 'Task Board',
        'columns' => [
            'New' => 'New Tasks',
            'In Progress' => 'In Progress',
            'Review' => 'Under Review',
            'Completed' => 'Completed',
        ],
        'priorities' => [
            'High' => 'High',
            'Medium' => 'Medium',
            'Low' => 'Low',
        ],
    ],
    'notifications' => [
        'new_task_assigned' => [
            'title' => 'New Task Assigned',
            'body' => 'Task: :title has been assigned to you.',
        ],
    ],
];
