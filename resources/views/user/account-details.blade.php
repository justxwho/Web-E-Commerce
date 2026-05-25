@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Account Details</h2>
            <div class="row">
                <div class="col-lg-3">
                    <ul class="account-nav">
                        @include('user.account-nav')
                    </ul>
                </div>
                <div class="col-lg-9">
                    <div class="page-content my-account__edit">
                        @if (session()->has('success'))
                            <p class="alert alert-success">{{ session()->get('success') }}</p>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <div class="my-account__edit-form">
                            <form name="account_edit_form" action="{{ route('user.account.update') }}" method="POST"
                                class="needs-validation" novalidate="" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating my-3">
                                            <input type="text" class="form-control" placeholder="Full Name"
                                                name="name" value="{{ old('name', $user->name) }}" required="">
                                            <label for="name">Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating my-3">
                                            <input type="text" class="form-control" placeholder="Mobile Number"
                                                name="mobile" value="{{ old('mobile', $user->mobile) }}" required="">
                                            <label for="mobile">Mobile Number</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating my-3">
                                            <input type="email" class="form-control" placeholder="Email Address"
                                                name="email" value="{{ old('email', $user->email) }}" required="">
                                            <label for="account_email">Email Address</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="my-3">
                                            <h5 class="text-uppercase mb-0">Avatar</h5>
                                        </div>
                                        <div class="form-floating my-3">
                                            <div class="upload-image flex-grow">
                                                <div id="upload-file" class="item up-load">
                                                    <label class="uploadfile" for="myFile"
                                                        style="cursor:pointer;border:2px dashed #ddd;padding:20px;display:block;text-align:center;border-radius:8px;">
                                                        <div id="imgpreview"
                                                            style="{{ $user->avatar ? 'display:block' : 'display:none' }};margin-bottom:10px;">
                                                            <img src="{{ $user->avatar ? asset('uploads/avatars/' . $user->avatar) : '' }}"
                                                                alt="avatar"
                                                                style="width:80px;height:80px;border-radius:50%;object-fit:cover;">
                                                        </div>
                                                        <div id="upload-label"
                                                            style="{{ $user->avatar ? 'display:none' : 'display:block' }}">
                                                            <i class="icon-upload-cloud" style="font-size:24px;"></i>
                                                            <p class="mb-0">Drop your image here or select <span
                                                                    class="text-primary">click to browse</span></p>
                                                        </div>
                                                        <input type="file" id="myFile" name="avatar" accept="image/*"
                                                            style="display:none;">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="my-3">
                                            <h5 class="text-uppercase mb-0">Password Change</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating my-3">
                                            <input type="password" class="form-control" id="old_password"
                                                name="old_password" placeholder="Old password" required="">
                                            <label for="old_password">Old password</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating my-3">
                                            <input type="password" class="form-control" id="new_password"
                                                name="new_password" placeholder="New password" required="">
                                            <label for="account_new_password">New password</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating my-3">
                                            <input type="password" class="form-control" cfpwd=""
                                                data-cf-pwd="#new_password" id="new_password_confirmation"
                                                name="new_password_confirmation" placeholder="Confirm new password"
                                                required="">
                                            <label for="new_password_confirmation">Confirm new password</label>
                                            <div class="invalid-feedback">Passwords did not match!</div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="my-3">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@push('scripts')
    <script>
        $(function() {
            $("#myFile").on("change", function() {
                const [file] = this.files;
                if (file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                    $("#upload-label").hide();
                }
            });
        });
    </script>
@endpush
