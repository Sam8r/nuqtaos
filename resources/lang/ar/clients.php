<?php

return [
    'navigation_label' => 'العملاء',
    'model_label' => 'عميل',
    'plural_model_label' => 'العملاء',
    'fields' => [
        'company_name' => 'اسم الشركة',
        'company_name_en' => 'اسم الشركة (إنجليزي)',
        'company_name_ar' => 'اسم الشركة (عربي)',
        'contact_person_details' => 'بيانات الشخص المسؤول',
        'address' => 'العنوان',
        'emails' => 'البريد الإلكتروني',
        'email' => 'البريد الإلكتروني',
        'phones' => 'أرقام الهاتف',
        'phone' => 'رقم الهاتف',
        'tax_number' => 'الرقم الضريبي',
        'registration_documents' => 'مستندات التسجيل',
        'credit_limit' => 'الحد الائتماني',
        'credit_currency' => 'العملة',
        'payment_terms' => 'شروط الدفع',
        'payment_terms_en' => 'شروط الدفع (إنجليزي)',
        'payment_terms_ar' => 'شروط الدفع (عربي)',
        'industry_type' => 'نوع النشاط',
        'industry_type_en' => 'نوع النشاط (إنجليزي)',
        'industry_type_ar' => 'نوع النشاط (عربي)',
        'status' => 'الحالة',
        'tier' => 'الفئة',
        'country' => 'الدولة',
    ],
    'statuses' => [
        'Active' => 'نشط',
        'Inactive' => 'غير نشط',
        'Pending' => 'قيد الانتظار',
    ],
    'tiers' => [
        'Gold' => 'ذهبية',
        'Silver' => 'فضية',
        'Bronze' => 'برونزية',
    ],
    'filters' => [
        'status' => 'الحالة',
        'tier' => 'الفئة',
        'country' => 'الدولة',
        'trashed' => 'المحذوفة',
    ],
    'helper_texts' => [
        'registration_documents' => 'الملفات المسموح بها: jpg, jpeg, png, webp, pdf, doc, docx.',
    ],
    'sections' => [
        'registration_documents' => 'مستندات التسجيل',
    ],
    'document_labels' => [
        'images' => 'صور مستندات التسجيل',
        'files' => 'المستندات',
        'image' => 'الصورة',
        'file_name' => 'اسم الملف',
    ],
    'messages' => [
        'no_documents' => 'لا توجد مستندات تسجيل متاحة',
        'document_link' => '<a href=":url" target="_blank" class="text-primary-600 hover:text-primary-800 hover:underline font-medium block mb-1">:name <br></a>',
    ],
    'modals' => [
        'view_document_image' => 'عرض صورة مستند التسجيل',
    ],
    'actions' => [
        'create' => 'إضافة عميل',
        'edit' => 'تعديل عميل',
        'view' => 'عرض عميل',
        'delete' => 'حذف عميل',
        'restore' => 'استعادة عميل',
        'force_delete' => 'حذف العميل نهائياً',
    ],
];
