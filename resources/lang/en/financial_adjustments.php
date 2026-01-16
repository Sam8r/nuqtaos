<?php

return [
    'navigation_label' => 'Financial Adjustments',
    'model_label' => 'Financial Adjustment',
    'plural_model_label' => 'Financial Adjustments',

    'page' => [
        'list' => 'Financial Adjustments',
        'create' => 'Create Financial Adjustment',
        'view' => 'View Financial Adjustment',
        'edit' => 'Edit Financial Adjustment',
    ],

    'fields' => [
        'employee' => 'Employee',
        'type' => 'Type',
        'category' => 'Category',
        'amount_cash' => 'Amount (Cash)',
        'amount' => 'Amount',
        'days' => 'Days',
        'days_count' => 'Days Count',
        'reason' => 'Reason',
        'processing_date' => 'Processing Date',
        'status' => 'Status',
        'created_at' => 'Created At',
    ],

    'types' => [
        'Bonus' => 'Bonus',
        'Deduction' => 'Deduction',
    ],

    'category_groups' => [
        'Bonus' => [
            'Performance-based' => 'Performance-based',
            'Attendance-based' => 'Attendance-based',
            'Manual Bonus' => 'Manual Bonus',
        ],
        'Deduction' => [
            'Leave Overuse' => 'Leave Overuse',
            'Absence Without Notice' => 'Absence Without Notice',
            'Penalty' => 'Penalty',
            'Loan / Advance' => 'Loan / Advance',
        ],
    ],

    'categories' => [
        'Performance-based' => 'Performance-based',
        'Attendance-based' => 'Attendance-based',
        'Manual Bonus' => 'Manual Bonus',
        'Leave Overuse' => 'Leave Overuse',
        'Absence Without Notice' => 'Absence Without Notice',
        'Penalty' => 'Penalty',
        'Loan / Advance' => 'Loan / Advance',
    ],

    'statuses' => [
        'Pending' => 'Pending',
        'Processed' => 'Processed',
        'Rejected' => 'Rejected',
    ],

    'filters' => [
        'type' => 'Type',
        'status' => 'Status',
    ],

    'actions' => [
        'create' => 'Add Adjustment',
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ],
];
