<?php

return [
    'navigation_label' => 'Invoices',
    'model_label' => 'Invoice',
    'plural_model_label' => 'Invoices',

    'pages' => [
        'list' => 'Invoices',
        'create' => 'Create Invoice',
        'edit' => 'Edit Invoice',
        'view' => 'Invoice Details',
        'activities' => 'Activity Log',
    ],

    'sections' => [
        'details' => 'Invoice Details',
        'financial_summary' => 'Financial Summary',
        'items' => 'Items',
    ],

    'fields' => [
        'number' => 'Invoice #',
        'issue_date' => 'Issue Date',
        'valid_until' => 'Valid Until',
        'due_date' => 'Due Date',
        'status' => 'Status',
        'computed_status' => 'Status',
        'client' => 'Client',
        'payment_terms' => 'Payment Terms',
        'currency' => 'Currency',
        'subtotal' => 'Subtotal',
        'discount_value' => 'Discount Value',
        'discount_percent' => 'Discount (%)',
        'discount_total' => 'Discount Total',
        'tax_value' => 'Tax Value',
        'tax_percent' => 'Tax (%)',
        'tax_total' => 'Tax Total',
        'late_payment_penalty_percent' => 'Late Payment (%)',
        'total' => 'Total',
        'total_paid' => 'Total Paid',
        'outstanding_balance' => 'Balance Due',
        'items' => 'Invoice Items',
        'product' => 'Product',
        'custom_name' => 'Custom Product Name',
        'description' => 'Description',
        'quantity' => 'Quantity',
        'unit_price' => 'Unit Price',
        'unit_price_converted' => 'Unit Price (Converted)',
        'total_price' => 'Total',
        'original_price' => 'Original Price',
        'original_currency' => 'Original Currency',
        'grand_total' => 'Grand Total',
    ],

    'statuses' => [
        'Draft' => 'Draft',
        'Pending' => 'Pending',
        'Partially Paid' => 'Partially Paid',
        'Paid' => 'Paid',
        'Overdue' => 'Overdue',
    ],

    'filters' => [
        'status' => 'Filter by Status',
        'status_options' => [
            'Draft' => 'Draft',
            'Pending' => 'Pending',
            'Partially Paid' => 'Partially Paid',
            'Paid' => 'Paid',
            'Overdue' => 'Overdue',
        ],
    ],

    'actions' => [
        'create' => 'Create Invoice',
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'restore' => 'Restore',
        'force_delete' => 'Force Delete',
        'print' => 'Print',
        'print_arabic' => 'Print (Arabic)',
        'print_english' => 'Print (English)',
    ],

    'prompts' => [
        'select_print_language' => 'Select Print Language',
        'print_language' => 'Language',
    ],

    'notifications' => [
        'success' => 'Success',
        'converted' => 'Invoice created from quotation successfully!',
    ],

    'languages' => [
        'ar' => 'Arabic',
        'en' => 'English',
    ],
];
