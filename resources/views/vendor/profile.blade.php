@extends('admin/layouts/master')
@section('title')
    {{isset($setting) ? $setting->where('key','app_name')->first()->value : ''}} | Profile
@endsection

@section('page_name')
    {{ trns('My Profile') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="wideget-user text-center">
                        <div class="wideget-user-desc">
                            <div class="wideget-user-img">
                                {{--                                <img href="' . getFile($obj->image) . '" src="{{--}}
                                {{--                                                $admin->image ? asset($admin->image) :--}}
                                {{--                                                $photo = 'https://ui-avatars.com/api/?length=1&background=random&bold=true&color=ffff&name=' . auth('admin')->user()->name--}}
                                {{--                                    }}" class="image-popup avatar avatar-md rounded-circle" style="cursor:pointer;" width="100" height="100" loading="lazy">--}}
                                <img class="image-popup avatar avatar-md rounded-circle" href="{{
                                                $admin->image ? asset($admin->image) :
                                                $photo = 'https://ui-avatars.com/api/?length=1&background=random&bold=true&color=ffff&name=' . auth('admin')->user()->name
                                    }}"

                                     src="{{
                                                $admin->image ? asset($admin->image) :
                                                $photo = 'https://ui-avatars.com/api/?length=1&background=random&bold=true&color=ffff&name=' . auth('admin')->user()->name
                                    }}"
                                     alt="img">
                            </div>
                            <div class="user-wrap">
                                <h4 class="mb-1 text-capitalize">{{$admin->name}}</h4>
                                <h6 class="text-muted mb-4"> {{$admin->email}}</h6>
                            </div>
                            {{--                            <div class="user-wrap">--}}
                            {{--                                <button class="mb-1 btn btn-primary">{{trns('update password')}}</button>--}}
                            {{--                            </div>--}}


                            {{--                            <h3 class="card-title"> {{ trns('roles') }} {{ config()->get('app.name') }}</h3>--}}
                            <div class="">
                                <button class="btn btn-secondary btn-icon text-white editBtn m-2">
                                            <span>
                                                <i class="fe fe-edit"></i>
                                            </span>
                                    {{ trns('update_password') }}
                                </button>
                                <button class="btn btn-secondary btn-icon text-white updateProfileImageBtn m-2">
                                            <span>
                                                <i class="fe fe-image"></i>
                                            </span>
                                    {{ trns('update_profile_image') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="wideget-user-tab">
                    <div class="tab-menu-heading">
                        <div class="tabs-menu1">
                            <ul class="nav">
                                <li class=""><a href="#tab-1" class="active show tab-action"
                                                data-toggle="tab">{{ trns('Information') }}</a></li>
                                <li class=""><a href="#tab-2" class=" tab-action"
                                                data-toggle="tab">{{ trns('activities') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane active show" id="tab-1">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="media-heading">
                                    <h5><strong>{{ trns('Personal Information') }}</strong></h5>
                                </div>
                                <div class="table-responsive ">
                                    <table class="table row table-borderless">
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td class="text-capitalize"><strong>{{ trns('Name') }}
                                                    :</strong> {{$admin->name}}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('Email') }} :</strong> {{$admin->email}}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('Register Date') }}
                                                    :</strong> {{$admin->created_at->diffForHumans()}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane" id="tab-2">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="media-heading">
                                    <h5><strong>{{ trns('Personal activities') }}</strong></h5>
                                </div>
                                {{--                                @dd($activities)--}}
                                @if($activities->count()>0)
                                    <div class="table-responsive ">
                                        <table class="table " id="dataTable">
                                            <thead>
                                            <tr>
                                                <th>
                                                    {{trans('description')}}
                                                </th>
                                                <th>
                                                    {{trans('module_type')}}
                                                </th>
                                                <th>
                                                    {{trans('id')}}
                                                </th>
                                                {{--                                                <th>--}}
                                                {{--                                                    {{trans('causer type')}}--}}
                                                {{--                                                </th>--}}
                                                {{--                                                <th>--}}
                                                {{--                                                    {{trans('module_type')}}--}}
                                                {{--                                                </th>--}}
                                                {{--                                                <th>--}}
                                                {{--                                                    {{trans('causer')}}--}}
                                                {{--                                                </th>--}}
                                            </tr>
                                            </thead>
                                            <tbody class="col-lg-12 col-xl-4 p-0">
                                            @foreach($activities as $activity)
                                                <tr>
                                                    <td>
                                                        {{$activity->description}}
                                                    </td>
                                                    <td>
                                                        {{class_basename($activity->subject_type)}}
                                                    </td>
                                                    <td>
                                                        {{$activity->id}}
                                                    </td>
                                                    {{--                                                    <td class="text-capitalize">--}}

                                                    {{--                                                        {{class_basename($activity->subject_type)}}--}}
                                                    {{--                                                    </td>--}}
                                                    {{--                                                    <td class="text-capitalize">--}}
                                                    {{--                                                        {{ $admin->name }}--}}
                                                    {{--                                                    </td>--}}
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4>{{ trns('no_activities_yet_for_this_user') }} </h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->


        <!-- update password and name -->
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">{{  trns('object_details')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- update password and name -->

        <!-- update profile image -->
        <div class="modal fade" id="updateProfileImage" data-backdrop="static" tabindex="-1" role="dialog"
             aria-hidden="true">
            <form enctype="multipart/form-data" action="{{route('myProfile.update.image')}}" method="POST" id="updateForm">
                @csrf
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="example-Modal3">{{  trns('object_details')}}</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="updateProfileImageModalBody">
                            <div class="row">

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="image" class="form-control-label">{{ trns('image') }}</label>
                                        <input type="file" class="dropify" name="image" id="image">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">{{ trns('close') }}</button>
                            <button type="submit" class="btn btn-success"
                                    id="updateButton">{{ trns('update') }}</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
        <!-- update profile image -->
    </div>
    @include('admin/layouts/myAjaxHelper')

@endsection
@section('ajaxCalls')
    <script>
        async function showDataTable() {
            $('#dataTable').DataTable();
        }

        $(document).ready(function () {
            showDataTable();
        })
        // Delete Using Ajax
        {{--deleteScript('{{route($route.'.destroy',':id')}}');--}}
        {{--// Add Using Ajax--}}
        {{--showAddModal('{{route($route.'.create')}}');--}}
        {{--addScript();--}}
        // Add Using Ajax
        showEditModal('{{route($updateRoute.'.edit')}}');
        showUpdateProfileImage('{{route($updateRoute.'.edit.image')}}');
        editScript();
    </script>
    <script>
        $('.tab-action').on('click', function (e) {
            $('.tab-action').removeClass('active show');
            $('.tab-pane').removeClass('active show');

            $(this).addClass('active show');
            let href = $(this).attr('href');
            $(`${href}`).addClass('active show');
        });
    </script>
@endsection

