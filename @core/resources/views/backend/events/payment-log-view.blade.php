@extends('backend.admin-master')
@section('site-title')
    {{__('Payment Details')}}
@endsection
@section('style')
    <x-media.css/>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 margin-top-40">
        <div class="row">
            <div class="col-lg-12 mt-t">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Payment Details')}}</h4>
                        <li>
                            <li><strong>{{__('Name')}}:</strong> {{$payment->name}}</li>
                            <li><strong>{{__('Email')}}:</strong> {{$payment->email}}</li>
                            <li><strong>{{__('Event')}}:</strong> {{$payment->event_name}}</li>
                            <li><strong>{{__('Ticket Cost')}} :</strong> {{amount_with_currency_symbol($payment->event_cost)}}</li>
                            <li><strong>{{__('Payment Gateway')}}:</strong> {{$payment->package_gateway}}</li>
                            <li><strong>{{__('Transaction ID')}}:</strong> {{$payment->transaction_id}}</li>
                            <li><strong>{{__('Status')}}:</strong> {{$payment->status}}</li>

                            @if(!empty($payment->manual_payment_attachment))
                                <li>
                                    <strong class="d-block">{{__(' Manual Payment Attachment :')}}  </strong>
                                        <a class="btn btn-info btn-sm btn-sm" href="{{url('assets/uploads/attachment/'.$payment->manual_payment_attachment)}}" target="_blank">
                                            {{__('View Attachment')}}
                                        </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
