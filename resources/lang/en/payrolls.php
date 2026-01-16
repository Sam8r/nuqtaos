<?php

return [
    'navigation_label' => 'Payrolls',
    'model_label' => 'Payroll',
    'plural_model_label' => 'Payrolls',

    'pages' => [
        'list' => 'Payrolls',
        'create' => 'Generate Payroll',
        'view' => 'Payroll Details',
        'edit' => 'Edit Payroll',
    ],

    'tabs' => [
        'process' => 'Payroll Process',
        'salary_overview' => 'Salary Overview',
    ],

    'sections' => [
        'selection_period' => 'Selection & Period',
        'results' => 'Payroll Summary',
    ],

    'fields' => [
        'employee' => 'Employee',
        'month_year' => 'Payroll Month',
        'basic_salary' => 'Basic Salary',
        'daily_rate' => 'Daily Rate (Fixed 1/30)',
        'working_days_target' => 'Target Working Days',
        'bonuses_total' => 'Total Additions',
        'deductions_total' => 'Total Deductions',
        'leaves_deduction' => 'Leave Deductions',
        'net_salary' => 'Net Salary',
        'created_at' => 'Generated On',
    ],

    'filters' => [
        'employee' => 'Filter by Employee',
        'month' => 'Filter by Month',
    ],

    'actions' => [
        'create' => 'Generate Payroll',
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ],
];
