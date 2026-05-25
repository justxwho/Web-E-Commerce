@extends('layouts.app')
@section('content')
    <style>
        #header {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .logo__image {
            max-width: 220px;
        }
    </style>
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Address</h2>
            <div class="row">
                <div class="col-lg-3">
                    @include('user.account-nav')
                </div>
                <div class="col-lg-9">
                    <div class="page-content my-account__address">
                        <div class="row">
                            <div class="col-6">
                                <p class="notice">The following addresses will be used on the checkout page by default.</p>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('user.addresses.index') }}" class="btn btn-sm btn-danger">Back</a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="card mb-5">
                                    <div class="card-header">
                                        <h5>Edit Address</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('user.addresses.update') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $address->id }}" />
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-floating my-3">
                                                        <input type="text"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            name="name" value="{{ $address->name }}">
                                                        <label for="name">Full Name *</label>
                                                        @error('name')
                                                            <span class="alert alert-danger text-center">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating my-3">
                                                        <input type="text"
                                                            class="form-control @error('phone') is-invalid @enderror"
                                                            name="phone" value="{{ $address->phone }}">
                                                        <label for="phone">Phone Number *</label>
                                                        @error('phone')
                                                            <span class="alert alert-danger text-center">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-floating my-3">
                                                        <input type="text"
                                                            class="form-control @error('zip') is-invalid @enderror"
                                                            name="zip" value="{{ $address->zip }}">
                                                        <label for="zip">Pincode *</label>
                                                        @error('zip')
                                                            <span class="alert alert-danger text-center">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-floating mt-3 mb-3">
                                                        <input type="text"
                                                            class="form-control @error('state') is-invalid @enderror"
                                                            name="state" value="{{ $address->state }}">
                                                        <label for="state">State *</label>
                                                        @error('state')
                                                            <span class="alert alert-danger text-center">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-floating my-3">
                                                        <input type="text"
                                                            class="form-control @error('city') is-invalid @enderror"
                                                            name="city" value="{{ $address->city }}">
                                                        <label for="city">Town / City *</label>
                                                        @error('city')
                                                            <span class="alert alert-danger text-center">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating my-3">
                                                        <input type="text"
                                                            class="form-control @error('address') is-invalid @enderror"
                                                            name="address" value="{{ $address->address }}">
                                                        <label for="address">House no, Building Name *</label>
                                                        @error('address')
                                                            <span class="alert alert-danger text-center">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating my-3">
                                                        <input type="text"
                                                            class="form-control @error('locality') is-invalid @enderror"
                                                            name="locality" value="{{ $address->locality }}">
                                                        <label for="locality">Road Name, Area, Colony *</label>
                                                        @error('locality')
                                                            <span class="alert alert-danger text-center">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating my-3">
                                                        <input type="text"
                                                            class="form-control @error('landmark') is-invalid @enderror"
                                                            name="landmark" value="{{ $address->landmark }}">
                                                        <label for="landmark">Landmark</label>
                                                        @error('landmark')
                                                            <span class="alert alert-danger text-center">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating my-3">
                                                        <input type="text"
                                                            class="form-control @error('country') is-invalid @enderror"
                                                            name="country" value="{{ $address->country }}">
                                                        <label for="country">Country *</label>
                                                        @error('country')
                                                            <span class="alert alert-danger text-center">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1"
                                                            id="isdefault" name="isdefault">
                                                        <label class="form-check-label" for="isdefault">
                                                            Make as Default address
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 text-right">
                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
