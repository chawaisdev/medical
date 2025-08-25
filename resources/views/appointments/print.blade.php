<!DOCTYPE html>
<html>
<head>
    <title>Appointment Invoice</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; color: #333; margin:0; padding:0; }

        /* General page styling */
        .invoice-box {
            width: 100%;
            max-width: 520px; /* A5 width ~ 148mm */
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 18px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            border-radius: 6px;
            background: #fff;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
            color: #4CAF50;
        }

        .header p {
            margin: 3px 0 0;
            font-size: 13px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            word-wrap: break-word;
        }

        th, td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f8f8f8;
            font-weight: 600;
        }

        .section-title {
            margin-top: 15px;
            font-size: 15px;
            font-weight: 600;
            color: #4CAF50;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
        }

        .total {
            text-align: right;
            font-weight: bold;
            background: #f0fdf4;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px dashed #aaa;
            padding-top: 8px;
        }

        /* Print-specific styling */
        @media print {
            @page { size: A5 portrait; margin: 8mm; }
            body { margin: 0; padding: 0; }
            .invoice-box { box-shadow: none; border: none; width: 100%; max-width: none; }
            table, th, td { page-break-inside: avoid; }
        }

        /* Responsive on small screens */
        @media screen and (max-width: 600px) {
            .invoice-box { padding: 10px; }
            table, th, td { font-size: 12px; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="invoice-box">
        <div class="header">
            <h2>🩺 My Medical Center</h2>
            <p><strong>Appointment Receipt</strong></p>
        </div>

        <!-- Appointment Info -->
        <div class="section-title">Appointment Info</div>
        <table>
            <tr>
                <td><strong>Appointment ID:</strong> {{ $appointment->id }}</td>
                <td><strong>Date:</strong> {{ $appointment->date }}</td>
                <td><strong>Time:</strong> {{ $appointment->time }}</td>
            </tr>
            <tr>
                <td><strong>Doctor:</strong> {{ $appointment->doctor->name ?? 'N/A' }}</td>
                <td colspan="2"><strong>Patient:</strong> {{ $appointment->patient->name ?? 'N/A' }}</td>
            </tr>
        </table>

        <!-- Services -->
        <div class="section-title">Services</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Service</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointment->services as $index => $service)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $service->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Payment -->
        <div class="section-title">Payment Details</div>
        <table>
            <tr>
                <td><strong>Fee</strong></td>
                <td>{{ number_format($appointment->fee, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Discount</strong></td>
                <td>{{ number_format($appointment->discount, 2) }}</td>
            </tr>
            <tr>
                <td class="total">Final Fee</td>
                <td class="total">{{ number_format($appointment->final_fee, 2) }}</td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for choosing <strong>My Medical Center</strong><br>
            For inquiries, call: +92-300-1234567</p>
        </div>
    </div>
</body>
</html>
