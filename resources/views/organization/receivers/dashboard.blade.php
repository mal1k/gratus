@extends('organization.layout')

@section('content')
@section('title', 'Receivers')

<div class="card mb-5 mb-xl-8">
    <!--begin::Header-->
    <div class="card-header border-0">
        <div class="card-toolbar">
            <a href="{{ route('organizationreceivers.create', $org->slug) }}" class="btn btn-sm btn-primary">
                <span class="svg-icon svg-icon-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#ffffff" fill-rule="nonzero" opacity="0.3" />
                        <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#ffffff" fill-rule="nonzero" />
                    </svg>
                </span>
                Add new receiver
            </a>
        </div>
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body py-3">
        <!--begin::Table container-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle gs-0 gy-4">
                <!--begin::Table head-->
                <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="ps-4 min-w-25px rounded-start">ID</th>
                        <th class="min-w-225px">Email</th>
                        <th class="min-w-225px">Username</th>
                        <th class="min-w-225px">Date added</th>
                        <th class="min-w-225px">KYC</th>
                        <th class="min-w-225px">Status</th>
                        <th class="min-w-225px">Group</th>
                        <th></th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                @foreach ( $receivers as $receiver )
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="d-flex justify-content-start flex-column">
                                    <span  class="text-dark fw-bolder mb-1 fs-6">{{ $receiver->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-dark fw-bolder d-block mb-1 fs-6">
                                {{ $receiver->email }}
                            </span>
                        </td>
                        <td>
                            <span class="text-dark fw-bolder d-block mb-1 fs-6">{{ $receiver->first_name . ' ' . $receiver->last_name }}</span>
                        </td>
                        <td>
                            <span class="text-dark fw-bolder d-block mb-1 fs-6">
                                {{ $receiver->created_at->format('d/m/Y') }}
                            </span>
                        </td>
                        <td>
                            <span class="text-dark fw-bolder d-block mb-1 fs-6">
                                {{ $receiver->kyc_status }}
                            </span>
                        </td>
                        <td>
                            <span class="text-dark fw-bolder d-block mb-1 fs-6">
                                {{ $receiver->status }}
                            </span>
                        </td>
                        <td>
                            <span class="text-dark fw-bolder d-block mb-1 fs-6">
                                <?php
                                if ( isset($receiver->if_in_group) ) {
                                    $group = \App\Models\organizationgroups::find($receiver->if_in_group);
                                    if ( isset($group) )
                                        echo $group->name;
                                }
                                ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('organizationreceivers.remove', [$org->slug, $receiver]) }}">

                                @if ( $receiver->status == 'Blocked' )
                                    <a href="{{ route('organizationreceivers.unblock', [$org->slug, $receiver]) }}" class="btn btn-icon btn-bg-light btn-active-color-primary bg-warning btn-sm me-1">
                                        <!--begin::Svg Icon | path: icons/duotone/Communication/Write.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg width="24px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g>
                                                <path class="st0" d="M310.7,247.7H68.8c-8.7,0-13.1,2.2-15.5,4l-7.4,9.8c-2.7,6.2-4.3,12.8-4.6,19.5v164.9c0,11.3,2.5,17.5,4.6,21   l8.5,7.8c4.5,2.4,9.4,3.8,14.4,4.2l0.4,0h241.9c8.7,0,13.1-2.2,15.5-4l7.4-9.8c2.7-6.2,4.3-12.8,4.6-19.5V280.8   c0-11.3-2.5-17.5-4.6-21l-8.4-7.8C320.9,249.5,315.9,248.1,310.7,247.7z M311.1,214.7H68.8c-60.5,0-60.5,66.1-60.5,66.1v165.2   C8.3,512,68.8,512,68.8,512h242.3c60.5,0,60.5-66.1,60.5-66.1V280.8C371.6,214.7,311.1,214.7,311.1,214.7z M272.5,115.6   C272.5,51.8,324.3,0,388.1,0s115.6,51.8,115.6,115.6v99.1h-33v-99.1c0-45.6-37-82.6-82.6-82.6s-82.6,37-82.6,82.6v99.1h-33V115.6z"/></g>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </a>
                                @endif

                                @if ( $receiver->status == 'Active' )
                                <a href="{{ route('organizationreceivers.block', [$org->slug, $receiver]) }}" class="btn btn-icon btn-bg-light btn-active-color-primary bg-warning btn-sm me-1">
                                    <!--begin::Svg Icon | path: icons/duotone/Communication/Write.svg-->
                                    <span class="svg-icon svg-icon-3">
                                        <svg width="24px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
                                            <g><g><path d="M500,10C229.4,10,10,229.4,10,500c0,270.7,219.4,490,490,490c270.6,0,490-219.4,490-490C990,229.4,770.6,10,500,10z M112.1,500c0-214.2,173.6-387.9,387.9-387.9c93.3,0,178.9,33,245.9,87.9L200,745.8C145.1,678.9,112.1,593.3,112.1,500L112.1,500z M500,887.8c-93.3,0-178.9-33-245.8-87.9L800,254.2c54.9,67,87.9,152.5,87.9,245.8C887.9,714.2,714.2,887.8,500,887.8L500,887.8z"/></g></g>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </a>

                                <a href="{{ route('organizationreceivers.show', [$org->slug, $receiver]) }}" class="btn btn-icon btn-bg-light btn-active-color-primary bg-warning btn-sm me-1">
                                    <!--begin::Svg Icon | path: icons/duotone/Communication/Write.svg-->
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg" enable-background="new 0 0 32 32" height="24px" id="svg2" version="1.1" viewBox="0 0 32 32" width="24px" xml:space="preserve"><g id="background"><rect fill="none" height="32" width="32"/></g><g id="view"><circle cx="16" cy="16" r="6"/>
                                            <path d="M16,6C6,6,0,15.938,0,15.938S6,26,16,26s16-10,16-10S26,6,16,6z M16,24c-8.75,0-13.5-8-13.5-8S7.25,8,16,8s13.5,8,13.5,8   S24.75,24,16,24z"/></g>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </a>

                                <a href="{{ route('organizationreceiversinfo.edit', [$org->slug, $receiver]) }}" class="btn btn-icon btn-bg-light btn-active-color-primary bg-warning btn-sm me-1">
                                    <!--begin::Svg Icon | path: icons/duotone/Communication/Write.svg-->
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)" />
                                            <path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </a>
                                @endif

                                <!-- <a href="{{ route('organizationreceivers.edit', [$org->slug, $receiver]) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)" />
                                            <path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        </svg>
                                    </span>
                                </a> -->
                                @csrf
                                <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary bg-danger btn-sm">
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero" />
                                                <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3" />
                                            </g>
                                        </svg>
                                    </span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <!--end::Table body-->
            </table>
            <!--end::Table-->
        </div>
        <!--end::Table container-->
    </div>
    <!--begin::Body-->
</div>

@endsection
