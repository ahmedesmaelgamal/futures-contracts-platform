@extends('vendor/layouts/master')

@section('title')
    {{ config()->get('app.name') ?? '' }} | الإعدادات
@endsection
@section('page_name')
الإعدادات
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <form id="addForm" class="addForm">
                    <div class="row">

                        {{--                        <div class="col-6">--}}
                        {{--                            <div class="form-group">--}}
                        {{--                                <label for="logo" class="form-control-label">الشعار</label>--}}
                        {{--                                <input type="file" class="dropify" name="logo" id="logo"  data-default-file="{{  getFile(getAuthSetting('logo') ) }}">--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        {{--                        <div class="col-6">--}}
                        {{--                            <div class="form-group">--}}
                        {{--                                <label for="loader" class="form-control-label">أيقونة التحميل</label>--}}
                        {{--                                <input type="file" class="dropify" name="loader" id="loader" data-default-file="{{ getFile(getAuthSetting('loader') )}}">--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        {{--                        <div class="col-6">--}}
                        {{--                            <div class="form-group">--}}
                        {{--                                <label for="fav_icon" class="form-control-label">أيقونة علامه التيوب</label>--}}
                        {{--                                <input type="file" class="dropify" name="fav_icon" id="fav_icon" data-default-file="{{ getFile(getAuthSetting('fav_icon'))}}">--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <div class="col-12" style="display: none">
                            <div class="form-group">
                                <label for="image" class="form-control-label">الصورة الشخصية</label>
                                <input type="file" style="display: none" class="dropify" name="image" id="image"
                                       data-default-file="{{ isset($vendorSetting->image) ? getFile($vendorSetting->image) : '' }}">
                            </div>
                        </div>


                        <div class="col-5" style="display: none">
                            <div class="form-group">
                                <label for="name" class="form-control-label">أسم المكتب</label>
                                <input type="text" class="form-control" name="name" id="name"
                                       value="{{isset($vendorSetting->name) ? $vendorSetting->name : ''}}">
                            </div>
                        </div>
                        <div class="col-3" style="display: none">
                            <div class="form-group">
                                <label for="phone" class="form-control-label">رقم الجوال</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="phone"
                                           {{isset($vendorSetting->phone) ? 'value='.substr($vendorSetting->phone, 4).'' : ''}}  name="phone"
                                           maxlength="11">
                                    <span class="input-group-text">966+</span>

                                </div>
                            </div>
                        </div>


                        <div class="col-4" style="display: none">
                            <div class="form-group">
                                <label for="commercial_number" class="form-control-label">رقم السجل التجاري</label>
                                <input type="number" class="form-control" name="commercial_number"
                                       id="commercial_number" {{isset($vendorSetting->commercial_number) ? 'value='.$vendorSetting->commercial_number.'' : ''}} >
                            </div>
                        </div>


                        <div class="col-6" style="display: none">
                            <div class="form-group">
                                <label for="email" class="form-control-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control" name="email"
                                       id="email" {{isset($vendorSetting->email) ? 'value='.$vendorSetting->email.'' : ''}} >
                            </div>
                        </div>


                        <div class="col-6" style="display: none">
                            <div class="form-group">
                                <label for="city_id" class="form-control-label">المدينة</label>
                                <select class="form-control" name="city_id" id="city_id">
                                    @foreach ($cities as $city)
                                        <option
                                            value="{{ $city->id }}" {{isset($vendorSetting->city_id) && $vendorSetting->city_id == $city->id ? 'selected' : ''}} >{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="password" class="form-control-label">كلمه السر</label>
                                <input type="password" class="form-control" name="password" id="password"
                                       autocomplete="new-password">
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-control-label">تأكيد كلمه السر</label>
                                <input type="password" class="form-control" name="password_confirmation"
                                       id="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>


                        <div class="col-6" style="display: none">
                            <div class="form-group">
                                <label for="profit_ratio" class="form-control-label">نسبة الربح</label>
                                <input type="number" class="form-control" name="profit_ratio"
                                       id="profit_ratio" {{isset($vendorSetting->profit_ratio) ? 'value='.$vendorSetting->profit_ratio.'' : ''}} >
                            </div>
                        </div>


                        <div class="col-6" style="display: none">
                            <div class="form-group">
                                <label for="is_profit_ratio_static" class="form-control-label">هل نسبه الربح
                                    ثابتة</label>
                                <select class="form-control" name="is_profit_ratio_static" id="is_profit_ratio_static">
                                    <option value="0" {{$vendorSetting->is_profit_ratio_static == 0 ? 'selected' : ''}}>
                                        لا
                                    </option>
                                    <option value="1" {{$vendorSetting->is_profit_ratio_static == 1 ? 'selected' : ''}}>
                                        نعم
                                    </option>
                                </select>
                            </div>
                        </div>
                                                @can('create_setting')

                        <div class="modal-footer">

                            <button type="submit" class="btn btn-primary" id="addButton">حفظ
                            </button>
                        @endcan
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('vendor/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')

    <script>
        $(document).ready(function () {
            $('#addForm').on('submit', function (event) {
                event.preventDefault();
                var formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');
                $.ajax({
                    method: "POST",
                    enctype: 'multipart/form-data',
                    url: "{{ route('vendorSetting.store') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#addButton').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                            ' ></span> <span style="margin-left: 4px;">جاري التحميل...</span>').attr('disabled', true);
                    },

                    success: function (data) {
                        if (data.status == 200) {
                            $('#addButton').html(`اضافه`).attr('disabled', false);

                            toastr.success(data.msg);


                        } else {
                            $('#addButton').html(`اضافه`).attr('disabled', false);

                            toastr.error(data.msg);
                        }
                    },
                    error: function (xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        $('#addButton').html(`اضافه`).attr('disabled', false);

                        $.each(errors, function (key, value) {
                            toastr.error(value);
                        });
                    }
                });
            });
        });
    </script>

    <script>
        function handlePhoneInput(inputId) {
            document.getElementById(inputId).addEventListener('input', function (e) {
                let value = e.target.value;

                // Remove leading zero
                if (value.startsWith('0')) {
                    value = value.slice(1);
                }

                // Limit to a maximum of 9 digits
                if (value.length > 9) {
                    value = value.slice(0, 9);
                }

                e.target.value = value;
            });
        }

        handlePhoneInput('phone');
    </script>
@endsection
