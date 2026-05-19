@extends('layouts.admin')
@section('content')
    <style>
        .table-striped td.pname {
            min-width: 220px;
            padding: 10px 12px !important;
        }

        .table-striped td.pname .inner {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .table-striped th:nth-child(2) {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            vertical-align: middle !important;
        }

        .table-striped td:nth-child(2) {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            vertical-align: middle !important;
        }

        .table-striped .image {
            width: 60px;
            height: 60px;
            flex-shrink: 0;
            border-radius: 8px;
            overflow: hidden;
        }

        .table-striped .image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .table-striped .name a.body-title-2 {
            font-weight: 600;
            font-size: 14px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            color: #333;
        }

        .table-striped .name .text-tiny {
            font-size: 11px;
            color: #999;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 140px;
        }

        .table-striped td,
        .table-striped th {
            vertical-align: middle !important;
            font-size: 13px;
        }

        .table-striped thead th {
            white-space: nowrap;
            font-weight: 600;
        }
    </style>
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Coupons</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Coupons</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search">
                            <fieldset class="name">
                                <input type="text" placeholder="Search here..." class="" name="name"
                                    tabindex="2" value="" aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.coupons.add') }}"><i class="icon-plus"></i>
                        Add new
                    </a>
                </div>
                <div class="wg-table table-all-user">
                    @if (session()->has('status'))
                        <p class="alert alert-success">{{ session()->get('status') }}</p>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Cart Value</th>
                                    <th>Expiry Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupons as $coupon)
                                    <tr>
                                        <td>{{ $coupon->id }}</td>
                                        <td>{{ $coupon->code }}</td>
                                        <td>{{ $coupon->type }}</td>
                                        <td>{{ $coupon->value }}</td>
                                        <td>${{ $coupon->cart_value }}</td>
                                        <td>{{ $coupon->expiry_date }}</td>
                                        <td>
                                            <div class="list-icon-function">
                                                <a href="{{ route('admin.coupons.edit', ['id' => $coupon->id]) }}">
                                                    <div class="item edit">
                                                        <i class="icon-edit-3"></i>
                                                    </div>
                                                </a>
                                                <form action="{{ route('admin.coupons.delete', ['id' => $coupon->id]) }}"
                                                    method="POST">
                                                    <div class="item text-danger delete">
                                                        <i class="icon-trash-2"></i>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $coupons->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            $('.delete').on('click', function(e) {
                e.preventDefault();

                let form = $(this).closest('form');

                swal({
                    title: "Delete Coupon?",
                    text: "This action cannot be undone.\nThis coupon will be permanently removed.",
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
