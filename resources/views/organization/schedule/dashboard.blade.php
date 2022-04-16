@extends('organization.layout')

@section('content')
@section('title')
    Shifts
@endsection

<div class="card mb-5 mb-xl-8">
    <div class="card-body py-3">
        <!--begin::Table container-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="min-w-225px">Receiver</th>
                        <th class="min-w-225px">Punch in</th>
                        <th class="min-w-225px">Punch out</th>
                    </tr>
                </thead>
                <tbody>
            @isset ( $receivers )
                @foreach ( $receivers as $receiver )
                    <tr>
                        <td>
                            <span class="text-dark fw-bolder d-block mb-1 fs-6">
                            <?php
                                $receiver_name = \App\Models\Receiver::find($receiver->user_id);
                            ?>
                            @isset ( $receiver_name )
                                {{ $receiver_name->first_name }} {{ $receiver_name->last_name }}
                            @endisset
                            </span>
                        </td>
                        <td>
                            <span class="text-dark fw-bolder d-block mb-1 fs-6">
                              @isset ( $receiver->punch_in )
                                {{ date('d/m/Y H:i', strtotime($receiver->punch_in)) }}
                              @endisset
                            </span>
                        </td>
                        <td>
                            <span class="text-dark fw-bolder d-block mb-1 fs-6">
                              @isset ( $receiver->punch_out )
                                {{ date('d/m/Y H:i', strtotime($receiver->punch_out)) }}
                              @endisset
                            </span>
                        </td>
                        {{-- <td class="text-end">
                            <form method="POST" action="{{ route('organizationreceivers.remove', [$org->slug, $receiver]) }}">
                              @if ( $receiver->status == 'Active' )
                                <a href="{{ route('organizationreceiversinfo.edit', [$org->slug, $receiver]) }}" class="btn btn-icon btn-bg-light btn-active-color-primary bg-warning btn-sm me-1">
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)" />
                                            <path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        </svg>
                                    </span>
                                </a>
                              @endif
                            </form>
                        </td> --}}
                    </tr>
                @endforeach
            @endisset

                </tbody>
                <!--end::Table body-->
            </table>
            <!--end::Table-->
        </div>
        <div class="d-flex justify-content-center">
            {{ $receivers->links() }}
        </div>
        <!--end::Table container-->
    </div>
    <!--begin::Body-->
</div>

@endsection
