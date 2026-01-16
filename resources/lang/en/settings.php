<?php

return [
    'page' => [
        'title' => 'Settings',
    ],

    'fields' => [
        'name' => 'Site Name',
        'logo' => 'Site Logo',
        'phone' => 'Phone Number',
        'email' => 'Email Address',
        'address' => 'Address',
        'tax_number' => 'Tax Number',
        'language' => 'Default Language',
        'currency' => 'Currency',
        'salary_currency' => 'Salary Currency',
        'tax' => 'Tax %',
        'default_printable_language' => 'Default Invoice/Quotation Language',
        'break_minutes' => 'Default Break Minutes',
        'overtime_minutes' => 'Default Overtime Minutes',
        'days_off_limit' => 'Days Off Limit',
        'encashment_limit' => 'Encashment Limit',
        'weekends' => 'Weekends',
        'default_payroll_start_day' => 'Default Payroll Start Day',
        'overtime_type' => 'Overtime Type',
        'overtime_value' => 'Overtime Value',
        'default_work_from' => 'Default Work From',
        'default_work_to' => 'Default Work To',
        'grace_period_minutes' => 'Grace Period Minutes',
        'work_type_days' => 'Work Schedule per Contract Type',
        'contract_type' => 'Contract Type',
        'working_days' => 'Working Days',
        'overtime_active_mode' => 'Current Overtime Calculation Method',
        'overtime_percentage' => 'Overtime Percentage (e.g., 1.5)',
        'overtime_fixed_rate' => 'Overtime Fixed Rate (Per Hour)',
        'location' => 'Select Office Location',
        'company_latitude' => 'Latitude',
        'company_longitude' => 'Longitude',
        'radius_meter' => 'Allowed Radius (Meters)',
    ],

    'languages' => [
        'en' => 'English',
        'ar' => 'Arabic',
    ],

    'options' => [
        'invoice_languages' => [
            'none' => 'None',
        ],
        'weekdays' => [
            'Saturday' => 'Saturday',
            'Sunday' => 'Sunday',
            'Monday' => 'Monday',
            'Tuesday' => 'Tuesday',
            'Wednesday' => 'Wednesday',
            'Thursday' => 'Thursday',
            'Friday' => 'Friday',
        ],
        'contract_types' => [
            'Full Time' => 'Full Time',
            'Part Time' => 'Part Time',
            'Intern' => 'Intern',
        ],
        'overtime_types' => [
            'Percentage' => 'Percentage',
            'Fixed' => 'Fixed',
        ],
        'overtime_modes' => [
            'percentage' => 'Percentage of Hourly Salary',
            'fixed' => 'Fixed Hourly Rate',
        ],
    ],

    'sections' => [
        'work_hours' => [
            'title' => 'Work Hours & Overtime',
        ],
        'geofencing' => [
            'title' => 'Office Location & Geofencing',
            'description' => 'Pin your office on the map and set the allowed radius for attendance.',
        ],
    ],

    'prefixes' => [
        'currency' => '$',
    ],

    'suffixes' => [
        'meters' => 'm',
    ],

    'messages' => [
        'saved' => 'Settings saved successfully',
    ],

    'actions' => [
        'save' => 'Save Settings',
    ],
];
