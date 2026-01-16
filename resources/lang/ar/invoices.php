<?php

return [
    'navigation_label' => 'الفواتير',
    'model_label' => 'فاتورة',
    'plural_model_label' => 'الفواتير',

    'pages' => [
        'list' => 'الفواتير',
        'create' => 'إنشاء فاتورة',
        'edit' => 'تعديل الفاتورة',
        'view' => 'تفاصيل الفاتورة',
        'activities' => 'سجل النشاط',
    ],

    'sections' => [
        'details' => 'تفاصيل الفاتورة',
        'financial_summary' => 'الملخص المالي',
        'items' => 'العناصر',
    ],

    'fields' => [
        'number' => 'رقم الفاتورة',
        'issue_date' => 'تاريخ الإصدار',
        'valid_until' => 'صالح حتى',
        'due_date' => 'تاريخ الاستحقاق',
        'status' => 'الحالة',
        'computed_status' => 'الحالة',
        'client' => 'العميل',
        'payment_terms' => 'شروط الدفع',
        'currency' => 'العملة',
        'subtotal' => 'الإجمالي الفرعي',
        'discount_value' => 'قيمة الخصم',
        'discount_percent' => 'نسبة الخصم (%)',
        'discount_total' => 'إجمالي الخصم',
        'tax_value' => 'قيمة الضريبة',
        'tax_percent' => 'نسبة الضريبة (%)',
        'tax_total' => 'إجمالي الضريبة',
        'late_payment_penalty_percent' => 'غرامة التأخير (%)',
        'total' => 'الإجمالي',
        'total_paid' => 'إجمالي المدفوع',
        'outstanding_balance' => 'الرصيد المستحق',
        'items' => 'عناصر الفاتورة',
        'product' => 'المنتج',
        'custom_name' => 'اسم منتج مخصص',
        'description' => 'الوصف',
        'quantity' => 'الكمية',
        'unit_price' => 'سعر الوحدة',
        'unit_price_converted' => 'سعر الوحدة (محول)',
        'total_price' => 'الإجمالي',
        'original_price' => 'السعر الأصلي',
        'original_currency' => 'العملة الأصلية',
        'grand_total' => 'الإجمالي الكلي',
    ],

    'statuses' => [
        'Draft' => 'مسودة',
        'Pending' => 'قيد الانتظار',
        'Partially Paid' => 'مدفوع جزئياً',
        'Paid' => 'مدفوع',
        'Overdue' => 'متأخر',
    ],

    'filters' => [
        'status' => 'تصفية حسب الحالة',
        'status_options' => [
            'Draft' => 'مسودة',
            'Pending' => 'قيد الانتظار',
            'Partially Paid' => 'مدفوع جزئياً',
            'Paid' => 'مدفوع',
            'Overdue' => 'متأخر',
        ],
    ],

    'actions' => [
        'create' => 'إنشاء فاتورة',
        'view' => 'عرض',
        'edit' => 'تعديل',
        'delete' => 'حذف',
        'restore' => 'استعادة',
        'force_delete' => 'حذف نهائي',
        'print' => 'طباعة',
        'print_arabic' => 'طباعة (العربية)',
        'print_english' => 'طباعة (الإنجليزية)',
    ],

    'prompts' => [
        'select_print_language' => 'اختر لغة الطباعة',
        'print_language' => 'اللغة',
    ],

    'notifications' => [
        'success' => 'تم بنجاح',
        'converted' => 'تم إنشاء فاتورة من العرض بنجاح!',
    ],

    'languages' => [
        'ar' => 'العربية',
        'en' => 'الإنجليزية',
    ],
];
