<?php

return [
    'navigation_group' => 'المصاريف',
    'navigation_label' => 'المصاريف',
    'model_label' => 'مصروف',
    'plural_model_label' => 'المصاريف',
    'fields' => [
        'category' => 'الفئة',
        'amount' => 'المبلغ',
        'status' => 'الحالة',
        'expense_date' => 'تاريخ المصروف',
        'description' => 'الوصف',
        'documents' => 'المستندات المرفقة',
        'documents_images' => 'صور المستندات',
        'documents_files' => 'ملفات المستندات',
        'file_name' => 'اسم الملف',
        'submitted_by' => 'تم الإرسال بواسطة',
        'approved_by' => 'تمت الموافقة بواسطة',
    ],
    'actions' => [
        'create' => 'إضافة مصروف',
        'edit' => 'تعديل المصروف',
        'view' => 'عرض المصروف',
        'approve' => 'موافقة',
        'reject' => 'رفض',
        'view_document' => 'عرض المستند',
    ],
    'statuses' => [
        'Pending' => 'معلق',
        'Approved' => 'تمت الموافقة',
        'Rejected' => 'مرفوض',
    ],
    'filters' => [
        'status' => 'الحالة',
    ],
    'helper_texts' => [
        'documents' => 'قم برفع الإيصالات أو الفواتير أو المستندات الأخرى. الملفات المسموح بها: jpg, jpeg, png, webp, pdf, doc, docx.',
    ],
    'sections' => [
        'documents_files' => 'ملفات المستندات',
    ],
    'modals' => [
        'view_document' => 'عرض مستند المصروف',
    ],
    'messages' => [
        'document_link' => '<a href=":url" target="_blank"
                        class="text-primary-600 hover:text-primary-800 hover:underline font-medium block mb-1">
                        :name
                    </a>',
        'no_documents' => 'لا توجد مستندات متاحة',
    ],
];
