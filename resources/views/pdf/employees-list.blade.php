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

        .badge {
            padding: 2px 8px;
            border-radius: 10px;
            background-color: #E3C9A8;
            font-size: 9px;
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
        <h1>Employees</h1>
        <div class="code">Total Records: {{ $employees->count() }}</div>
    </div>

    <div class="section-title">Employee List</div>
    <table class="data">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Position</th>
                <th>Type</th>
                <th>Date Hired</th>
                <th>Date Terminated</th>
                <th>Rate</th>
                <th>Rate Type</th>
                <th>Contact No.</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
            <tr>
                <td>{{ $employee->employee_code }}</td>
                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                <td>{{ $employee->position }}</td>
                <td>{{ $employee->employee_type }}</td>
                <td>{{ $employee->date_hired->format('M d, Y') }}</td>
                <td>{{ $employee->date_terminated?->format('M d, Y') ?? '—' }}</td>
                <td>₱ {{ number_format($employee->basic_rate, 2) }}</td>
                <td>{{ $employee->rate_type }}</td>
                <td>{{ $employee->contact_no }}</td>
                <td><span class="badge">{{ $employee->status }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Generated on {{ now()->format('M d, Y h:i A') }}</div>
</body>

</html>