<?php

return [
    'page' => [
        'title' => 'الإعدادات',
    ],

    'fields' => [
        'name' => 'اسم الموقع',
        'logo' => 'شعار الموقع',
        'phone' => 'رقم الهاتف',
        'email' => 'البريد الإلكتروني',
        'address' => 'العنوان',
        'tax_number' => 'الرقم الضريبي',
        'language' => 'اللغة الافتراضية',
        'currency' => 'العملة',
        'salary_currency' => 'عملة الرواتب',
        'tax' => 'نسبة الضريبة',
        'default_printable_language' => 'اللغة الافتراضية للفواتير والعروض',
        'break_minutes' => 'دقائق الاستراحة الافتراضية',
        'overtime_minutes' => 'دقائق العمل الإضافي الافتراضية',
        'days_off_limit' => 'حد أيام الإجازة',
        'encashment_limit' => 'حد الاستبدال النقدي',
        'weekends' => 'عطلات نهاية الأسبوع',
        'default_payroll_start_day' => 'اليوم الافتراضي لبدء الرواتب',
        'overtime_type' => 'نوع العمل الإضافي',
        'overtime_value' => 'قيمة العمل الإضافي',
        'default_work_from' => 'بداية الدوام الافتراضية',
        'default_work_to' => 'نهاية الدوام الافتراضية',
        'grace_period_minutes' => 'دقائق السماح',
        'work_type_days' => 'جدول العمل حسب نوع العقد',
        'contract_type' => 'نوع العقد',
        'working_days' => 'أيام العمل',
        'overtime_active_mode' => 'طريقة احتساب العمل الإضافي الحالية',
        'overtime_percentage' => 'نسبة العمل الإضافي (مثال: 1.5)',
        'overtime_fixed_rate' => 'قيمة العمل الإضافي الثابتة (للساعة)',
        'location' => 'حدد موقع المكتب',
        'company_latitude' => 'خط العرض',
        'company_longitude' => 'خط الطول',
        'radius_meter' => 'نصف القطر المسموح (متر)',
    ],

    'languages' => [
        'en' => 'الإنجليزية',
        'ar' => 'العربية',
    ],

    'options' => [
        'invoice_languages' => [
            'none' => 'بدون',
        ],
        'weekdays' => [
            'Saturday' => 'السبت',
            'Sunday' => 'الأحد',
            'Monday' => 'الاثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة',
        ],
        'contract_types' => [
            'Full Time' => 'دوام كامل',
            'Part Time' => 'دوام جزئي',
            'Intern' => 'متدرب',
        ],
        'overtime_types' => [
            'Percentage' => 'نسبة مئوية',
            'Fixed' => 'قيمة ثابتة',
        ],
        'overtime_modes' => [
            'percentage' => 'نسبة من الراتب بالساعة',
            'fixed' => 'قيمة ثابتة لكل ساعة',
        ],
    ],

    'sections' => [
        'work_hours' => [
            'title' => 'ساعات العمل والعمل الإضافي',
        ],
        'geofencing' => [
            'title' => 'موقع المكتب وتحديد النطاق',
            'description' => 'قم بتثبيت موقع المكتب على الخريطة وحدد نصف القطر المسموح للحضور.',
        ],
    ],

    'prefixes' => [
        'currency' => 'ج.م',
    ],

    'suffixes' => [
        'meters' => 'م',
    ],

    'messages' => [
        'saved' => 'تم حفظ الإعدادات بنجاح',
    ],

    'actions' => [
        'save' => 'حفظ الإعدادات',
    ],
];
