<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Slip - {{ $paymentSlip->slip_number }}</title>
    <style>
        /* Base Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 30px; /* Increased padding */
            font-size: 13px; /* Slightly smaller base font */
            line-height: 1.5;
            color: #333;
        }
        
        /* Header Section */
        .header {
            text-align: center;
            border-bottom: 4px solid #2563eb; /* Blue border */
            padding-bottom: 25px;
            margin-bottom: 35px;
        }
        .logo {
            width: 70px;
            height: 70px;
            background: #2563eb;
            border-radius: 50%;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: bold;
        }
        .org-name {
            font-size: 22px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 4px;
        }
        .org-subtitle {
            font-size: 15px;
            color: #6b7280;
        }
        .slip-title {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            margin-top: 20px;
        }
        .slip-number {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-top: 5px;
        }

        /* Amount Section */
        .amount {
            font-size: 26px;
            font-weight: bold;
            color: #059669; /* Green for amount */
            text-align: center;
            background: #ecfdf5; /* Light green background */
            border: 2px solid #10b981;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0 40px 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* Content Grid */
        .content {
            /* Using tables for PDF layout stability */
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .content td {
            width: 50%;
            padding: 0 15px;
            vertical-align: top;
        }

        /* Section Styling */
        .section {
            border: 1px solid #d1d5db; /* Lighter border */
            border-radius: 8px;
            padding: 20px;
            background: #ffffff; /* White background */
            height: 100%; /* Ensure consistent height for grid-like appearance */
        }
        .section-title {
            font-size: 15px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
            text-transform: uppercase;
        }
        
        /* Field Styling */
        .field {
            margin-bottom: 12px;
            display: block; /* Use block to manage spacing */
            clear: both;
        }
        .field-label {
            font-weight: bold;
            color: #6b7280; /* Gray label */
            float: left;
            width: 45%;
            font-size: 13px;
        }
        .field-value {
            color: #1f2937;
            float: right;
            width: 50%;
            text-align: right;
            font-weight: 500;
            font-size: 13px;
        }
        .clear-float {
            clear: both;
        }

        /* Status Styling */
        .status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-unpaid {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }
        .status-paid {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #10b981;
        }
        .status-expired {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }

        /* Instructions & Notes */
        .instructions {
            border: 2px solid #93c5fd; /* Light blue border */
            background: #eff6ff; /* Very light blue background */
            border-radius: 8px;
            padding: 20px;
            margin-top: 35px;
        }
        .instructions-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af; /* Dark blue title */
            margin-bottom: 10px;
            border-bottom: 1px dashed #bfdbfe;
            padding-bottom: 5px;
        }
        .instructions ul {
            margin: 10px 0 0 0;
            padding-left: 20px;
            list-style-type: disc;
        }
        .instructions li {
            margin-bottom: 8px;
            color: #1e40af;
        }
        .note {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #bfdbfe;
            font-size: 12px;
            font-weight: bold;
            color: #1e40af;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px dashed #d1d5db;
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">LGU</div>
        <div class="org-name">Local Government Unit</div>
        <div class="org-subtitle">LGU1 - Reservation Payment System</div>
        <div class="slip-title">OFFICIAL PAYMENT SLIP</div>
        <div class="slip-number">{{ $paymentSlip->slip_number }}</div>
    </div>

    <div class="amount">
        AMOUNT DUE: ₱{{ number_format($paymentSlip->amount, 2) }}
    </div>

    <table class="content">
        <tr>
            <td style="padding-left: 0;">
                <div class="section">
                    <div class="section-title">Payment Information</div>
                    <div class="field">
                        <span class="field-label">Payment Slip No:</span>
                        <span class="field-value">{{ $paymentSlip->slip_number }}</span>
                    </div><div class="clear-float"></div>
                    <div class="field">
                        <span class="field-label">Amount Due:</span>
                        <span class="field-value">₱{{ number_format($paymentSlip->amount, 2) }}</span>
                    </div><div class="clear-float"></div>
                    <div class="field">
                        <span class="field-label">Generated Date:</span>
                        <span class="field-value">{{ $paymentSlip->created_at->format('F j, Y') }}</span>
                    </div><div class="clear-float"></div>
                    <div class="field">
                        <span class="field-label">Due Date:</span>
                        <span class="field-value">{{ $paymentSlip->due_date->format('F j, Y') }}</span>
                    </div><div class="clear-float"></div>
                    <div class="field">
                        <span class="field-label">Status:</span>
                        <span class="field-value">
                            <span class="status status-{{ $paymentSlip->status }}">{{ ucfirst($paymentSlip->status) }}</span>
                        </span>
                    </div><div class="clear-float"></div>
                    @if($paymentSlip->paid_at)
                    <div class="field">
                        <span class="field-label">Paid Date:</span>
                        <span class="field-value">{{ $paymentSlip->paid_at->format('F j, Y g:i A') }}</span>
                    </div><div class="clear-float"></div>
                    @endif
                </div>
            </td>

            <td style="padding-right: 0;">
                <div class="section">
                    <div class="section-title">Citizen Information</div>
                    <div class="field">
                        <span class="field-label">Name:</span>
                        <span class="field-value">{{ $paymentSlip->booking->applicant_name }}</span>
                    </div><div class="clear-float"></div>
                    <div class="field">
                        <span class="field-label">Email:</span>
                        <span class="field-value">{{ $paymentSlip->booking->applicant_email }}</span>
                    </div><div class="clear-float"></div>
                    <div class="field">
                        <span class="field-label">Phone:</span>
                        <span class="field-value">{{ $paymentSlip->booking->applicant_phone }}</span>
                    </div><div class="clear-float"></div>
                    <div class="field">
                        <span class="field-label">Address:</span>
                        <span class="field-value">{{ $paymentSlip->booking->applicant_address }}</span>
                    </div><div class="clear-float"></div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <div class="section-title">Reservation Details</div>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; padding-right: 20px;">
                    <div class="field">
                        <span class="field-label">Event Name:</span>
                        <span class="field-value">{{ $paymentSlip->booking->event_name }}</span>
                    </div><div class="clear-float"></div>
                    <div class="field">
                        <span class="field-label">Facility:</span>
                        <span class="field-value">{{ $paymentSlip->booking->facility->name ?? 'N/A' }}</span>
                    </div><div class="clear-float"></div>
                    <div class="field">
                        <span class="field-label">Event Date:</span>
                        <span class="field-value">{{ $paymentSlip->booking->event_date->format('F j, Y') }}</span>
                    </div><div class="clear-float"></div>
                </td>
                <td style="width: 50%;">
                    <div class="field">
                        <span class="field-label">Start Time:</span>
                        <span class="field-value">{{ $paymentSlip->booking->start_time }}</span>
                    </div><div class="clear-float"></div>
                    <div class="field">
                        <span class="field-label">End Time:</span>
                        <span class="field-value">{{ $paymentSlip->booking->end_time }}</span>
                    </div><div class="clear-float"></div>
                    <div class="field">
                        <span class="field-label">Expected Attendees:</span>
                        <span class="field-value">{{ $paymentSlip->booking->expected_attendees }} people</span>
                    </div><div class="clear-float"></div>
                </td>
            </tr>
        </table>

        @if($paymentSlip->booking->event_description)
        <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #e5e7eb;">
            <div class="field-label" style="float: none; width: auto; font-size: 14px; color: #1f2937;">Event Description:</div>
            <div style="margin-top: 5px; color: #333;">{{ $paymentSlip->booking->event_description }}</div>
        </div>
        @endif
    </div>

    @if($paymentSlip->status === 'unpaid')
    <div class="instructions">
        <div class="instructions-title">PAYMENT INSTRUCTIONS</div>
        <ul>
            <li>Present this payment slip to the **LGU1 Cashier's Office**.</li>
            <li>Bring a **valid government-issued ID** for verification.</li>
            <li>Pay the exact amount in cash or check made payable to "**LGU1**".</li>
            <li>Payment must be made **before the due date** to avoid expiration.</li>
            <li>Keep your official receipt for your records.</li>
        </ul>
        <div class="note">
            Office Hours: Monday - Friday, 8:00 AM - 5:00 PM
        </div>
    </div>
    @endif

    @if($paymentSlip->cashier_notes)
    <div class="instructions" style="margin-top: 20px; border-color: #fca5a5; background: #fee2e2;">
        <div class="instructions-title" style="color: #991b1b; border-bottom-color: #fecaca;">CASHIER NOTES</div>
        <p style="margin: 0; color: #991b1b;">{{ $paymentSlip->cashier_notes }}</p>
    </div>
    @endif

    <div class="footer">
        <p>This is an official payment slip generated by the LGU1 Reservation System. Please keep a copy for your reference.</p>
        <p>Generated on {{ now()->format('F j, Y g:i A') }} | For inquiries, contact LGU1 at admin@lgu1.gov.ph</p>
    </div>
</body>
</html>