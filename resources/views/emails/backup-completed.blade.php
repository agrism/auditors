<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <title>Datubāzes rezerves kopija</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 24px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: {{ ($backupDetails['success'] ?? false) ? '#059669' : '#dc2626' }};
            color: #ffffff;
            padding: 24px 32px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }
        .content {
            padding: 32px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
            background-color: {{ ($backupDetails['success'] ?? false) ? '#d1fae5' : '#fee2e2' }};
            color: {{ ($backupDetails['success'] ?? false) ? '#065f46' : '#991b1b' }};
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            margin-bottom: 24px;
        }
        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }
        th {
            color: #64748b;
            font-weight: 600;
            width: 35%;
        }
        td {
            color: #0f172a;
            font-weight: 500;
        }
        .code {
            font-family: monospace;
            background-color: #f1f5f9;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 13px;
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px 32px;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $backupDetails['subject_name'] ?? 'Auditors.lv' }} sistēmas atskaite</h1>
        </div>

        <div class="content">
            <div class="badge">
                {{ ($backupDetails['success'] ?? false) ? '✓ Rezerves kopija veiksmīgi izveidota un nosūtīta' : '✗ Rezerves kopijas kļūda' }}
            </div>

            <p style="font-size: 15px; line-height: 1.5; margin-top: 0;">
                @if($backupDetails['success'] ?? false)
                    MySQL datubāzes (<strong>{{ $backupDetails['database'] ?? 'auditors' }}</strong>) rezerves kopija ir veiksmīgi noģenerēta un saglabāta Google Drive mapē.
                @else
                    Rezerves kopijas veidošanas procesā radās kļūda: <strong>{{ $backupDetails['error'] ?? 'Nezināma kļūda' }}</strong>
                @endif
            </p>

            <table>
                <tr>
                    <th>Datubāze</th>
                    <td><span class="code">{{ $backupDetails['database'] ?? '-' }}</span></td>
                </tr>
                <tr>
                    <th>Faila nosaukums</th>
                    <td><span class="code">{{ $backupDetails['file_name'] ?? '-' }}</span></td>
                </tr>
                <tr>
                    <th>Faila izmērs</th>
                    <td>{{ $backupDetails['file_size_mb'] ?? '-' }} MB</td>
                </tr>
                <tr>
                    <th>Izpildes laiks</th>
                    <td>{{ $backupDetails['duration_seconds'] ?? '-' }} sekundes</td>
                </tr>
                <tr>
                    <th>Galamērķis</th>
                    <td>Google Drive (Mape: <code>{{ $backupDetails['folder_id'] ?? '-' }}</code>)</td>
                </tr>
                @if(!empty($backupDetails['file_id']))
                <tr>
                    <th>Google Drive ID</th>
                    <td><span class="code">{{ $backupDetails['file_id'] }}</span></td>
                </tr>
                @endif
                @if(isset($backupDetails['deleted_count']))
                <tr>
                    <th>Dzēstās vecās kopijas</th>
                    <td>{{ $backupDetails['deleted_count'] }} faili (saglabāšanas periods: {{ $backupDetails['retention_days'] ?? 30 }} d.)</td>
                </tr>
                @endif
                <tr>
                    <th>Izveides datums</th>
                    <td>{{ date('d.m.Y H:i:s') }}</td>
                </tr>
                <tr>
                    <th>Serveris</th>
                    <td>{{ $backupDetails['server_host'] ?? gethostname() }} ({{ $backupDetails['server_ip'] ?? '65.21.182.7' }})</td>
                </tr>
            </table>

            @if($backupDetails['success'] ?? false)
            <div style="text-align: center; margin-top: 24px;">
                <a href="https://drive.google.com/drive/folders/{{ $backupDetails['folder_id'] ?? '1mjRZpsGZgdpZllMDYO7s8R7luuv8rKb2' }}" class="button" target="_blank">
                    Atvērt Google Drive mapi
                </a>
            </div>
            @endif
        </div>

        <div class="footer">
            Šis ir automātisks paziņojums no {{ $backupDetails['subject_name'] ?? 'Auditors.lv' }} servera cron procesa.
        </div>
    </div>
</body>
</html>
