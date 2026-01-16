<?php

return [
    'navigation_label' => 'Categories',
    'model_label' => 'Category',
    'plural_model_label' => 'Categories',
    'fields' => [
        'name' => 'Name',
        'name_en' => 'Name (English)',
        'name_ar' => 'Name (Arabic)',
        'description' => 'Description',
        'image' => 'Category Image',
        'priority' => 'Priority',
    ],
    'priorities' => [
        1 => 'Important',
        2 => 'Main',
        3 => 'Secondary',
    ],
    'actions' => [
        'create' => 'Create Category',
        'edit' => 'Edit Category',
        'view' => 'View Category',
        'delete' => 'Delete Category',
        'restore' => 'Restore Category',
        'force_delete' => 'Delete Category Permanently',
    ],
    'filters' => [
        'priority' => 'Priority',
        'trashed' => 'Trashed',
    ],
    'messages' => [
        'no_image' => 'No category image available',
    ],
    'modals' => [
        'view_image' => 'View Category Image',
    ],
];
