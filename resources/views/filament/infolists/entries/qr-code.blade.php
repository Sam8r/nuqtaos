<div class="flex flex-col items-center space-y-2">
    <span class="text-sm font-medium text-gray-700">QR Code</span>
    <div class="border p-2 rounded-md bg-white shadow">
        {!! QrCode::size(150)->generate($record->qr_value) !!}
    </div>
</div>
