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

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        table.data thead {
            display: table-header-group;
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

        table.data tr {
            page-break-inside: avoid;
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
        <h1>Materials</h1>
        <div class="code">Total Records: {{ $materials->count() }}</div>
    </div>

    <div class="section-title">Material List</div>
    <table class="data">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Unit</th>
                <th>Price</th>
                <th>Vendor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($materials as $material)
            <tr>
                <td>{{ $material->name }}</td>
                <td>{{ $material->category }}</td>
                <td>{{ $material->unit }}</td>
                <td>₱ {{ number_format($material->price, 2) }}</td>
                <td>{{ $material->vendor->name ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Generated on {{ now()->format('M d, Y h:i A') }}</div>
</body>

</html>