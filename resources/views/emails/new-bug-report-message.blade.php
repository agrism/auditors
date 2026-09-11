<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <title>{{ $isNewThread ? 'Jauns pieteikums' : 'Jauna klienta ziņa' }} #{{ $bugReport->id }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            color: #334155;
            margin: 0;
            padding: 24px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #002855;
            color: #ffffff;
            padding: 22px 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.2px;
        }
        .content {
            padding: 28px 30px;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
            margin-bottom: 18px;
        }
        .message-card {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-left: 4px solid #002855;
            border-radius: 4px;
            padding: 16px 20px;
            margin: 16px 0 20px 0;
            font-size: 14px;
            line-height: 1.6;
            color: #0f172a;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .info-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 13px;
        }
        .info-table td.label {
            width: 130px;
            padding: 10px 14px;
            background-color: #f1f5f9;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #edf2f7;
            border-right: 1px solid #edf2f7;
            vertical-align: middle;
        }
        .info-table td.value {
            padding: 10px 14px;
            border-bottom: 1px solid #edf2f7;
            color: #0f172a;
            vertical-align: middle;
        }
        .info-table tr:last-child td {
            border-bottom: none;
        }
        .button {
            display: inline-block;
            background-color: #002855;
            color: #ffffff !important;
            text-decoration: none;
            padding: 11px 22px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 13px;
            text-align: center;
        }
        .button:hover {
            background-color: #001f42;
        }
        .footer {
            background-color: #f8fafc;
            padding: 14px 28px;
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

            <p style="font-size: 13px; line-height: 1.5; margin: 0 0 12px 0; color: #475569;">
                @if($isNewThread)
                    Klients ir iesniedzis jaunu pieteikumu / ziņojumu par kļūdu sistēmā:
                @else
                    Klients ir pievienojis jaunu ziņu esošajā sarunā:
                @endif
            </p>

            <!-- Message Card -->
            <div class="message-card">{{ $item->message }}</div>

            <!-- Structured Info Table -->
            <table class="info-table" cellpadding="0" cellspacing="0" border="0" style="width: 100%; border-collapse: separate; border-spacing: 0; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin: 20px 0; font-size: 13px;">
                <tbody>
                    <tr>
                        <td class="label" style="width: 130px; padding: 10px 14px; background-color: #f1f5f9; color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #edf2f7; border-right: 1px solid #edf2f7; vertical-align: middle;">Pieteikuma ID</td>
                        <td class="value" style="padding: 10px 14px; border-bottom: 1px solid #edf2f7; vertical-align: middle;">
                            <span style="display: inline-block; background-color: #002855; color: #ffffff; padding: 2px 8px; border-radius: 3px; font-family: monospace; font-size: 12px; font-weight: 700; letter-spacing: 0.5px;">#{{ $bugReport->id }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label" style="width: 130px; padding: 10px 14px; background-color: #f1f5f9; color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #edf2f7; border-right: 1px solid #edf2f7; vertical-align: middle;">Klients / Autors</td>
                        <td class="value" style="padding: 10px 14px; border-bottom: 1px solid #edf2f7; vertical-align: middle;">
                            @if($item->user)
                                <strong>{{ $item->user->name }}</strong> ({{ $item->user->email }})
                                @if(!empty($bugReport->email) && $bugReport->email !== $item->user->email)
                                    <div style="margin-top: 3px; font-size: 12px; color: #b45309;">
                                        ✉️ Atbildes e-pasts: <strong>{{ $bugReport->email }}</strong>
                                    </div>
                                @endif
                            @elseif($bugReport->user)
                                <strong>{{ $bugReport->user->name }}</strong> ({{ $bugReport->user->email }})
                                @if(!empty($bugReport->email) && $bugReport->email !== $bugReport->user->email)
                                    <div style="margin-top: 3px; font-size: 12px; color: #b45309;">
                                        ✉️ Atbildes e-pasts: <strong>{{ $bugReport->email }}</strong>
                                    </div>
                                @endif
                            @elseif(!empty($bugReport->email))
                                <strong>{{ $bugReport->email }}</strong> (Viesis)
                            @else
                                Viesis / Nereģistrēts
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label" style="width: 130px; padding: 10px 14px; background-color: #f1f5f9; color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #edf2f7; border-right: 1px solid #edf2f7; vertical-align: middle;">Sadaļa / Vieta</td>
                        <td class="value" style="padding: 10px 14px; border-bottom: 1px solid #edf2f7; color: #1e293b; font-weight: 500; vertical-align: middle;">
                            {{ $bugReport->section ?: 'Klientu portāls' }}
                        </td>
                    </tr>
                    @if(!empty($bugReport->url))
                    <tr>
                        <td class="label" style="width: 130px; padding: 10px 14px; background-color: #f1f5f9; color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #edf2f7; border-right: 1px solid #edf2f7; vertical-align: middle;">Lapas URL</td>
                        <td class="value" style="padding: 10px 14px; border-bottom: 1px solid #edf2f7; vertical-align: middle;">
                            <a href="{{ $bugReport->url }}" target="_blank" style="color: #0284c7; text-decoration: none; word-break: break-all; font-size: 12px;">
                                {{ $bugReport->url }}
                            </a>
                        </td>
                    </tr>
                    @endif
                    @if(!empty($item->attachments) && is_array($item->attachments) && count($item->attachments) > 0)
                    <tr>
                        <td class="label" style="width: 130px; padding: 10px 14px; background-color: #f1f5f9; color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #edf2f7; border-right: 1px solid #edf2f7; vertical-align: middle;">Pielikumi</td>
                        <td class="value" style="padding: 10px 14px; border-bottom: 1px solid #edf2f7; vertical-align: middle;">
                            <span style="display: inline-block; background-color: #f1f5f9; color: #334155; padding: 2px 7px; border-radius: 3px; font-size: 12px; border: 1px solid #e2e8f0;">
                                📎 {{ count($item->attachments) }} {{ count($item->attachments) === 1 ? 'fails' : 'faili' }}
                            </span>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="label" style="width: 130px; padding: 10px 14px; background-color: #f1f5f9; color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid #edf2f7; vertical-align: middle;">Laiks</td>
                        <td class="value" style="padding: 10px 14px; font-family: monospace; font-size: 12px; color: #64748b; vertical-align: middle;">
                            {{ $item->created_at ? $item->created_at->format('d.m.Y H:i:s') : date('d.m.Y H:i:s') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div style="text-align: center; margin-top: 24px;">
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
