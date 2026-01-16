<?php

return [
    'navigation_label' => 'عروض الأسعار',
    'model_label' => 'عرض سعر',
    'plural_model_label' => 'عروض الأسعار',

    'pages' => [
        'list' => 'عروض الأسعار',
        'create' => 'إنشاء عرض سعر',
        'edit' => 'تعديل عرض السعر',
        'view' => 'تفاصيل عرض السعر',
    ],

    'sections' => [
        'details' => 'تفاصيل عرض السعر',
        'financial_summary' => 'الملخص المالي',
        'items' => 'العناصر',
    ],

    'fields' => [
        'number' => 'رقم العرض',
        'issue_date' => 'تاريخ الإصدار',
        'valid_until' => 'صالح حتى',
        'status' => 'الحالة',
        'computed_status' => 'الحالة',
        'client' => 'العميل',
        'terms_and_conditions' => 'الشروط والأحكام',
        'subtotal' => 'الإجمالي الفرعي',
        'discount_value' => 'قيمة الخصم',
        'discount_percent' => 'الخصم (%)',
        'discount_total' => 'إجمالي الخصم',
        'tax_value' => 'قيمة الضريبة',
        'tax_percent' => 'الضريبة (%)',
        'tax_total' => 'إجمالي الضريبة',
        'total' => 'الإجمالي',
        'currency' => 'العملة',
        'items' => 'عناصر العرض',
        'product' => 'المنتج',
        'description' => 'الوصف',
        'quantity' => 'الكمية',
        'unit_price' => 'سعر الوحدة',
        'unit_price_converted' => 'سعر الوحدة (محول)',
        'total_price' => 'الإجمالي',
        'original_price' => 'السعر الأصلي',
        'original_currency' => 'العملة الأصلية',
        'custom_name' => 'اسم منتج مخصص',
        'late_payment_penalty_percent' => 'غرامة التأخير (%)',
    ],

    'form_statuses' => [
        'Draft' => 'مسودة',
        'Sent' => 'مرسل',
        'Under Review' => 'قيد المراجعة',
        'Accepted' => 'مقبول',
        'Rejected' => 'مرفوض',
    ],

    'invoice_statuses' => [
        'Draft' => 'مسودة',
        'Pending' => 'قيد الانتظار',
        'Partially Paid' => 'مدفوع جزئياً',
        'Paid' => 'مدفوع',
        'Overdue' => 'متأخر',
    ],

    'computed_statuses' => [
        'draft' => 'مسودة',
        'sent' => 'مرسل',
        'approved' => 'موافق عليه',
        'rejected' => 'مرفوض',
        'expired' => 'منتهي الصلاحية',
    ],

    'filters' => [
        'status' => 'تصفية حسب الحالة',
        'trashed' => 'العناصر المحذوفة',
        'status_options' => [
            'expired' => 'منتهي الصلاحية',
            'draft' => 'مسودة',
            'sent' => 'مرسل',
            'approved' => 'موافق عليه',
            'rejected' => 'مرفوض',
        ],
    ],

    'actions' => [
        'create' => 'إنشاء عرض سعر',
        'view' => 'عرض',
        'edit' => 'تعديل',
        'delete' => 'حذف',
        'restore' => 'استعادة',
        'force_delete' => 'حذف نهائي',
        'print' => 'طباعة',
        'print_arabic' => 'طباعة (العربية)',
        'print_english' => 'طباعة (الإنجليزية)',
        'convert_to_invoice' => 'تحويل إلى فاتورة',
    ],

    'prompts' => [
        'select_print_language' => 'اختر لغة الطباعة',
        'print_language' => 'اللغة',
        'convert_modal_heading' => 'تحويل العرض إلى فاتورة',
    ],

    'notifications' => [
        'success' => 'تم بنجاح',
        'converted' => 'تم تحويل العرض إلى فاتورة بنجاح!',
    ],
];
