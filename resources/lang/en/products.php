<?php

return [
    'navigation_label' => 'Products',
    'model_label' => 'Product',
    'plural_model_label' => 'Products',
    'fields' => [
        'code' => 'Product Code',
        'sku' => 'SKU',
        'name' => 'Product Name',
        'name_en' => 'Product Name (English)',
        'name_ar' => 'Product Name (Arabic)',
        'description' => 'Description',
        'description_en' => 'Description (English)',
        'description_ar' => 'Description (Arabic)',
        'price' => 'Price',
        'currency' => 'Currency',
        'type' => 'Type',
        'unit' => 'Unit',
        'barcode' => 'Barcode',
        'images' => 'Product Images',
        'status' => 'Status',
        'category' => 'Category',
    ],
    'types' => [
        'Service' => 'Service',
        'Physical' => 'Physical',
    ],
    'statuses' => [
        'Active' => 'Active',
        'Inactive' => 'Inactive',
        'Discontinued' => 'Discontinued',
    ],
    'filters' => [
        'status' => 'Status',
        'type' => 'Type',
        'trashed' => 'Trashed',
    ],
    'actions' => [
        'create' => 'Create Product',
        'edit' => 'Edit Product',
        'view' => 'View Product',
        'delete' => 'Delete Product',
        'restore' => 'Restore Product',
        'force_delete' => 'Delete Product Permanently',
    ],
    'placeholders' => [
        'unit' => 'e.g. piece, kg',
    ],
    'modals' => [
        'view_image' => 'View Product Image',
        'view_barcode' => 'View Barcode',
    ],
    'labels' => [
        'image' => 'Image',
    ],
    'messages' => [
        'no_image' => 'No image available',
    ],
];
