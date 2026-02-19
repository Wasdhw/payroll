<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0.5in; }
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; background-color: #fff; margin: 0; padding: 0; }
        
        .payslip-wrapper { 
            page-break-after: always; 
            width: 6.5in; /* Standard centralized width */
            margin: 0 auto;
            padding: 30px;
            border: 1px solid #d1d5db;
        }
        
        .payslip-wrapper:last-child { page-break-after: auto; }
        
        .header { text-align: center; border-bottom: 3px solid #0d9488; padding-bottom: 15px; margin-bottom: 25px; }
        .company-name { font-size: 22px; font-weight: bold; color: #0d9488; margin: 0; text-transform: uppercase; }
        .payslip-title { font-size: 12px; color: #6b7280; margin-top: 5px; letter-spacing: 1px; }

        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-grid td { padding: 4px 0; font-size: 11px; }
        .label { font-weight: bold; color: #4b5563; width: 100px; }
        .value { color: #111827; border-bottom: 1px solid #e5e7eb; }

        .section-title { background: #f9fafb; padding: 8px 12px; font-weight: bold; font-size: 11px; margin-top: 20px; color: #0d9488; border: 1px solid #e5e7eb; border-left: 5px solid #0d9488; }
        
        .data-table { width: 100%; border-collapse: collapse; margin-top: 0; }
        .data-table td { padding: 10px 12px; border: 1px solid #e5e7eb; font-size: 11px; }
        
        .total-box { margin-top: 25px; width: 100%; border-collapse: collapse; }
        .total-box td { padding: 15px; background: #0d9488; color: white; text-align: right; }
        .total-label { font-size: 10px; text-transform: uppercase; font-weight: bold; opacity: 0.9; }
        .total-amount { font-size: 20px; font-weight: bold; }

        .footer-note { font-size: 10px; margin-top: 30px; font-style: italic; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    @foreach($items as $item)
    @php $details = json_decode($item->details); @endphp
    
    <div class="payslip-wrapper">
        <div class="header">
            <h1 class="company-name">SDSC Payroll System</h1>
            <br>
        </div>

        <table class="info-grid">
            <tr>
                <td class="label">EMPLOYEE:</td>
                <td class="value" style="width: 250px;">{{ strtoupper($item->employee->first_name) }} {{ strtoupper($item->employee->last_name) }}</td>
                <td style="width: 20px;"></td>
                <td class="label">BATCH ID:</td>
                <td class="value">{{ $batch->batch_id }}</td>
            </tr>
            <tr>
                <td class="label">EMPLOYEE ID:</td>
                <td class="value">{{ $item->employee->employee_id }}</td>
                <td></td>
                <td class="label">PERIOD:</td>
                <td class="value">{{ $batch->period_start }} to {{ $batch->period_end }}</td>
            </tr>
        </table>

        <div class="section-title">EARNINGS</div>
        <table class="data-table">
            <tr>
                <td>Basic Pay (Adjusted for Absences)</td>
                <td style="text-align: right; font-family: DejaVu Sans, sans-serif;">₱{{ number_format($item->basic_pay, 2) }}</td>
            </tr>
            <tr>
                <td>Additions (Allowances/Incentives)</td>
                <td style="text-align: right; font-family: DejaVu Sans, sans-serif;">₱{{ number_format($item->additions, 2) }}</td>
            </tr>
        </table>

        <div class="section-title">MANDATORY DEDUCTIONS</div>
        <table class="data-table">
            <tr><td>SSS Contribution</td><td style="text-align: right; color: #dc2626; font-family: DejaVu Sans, sans-serif;">-₱{{ number_format($details->sss, 2) }}</td></tr>
            <tr><td>PhilHealth</td><td style="text-align: right; color: #dc2626; font-family: DejaVu Sans, sans-serif;">-₱{{ number_format($details->philhealth, 2) }}</td></tr>
            <tr><td>Pag-IBIG</td><td style="text-align: right; color: #dc2626; font-family: DejaVu Sans, sans-serif;">-₱{{ number_format($details->pagibig, 2) }}</td></tr>
        </table>

        <table class="total-box">
            <tr>
                <td>
                    <span class="total-label">Net Take Home Pay</span><br>
                    <span class="total-amount" style="font-family: DejaVu Sans, sans-serif;">₱{{ number_format($item->net_pay, 2) }}</span>
                </td>
            </tr>
        </table>
        
        <p class="footer-note"> Generated on {{ date('Y-m-d H:i') }}</p>
    </div>
    @endforeach
</body>
</html>