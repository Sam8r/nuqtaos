<?php

return [
    'navigation_label' => 'Clients',
    'model_label' => 'Client',
    'plural_model_label' => 'Clients',
    'fields' => [
        'company_name' => 'Company Name',
        'company_name_en' => 'Company Name (English)',
        'company_name_ar' => 'Company Name (Arabic)',
        'contact_person_details' => 'Contact Person Details',
        'address' => 'Address',
        'emails' => 'Emails',
        'email' => 'Email',
        'phones' => 'Phones',
        'phone' => 'Phone',
        'tax_number' => 'Tax Number',
        'registration_documents' => 'Registration Documents',
        'credit_limit' => 'Credit Limit',
        'credit_currency' => 'Currency',
        'payment_terms' => 'Payment Terms',
        'payment_terms_en' => 'Payment Terms (English)',
        'payment_terms_ar' => 'Payment Terms (Arabic)',
        'industry_type' => 'Industry Type',
        'industry_type_en' => 'Industry Type (English)',
        'industry_type_ar' => 'Industry Type (Arabic)',
        'status' => 'Status',
        'tier' => 'Tier',
        'country' => 'Country',
    ],
    'statuses' => [
        'Active' => 'Active',
        'Inactive' => 'Inactive',
        'Pending' => 'Pending',
    ],
    'tiers' => [
        'Gold' => 'Gold',
        'Silver' => 'Silver',
        'Bronze' => 'Bronze',
    ],
    'filters' => [
        'status' => 'Status',
        'tier' => 'Tier',
        'country' => 'Country',
        'trashed' => 'Trashed',
    ],
    'helper_texts' => [
        'registration_documents' => 'Allowed file types: jpg, jpeg, png, webp, pdf, doc, docx.',
    ],
    'sections' => [
        'registration_documents' => 'Registration Documents',
    ],
    'document_labels' => [
        'images' => 'Registration Document Images',
        'files' => 'Documents',
        'image' => 'Image',
        'file_name' => 'File Name',
    ],
    'messages' => [
        'no_documents' => 'No registration documents available',
        'document_link' => '<a href=":url" target="_blank" class="text-primary-600 hover:text-primary-800 hover:underline font-medium block mb-1">:name <br></a>',
    ],
    'modals' => [
        'view_document_image' => 'View Registration Document Image',
    ],
    'actions' => [
        'create' => 'Create Client',
        'edit' => 'Edit Client',
        'view' => 'View Client',
        'delete' => 'Delete Client',
        'restore' => 'Restore Client',
        'force_delete' => 'Delete Client Permanently',
    ],
];
