<?php

return [
    'navigation_label' => 'المشاريع',
    'model_label' => 'مشروع',
    'plural_model_label' => 'المشاريع',
    'fields' => [
        'name' => 'اسم المشروع',
        'client' => 'العميل',
        'client_name' => 'اسم العميل',
        'team_leader' => 'مدير المشروع',
        'status' => 'الحالة',
        'start_date' => 'تاريخ البدء',
        'end_date' => 'تاريخ الانتهاء',
        'deadline' => 'الموعد النهائي',
        'budget' => 'الميزانية',
        'progress_percentage' => 'نسبة التقدم (%)',
        'description' => 'الوصف',
    ],
    'statuses' => [
        'Active' => 'نشط',
        'Completed' => 'مكتمل',
        'On Hold' => 'قيد الانتظار',
    ],
    'filters' => [
        'status' => 'الحالة',
    ],
    'actions' => [
        'create' => 'إنشاء مشروع',
        'edit' => 'تعديل مشروع',
        'view' => 'عرض مشروع',
        'delete' => 'حذف مشروع',
    ],
    'messages' => [
        'progress_with_value' => ':value%',
    ],
];
