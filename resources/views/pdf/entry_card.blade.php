<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Event Entry Card</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            background-color: #fff;
            color: #222;
        }

        .card {
            width: 100%;
            /* margin: 15px auto; */
            /* border: 1px dashed #1e293b; */
            /* border-radius: 12px;  */
            background: #fff;
            /* box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15); */
            overflow: hidden;
        }

        .header {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
            color: #fff;
            text-align: center;
            padding: 15px 0;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            letter-spacing: 0.5px;
        }

        .header p {
            margin: 3px 0 0;
            font-size: 15px;
            opacity: 0.9;
        }

        .section {
            padding: 20px 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .qr-code {
            width: 35%;
            text-align: right;
            vertical-align: middle;
        }

        .details {
            width: 65%;
            vertical-align: top;
            /* padding-left: 15px; */
            line-height: 25px
        }

        .details p {
            margin: 5px 0;
            font-size: 13px;
        }

        .label {
            font-weight: bold;
            color: #334155;
        }

        .highlight {
            color: #dc2626;
            font-weight: 700;
        }

        .paid {
            color: #16a34a;
            font-weight: bold;
        }

        .unpaid {
            color: #dc2626;
            font-weight: bold;
        }

        .divider {
            border-top: 2px dashed #94a3b8;
            text-align: center;
            margin: 20px 30px;
            padding-top: 8px;
            font-size: 14px;
            color: #475569;
        }

        .event-info {
            font-size: 11.5px;
            margin-top: 12px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            color: #475569;
        }

        .food-coupon {
            background: #fff;
            padding: 18px 25px 25px;
        }

        .food-coupon h2 {
            text-align: center;
            font-size: 20px;
            color: #1e40af;
            margin: 0 0 10px;
            border-bottom: 1px dashed #94a3b8;
            padding-bottom: 5px;
        }

        .food-coupon-details p {
            margin: 5px 0;
            font-size: 13px;
        }

        .member-counts {
            margin-top: 10px;
            text-align: center;
            background: #e0f2fe;
            border-radius: 6px;
            padding: 8px;
            font-weight: 600;
            color: #0c4a6e;
        }

        .footer-note {
            text-align: center;
            font-size: 10px;
            color: #64748b;
            margin-top: 10px;
        }

        .qr-caption {
            font-size: 10.5px;
            color: #475569;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    @php
        $maleCount = 0;
        $femaleCount = 0;
        $childCount = 0;
        foreach ($registration->members as $member) {
            if ($member->gender == 'Male') {
                $maleCount++;
            } elseif ($member->gender == 'Female') {
                $femaleCount++;
            } elseif ($member->gender == 'Child') {
                $childCount++;
            }
        }
        $qrSvg = QrCode::size(100)->generate($registration->registration_id);
        $qrSvg = preg_replace('/<\?xml.*?\?>/', '', $qrSvg);
    @endphp

    <div class="card">
        <!-- Header -->
        <div class="header">
            <h1>Family Annual Event 2026</h1>
            <p>Entry Pass & Food Coupon</p>
        </div>

        <!-- Entry Info -->
        <div class="section">
            <table>
                <tr>
                    <td class="details">
                        <p><span class="label">Registration ID:</span> <span
                                class="highlight">{{ $registration->registration_id }}</span></p>
                        <p><span class="label">Head of Family:</span> {{ $registration->name }}</p>
                        <p><span class="label">Group:</span> {{ $registration->group->name ?? 'N/A' }}</p>
                        {{-- <p><span class="label">Group:</span> {{ $registration->group->description."-". $registration->group->name ?? 'N/A' }}</p> --}}
                        <p><span class="label">Mobile:</span> {{ $registration->mobile }}</p>
                        <p><span class="label">Total Members:</span> {{ $registration->total_members }}</p>
                        <p><span class="label">Payment Status:</span>
                            <span class="{{ $registration->payment_status == 'Paid' ? 'paid' : 'unpaid' }}">
                                {{ $registration->payment_status }}
                            </span>
                        </p>
                    </td>
                    <td class="qr-code">
                        {!! $qrSvg !!}
                        <p class="qr-caption">Scan upon entry</p>
                    </td>
                </tr>
            </table>

            <div class="event-info">
                <p><strong>Date:</strong> 25 March, 2026 </p>
                <p><strong>Venue:</strong> Chaydoba Primary School Field, Pakuria Vangapara </p>
                <p><strong>Organized by:</strong> Horkora Foundation</p>
                <p><strong>Facebook:</strong> https://www.facebook.com/share/17EUKyDDpT/ </p>
                <p><strong>WhatsApp:</strong> https://chat.whatsapp.com/B6UHmbZnRAJCqfB1n6tP5l
            </div>
        </div>
        {{-- rules --}}
        <div
            style="margin-top: 12px; font-size: 11.5px; page-break-inside: avoid; border-top: 1px solid #e2e8f0; padding-top: 8px; padding-left: 20px">
            <h4 style="text-align:center; text-decoration: underline; margin-bottom: 8px;">
                Entry Rules & Regulations
            </h4>
            <ol style="padding-left: 20px; line-height: 1.7; text-align: justify;">
                <li>This card is valid only for registered participants.</li>
                <li>Please bring this card with you for entry.</li>
                {{-- <li>Entry is allowed only after scanning the QR code.</li> --}}
                {{-- <li>Each card admits one person only.</li> --}}
                <li>Lost cards will not be reissued.</li>
                <li>Maintain proper discipline during the event.</li>
                <li>Any kind of misconduct or disorderly behavior will lead to cancellation of entry.</li>
                <li>Parents are responsible for their children’s safety.</li>
                <li>Access to the stage or technical area without permission is strictly prohibited.</li>
                <li>Please keep this card safe for future events or reference.</li>
            </ol>
        </div>
        {{-- rules area --}}

        <div class="divider">-- ✂️ Cut Here for Food Coupon --</div>

        <!-- Food Coupon -->
        <div class="food-coupon">
            <h2>Food Coupon</h2>
            <div class="food-coupon-details">
                <p><span class="label">Registration ID:</span> <span
                        class="highlight">{{ $registration->registration_id }}</span></p>
                <p><span class="label">Head of Family:</span> {{ $registration->name }}</p>
                <div class="member-counts">
                    Total Members: {{ $registration->total_members }} <br>
                    <span style="font-weight:400; font-size:12px;">
                        (Male: {{ $maleCount }}, Female: {{ $femaleCount }}, Child: {{ $childCount }})
                    </span>
                </div>
            </div>
            <p class="footer-note">Please present this coupon at the food counter.</p>
        </div>
    </div>
</body>

</html>
