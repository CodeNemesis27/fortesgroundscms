<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0;
            background-color: #FFF1E3;
        }

        * {
            font-family: 'Onest', sans-serif;
        }

        body {
            margin: 0;
            padding: 40px;
            background-color: #FFF1E3;
            color: #2B2118;
            font-size: 11px;
        }

        .header {
            border-bottom: 2px solid #C97B3D;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0 0 4px;
        }

        .header .code {
            font-size: 11px;
            color: #6B5B4D;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 24px 0 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #C97B3D;
            border-bottom: 1px solid #E3C9A8;
            padding-bottom: 4px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .meta-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .meta-table td.label {
            width: 160px;
            color: #6B5B4D;
            font-weight: bold;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        table.data th {
            background-color: #E3C9A8;
            text-align: left;
            padding: 6px 8px;
            font-size: 10px;
            text-transform: uppercase;
        }

        table.data td {
            padding: 6px 8px;
            border-bottom: 1px solid #E9DAC5;
            font-size: 10px;
        }

        .grand-total {
            text-align: right;
            font-weight: bold;
            padding: 6px 8px;
            font-size: 11px;
        }

        .footer {
            margin-top: 30px;
            font-size: 9px;
            color: #8A7A68;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $purchaseOrder->purchase_order_no }}</h1>
        <div class="code">Materials Purchased</div>
    </div>

    <div class="section-title">Purchase Order Details</div>
    <table class="meta-table">
        <tr>
            <td class="label">Project</td>
            <td>{{ $purchaseOrder->project->project_code ?? '—' }} — {{ $purchaseOrder->project->name ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Delivery Address</td>
            <td>{{ $purchaseOrder->delivery_address }}</td>
        </tr>
        <tr>
            <td class="label">Expected Delivery</td>
            <td>{{ $purchaseOrder->expected_delivery_date->format('M d, Y') }}</td>
        </tr>
        <tr>
            <td class="label">Actual Delivery</td>
            <td>{{ $purchaseOrder->actual_delivery_date?->format('M d, Y') ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Approved Date</td>
            <td>{{ $purchaseOrder->approved_date?->format('M d, Y') ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Grand Total</td>
            <td>₱ {{ number_format($purchaseOrder->grand_total, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Materials ({{ $purchaseOrder->purchaseOrderItems->count() }})</div>
    <table class="data">
        <thead>
            <tr>
                <th>Material</th>
                <th>Category</th>
                <th>Unit</th>
                <th>Vendor</th>
                <th>Quantity</th>
                <th>Unit Cost</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseOrder->purchaseOrderItems as $item)
            <tr>
                <td>{{ $item->material->name ?? '—' }}</td>
                <td>{{ $item->material->category ?? '—' }}</td>
                <td>{{ $item->material->unit ?? '—' }}</td>
                <td>{{ $item->material->vendor->name ?? '—' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₱ {{ number_format($item->unit_cost, 2) }}</td>
                <td>₱ {{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="6" class="grand-total">Grand Total</td>
                <td class="grand-total">₱ {{ number_format($purchaseOrder->grand_total, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">Generated on {{ now()->format('M d, Y h:i A') }}</div>
</body>

</html>