<?php

return [
    'navigation_label' => 'Quotations',
    'model_label' => 'Quotation',
    'plural_model_label' => 'Quotations',

    'pages' => [
        'list' => 'Quotations',
        'create' => 'Create Quotation',
        'edit' => 'Edit Quotation',
        'view' => 'Quotation Details',
    ],

    'sections' => [
        'details' => 'Quotation Details',
        'financial_summary' => 'Financial Summary',
        'items' => 'Items',
    ],

    'fields' => [
        'number' => 'Quotation #',
        'issue_date' => 'Issue Date',
        'valid_until' => 'Valid Until',
        'due_date' => 'Due Date',
        'status' => 'Status',
        'computed_status' => 'Status',
        'client' => 'Client',
        'terms_and_conditions' => 'Terms & Conditions',
        'subtotal' => 'Subtotal',
        'discount_value' => 'Discount Value',
        'discount_percent' => 'Discount (%)',
        'discount_total' => 'Discount Total',
        'tax_value' => 'Tax Value',
        'tax_percent' => 'Tax (%)',
        'tax_total' => 'Tax Total',
        'total' => 'Total',
        'currency' => 'Currency',
        'items' => 'Quotation Items',
        'product' => 'Product',
        'description' => 'Description',
        'quantity' => 'Quantity',
        'unit_price' => 'Unit Price',
        'unit_price_converted' => 'Unit Price (Converted)',
        'total_price' => 'Total',
        'original_price' => 'Original Price',
        'original_currency' => 'Original Currency',
        'custom_name' => 'Custom Product Name',
        'late_payment_penalty_percent' => 'Late Payment (%)',
    ],

    'form_statuses' => [
        'Draft' => 'Draft',
        'Sent' => 'Sent',
        'Under Review' => 'Under Review',
        'Accepted' => 'Accepted',
        'Rejected' => 'Rejected',
    ],

    'invoice_statuses' => [
        'Draft' => 'Draft',
        'Pending' => 'Pending',
        'Partially Paid' => 'Partially Paid',
        'Paid' => 'Paid',
        'Overdue' => 'Overdue',
    ],

    'computed_statuses' => [
        'draft' => 'Draft',
        'sent' => 'Sent',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'expired' => 'Expired',
    ],

    'filters' => [
        'status' => 'Filter by Status',
        'trashed' => 'Trashed',
        'status_options' => [
            'expired' => 'Expired',
            'draft' => 'Draft',
            'sent' => 'Sent',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ],
    ],

    'actions' => [
        'create' => 'Create Quotation',
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'restore' => 'Restore',
        'force_delete' => 'Force Delete',
        'print' => 'Print',
        'print_arabic' => 'Print (Arabic)',
        'print_english' => 'Print (English)',
        'convert_to_invoice' => 'Convert to Invoice',
    ],

    'prompts' => [
        'select_print_language' => 'Select Print Language',
        'print_language' => 'Language',
        'convert_modal_heading' => 'Convert Quotation to Invoice',
    ],

    'notifications' => [
        'success' => 'Success',
        'converted' => 'Quotation converted to Invoice successfully!',
    ],

    'languages' => [
        'ar' => 'Arabic',
        'en' => 'English',
    ],
];
