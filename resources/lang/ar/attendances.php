<?php

return [
    'navigation_label' => 'الحضور',
    'model_label' => 'سجل حضور',
    'plural_model_label' => 'الحضور',
    'fields' => [
        'date' => 'التاريخ',
        'employee' => 'الموظف',
        'employee_name' => 'اسم الموظف',
        'check_in' => 'وقت تسجيل الدخول',
        'check_out' => 'وقت تسجيل الخروج',
        'break_duration' => 'مدة الاستراحة',
        'break_duration_minutes' => 'مدة الاستراحة (بالدقائق)',
        'total_working_hours' => 'إجمالي ساعات العمل',
        'overtime_hours' => 'ساعات العمل الإضافي',
    ],
    'actions' => [
        'create' => 'إضافة حضور',
        'edit' => 'تعديل حضور',
        'view' => 'عرض حضور',
        'delete' => 'حذف حضور',
    ],
    'filters' => [
        'period' => 'تصفية حسب الفترة',
        'custom_date' => 'نطاق تاريخ مخصص',
        'from' => 'من تاريخ',
        'to' => 'إلى تاريخ',
        'today' => 'اليوم',
        'yesterday' => 'أمس',
        'this_week' => 'هذا الأسبوع',
        'last_week' => 'الأسبوع الماضي',
        'this_month' => 'هذا الشهر',
        'last_month' => 'الشهر الماضي',
    ],
    'messages' => [
        'not_checked_out' => 'لم يتم تسجيل الخروج',
        'not_calculated' => 'لم يتم الحساب',
    ],
    'suffixes' => [
        'minutes' => ' دقيقة',
        'hours' => ' ساعة',
    ],
    'notifications' => [
        'check_in_success' => 'تم تسجيل الدخول بنجاح',
        'check_out_success' => 'تم تسجيل الخروج بنجاح',
        'gps_error' => 'تعذر الحصول على الموقع.',
        'out_of_range_title' => 'خارج النطاق',
        'out_of_range_body' => 'المسافة: :distance م. المسموح: :allowed م.',
        'location_denied_title' => 'تم رفض الوصول للموقع',
        'location_denied_body' => 'يرجى تفعيل GPS لتسجيل حضورك.',
        'completed' => 'اكتمل',
    ],
    'buttons' => [
        'check_in' => 'تسجيل دخول',
        'check_out' => 'تسجيل خروج',
    ],
    'statuses' => [
        'success' => 'success',
        'warning' => 'warning',
        'secondary' => 'secondary',
    ],
];
