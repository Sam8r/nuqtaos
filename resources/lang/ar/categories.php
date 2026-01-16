<?php

return [
    'navigation_label' => 'الفئات',
    'model_label' => 'فئة',
    'plural_model_label' => 'الفئات',
    'fields' => [
        'name' => 'الاسم',
        'name_en' => 'الاسم (إنجليزي)',
        'name_ar' => 'الاسم (عربي)',
        'description' => 'الوصف',
        'image' => 'صورة الفئة',
        'priority' => 'الأولوية',
    ],
    'priorities' => [
        1 => 'هامة',
        2 => 'رئيسية',
        3 => 'ثانوية',
    ],
    'actions' => [
        'create' => 'إنشاء فئة',
        'edit' => 'تعديل الفئة',
        'view' => 'عرض الفئة',
        'delete' => 'حذف الفئة',
        'restore' => 'استعادة الفئة',
        'force_delete' => 'حذف الفئة نهائياً',
    ],
    'filters' => [
        'priority' => 'الأولوية',
        'trashed' => 'المحذوفة',
    ],
    'messages' => [
        'no_image' => 'لا توجد صورة متاحة لهذه الفئة',
    ],
    'modals' => [
        'view_image' => 'عرض صورة الفئة',
    ],
];
