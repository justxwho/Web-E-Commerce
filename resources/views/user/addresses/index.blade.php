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
            <h2 class="page-title">Addresses</h2>
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
                                <a href="{{ route('user.addresses.add') }}" class="btn btn-sm btn-info">Add New</a>
                            </div>
                        </div>
                        <div class="my-account__address-list row">
                            <h5>Shipping Address</h5>
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @forelse($addresses as $address)
                                <div class="my-account__address-item col-md-6">
                                    <div class="my-account__address-item__title">
                                        <h5>
                                            {{ $address->name }}
                                            @if ($address->isdefault)
                                                <i class="fa fa-check-circle text-success ms-2"></i>
                                            @endif
                                        </h5>
                                        <div style="display:flex;gap:10px;">
                                            <a href="{{ route('user.addresses.edit', ['id' => $address->id]) }}">Edit</a>
                                            <form action="{{ route('user.addresses.delete', ['id' => $address->id]) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="delete"
                                                    style="background:none;border:none;
                                                    padding:0;cursor:pointer;font-size:0.8125rem;text-transform:uppercase;border-bottom:2px solid;color:red;font-family:inherit;">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="my-account__address-item__detail">
                                        <p>{{ $address->address }}, {{ $address->locality }}</p>
                                        <p>{{ $address->city }}, {{ $address->state }}, {{ $address->country }}</p>
                                        <p>Near: {{ $address->landmark }}</p>
                                        <p>Pin code: {{ $address->zip }}</p>
                                        <br>
                                        <p>Mobile : {{ $address->phone }}</p>
                                    </div>
                                </div>
                            @empty
                                <p>No address found.</p>
                            @endforelse
                            <hr>
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
            $('.delete').on('click', function(e) {
                e.preventDefault();

                let form = $(this).closest('form');

                swal({
                    title: "Delete Address?",
                    text: "This action cannot be undone.\nThis address will be permanently removed.",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "Cancel",
                            visible: true,
                            className: "swal-button--cancel"
                        },
                        confirm: {
                            text: "Delete",
                            className: "swal-button--danger"
                        }
                    },
                    dangerMode: true
                }).then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
