@extends('layouts.admin')
@section('content')
    <style>
        .table-striped td.pname {
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

        .table-striped th:first-child,
        .table-striped td:first-child {
            width: 80px;
            white-space: nowrap;
        }

        .table-striped th:nth-child(2),
        .table-striped td:nth-child(2) {
            width: 400px;
            white-space: nowrap;
            overflow: hidden;
            padding: 8px !important;
        }

        .table-striped th:nth-child(3),
        .table-striped td:nth-child(3) {
            width: 200px;
            white-space: nowrap;
        }

        .table-striped th:last-child,
        .table-striped td:last-child {
            width: 80px;
            white-space: nowrap;
        }
    </style>
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Users</h3>
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
                        <div class="text-tiny">All User</div>
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

                </div>
                <div class="wg-table table-all-user">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th class="text-center">Total Orders</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td class="pname">
                                            <div class="inner">
                                                <div class="image">
                                                    <img src="{{ asset('assets/images/avatar-default.png') }}"
                                                        alt="{{ $user->name }}">
                                                </div>
                                                <div class="name">
                                                    <a href="#" class="body-title-2">{{ $user->name }}</a>
                                                    <div class="text-tiny ms-3">
                                                        <span
                                                            class="badge {{ $user->utype === 'ADM' ? 'bg-danger' : 'bg-primary' }}">
                                                            {{ $user->utype === 'ADM' ? 'Admin' : 'User' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $user->orders_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="list-icon-function d-flex align-items-center justify-content-center">
                                                <form action="{{ route('admin.users.ban', ['id' => $user->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="button" class="ban" data-status="{{ $user->status }}"
                                                        style="border: none; background: none; padding: 0;"
                                                        title="{{ $user->status == 1 ? 'Active - Click to Ban' : 'Banned - Click to Unban' }}">
                                                        <div class="item edit mb-5"
                                                            style="opacity: {{ $user->status == 1 ? '1' : '0.4' }}">
                                                            <img src="{{ asset('assets/icons/ban-user.png') }}"
                                                                alt="ban">
                                                        </div>
                                                    </button>
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
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            $('.ban').on('click', function(e) {
                e.preventDefault();

                let form = $(this).closest('form');
                let isBanned = $(this).data('status') == 0;

                swal({
                    title: isBanned ? "Unban User?" : "Ban User?",
                    text: isBanned ? "This user will be able to login again." :
                        "This user will no longer be able to login.",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "Cancel",
                            visible: true,
                            className: "swal-button--cancel"
                        },
                        confirm: {
                            text: isBanned ? "Unban" : "Ban",
                            className: "swal-button--danger"
                        }
                    },
                    dangerMode: true
                }).then((willBan) => {
                    if (willBan) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
