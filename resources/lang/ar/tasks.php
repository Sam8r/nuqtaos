<?php

return [
    'navigation_group' => 'المهام',
    'navigation_label' => 'المهام',
    'model_label' => 'مهمة',
    'plural_model_label' => 'المهام',
    'fields' => [
        'title' => 'عنوان المهمة',
        'project' => 'المشروع',
        'assigned_to' => 'مكلّفة إلى',
        'status' => 'الحالة',
        'priority' => 'الأولوية',
        'due_date' => 'تاريخ الاستحقاق',
        'description' => 'الوصف',
        'created_at' => 'تاريخ الإنشاء',
    ],
    'statuses' => [
        'New' => 'جديدة',
        'In Progress' => 'قيد التنفيذ',
        'Review' => 'قيد المراجعة',
        'Completed' => 'مكتملة',
    ],
    'priorities' => [
        'High' => 'مرتفعة',
        'Medium' => 'متوسطة',
        'Low' => 'منخفضة',
    ],
    'filters' => [
        'status' => 'الحالة',
    ],
    'actions' => [
        'create' => 'إنشاء مهمة',
        'edit' => 'تعديل مهمة',
        'view' => 'عرض مهمة',
    ],
    'board' => [
        'navigation_label' => 'لوحة المهام',
        'navigation_group' => 'المهام',
        'title' => 'لوحة المهام',
        'columns' => [
            'New' => 'مهام جديدة',
            'In Progress' => 'قيد التنفيذ',
            'Review' => 'قيد المراجعة',
            'Completed' => 'مكتملة',
        ],
        'priorities' => [
            'High' => 'مرتفعة',
            'Medium' => 'متوسطة',
            'Low' => 'منخفضة',
        ],
    ],
    'notifications' => [
        'new_task_assigned' => [
            'title' => 'تم تعيين مهمة جديدة',
            'body' => 'المهمة: :title تم تعيينها لك.',
        ],
    ],
];
