<!DOCTYPE html>
<html>

<head>
    <title>Appointment Invoice</title>
    <style>
        :root {
            --primary-rgb: 71, 143, 55;
            --primary-color: rgb(var(--primary-rgb));
            --primary-gradient: linear-gradient(90deg, rgb(var(--primary-rgb)), rgb(var(--primary-rgb)));
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #2d3748;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
        }

        .invoice-container {
            width: 100%;
            max-width: 500px;
            margin: 15px auto;
            padding: 15px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .invoice-header img {
            max-height: 60px;
            border-radius: 6px;
            background: #fff;
            padding: 3px;
        }

        .invoice-header h1 {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            color: var(--primary-color);
        }

        .invoice-header .details {
            text-align: right;
            font-size: 12px;
            color: #4a5568;
            line-height: 1.4;
        }

        .section {
            margin-bottom: 15px;
        }

        .section-title {
            font-weight: 600;
            font-size: 14px;
            color: var(--primary-color);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .info-table {
            width: 100%;
            font-size: 12px;
            margin-bottom: 15px;
        }

        .info-table td {
            vertical-align: top;
            padding: 5px 8px;
        }

        .services-table,
        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 12px;
        }

        .services-table th,
        .services-table td,
        .payment-table th,
        .payment-table td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
        }

        .services-table th {
            background: var(--primary-gradient);
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
        }

        .services-table tbody tr:nth-child(even) {
            background-color: #f7fafc;
        }

        .services-table tbody tr:hover {
            background-color: #edf2ff;
        }

        .payment-table td {
            background-color: #f7fafc;
        }

        .total-row td {
            font-weight: 700;
            background: linear-gradient(90deg, #edf2ff, #e2e8f0);
            color: #1a202c;
        }

        .footer {
            font-size: 11px;
            text-align: center;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            margin-top: 15px;
        }

        .footer strong {
            color: #2d3748;
        }

        @media print {
            @page {
                size: A5 portrait;
                margin: 5mm;
            }

            .invoice-container {
                box-shadow: none;
                margin: 0;
                width: 90%;
                max-width: none;
                padding: 10mm;
            }

            body {
                background: #fff;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <img src="{{ asset('assets/images/getwell.png') }}" alt="GetWell Logo">
            <div class="details">
                <h1>Appointment Invoice</h1>
                <div>Invoice #: {{ $appointment->id }}</div>
                <div>Date: {{ $appointment->date }}</div>
                <div>Time: {{ $appointment->time }}</div>
            </div>
        </div>

        <!-- From / Bill To -->
        <div class="section">
            <table class="info-table">
                <tr>
                    <td>
                        <div class="section-title">From</div>
                        <strong>GetWell Medical Center</strong><br>
                        Doctor: {{ $appointment->doctor->name ?? 'N/A' }}<br>
                        Phone: +92-330-8881122<br>
                        Address: Plot 17-E, New Alhayat Plaza, G-10 Markaz, Islamabad
                    </td>
                    <td>
                        <div class="section-title">Bill To</div>
                        <strong>{{ $appointment->patient->name ?? 'N/A' }}</strong><br>
                        Email: {{ $appointment->patient->email ?? 'N/A' }}<br>
                        Phone: {{ $appointment->patient->phone ?? 'N/A' }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Services Table -->
        <div class="section">
            <table class="services-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Service</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total_service_fee = 0; @endphp
                    @foreach ($appointment->services as $index => $service)
                        @php $total_service_fee += $service->price; @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $service->name }}</td>
                            <td>{{ number_format($service->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Payment Summary -->
        <div class="section">
            <table class="payment-table">
                <tr>
                    <td>Appointment Fee</td>
                    <td>{{ number_format($appointment->fee, 2) }}</td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td>{{ number_format($appointment->discount, 2) }}</td>
                </tr>
                <tr>
                    <td>Service Total</td>
                    <td>{{ number_format($total_service_fee, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Amount</td>
                    <td>{{ number_format($appointment->final_fee + $total_service_fee, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            Thank you for choosing <strong>GetWell Medical Center</strong><br>
            For inquiries, please contact: +92-330-8881122 | info@getwellcenter.com
        </div>
    </div>
</body>

</html>
