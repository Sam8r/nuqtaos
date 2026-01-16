<?php

return [
    'navigation_label' => 'Departments',
    'model_label' => 'Department',
    'plural_model_label' => 'Departments',
    'fields' => [
        'name' => 'Name',
        'name_en' => 'Name (English)',
        'name_ar' => 'Name (Arabic)',
        'parent' => 'Parent Department',
    ],
    'filters' => [
        'trashed' => 'Trashed Departments',
    ],
    'relation' => [
        'sub_departments' => 'Sub-Departments',
        'name' => 'Sub-Department Name',
        'actions' => [
            'create' => 'Create Sub-Department',
            'associate' => 'Associate Existing',
            'edit' => 'Edit Sub-Department',
            'dissociate' => 'Dissociate',
            'delete' => 'Delete',
            'force_delete' => 'Force Delete',
            'restore' => 'Restore',
        ],
        'bulk_actions' => [
            'dissociate' => 'Dissociate selected',
            'delete' => 'Delete selected',
            'force_delete' => 'Force delete selected',
            'restore' => 'Restore selected',
        ],
        'table' => [
            'heading' => 'Sub-Departments',
            'search' => 'Search sub-departments',
            'empty_heading' => 'No sub-departments',
            'empty_description' => 'Add a department to get started.',
            'empty_action' => 'Create Sub-Department',
        ],
    ],
    'actions' => [
        'create' => 'Create Department',
        'edit' => 'Edit Department',
        'view' => 'View Department',
    ],
    'messages' => [
        'no_parent' => 'No parent department',
    ],
];
