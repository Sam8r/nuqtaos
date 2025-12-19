<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <title>Quotation {{ $quotation->number }}</title>
    <style>
        body { font-family: {{ $lang === 'ar' ? 'dejavusans' : 'Helvetica' }}; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
<h1>{{ $lang === 'ar' ? 'عرض سعر' : 'Quotation' }}: {{ $quotation->number }}</h1>
<p>{{ $lang === 'ar' ? 'تاريخ الإصدار' : 'Issue Date' }}: {{ $quotation->issue_date }}</p>
<p>{{ $lang === 'ar' ? 'صالح حتى' : 'Valid Until' }}: {{ $quotation->valid_until }}</p>
<p>{{ $lang === 'ar' ? 'العميل' : 'Client' }}: {{ $quotation->client->company_name['en'] ?? '' }}</p>

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
    @foreach($quotation->items as $item)
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

<p>{{ $lang === 'ar' ? 'المجموع' : 'Subtotal' }}: {{ $quotation->subtotal }}</p>
<p>{{ $lang === 'ar' ? 'إجمالي الخصم' : 'Discount' }}: {{ $quotation->discount_total }}</p>
<p>{{ $lang === 'ar' ? 'الإجمالي' : 'Total' }}: {{ $quotation->total }}</p>
</body>
</html>
