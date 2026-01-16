<?php

return [
    'navigation_label' => 'المنتجات',
    'model_label' => 'منتج',
    'plural_model_label' => 'المنتجات',
    'fields' => [
        'code' => 'رمز المنتج',
        'sku' => 'SKU',
        'name' => 'اسم المنتج',
        'name_en' => 'اسم المنتج (إنجليزي)',
        'name_ar' => 'اسم المنتج (عربي)',
        'description' => 'الوصف',
        'description_en' => 'الوصف (إنجليزي)',
        'description_ar' => 'الوصف (عربي)',
        'price' => 'السعر',
        'currency' => 'العملة',
        'type' => 'النوع',
        'unit' => 'الوحدة',
        'barcode' => 'الباركود',
        'images' => 'صور المنتج',
        'status' => 'الحالة',
        'category' => 'الفئة',
    ],
    'types' => [
        'Service' => 'خدمة',
        'Physical' => 'منتج فعلي',
    ],
    'statuses' => [
        'Active' => 'نشط',
        'Inactive' => 'غير نشط',
        'Discontinued' => 'متوقف',
    ],
    'filters' => [
        'status' => 'الحالة',
        'type' => 'النوع',
        'trashed' => 'المحذوفة',
    ],
    'actions' => [
        'create' => 'إضافة منتج',
        'edit' => 'تعديل منتج',
        'view' => 'عرض منتج',
        'delete' => 'حذف منتج',
        'restore' => 'استعادة منتج',
        'force_delete' => 'حذف المنتج نهائياً',
    ],
    'placeholders' => [
        'unit' => 'مثال: قطعة، كجم',
    ],
    'modals' => [
        'view_image' => 'عرض صورة المنتج',
        'view_barcode' => 'عرض الباركود',
    ],
    'labels' => [
        'image' => 'صورة',
    ],
    'messages' => [
        'no_image' => 'لا توجد صورة متاحة',
    ],
];
