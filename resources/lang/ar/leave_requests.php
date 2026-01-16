<?php

return [
    'navigation_label' => 'طلبات الإجازة',
    'model_label' => 'طلب إجازة',
    'plural_model_label' => 'طلبات الإجازة',

    'pages' => [
        'list' => 'طلبات الإجازة',
        'create' => 'تقديم طلب إجازة',
        'view' => 'تفاصيل طلب الإجازة',
    ],

    'fields' => [
        'selected_dates' => 'التواريخ المحددة',
        'date' => 'التاريخ',
        'type' => 'نوع الإجازة',
        'encashed' => 'تم صرفها نقداً',
        'is_encashed' => 'طلب صرف نقدي',
        'reason' => 'السبب',
        'rejection_reason' => 'سبب الرفض',
        'employee' => 'الموظف',
        'status' => 'الحالة',
        'total_days' => 'إجمالي الأيام',
        'created_at' => 'تاريخ الطلب',
    ],

    'filters' => [
        'period' => 'تصفية حسب الفترة',
        'status' => 'تصفية حسب الحالة',
    ],

    'types' => [
        'paid' => 'مدفوعة',
        'unpaid' => 'غير مدفوعة',
    ],

    'periods' => [
        'today' => 'اليوم',
        'yesterday' => 'أمس',
        'this_week' => 'هذا الأسبوع',
        'last_week' => 'الأسبوع الماضي',
        'this_month' => 'هذا الشهر',
        'last_month' => 'الشهر الماضي',
    ],

    'statuses' => [
        'Pending' => 'قيد الانتظار',
        'Approved' => 'مقبول',
        'Rejected' => 'مرفوض',
    ],

    'actions' => [
        'create' => 'تقديم طلب إجازة',
        'approve' => 'قبول',
        'reject' => 'رفض',
        'delete' => 'حذف',
        'view' => 'عرض',
        'edit' => 'تعديل',
    ],

    'notifications' => [
        'insufficient_balance' => [
            'title' => 'رصيد غير كافٍ',
            'body' => 'لديك فقط :days يوم متاح في رصيدك.',
        ],
        'request_approved' => 'تم قبول الطلب',
        'request_rejected' => 'تم رفض الطلب',
    ],

    'prompts' => [
        'rejection_reason' => 'سبب الرفض',
    ],

    'validation' => [
        'encashment_limit_exceeded' => 'لقد تجاوزت الحد الأقصى المسموح لصرف الإجازة نقداً.',
        'insufficient_balance' => 'لديك فقط :days يوم متاح في رصيدك.',
    ],
];
