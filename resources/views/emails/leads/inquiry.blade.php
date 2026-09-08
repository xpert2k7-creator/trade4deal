<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Lead Inquiry — Trade4Deal</title>
</head>
<body style="margin:0;padding:0;background:#F7F8FA;font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#0F172A;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#F7F8FA;padding:32px 16px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #E2E8F0;">
                <tr>
                    <td style="background:linear-gradient(135deg,#0B3A6E 0%,#0E7490 100%);padding:28px 32px;text-align:center;">
                        <div style="font-size:22px;font-weight:800;letter-spacing:-0.03em;color:#ffffff;">Trade<span style="color:#F58220;">4</span>Deal</div>
                        <div style="margin-top:6px;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:rgba(255,255,255,0.75);">New business inquiry</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:32px;">
                        <div style="display:inline-block;background:rgba(14,116,144,0.12);color:#0E7490;font-size:12px;font-weight:700;padding:6px 12px;border-radius:999px;margin-bottom:16px;">INQUIRY</div>
                        <h1 style="margin:0 0 12px;font-size:22px;line-height:1.3;color:#0B3A6E;">Someone wants to connect</h1>
                        <p style="margin:0 0 16px;font-size:15px;line-height:1.6;color:#334155;">
                            Hello {{ $lead->contact_name }},
                        </p>
                        <p style="margin:0 0 20px;font-size:15px;line-height:1.6;color:#334155;">
                            A Trade4Deal member reached out about your lead <strong>{{ $lead->product_interest }}</strong> from {{ $lead->company_name }}.
                        </p>

                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#F7F8FA;border-radius:12px;border:1px solid #E2E8F0;margin-bottom:20px;">
                            <tr>
                                <td style="padding:16px 18px;">
                                    <p style="margin:0 0 8px;font-size:12px;text-transform:uppercase;letter-spacing:0.06em;color:#64748B;font-weight:700;">Inquirer details</p>
                                    <p style="margin:0 0 6px;font-size:14px;color:#0F172A;"><strong>Name:</strong> {{ $inquiry['name'] }}</p>
                                    <p style="margin:0 0 6px;font-size:14px;color:#0F172A;"><strong>Email:</strong> {{ $inquiry['email'] }}</p>
                                    <p style="margin:0 0 6px;font-size:14px;color:#0F172A;"><strong>Company:</strong> {{ $inquiry['company_name'] }}</p>
                                    <p style="margin:0 0 6px;font-size:14px;color:#0F172A;"><strong>Country:</strong> {{ $inquiry['country'] }}</p>
                                    @if (! empty($inquiry['phone']))
                                        <p style="margin:0;font-size:14px;color:#0F172A;"><strong>Phone:</strong> {{ $inquiry['phone'] }}</p>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#FFF7ED;border-radius:12px;border:1px solid #FED7AA;margin-bottom:24px;">
                            <tr>
                                <td style="padding:16px 18px;">
                                    <p style="margin:0 0 8px;font-size:12px;text-transform:uppercase;letter-spacing:0.06em;color:#C2410C;font-weight:700;">Message</p>
                                    <p style="margin:0;font-size:14px;line-height:1.65;color:#0F172A;white-space:pre-wrap;">{{ $inquiry['message'] }}</p>
                                </td>
                            </tr>
                        </table>

                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 auto 8px;">
                            <tr>
                                <td style="border-radius:10px;background:#0B3A6E;">
                                    <a href="{{ $leadUrl }}" style="display:inline-block;padding:14px 28px;font-size:14px;font-weight:700;color:#ffffff;text-decoration:none;">View your lead</a>
                                </td>
                            </tr>
                        </table>
                        <p style="margin:16px 0 0;font-size:13px;line-height:1.5;color:#64748B;text-align:center;">
                            Reply directly to this email to contact {{ $inquiry['name'] }}.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:18px 32px 28px;border-top:1px solid #E2E8F0;text-align:center;">
                        <p style="margin:0;font-size:12px;line-height:1.5;color:#94A3B8;">
                            &copy; {{ date('Y') }} Trade4Deal. Global B2B Marketplace.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
