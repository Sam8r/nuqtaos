<?php

return [
    'navigation_label' => 'Projects',
    'model_label' => 'Project',
    'plural_model_label' => 'Projects',
    'fields' => [
        'name' => 'Project Name',
        'client' => 'Client',
        'client_name' => 'Client Name',
        'team_leader' => 'Project Manager',
        'status' => 'Status',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'deadline' => 'Deadline',
        'budget' => 'Budget',
        'progress_percentage' => 'Progress (%)',
        'description' => 'Description',
    ],
    'statuses' => [
        'Active' => 'Active',
        'Completed' => 'Completed',
        'On Hold' => 'On Hold',
    ],
    'filters' => [
        'status' => 'Status',
    ],
    'actions' => [
        'create' => 'Create Project',
        'edit' => 'Edit Project',
        'view' => 'View Project',
        'delete' => 'Delete Project',
    ],
    'messages' => [
        'progress_with_value' => ':value%',
    ],
];
