<?php

return [
    'navigation_group' => 'Expenses',
    'navigation_label' => 'Expense Categories',
    'model_label' => 'Expense Category',
    'plural_model_label' => 'Expense Categories',
    'fields' => [
        'name' => 'Name',
        'name_en' => 'Name (English)',
        'name_ar' => 'Name (Arabic)',
        'parent' => 'Parent Expense Category',
    ],
    'actions' => [
        'create' => 'Create Expense Category',
        'edit' => 'Edit Expense Category',
        'view' => 'View Expense Category',
    ],
    'messages' => [
        'no_parent' => 'No parent category',
    ],
];
