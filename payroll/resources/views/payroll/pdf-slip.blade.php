<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $item->employee->last_name }}</title>
    <style>

        @page { 
            size: A4 portrait; 
            margin: 0in 0.5in 0.5in 0.5in; /* Top, Right, Bottom, Left */
        }
        
        body { font-family: 'Helvetica', sans-serif; color: #1f2937; margin: 0; padding: 0; background-color: #fff; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .w-full { width: 100%; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        .container { 
            width: 6.5in; 
            margin: 0.5in auto; 
            padding: 30px; 
            border: 1px solid #d1d5db; 
        }
        
        /* UPDATED HEADER FOR LOGO */
        .header { border-bottom: 3px solid #0d9488; padding-bottom: 15px; margin-bottom: 25px; overflow: hidden; }
        .header-logo { float: left; width: 70px; height: 70px; background-color: #fff; border-radius: 50%; }
        .header-text { margin-left: 85px; text-align: left; padding-top: 10px; }
        .company-name { font-size: 22px; color: #0d9488; margin: 0; text-transform: uppercase; font-weight: bold; }
        .payslip-title { font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 2px; margin-top: 2px; }

        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-grid td { padding: 4px 0; font-size: 11px; }
        .label { font-weight: bold; color: #4b5563; width: 100px; }
        .value { color: #111827; border-bottom: 1px solid #e5e7eb; }

        .peso { font-family: 'DejaVu Sans', sans-serif; }

        .section-title { background: #f9fafb; padding: 8px 12px; font-weight: bold; font-size: 11px; margin-top: 20px; color: #0d9488; border: 1px solid #e5e7eb; border-left: 5px solid #0d9488; text-transform: uppercase; }

        .main-table { border-collapse: collapse; margin-top: 0; width: 100%; }
        .main-table th { background: #0d9488; color: white; padding: 10px; font-size: 10px; border: 1px solid #0d9488; text-align: left; }
        .main-table td { border: 1px solid #e5e7eb; padding: 12px; font-size: 11px; vertical-align: top; }
        
        .total-box { margin-top: 25px; width: 100%; border-collapse: collapse; }
        .total-box td { padding: 15px; background: #0d9488; color: white; text-align: right; }
        .total-label { font-size: 10px; text-transform: uppercase; font-weight: bold; opacity: 0.9; }
        .total-amount { font-size: 20px; font-weight: bold; font-family: 'DejaVu Sans', sans-serif; }
    </style>
</head>
<body>

@php $details = json_decode($item->details); @endphp

<div class="container mx-auto">
    <div class="header">
        <img src="{{ public_path('images/sdsc_logo.jpg') }}" class="header-logo">
        <div class="header-text">
            <h1 class="company-name">SDSC Payroll System</h1>
            <div class="payslip-title">Official Employee Payslip</div>
        </div>
    </div>

    <table class="info-grid">
        <tr>
            <td class="label">EMPLOYEE:</td>
            <td class="value" style="width: 250px;">{{ strtoupper($item->employee->first_name) }} {{ strtoupper($item->employee->last_name) }}</td>
            <td style="width: 20px;"></td>
            <td class="label">BATCH ID:</td>
            <td class="value">{{ $item->payrollBatch->batch_id }}</td>
        </tr>
        <tr>
            <td class="label">EMPLOYEE ID:</td>
            <td class="value">{{ $item->employee->employee_id }}</td>
            <td></td>
            <td class="label">PERIOD:</td>
            <td class="value">{{ date('M d', strtotime($item->payrollBatch->period_start)) }} - {{ date('M d, Y', strtotime($item->payrollBatch->period_end)) }}</td>
        </tr>
    </table>

    <div class="section-title">Earnings & Allowances</div>
    <table class="main-table">
        <tr>
            <th style="width: 50%;">Description</th>
            <th style="width: 20%; text-align: center;">Total Hrs</th>
            <th style="width: 30%; text-align: right;">Amount</th>
        </tr>
        <tr>
            <td>Basic Salary</td>
            <td style="text-align: center; font-weight: bold; color: #374151;">{{ $details->total_hours ?? 0 }}</td>
            <td class="text-right peso">₱{{ number_format($details->gross_basic ?? $item->basic_pay, 2) }}</td>
        </tr>
        
        @if($item->employee->salary_type !== 'Hourly' && ($details->absences ?? 0) > 0)
        <tr>
            <td style="color: #dc2626; font-style: italic;">Less: Absences</td>
            <td style="text-align: center; color: #dc2626; font-weight: bold;">{{ $details->absences }} days</td>
            <td class="text-right peso" style="color: #dc2626;">-₱{{ number_format($details->absence_deduction ?? 0, 2) }}</td>
        </tr>
        @endif

        <tr>
            <td>Additions (Allowances/Incentives)</td>
            <td style="text-align: center; color: #9ca3af;">-</td>
            <td class="text-right peso" style="color: #059669; font-weight: bold;">₱{{ number_format($item->additions, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Mandatory Deductions</div>
    <table class="main-table">
        <tr>
            <th colspan="2">Deduction Type</th>
            <th style="text-align: right;">Amount</th>
        </tr>
        <tr>
            <td colspan="2">SSS Contribution</td>
            <td class="text-right peso" style="color: #dc2626;">-₱{{ number_format($details->sss ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2">PhilHealth</td>
            <td class="text-right peso" style="color: #dc2626;">-₱{{ number_format($details->philhealth ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2">Pag-IBIG</td>
            <td class="text-right peso" style="color: #dc2626;">-₱{{ number_format($details->pagibig ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight: bold;">Withholding Tax (WHT)</td>
            <td class="text-right peso" style="color: #dc2626; font-weight: bold;">-₱{{ number_format($details->tax ?? 0, 2) }}</td>
        </tr>
    </table>

    <table class="total-box">
        <tr>
            <td>
                <span class="total-label">Net Take Home Pay</span><br>
                <span class="total-amount">₱{{ number_format($item->net_pay, 2) }}</span>
            </td>
        </tr>
    </table>

    <table class="w-full" style="margin-top: 60px;">
        <tr>
            <td><div style="width: 180px; border-top: 1px solid #000; text-align: center; font-size: 9px; padding-top: 5px;"><b>AUTHORIZED SIGNATURE</b></div></td>
            <td class="text-right"><div style="width: 180px; border-top: 1px solid #000; text-align: center; font-size: 9px; padding-top: 5px; margin-left: auto;"><b>EMPLOYEE SIGNATURE</b></div></td>
        </tr>
    </table>

    <div class="text-center" style="margin-top: 40px; font-size: 10px; color: #9ca3af; font-style: italic;">
        Generated on {{ date('Y-m-d H:i') }} by {{ $item->payrollBatch->processed_by }}
    </div>
</div>

</body>
</html>