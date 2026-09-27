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
            margin-bottom: 16px;
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
        <h1>{{ $project->name }}</h1>
        <div class="code">Project Code: {{ $project->project_code }} &nbsp;|&nbsp; Status: {{ $project->status }}</div>
    </div>

    <div class="section-title">Project Details</div>
    <table class="meta-table">
        <tr>
            <td class="label">Client</td>
            <td>{{ $project->client->user->name ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Project Type</td>
            <td>{{ $project->project_type }}</td>
        </tr>
        <tr>
            <td class="label">Site Address</td>
            <td>{{ $project->site_address }}</td>
        </tr>
        <tr>
            <td class="label">Start Date</td>
            <td>{{ $project->start_date->format('M d, Y') }}</td>
        </tr>
        <tr>
            <td class="label">Expected End Date</td>
            <td>{{ $project->expected_end_date->format('M d, Y') }}</td>
        </tr>
        <tr>
            <td class="label">Actual End Date</td>
            <td>{{ $project->actual_end_date?->format('M d, Y') ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Contract Value</td>
            <td>₱ {{ number_format($project->contract_value, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Description</td>
            <td>{{ $project->description }}</td>
        </tr>
    </table>

    <div class="section-title">Contracts ({{ $project->contracts->count() }})</div>
    @if ($project->contracts->isEmpty())
    <p>No contracts recorded.</p>
    @else
    <table class="data">
        <thead>
            <tr>
                <th>Contract No.</th>
                <th>Type</th>
                <th>Original Value</th>
                <th>Current Value</th>
                <th>Retention %</th>
                <th>Effective Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($project->contracts as $contract)
            <tr>
                <td>{{ $contract->contract_no }}</td>
                <td>{{ $contract->contract_type }}</td>
                <td>₱ {{ number_format($contract->original_value, 2) }}</td>
                <td>₱ {{ number_format($contract->current_value, 2) }}</td>
                <td>{{ number_format($contract->retention_percentage, 2) }}%</td>
                <td>{{ $contract->effective_date->format('M d, Y') }}</td>
                <td><span class="badge">{{ $contract->status }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="section-title">Schedule Tasks ({{ $project->scheduleTasks->count() }})</div>
    @if ($project->scheduleTasks->isEmpty())
    <p>No schedule tasks recorded.</p>
    @else
    <table class="data">
        <thead>
            <tr>
                <th>Task</th>
                <th>Type</th>
                <th>Planned Start</th>
                <th>Planned End</th>
                <th>Actual Start</th>
                <th>Actual End</th>
                <th>Duration</th>
                <th>Assigned To</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($project->scheduleTasks as $task)
            <tr>
                <td>{{ $task->name }}</td>
                <td>{{ $task->task_type }}</td>
                <td>{{ $task->planned_start_date->format('M d, Y') }}</td>
                <td>{{ $task->planned_end_date->format('M d, Y') }}</td>
                <td>{{ $task->actual_start_date?->format('M d, Y') ?? '—' }}</td>
                <td>{{ $task->actual_end_date?->format('M d, Y') ?? '—' }}</td>
                <td>{{ $task->duration }}</td>
                <td>{{ $task->employee->user->name ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">Generated on {{ now()->format('M d, Y h:i A') }}</div>
</body>

</html>