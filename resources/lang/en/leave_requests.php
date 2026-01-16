<?php

return [
    'navigation_label' => 'Leave Requests',
    'model_label' => 'Leave Request',
    'plural_model_label' => 'Leave Requests',

    'pages' => [
        'list' => 'Leave Requests',
        'create' => 'Submit Leave Request',
        'view' => 'Leave Request Details',
    ],

    'fields' => [
        'selected_dates' => 'Selected Dates',
        'date' => 'Date',
        'type' => 'Leave Type',
        'encashed' => 'Encashed',
        'is_encashed' => 'Request Cash Encashment',
        'reason' => 'Reason',
        'rejection_reason' => 'Rejection Reason',
        'employee' => 'Employee',
        'status' => 'Status',
        'total_days' => 'Total Days',
        'created_at' => 'Requested At',
    ],

    'filters' => [
        'period' => 'Filter by Period',
        'status' => 'Filter by Status',
    ],

    'types' => [
        'paid' => 'Paid',
        'unpaid' => 'Unpaid',
    ],

    'periods' => [
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        'this_week' => 'This Week',
        'last_week' => 'Last Week',
        'this_month' => 'This Month',
        'last_month' => 'Last Month',
    ],

    'statuses' => [
        'Pending' => 'Pending',
        'Approved' => 'Approved',
        'Rejected' => 'Rejected',
    ],

    'actions' => [
        'create' => 'Submit Leave Request',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'delete' => 'Delete',
        'view' => 'View',
        'edit' => 'Edit',
    ],

    'notifications' => [
        'insufficient_balance' => [
            'title' => 'Insufficient Balance',
            'body' => 'You only have :days days available in your balance.',
        ],
        'request_approved' => 'Request Approved',
        'request_rejected' => 'Request Rejected',
    ],

    'prompts' => [
        'rejection_reason' => 'Reason for rejection',
    ],

    'validation' => [
        'encashment_limit_exceeded' => 'You have exceeded the maximum allowed encashment limit.',
        'insufficient_balance' => 'You only have :days days available in your balance.',
    ],
];
