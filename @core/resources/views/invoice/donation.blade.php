
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{__('Donations Invoice')}}</title>
    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        table {
            font-size: x-small;
        }
        td  {
            font-size: 14px;
            padding: 5px;
            vertical-align: middle !important;
        }
        table tr th {
            line-height: 20px;
            font-size: 14px;
            font-weight: 700;
            padding: 5px 5px;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }
        .gray {
            background-color: lightgray
        }
        .table-footer tr td {
            text-align: left;
            font-size: 14px;
            padding: 5px;
        }
        .table-top td p,
        .table-footer td p {
            line-height: 18px;
            display: block;
            padding: 5px 0;
        }
        .totalAmount {
            font-width: 700;
            font-size: 25px;
            text-align: right;
            display: block;
        }
        table thead tr th {
            border: 0;
        }
        table thead tr th {
            border: 0;
        }
        table thead tr th:first-child {
            text-align: left;
            padding: 10px 30px;
        }
        table thead tr th:last-child {
            text-align: right;
            padding: 10px 30px;
        }
        .borderStyle{
            margin-bottom: 5px;
        }
        .border-top{ border-top: 2px solid #000;}

        .singleItems{
            font-size: 14px;
        }

    </style>
</head>
<body>

<table width="100%" class="table-top">
    <tr>
        <td valign="top">
            @php
                $logo = get_attachment_image_by_id(get_static_option('site_logo'));
            @endphp
            <img src="{!! $logo['img_url'] ?? '' !!}" alt="" width="150"/>
        </td>
    </tr>

    <tr>
        <td valign="top">
            <p><strong>{{__('Date : ')}}</strong> {{date('d-m-Y',strtotime($donation_details->created_at))}}</p>
            <p><strong>{{__('From : ')}}</strong> {{get_static_option('site_global_email')}}</p>
            <p><strong>{{__('To : ')}}</strong>{{$donation_details->name}}</p>
            <p><strong>{{__('Donor Email : ')}}</strong>{{$donation_details->email}}</p>
        </td>

        <td align="right">
            <h3> {{get_static_option('company_name')}} </h3>
            <p>{{get_static_option('company_address')}}</p>
            <p>{{get_static_option('company_email') }}</p>
            <p> {{get_static_option('company_phone')}} </p>
        </td>
    </tr>
</table>

<table class="table-footer" width="100%">
    <thead style="background-color: lightgray;">
    <tr>
        <th>{{__('Description')}}</th>
        <th>{{__('Amount')}}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td valign="top">
            <div>
                <p class="singleItems"><strong>{{__('Title : ')}}</strong> {{optional($donation_details->cause)->title}}</p>
                <p class="singleItems"><strong>{{__('Donor Name : ')}}</strong>{{$donation_details->name}}</p>
                <p class="singleItems"><strong>{{__('Payment Gateway : ')}}</strong>{{str_replace('_',' ',$donation_details->payment_gateway)}}</p>
                <p class="singleItems"><strong>{{__('Payment Status : ')}}</strong>{{str_replace('_',' ',$donation_details->status)}}</p>
                <p class="singleItems"><strong>{{__('Transaction ID : ')}}</strong>{{str_replace('_',' ',$donation_details->transaction_id)}}</p>
            </div>
        </td>


        <td align="right">
            <div class="borderStyle">
                <h6 class="singleItems" style="margin-bottom: 10px; display: block"><strong>{{__('Donated Amount : ')}}</strong>{{amount_with_currency_symbol($donation_details->amount,true)}}</h6>
                <h6 class="singleItems" style="margin-bottom: 10px; margin-top: 10px; display: block;"><strong>{{__('Admin Tip : ')}}</strong>{{amount_with_currency_symbol($donation_details->admin_charge,true)}}</h6>

                @php
                    $total_amount = $donation_details->amount + $donation_details->admin_charge;
                @endphp

                <h2 class=" border-top" style="margin-top: 20px;margin-bottom: 20px; display: block"><strong>{{__('Total Amount : ')}}</strong> {{amount_with_currency_symbol($total_amount,true) ?? 0 }}</h2>
            </div>
        </td>
    </tr>
    </tbody>

</table>







