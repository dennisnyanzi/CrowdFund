@extends('backend.admin-master')
@section('site-title')
    {{__('Email Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                @include('backend.partials.message')
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__("Email Settings")}}</h4>
                        @if($errors->any())
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger">{{$error}}</div>
                             @endforeach
                        @endif
                        <form action="{{route('admin.general.email.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                                <div class="form-group">
                                    <label for="contact_mail_success_message">{{__('Contact Mail Success Message')}}</label>
                                    <input type="text" name="contact_mail_success_message"  class="form-control" value="{{get_static_option('contact_mail_success_message')}}" id="contact_mail_success_message">
                                    <small class="form-text text-muted">{{__('this message will show when any one contact you via contact page form.')}}</small>
                                </div>
                                <div class="form-group">
                                    <label for="get_in_touch_mail_success_message">{{__('Get In Touch Form Success Message')}}</label>
                                    <input type="text" name="get_in_touch_mail_success_message"  class="form-control" value="{{get_static_option('get_in_touch_mail_success_message')}}" id="get_in_touch_mail_success_message">
                                    <small class="form-text text-muted">{{__('this message will show when any one contact you via get in touch form.')}}</small>
                                </div>
                                <div class="form-group">
                                    <label for="apply_job_success_message">{{__('Apply Job Form Success Message')}}</label>
                                    <input type="text" name="apply_job_success_message"  class="form-control" value="{{get_static_option('apply_job_success_message')}}" id="apply_job_success_message">
                                    <small class="form-text text-muted">{{__('this message will show when any apply to any job')}}</small>
                                </div>
                                <div class="form-group">
                                    <label for="event_attendance_mail_success_message">{{__('Event Attendance Form Success Message')}}</label>
                                    <input type="text" name="event_attendance_mail_success_message"  class="form-control" value="{{get_static_option('event_attendance_mail_success_message')}}" id="event_attendance_mail_success_message">
                                    <small class="form-text text-muted">{{__('this message will show when any submit event attendance form')}}</small>
                                </div>

                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
