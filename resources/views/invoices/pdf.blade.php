<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $lang === 'ar' ? 'فاتورة' : 'Invoice' }} {{ $invoice->number }}</title>
    <style>
        body { font-family: {{ $lang === 'ar' ? 'dejavusans' : 'Helvetica' }}; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        h1, p { margin: 5px 0; }
    </style>
</head>
<body>
<h1>{{ $lang === 'ar' ? 'فاتورة' : 'Invoice' }}: {{ $invoice->number }}</h1>
<p>{{ $lang === 'ar' ? 'تاريخ الإصدار' : 'Issue Date' }}: {{ $invoice->issue_date }}</p>
<p>{{ $lang === 'ar' ? 'تاريخ الاستحقاق' : 'Due Date' }}: {{ $invoice->due_date }}</p>
<p>{{ $lang === 'ar' ? 'العميل' : 'Client' }}: {{ $invoice->client->company_name ?? '' }}</p>

<table>
    <thead>
    <tr>
        <th>{{ $lang === 'ar' ? 'المنتج' : 'Product' }}</th>
        <th>{{ $lang === 'ar' ? 'الوصف' : 'Description' }}</th>
        <th>{{ $lang === 'ar' ? 'الكمية' : 'Quantity' }}</th>
        <th>{{ $lang === 'ar' ? 'سعر الوحدة' : 'Unit Price' }}</th>
        <th>{{ $lang === 'ar' ? 'الإجمالي' : 'Total' }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice->items as $item)
        <tr>
            <td>
                @if($item->product_id)
                    {{ is_array($item->product->name) ? ($item->product->name[$lang] ?? $item->product->name['en'] ?? '') : $item->product->name }}
                @else
                    {{ $item->custom_name }}
                @endif
            </td>
            <td>{{ $item->description ?? '' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->unit_price }}</td>
            <td>{{ $item->total_price }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<p>{{ $lang === 'ar' ? 'المجموع' : 'Subtotal' }}: {{ $invoice->subtotal }}</p>
<p>{{ $lang === 'ar' ? 'إجمالي الخصم' : 'Discount' }}: {{ $invoice->discount_total }}</p>
<p>{{ $lang === 'ar' ? 'إجمالي الضريبة' : 'Tax Total' }}: {{ $invoice->tax_total }}</p>
<p>{{ $lang === 'ar' ? 'الإجمالي النهائي' : 'Total' }}: {{ $invoice->total }}</p>
</body>
</html>
