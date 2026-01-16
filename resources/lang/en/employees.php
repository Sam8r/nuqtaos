<?php

return [
    'navigation_label' => 'Employees',
    'model_label' => 'Employee',
    'plural_model_label' => 'Employees',
    'fields' => [
        'id' => 'Employee ID',
        'name' => 'Name',
        'name_en' => 'Name (English)',
        'name_ar' => 'Name (Arabic)',
        'email' => 'Email',
        'password' => 'Password',
        'phone' => 'Phone',
        'emergency_contact' => 'Emergency Contact',
        'joining_date' => 'Joining Date',
        'payroll_start_day' => 'Payroll Cycle Start Day',
        'contract_type' => 'Contract Type',
        'work_type' => 'Work Type',
        'status' => 'Status',
        'salary' => 'Salary',
        'work_start' => 'Custom Work Start Time',
        'work_end' => 'Custom Work End Time',
        'documents' => 'Documents',
        'documents_images' => 'Images',
        'documents_files' => 'Other Documents',
        'position' => 'Position',
        'department' => 'Department',
    ],
    'actions' => [
        'create' => 'Create Employee',
        'edit' => 'Edit Employee',
        'view' => 'View Employee',
    ],
    'contract_types' => [
        'Full Time' => 'Full Time',
        'Part Time' => 'Part Time',
        'Intern' => 'Intern',
    ],
    'work_types' => [
        'Onsite' => 'Onsite',
        'Remote' => 'Remote',
    ],
    'statuses' => [
        'Active' => 'Active',
        'Inactive' => 'Inactive',
        'Terminated' => 'Terminated',
        'On Leave' => 'On Leave',
    ],
    'filters' => [
        'status' => 'Status',
        'contract_type' => 'Contract Type',
        'department' => 'Department',
        'position' => 'Position',
    ],
    'helper_texts' => [
        'documents' => 'Allowed file types: jpg, jpeg, png, webp, pdf, doc, docx.',
    ],
    'messages' => [
        'no_department' => 'No department assigned',
        'no_documents' => 'No documents available',
        'document_link' => '<a href=":url" target="_blank" class="text-primary-600 hover:text-primary-800 hover:underline font-medium block mb-1">:name</a>',
    ],
    'sections' => [
        'documents' => 'Documents',
    ],
    'modals' => [
        'view_document' => 'View Document Image',
    ],
    'document_labels' => [
        'images' => 'Images',
        'other_documents' => 'Other Documents',
        'file_name' => 'File Name',
    ],
];
