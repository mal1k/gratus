@extends('organization.layout')

@section('content')

@isset ( $tipper )
    @section('title')
        {{ $tipper->first_name . ' ' . $tipper->last_name }} Transactions history
    @endsection
@else
    @section('title', 'Transactions')
@endisset

<div class="card mb-5 mb-xl-8">
    <!--begin::Body-->
    <div class="card-body py-3">
        <!--begin::Table container-->
        <div class="table-responsive">
            <!--begin::Table-->

        @isset ( $tipper )
            <table class="table align-middle gs-0 gy-4">
                <!--begin::Table head-->
                <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="ps-4 min-w-25px rounded-start">ID</th>
                        <th class="min-w-225px">
                            {{ $payment_role }}
                        </th>
                        <th class="min-w-225px">Amount</th>
                        <th class="min-w-225px">Status</th>
                        <th class="min-w-225px">Date</th>
                        <th></th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                @isset ( $transactions )
                    @foreach ( $transactions as $transaction )
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex justify-content-start flex-column">
                                        <span  class="text-dark fw-bolder mb-1 fs-6">{{ $transaction->transaction_id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php
                                    if ( $payment_role == 'Receiver' ) {
                                        $model = '\App\Models\\'.$transaction->model;
                                        $user = $model::find($transaction->user_id);
                                    } else {
                                        $model = '\App\Models\\'.$payment_role;
                                        $user = $model::find($transaction->receiver_id);
                                    }
                                ?>
                                <span class="text-dark fw-bolder d-block mb-1 fs-6">{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}</span>
                            </td>
                            <td>
                                <span class="text-dark fw-bolder d-block mb-1 fs-6">
                                    {{ $transaction->amount }} $
                                </span>
                            </td>
                            <td>
                                <span class="text-dark fw-bolder d-block mb-1 fs-6">
                                    {{ $transaction->status ?? '' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-dark fw-bolder d-block mb-1 fs-6">
                                    {{ date('d/m/Y', strtotime($transaction->created_at)) ?? '' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('organizationtransactions.destroy', [$org->slug, $transaction]) }}">
                                    @if ( $transaction->status == 'Active' )
                                    <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary bg-warning btn-sm me-1">
                                        <!--begin::Svg Icon | path: icons/duotone/Communication/Write.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg" enable-background="new 0 0 32 32" height="24px" id="svg2" version="1.1" viewBox="0 0 32 32" width="24px" xml:space="preserve"><g id="background"><rect fill="none" height="32" width="32"/></g><g id="view"><circle cx="16" cy="16" r="6"/>
                                                <path d="M16,6C6,6,0,15.938,0,15.938S6,26,16,26s16-10,16-10S26,6,16,6z M16,24c-8.75,0-13.5-8-13.5-8S7.25,8,16,8s13.5,8,13.5,8   S24.75,24,16,24z"/></g>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </a>
                                    @endif
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary bg-danger btn-sm">
                                        <!--begin::Svg Icon | path: icons/duotone/General/Trash.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero" />
                                                    <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3" />
                                                </g>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endisset
                </tbody>
                <!--end::Table body-->
            </table>
        @else
            <table class="table align-middle gs-0 gy-4">
                <!--begin::Table head-->
                <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="ps-4 min-w-25px rounded-start">ID</th>
                        <th class="min-w-225px">Tipper</th>
                        <th class="min-w-225px">Receiver</th>
                        <th class="min-w-225px">Status</th>
                        <th class="min-w-225px">Amount</th>
                        <th class="min-w-225px">Date</th>
                        <th class="min-w-225px">Time</th>
                        <th></th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                @foreach ( $transactions as $transaction )
                    <?php
                        $receiver_model = '\App\Models\Receiver';
                        $receiver = $receiver_model::find($transaction->receiver_id);

                        $tipper_model = '\App\Models\Tipper';
                        $tipper = $tipper_model::find($transaction->tipper_id);
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="d-flex justify-content-start flex-column">
                                    <span  class="text-dark fw-bolder mb-1 fs-6">{{ $transaction->transaction_id }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-dark fw-bolder d-block mb-1 fs-6">{{ $tipper->first_name ?? '' }} {{ $tipper->last_name ?? '' }}</span>
                        </td>
                        <td>
                            <span class="text-dark fw-bolder d-block mb-1 fs-6">{{ $receiver->first_name ?? '' }} {{ $receiver->last_name ?? '' }}</span>
                        </td>
                        <td>
                            <span class="text-dark fw-bolder d-block mb-1 fs-6">
                                {{ $transaction->status ?? '' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-dark fw-bolder d-block mb-1 fs-6">
                                {{ $transaction->amount }} $
                            </span>
                        </td>
                        <td>
                            <span class="text-dark fw-bolder d-block mb-1 fs-6">
                                {{ date('d/m/Y', strtotime($transaction->created_at)) ?? '' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-dark fw-bolder d-block mb-1 fs-6">
                                {{ date('H:i', strtotime($transaction->created_at)) ?? '' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('organizationtransactions.destroy', [$org->slug, $transaction]) }}">
                                @if ( $transaction->status == 'Active' )
                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary bg-warning btn-sm me-1">
                                    <!--begin::Svg Icon | path: icons/duotone/Communication/Write.svg-->
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg" enable-background="new 0 0 32 32" height="24px" id="svg2" version="1.1" viewBox="0 0 32 32" width="24px" xml:space="preserve"><g id="background"><rect fill="none" height="32" width="32"/></g><g id="view"><circle cx="16" cy="16" r="6"/>
                                            <path d="M16,6C6,6,0,15.938,0,15.938S6,26,16,26s16-10,16-10S26,6,16,6z M16,24c-8.75,0-13.5-8-13.5-8S7.25,8,16,8s13.5,8,13.5,8   S24.75,24,16,24z"/></g>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </a>
                                @endif
                                @csrf
                                <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary bg-danger btn-sm">
                                    <!--begin::Svg Icon | path: icons/duotone/General/Trash.svg-->
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero" />
                                                <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3" />
                                            </g>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <!--end::Table body-->
            </table>
        @endisset

            <div class="d-flex justify-content-center">
                @isset ( $transactions )
                    {{ $transactions->links() }}
                @endisset
            </div>
            <!--end::Table-->
        </div>
        <!--end::Table container-->
    </div>
    <!--begin::Body-->
</div>

@endsection
