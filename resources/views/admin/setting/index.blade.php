@extends('admin.layouts.master')

@section('content')
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ route('settings.update') }}">
        @csrf
        @method('PUT')

        <div class="container rounded bg-white mt-5 mb-5">
            <div class="row">
                <div class="col-md-12 border-right">
                    <div class="p-3 py-5 mt-3">
                        <div class="row">
                            <!-- Logo -->
                            <div class="col-md-4 mt-3">
                                <label class="labels">اللوجو</label>
                                <input type="file" class="form-control dropify" name="logo"
                                    data-default-file="{{ isset($settings) && $settings->where('key', 'logo')->first() ? asset('storage/settings/' . $settings->where('key', 'logo')->first()->value) : '' }}">
                            </div>

                            <!-- Fav Icon -->
                            <div class="col-md-4 mt-3">
                                <label class="labels">الايقونة</label>
                                <input type="file" class="form-control dropify" name="fav_icon"
                                    data-default-file="{{ isset($settings) && $settings->where('key', 'fav_icon')->first() ? asset('storage/settings/' . $settings->where('key', 'fav_icon')->first()->value) : '' }}">
                            </div>

                            <!-- Loader -->
                            <div class="col-md-4 mt-3">
                                <label class="labels">اللودر</label>
                                <input type="file" class="form-control dropify" name="loader"
                                    data-default-file="{{ isset($settings) && $settings->where('key', 'loader')->first() ? asset('storage/settings/' . $settings->where('key', 'loader')->first()->value) : '' }}">
                            </div>

                            @php
                                use Illuminate\Support\Str;
                                $phones = $settings->where('guard', 'admin')->filter(function ($item) {
                                    return Str::startsWith($item->key, 'phone');
                                });
                            @endphp

                            <!-- Phones Section -->
                            <div id="plans_container">
                                @foreach ($phones as $phone)
                                    <div class="row phone-row border p-3 mb-2" id="phoneRow-{{ $loop->index }}">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="phone" class="form-control-label">رقم الهاتف</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control " name="phones[]"
                                                        maxlength="11" style="text-align: left;"
                                                        value="{{ $phone->value ?? '' }}" required>
                                                    <span class="input-group-text">+966</span>

                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-2 d-flex align-items-center">
                                            <button type="button" class="btn btn-danger remove-phone"
                                                data-id="{{ $loop->index }}">
                                                <i class="fe fe-trash"></i>حذف
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- IBAN Section -->
                            <div class="col-md-12 mt-4">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">بيانات البنك</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="iban" class="form-label">رقم IBAN</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-credit-card"></i></span>
                                                        <input type="text" class="form-control" id="iban"
                                                            name="iban" placeholder="SAXX XXXX XXXX XXXX XXXX XXXX"
                                                            value="{{ $settings->where('key', 'iban')->first()->value ?? '' }}"
                                                            {{-- pattern="[A-Z]{2}[0-9]{2}[a-zA-Z0-9]{1,30}" --}}
                                                            title="رقم IBAN يجب أن يتكون من 24 حرفًا ورقمًا">
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        من فضلك أدخل رقم IBAN بشكل صحيح. يجب أن يتكون من 24 حرفًا ورقمًا.
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bank_name" class="form-label">اسم البنك</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-university"></i></span>
                                                        <input type="text" class="form-control" id="bank_name"
                                                            name="bank_name" placeholder=" مثال مصرف الراجحي"
                                                            value="{{ $settings->where('key', 'bank_name')->first()->value ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <div class="form-group">
                                                    <label for="account_holder" class="form-label">اسم صاحب الحساب</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        <input type="text" class="form-control" id="account_holder"
                                                            name="account_holder" placeholder="اسم صاحب الحساب بالكامل"
                                                            value="{{ $settings->where('key', 'account_holder')->first()->value ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                    <!-- Add Phone Button -->
                    <div class="mt-4 text-left">
                        <button type="button" class="btn btn-primary" id="addPhoneButton">اضافه رقم</button>
                    </div>
                    @can('update_setting')
                        <!-- Submit Button -->
                        <div class="mt-5 text-right mr-5">
                            <button type="submit" class="btn btn-primary" id="updateButton">تحديث</button>
                        </div>
                    @endcan

                    </div>
                </div>
            </div>
        </div>
    </form>

    @include('admin.layouts.myAjaxHelper')
@endsection

@section('ajaxCalls')
    <script>
        editScript();
    </script>

    <script>
        function preventLeadingZero(input) {
            input.addEventListener('keydown', function(e) {
                if (e.key === '0' && this.selectionStart === 0) {
                    e.preventDefault(); // block typing 0 as the first digit
                }
            });

            input.addEventListener('input', function(e) {
                let value = this.value;

                // Remove leading zeros (in case of paste)
                while (value.startsWith('0')) {
                    value = value.substring(1);
                }

                // Limit to 9 digits
                if (value.length > 9) {
                    value = value.substring(0, 9);
                }

                this.value = value;
            });
        }

        function handlePhoneInputs() {
            document.querySelectorAll('input[name="phones[]"]').forEach(preventLeadingZero);
        }
        let phoneCount = {{ count($phones) }};

        $(document).ready(function() {
            handlePhoneInputs();

            $('#addPhoneButton').on('click', function() {
                // Add phone field
                phoneCount++;
                let phoneRow = `
                <div class="row phone-row border p-3 mb-2" id="phoneRow-${phoneCount}">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="phone" class="form-control-label">رقم الهاتف</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="phones[]" maxlength="11" required style="text-align: left;">
                                <span class="input-group-text">+966</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger remove-phone" data-id="${phoneCount}">
                            <i class="fe fe-trash"></i> حذف
                        </button>
                    </div>
                </div>
            `;
                $('#plans_container').append(phoneRow);

                // Re-bind event
                handlePhoneInputs();
            });

            // Remove phone
            $(document).on('click', '.remove-phone', function() {
                let phoneId = $(this).data('id');
                $('#phoneRow-' + phoneId).remove();
            });
        });
    </script>
@endsection
