<?php

return [
    'navigation_label' => 'التسويات المالية',
    'model_label' => 'تسوية مالية',
    'plural_model_label' => 'التسويات المالية',

    'page' => [
        'list' => 'التسويات المالية',
        'create' => 'إضافة تسوية مالية',
        'view' => 'عرض التسوية المالية',
        'edit' => 'تعديل التسوية المالية',
    ],

    'fields' => [
        'employee' => 'الموظف',
        'type' => 'النوع',
        'category' => 'الفئة',
        'amount_cash' => 'المبلغ (نقدي)',
        'amount' => 'المبلغ',
        'days' => 'الأيام',
        'days_count' => 'عدد الأيام',
        'reason' => 'السبب',
        'processing_date' => 'تاريخ المعالجة',
        'status' => 'الحالة',
        'created_at' => 'تاريخ الإنشاء',
    ],

    'types' => [
        'Bonus' => 'مكافأة',
        'Deduction' => 'خصم',
    ],

    'category_groups' => [
        'Bonus' => [
            'Performance-based' => 'مبنية على الأداء',
            'Attendance-based' => 'مبنية على الحضور',
            'Manual Bonus' => 'مكافأة يدوية',
        ],
        'Deduction' => [
            'Leave Overuse' => 'تجاوز الإجازات',
            'Absence Without Notice' => 'غياب بدون إشعار',
            'Penalty' => 'غرامة',
            'Loan / Advance' => 'سلف/قرض',
        ],
    ],

    'categories' => [
        'Performance-based' => 'مبنية على الأداء',
        'Attendance-based' => 'مبنية على الحضور',
        'Manual Bonus' => 'مكافأة يدوية',
        'Leave Overuse' => 'تجاوز الإجازات',
        'Absence Without Notice' => 'غياب بدون إشعار',
        'Penalty' => 'غرامة',
        'Loan / Advance' => 'سلف/قرض',
    ],

    'statuses' => [
        'Pending' => 'قيد الانتظار',
        'Processed' => 'مُعالجة',
        'Rejected' => 'مرفوضة',
    ],

    'filters' => [
        'type' => 'النوع',
        'status' => 'الحالة',
    ],

    'actions' => [
        'create' => 'إضافة تسوية',
        'view' => 'عرض',
        'edit' => 'تعديل',
        'delete' => 'حذف',
    ],
];
