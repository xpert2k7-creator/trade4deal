<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lead Approved — Trade4Deal</title>
</head>
<body style="margin:0;padding:0;background:#F7F8FA;font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#0F172A;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#F7F8FA;padding:32px 16px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #E2E8F0;">
                <tr>
                    <td style="background:linear-gradient(135deg,#0B3A6E 0%,#0E7490 100%);padding:28px 32px;text-align:center;">
                        <div style="font-size:22px;font-weight:800;letter-spacing:-0.03em;color:#ffffff;">Trade<span style="color:#F58220;">4</span>Deal</div>
                        <div style="margin-top:6px;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:rgba(255,255,255,0.75);">Connecting Buyer. Connecting Supplier. Creating Value.</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:32px;">
                        <div style="display:inline-block;background:rgba(5,150,105,0.12);color:#059669;font-size:12px;font-weight:700;padding:6px 12px;border-radius:999px;margin-bottom:16px;">APPROVED</div>
                        <h1 style="margin:0 0 12px;font-size:22px;line-height:1.3;color:#0B3A6E;">Your lead is now live</h1>
                        <p style="margin:0 0 16px;font-size:15px;line-height:1.6;color:#334155;">
                            Hello {{ $lead->contact_name }},
                        </p>
                        <p style="margin:0 0 20px;font-size:15px;line-height:1.6;color:#334155;">
                            Great news — your business inquiry has been reviewed and published on Trade4Deal. Verified buyers and sellers can now discover your interest.
                        </p>
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#F7F8FA;border-radius:12px;border:1px solid #E2E8F0;margin-bottom:24px;">
                            <tr>
                                <td style="padding:16px 18px;">
                                    <p style="margin:0 0 8px;font-size:12px;text-transform:uppercase;letter-spacing:0.06em;color:#64748B;font-weight:700;">Lead summary</p>
                                    <p style="margin:0 0 6px;font-size:14px;color:#0F172A;"><strong>Company:</strong> {{ $lead->company_name }}</p>
                                    <p style="margin:0 0 6px;font-size:14px;color:#0F172A;"><strong>Interest:</strong> {{ $lead->product_interest }}</p>
                                    <p style="margin:0 0 6px;font-size:14px;color:#0F172A;"><strong>Product type:</strong> {{ $lead->product_type?->label() }}</p>
                                    <p style="margin:0 0 6px;font-size:14px;color:#0F172A;"><strong>Trade:</strong> {{ $lead->currency?->value }} · {{ $lead->units?->label() }}</p>
                                    <p style="margin:0;font-size:14px;color:#0F172A;"><strong>Country:</strong> {{ $lead->country }}</p>
                                </td>
                            </tr>
                        </table>
                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 auto 8px;">
                            <tr>
                                <td style="border-radius:10px;background:#0B3A6E;">
                                    <a href="{{ $homeUrl }}" style="display:inline-block;padding:14px 28px;font-size:14px;font-weight:700;color:#ffffff;text-decoration:none;">View Trade4Deal</a>
                                </td>
                            </tr>
                        </table>
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
