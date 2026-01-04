<!--begin::Sidebar-->
<div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
    <!--begin::Card-->
    <div class="card mb-5 mb-xl-8">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Summary-->
            <!--begin::User Info-->
            <div class="d-flex flex-center flex-column py-5">
                <!--begin::Avatar-->
                <div class="symbol symbol-100px symbol-circle mb-7">
                    @php
                        $imageName = 'user_blank.png';
                        if ($child->gender) {
                            if ($child->gender === \App\Containers\AppSection\User\Enums\Gender::MALE) {
                                $imageName = 'boy_1.png';
                            } elseif ($child->gender === \App\Containers\AppSection\User\Enums\Gender::FEMALE) {
                                $imageName = 'girl_1.png';
                            }
                        }
                    @endphp
                    <img src="{{ asset('/themes/common/media/images/' . $imageName) }}" alt="image" />
                </div>
                <!--end::Avatar-->
                <!--begin::Name-->
                <a href="#" class="fs-3 text-gray-800 text-hover-primary text-center fw-bold mb-3">{{ $child->full_name }}</a>
                <!--end::Name-->
                <!--begin::Position-->
                <div class="mb-9">
                    <!--begin::Badge-->
                    @if($child->birth_date)
                        <div class="badge badge-lg badge-light-primary d-inline">{{ $child->age_readable }}</div>
                    @else
                        <div class="badge badge-lg badge-light-secondary d-inline">Sin fecha de nacimiento</div>
                    @endif
                    <!--end::Badge-->
                    @if(isset($showEnrollmentStatus) && $showEnrollmentStatus)
                        @php
                            $activeEnrollment = $child->activeEnrollment;
                        @endphp
                        <div class="badge badge-lg badge-light-primary d-block mt-2">{{ ($activeEnrollment && $activeEnrollment->status) ? $activeEnrollment->status->label() : '-' }}</div>
                    @endif
                </div>
                <!--end::Position-->
            </div>
            <!--end::User Info-->
            <!--end::Summary-->
            <!--begin::Details toggle-->
            <div class="d-flex flex-stack fs-4 py-3">
                <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">Detalle 
                <span class="ms-2 rotate-180">
                    <i class="ki-outline ki-down fs-3"></i>
                </span></div>
                <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit customer details">
                    
                </span>
            </div>
            <!--end::Details toggle-->
            <div class="separator"></div>
            <!--begin::Details content-->
            <div id="kt_user_view_details" class="collapse show">
                <div class="pb-5 fs-6">
                    <!--begin::Details item-->
                    <div class="fw-bold mt-5">Fecha de Nacimiento</div>
                    <div class="text-gray-600">
                        {{ $child->birth_date ? $child->birth_date->format('d/m/Y') : '-' }}
                    </div>
                    <!--begin::Details item-->
                    <div class="fw-bold mt-5">Centro de Cuidado</div>
                    <div class="text-gray-600">
                        {{ $child->activeEnrollment?->childcareCenter?->name ?? '-' }}
                    </div>
                    <!--begin::Details item-->
                    <!--begin::Details item-->
                    <div class="fw-bold mt-5">Sala/Grupo</div>
                    <div class="text-gray-600">
                        {{ $child->activeEnrollment?->room?->name ?? '-' }}
                    </div>
                    <!--begin::Details item-->
                    <!--begin::Details item-->
                    <div class="fw-bold mt-5">Género</div>
                    <div class="text-gray-600">
                        @if($child->gender)
                            {{ $child->gender->label() }}
                        @else
                            -
                        @endif
                    </div>
                    <!--begin::Details item-->
                </div>
            </div>
            <!--end::Details content-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
<!--end::Sidebar-->

