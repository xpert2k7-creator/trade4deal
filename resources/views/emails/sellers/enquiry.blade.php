<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seller Enquiry — Trade4Deal</title>
</head>
<body style="margin:0;padding:0;background:#F7F8FA;font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#0F172A;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#F7F8FA;padding:32px 16px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #E2E8F0;">
                <tr>
                    <td style="background:linear-gradient(135deg,#0B3A6E 0%,#0E7490 100%);padding:28px 32px;text-align:center;">
                        <div style="font-size:22px;font-weight:800;letter-spacing:-0.03em;color:#ffffff;">Trade<span style="color:#F58220;">4</span>Deal</div>
                        <div style="margin-top:6px;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:rgba(255,255,255,0.75);">Seller enquiry</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:32px;">
                        <h1 style="margin:0 0 12px;font-size:22px;line-height:1.3;color:#0B3A6E;">New buyer enquiry</h1>
                        <p style="margin:0 0 16px;font-size:15px;line-height:1.6;color:#334155;">
                            Hello {{ $seller->name }},
                        </p>
                        <p style="margin:0 0 20px;font-size:15px;line-height:1.6;color:#334155;">
                            Someone reached out through your Trade4Deal company page for <strong>{{ $seller->company_name }}</strong>.
                        </p>
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#F7F8FA;border-radius:12px;border:1px solid #E2E8F0;margin-bottom:20px;">
                            <tr>
                                <td style="padding:16px 18px;">
                                    <p style="margin:0 0 8px;font-size:12px;text-transform:uppercase;letter-spacing:0.06em;color:#64748B;font-weight:700;">Inquirer</p>
                                    <p style="margin:0 0 6px;font-size:14px;"><strong>Name:</strong> {{ $inquiry['name'] }}</p>
                                    <p style="margin:0 0 6px;font-size:14px;"><strong>Email:</strong> {{ $inquiry['email'] }}</p>
                                    <p style="margin:0 0 6px;font-size:14px;"><strong>Company:</strong> {{ $inquiry['company_name'] }}</p>
                                    <p style="margin:0 0 6px;font-size:14px;"><strong>Country:</strong> {{ $inquiry['country'] }}</p>
                                    @if (! empty($inquiry['phone']))
                                        <p style="margin:0;font-size:14px;"><strong>Phone:</strong> {{ $inquiry['phone'] }}</p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#FFF7ED;border-radius:12px;border:1px solid #FED7AA;margin-bottom:24px;">
                            <tr>
                                <td style="padding:16px 18px;">
                                    <p style="margin:0 0 8px;font-size:12px;text-transform:uppercase;letter-spacing:0.06em;color:#C2410C;font-weight:700;">Message</p>
                                    <p style="margin:0;font-size:14px;line-height:1.65;white-space:pre-wrap;">{{ $inquiry['message'] }}</p>
                                </td>
                            </tr>
                        </table>
                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 auto;">
                            <tr>
                                <td style="border-radius:10px;background:#0B3A6E;">
                                    <a href="{{ $profileUrl }}" style="display:inline-block;padding:14px 28px;font-size:14px;font-weight:700;color:#ffffff;text-decoration:none;">View your page</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
