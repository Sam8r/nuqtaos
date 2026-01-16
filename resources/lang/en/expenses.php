<?php

return [
    'navigation_group' => 'Expenses',
    'navigation_label' => 'Expenses',
    'model_label' => 'Expense',
    'plural_model_label' => 'Expenses',
    'fields' => [
        'category' => 'Category',
        'amount' => 'Amount',
        'status' => 'Status',
        'expense_date' => 'Expense Date',
        'description' => 'Description',
        'documents' => 'Supporting Documents',
        'documents_images' => 'Documents Images',
        'documents_files' => 'Files',
        'file_name' => 'File Name',
        'submitted_by' => 'Submitted By',
        'approved_by' => 'Approved By',
    ],
    'actions' => [
        'create' => 'Create Expense',
        'edit' => 'Edit Expense',
        'view' => 'View Expense',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'view_document' => 'View Document',
    ],
    'statuses' => [
        'Pending' => 'Pending',
        'Approved' => 'Approved',
        'Rejected' => 'Rejected',
    ],
    'filters' => [
        'status' => 'Status',
    ],
    'helper_texts' => [
        'documents' => 'Upload receipts, invoices, or other supporting documents. Allowed file types: jpg, jpeg, png, webp, pdf, doc, docx.',
    ],
    'sections' => [
        'documents_files' => 'Documents Files',
    ],
    'modals' => [
        'view_document' => 'View Expense Document',
    ],
    'messages' => [
        'document_link' => '<a href=":url" target="_blank"
                        class="text-primary-600 hover:text-primary-800 hover:underline font-medium block mb-1">
                        :name
                    </a>',
        'no_documents' => 'No documents available',
    ],
];
