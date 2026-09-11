<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <title>{{ $isNewThread ? 'Jauns pieteikums' : 'Jauna klienta ziņa' }} #{{ $bugReport->id }}</title>
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
            background-color: #e0f2fe;
            color: #0369a1;
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
            <h1>Auditors.lv &bull; Klientu saziņas paziņojums</h1>
        </div>

        <div class="content">
            <div class="badge">
                {{ $isNewThread ? '🚩 Jauns pieteikums #' . $bugReport->id : '💬 Jauna atbilde pieteikumā #' . $bugReport->id }}
            </div>

            <p style="font-size: 14px; line-height: 1.5; margin-top: 0; color: #475569;">
                @if($isNewThread)
                    Klients ir iesniedzis jaunu pieteikumu / ziņojumu par kļūdu sistēmā:
                @else
                    Klients ir pievienojis jaunu ziņu esošajā sarunā:
                @endif
            </p>

            <div class="message-card">{{ $item->message }}</div>

            <table>
                <tr>
                    <th>Pieteikuma ID</th>
                    <td><span class="code">#{{ $bugReport->id }}</span></td>
                </tr>
                <tr>
                    <th>Klients / Autors</th>
                    <td>
                        @if($item->user)
                            <strong>{{ $item->user->name }}</strong> ({{ $item->user->email }})
                        @elseif($bugReport->user)
                            <strong>{{ $bugReport->user->name }}</strong> ({{ $bugReport->user->email }})
                        @elseif(!empty($bugReport->email))
                            {{ $bugReport->email }} (Viesis)
                        @else
                            Viesis / Nereģistrēts
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Sadaļa / Vieta</th>
                    <td>{{ $bugReport->section ?: 'Klientu portāls' }}</td>
                </tr>
                @if(!empty($bugReport->url))
                <tr>
                    <th>Lapas URL</th>
                    <td>
                        <a href="{{ $bugReport->url }}" target="_blank" style="color: #0284c7; text-decoration: none;">
                            {{ $bugReport->url }}
                        </a>
                    </td>
                </tr>
                @endif
                @if(!empty($item->attachments) && is_array($item->attachments) && count($item->attachments) > 0)
                <tr>
                    <th>Pielikumi</th>
                    <td>
                        <strong>{{ count($item->attachments) }}</strong> {{ count($item->attachments) === 1 ? 'fails' : 'faili' }}
                    </td>
                </tr>
                @endif
                <tr>
                    <th>Nosūtīšanas laiks</th>
                    <td>{{ $item->created_at ? $item->created_at->format('d.m.Y H:i:s') : date('d.m.Y H:i:s') }}</td>
                </tr>
            </table>

            <div style="text-align: center; margin-top: 28px;">
                <a href="{{ route('admin.bug-reports.show', $bugReport->id) }}" class="button" target="_blank">
                    Atvērt pieteikumu administratora panelī &rarr;
                </a>
            </div>
        </div>

        <div class="footer">
            Šis ir automātisks sistēmas paziņojums no Auditors.lv pieteikumu moduļa.
        </div>
    </div>
</body>
</html>
