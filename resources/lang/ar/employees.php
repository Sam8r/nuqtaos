<?php

return [
    'navigation_label' => 'الموظفون',
    'model_label' => 'موظف',
    'plural_model_label' => 'الموظفون',
    'fields' => [
        'id' => 'رقم الموظف',
        'name' => 'الاسم',
        'name_en' => 'الاسم (إنجليزي)',
        'name_ar' => 'الاسم (عربي)',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'phone' => 'رقم الهاتف',
        'emergency_contact' => 'جهة الاتصال في حالات الطوارئ',
        'joining_date' => 'تاريخ الانضمام',
        'payroll_start_day' => 'بداية دورة الرواتب',
        'contract_type' => 'نوع العقد',
        'work_type' => 'نوع العمل',
        'status' => 'الحالة',
        'salary' => 'الراتب',
        'work_start' => 'وقت بدء العمل المخصص',
        'work_end' => 'وقت نهاية العمل المخصص',
        'documents' => 'المستندات',
        'documents_images' => 'الصور',
        'documents_files' => 'مستندات أخرى',
        'position' => 'الوظيفة',
        'department' => 'القسم',
    ],
    'actions' => [
        'create' => 'إضافة موظف',
        'edit' => 'تعديل الموظف',
        'view' => 'عرض الموظف',
    ],
    'contract_types' => [
        'Full Time' => 'دوام كامل',
        'Part Time' => 'دوام جزئي',
        'Intern' => 'متدرب',
    ],
    'work_types' => [
        'Onsite' => 'في مقر العمل',
        'Remote' => 'عن بعد',
    ],
    'statuses' => [
        'Active' => 'نشط',
        'Inactive' => 'غير نشط',
        'Terminated' => 'منتهي الخدمة',
        'On Leave' => 'في إجازة',
    ],
    'filters' => [
        'status' => 'الحالة',
        'contract_type' => 'نوع العقد',
        'department' => 'القسم',
        'position' => 'الوظيفة',
    ],
    'helper_texts' => [
        'documents' => 'الملفات المسموح بها: jpg, jpeg, png, webp, pdf, doc, docx.',
    ],
    'messages' => [
        'no_department' => 'لا يوجد قسم محدد',
        'no_documents' => 'لا توجد مستندات متاحة',
        'document_link' => '<a href=":url" target="_blank" class="text-primary-600 hover:text-primary-800 hover:underline font-medium block mb-1">:name</a>',
    ],
    'sections' => [
        'documents' => 'المستندات',
    ],
    'modals' => [
        'view_document' => 'عرض صورة المستند',
    ],
    'document_labels' => [
        'images' => 'الصور',
        'other_documents' => 'مستندات أخرى',
        'file_name' => 'اسم الملف',
    ],
];
