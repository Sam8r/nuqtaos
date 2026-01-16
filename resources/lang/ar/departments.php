<?php

return [
    'navigation_label' => 'الأقسام',
    'model_label' => 'قسم',
    'plural_model_label' => 'الأقسام',
    'fields' => [
        'name' => 'الاسم',
        'name_en' => 'الاسم (إنجليزي)',
        'name_ar' => 'الاسم (عربي)',
        'parent' => 'القسم الرئيسي',
    ],
    'filters' => [
        'trashed' => 'الأقسام المحذوفة',
    ],
    'relation' => [
        'sub_departments' => 'الأقسام الفرعية',
        'name' => 'اسم القسم الفرعي',
        'actions' => [
            'create' => 'إضافة قسم فرعي',
            'associate' => 'ربط قسم موجود',
            'edit' => 'تعديل القسم الفرعي',
            'dissociate' => 'إلغاء الربط',
            'delete' => 'حذف',
            'force_delete' => 'حذف نهائي',
            'restore' => 'استعادة',
        ],
        'bulk_actions' => [
            'dissociate' => 'إلغاء ربط المحدد',
            'delete' => 'حذف المحدد',
            'force_delete' => 'حذف نهائي للمحدد',
            'restore' => 'استعادة المحدد',
        ],
        'table' => [
            'heading' => 'الأقسام الفرعية',
            'search' => 'بحث في الأقسام الفرعية',
            'empty_heading' => 'لا توجد أقسام فرعية',
            'empty_description' => 'قم بإضافة قسم للبدء.',
            'empty_action' => 'إضافة قسم فرعي',
        ],
    ],
    'actions' => [
        'create' => 'إضافة قسم',
        'edit' => 'تعديل القسم',
        'view' => 'عرض القسم',
    ],
    'messages' => [
        'no_parent' => 'لا يوجد قسم رئيسي',
    ],
];
