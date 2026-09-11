<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <title>Atbilde uz pieteikumu #{{ $bugReport->id }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 24px;
        }
        .container {
            max-width: 620px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #002855;
            color: #ffffff;
            padding: 24px 32px;
        }
        .header h1 {
            margin: 0;
            font-size: 19px;
            font-weight: 700;
            letter-spacing: -0.2px;
        }
        .content {
            padding: 32px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: #ecfdf5;
            color: #047857;
            margin-bottom: 20px;
        }
        .message-card {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-left: 4px solid #002855;
            border-radius: 4px;
            padding: 16px 20px;
            margin: 20px 0;
            font-size: 14px;
            line-height: 1.6;
            color: #0f172a;
            white-space: pre-wrap;
            word-break: break-word;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px 14px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }
        th {
            color: #64748b;
            font-weight: 600;
            width: 32%;
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
            background-color: #002855;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
        }
        .button:hover {
            background-color: #001f42;
        }
        .footer {
            background-color: #f8fafc;
            padding: 16px 32px;
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
            <h1>Auditors.lv &bull; Klientu atbalsts</h1>
        </div>

        <div class="content">
            <div class="badge">
                💬 Jauna atbilde pieteikumā #{{ $bugReport->id }}
            </div>

            @php
                $clientName = $bugReport->user ? $bugReport->user->name : 'Klient';
            @endphp

            <p style="font-size: 15px; line-height: 1.5; margin-top: 0; color: #0f172a;">
                Labdien, <strong>{{ $clientName }}</strong>!
            </p>

            <p style="font-size: 14px; line-height: 1.5; color: #475569;">
                Auditors.lv atbalsta komanda ir sniegusi atbildi uz Jūsu pieteikumu:
            </p>

            <div class="message-card">{{ $item->message }}</div>

            <table>
                <tr>
                    <th>Pieteikuma ID</th>
                    <td><span class="code">#{{ $bugReport->id }}</span></td>
                </tr>
                <tr>
                    <th>Sadaļa / Vieta</th>
                    <td>{{ $bugReport->section ?: 'Klientu portāls' }}</td>
                </tr>
                <tr>
                    <th>Statuss</th>
                    <td><strong>Atbildēts</strong></td>
                </tr>
                @if(!empty($item->attachments) && is_array($item->attachments) && count($item->attachments) > 0)
                <tr>
                    <th>Pielikumi</th>
                    <td>
                        <strong>{{ count($item->attachments) }}</strong> {{ count($item->attachments) === 1 ? 'fails' : 'faili' }}
                    </td>
                </tr>
                @endif
                <tr>
                    <th>Atbildes laiks</th>
                    <td>{{ $item->created_at ? $item->created_at->format('d.m.Y H:i:s') : date('d.m.Y H:i:s') }}</td>
                </tr>
            </table>

            <div style="text-align: center; margin-top: 28px;">
                <a href="{{ url('/client') }}" class="button" target="_blank">
                    Atvērt pieteikumu sistēmā Auditors.lv &rarr;
                </a>
            </div>
        </div>

        <div class="footer">
            Šis ir automātisks paziņojums no Auditors.lv. Lai turpinātu saraksti, lūdzu atveriet sadaļu "Manas saziņas" klientu portālā.
        </div>
    </div>
</body>
</html>
