@extends('vendor@template::admin.layouts.master', ['page' => 'index'])

@section('breadcrumbs')
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold">
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a href="{{ route('admin.child.manage') }}" class="text-white text-hover-secondary">
                <i class="ki-outline ki-home text-white fs-3"></i>
            </a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a href="{{ route('admin.child.manage') }}" class="text-white text-hover-secondary">Infantes Inscritos</a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a class="text-white text-hover-secondary">Detalle</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            DETALLE DEL INFANTE
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">{{ $child->full_name }}</span>
        </h1>
    </div>
    <div class="d-flex gap-4 gap-lg-13">
        <a href="{{ route('admin.child.manage') }}" class="btn btn-light">
            <i class="ki-outline ki-arrow-left fs-2"></i> Volver
        </a>
    </div>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content">

        <div class="d-flex flex-column flex-lg-row">

            <!--begin::Sidebar-->
            <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body">
                        <div class="d-flex flex-center flex-column py-5">
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
                            <a href="#" class="fs-3 text-gray-800 text-center fw-bold mb-3">{{ $child->full_name }}</a>
                            <div class="fs-5 fw-bold text-muted mb-6">
                                {{ $child->birth_date ? $child->age_readable : 'Sin fecha de nacimiento' }}
                            </div>
                            <div class="mb-9">
                                @php
                                    $activeEnrollment = $child->activeEnrollment;
                                @endphp
                                <div class="badge badge-lg badge-light-primary d-block">{{ ($activeEnrollment && $activeEnrollment->status) ? $activeEnrollment->status->label() : '-' }}</div>
                            </div>
                        </div>                      
                    </div>
                </div>
            </div>
            <!--end::Sidebar-->

            <!--begin::Content-->
            <div class="flex-lg-row-fluid ms-lg-15">

                <!--begin::Ficha de Identificación-->
                        <div class="card card-flush mb-6 mb-xl-9">
                            <div class="card-header mt-6">
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">Ficha de Identificación</h2>
                                    <div class="fs-6 fw-semibold text-muted">
                                        Datos generales del Infante
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-9 pt-4">
                                <h6 class="mb-5 fw-bolder text-primary">Datos registrados</h6>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Apellido Paterno:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">{{ $child->paternal_last_name ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Apellido Materno:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">{{ $child->maternal_last_name ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Nombre(s):</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">{{ $child->first_name ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Fecha de Nacimiento:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ $child->birth_date ? $child->birth_date->locale('es')->translatedFormat('d \de F \de Y') : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Género:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            @if($child->gender)
                                                {{ $child->gender === \App\Containers\AppSection\User\Enums\Gender::MALE ? 'Masculino' : ($child->gender === \App\Containers\AppSection\User\Enums\Gender::FEMALE ? 'Femenino' : 'No Especificado') }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Dirección:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ $child->address ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Ciudad:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ $child->city ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Municipio:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ $child->municipality ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Departamento:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ $child->state ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                @if($child->language)
                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Idioma:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">{{ $child->language }}</p>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                        <!--end::Ficha de Identificación-->

                        <!--begin::Ficha Médica-->
                        <div class="card card-flush mb-6 mb-xl-9">
                            <div class="card-header mt-6">
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">Ficha Médica</h2>
                                    <div class="fs-6 fw-semibold text-muted">
                                        Información médica del Infante
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-9 pt-4">

                                @if($child->medicalRecord)
                                <h6 class="mb-5 mt-10 fw-bolder text-primary">Datos registrados</h6>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Peso:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ $child->medicalRecord->weight ? number_format($child->medicalRecord->weight, 2) . ' kg' : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Talla:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ $child->medicalRecord->height ? number_format($child->medicalRecord->height, 2) . ' cm' : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Seguro Médico:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ $child->medicalRecord->has_insurance ? ($child->medicalRecord->insurance_details ?? 'Sí') : 'No' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Alergias:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ $child->medicalRecord->has_allergies ? ($child->medicalRecord->allergies_details ?? 'Sí') : 'No' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Tratamiento Médico:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ $child->medicalRecord->has_medical_treatment ? ($child->medicalRecord->medical_treatment_details ?? 'Sí') : 'No' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Tratamiento Psicológico:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ $child->medicalRecord->has_psychological_treatment ? ($child->medicalRecord->psychological_treatment_details ?? 'Sí') : 'No' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Déficits:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-control form-control-plaintext">
                                            @if($child->medicalRecord->has_deficit && $child->medicalRecord->hasAnyDeficit())
                                                @if($child->medicalRecord->deficit_auditory)
                                                    <span class="badge badge-light-warning me-2">Auditivo</span>
                                                @endif
                                                @if($child->medicalRecord->deficit_visual)
                                                    <span class="badge badge-light-danger me-2">Visual</span>
                                                @endif
                                                @if($child->medicalRecord->deficit_tactile)
                                                    <span class="badge badge-light-info me-2">Táctil</span>
                                                @endif
                                                @if($child->medicalRecord->deficit_motor)
                                                    <span class="badge badge-light-primary me-2">Motor</span>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Enfermedades:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ $child->medicalRecord->has_illness ? ($child->medicalRecord->illness_details ?? 'Sí') : 'No' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Habilidades Destacadas:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ $child->medicalRecord->outstanding_skills ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Problemas de Nutrición:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ $child->medicalRecord->nutritional_problems ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Otras Observaciones:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ $child->medicalRecord->other_observations ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-info d-flex align-items-center p-5 mb-10">
                                    <i class="ki-outline ki-information-5 fs-2hx text-info me-4"></i>
                                    <div class="d-flex flex-column">
                                        <h4 class="mb-1 text-info">Sin registro médico</h4>
                                        <span>No se ha registrado información médica para este infante.</span>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                        <!--end::Ficha Médica-->

                        <!--begin::Ficha Social-->
                        <div class="card card-flush mb-6 mb-xl-9">
                            <div class="card-header mt-6">
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">Ficha Social</h2>
                                    <div class="fs-6 fw-semibold text-muted">
                                        Información social y económica del Infante
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-9 pt-4">
                                <!--begin::Integrantes de Familia-->
                                <h6 class="mb-5 fw-bolder text-primary">Integrantes de Familia</h6>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Infante a cargo de:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($child->socialRecord && $child->socialRecord->guardian_type) ? $child->socialRecord->guardian_type->label() : '-' }}
                                        </p>
                                    </div>
                                </div>

                                @if($child->socialRecord && $child->socialRecord->familyMembers && $child->socialRecord->familyMembers->count() > 0)
                                <div class="row">
                                    <div class="col-md-12 d-flex align-items-center mt-5">
                                        <div class="flex-grow-1">
                                            <div class="table-responsive">
                                                <table class="table table-bordered align-middle gs-0 gy-4 mb-3" aria-describedby="table">
                                                    <thead>
                                                    <tr class="border-bottom bg-light fs-6 fw-bold text-muted">
                                                        <th class="ps-4 rounded-start">Nombre</th>
                                                        <th class="text-start">Relación</th>
                                                        <th class="text-end rounded-end pe-4">Opciones</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($child->socialRecord->familyMembers as $member)
                                                        @php
                                                            $memberData = [
                                                                'first_name' => $member->first_name,
                                                                'last_name' => $member->last_name,
                                                                'birth_date' => $member->birth_date ? $member->birth_date->format('d/m/Y') : null,
                                                                'age' => $member->age,
                                                                'gender' => $member->gender?->label(),
                                                                'kinship' => $member->kinship?->label(),
                                                                'marital_status' => $member->marital_status?->label(),
                                                                'education_level' => $member->education_level,
                                                                'profession' => $member->profession,
                                                                'phone' => $member->phone,
                                                                'has_income' => $member->has_income,
                                                                'workplace' => $member->workplace,
                                                                'income_type' => $member->income_type?->label(),
                                                                'total_income' => $member->total_income,
                                                            ];
                                                        @endphp
                                                        <tr class="member-row" style="cursor: pointer;">
                                                            <td class="ps-4">
                                                                <span class="text-gray-800 fw-bold">{{ $member->full_name }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="text-gray-600">
                                                                    {{ $member->kinship ? $member->kinship->label() : '-' }}
                                                                </span>
                                                            </td>
                                                            <td class="text-end pe-4">
                                                                <button type="button" class="btn btn-sm btn-light-primary view-member-btn" 
                                                                        data-member='@json($memberData)'>
                                                                    <i class="ki-outline ki-eye fs-3"></i> Ver
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-info d-flex align-items-center p-5 mb-10">
                                    <i class="ki-outline ki-information-5 fs-2hx text-info me-4"></i>
                                    <div class="d-flex flex-column">
                                        <h4 class="mb-1 text-info">Sin integrantes registrados</h4>
                                        <span>No se han registrado integrantes de familia para este infante.</span>
                                    </div>
                                </div>
                                @endif
                                <!--end::Integrantes de Familia-->

                                <div class="separator separator-dashed border-muted my-8"></div>
                                <h6 class="mb-5 fw-bolder text-primary">Egresos Mensuales</h6>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Total de Egresos:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext fw-bold">
                                            {{ ($child->socialRecord) ? 'Bs. ' . number_format($child->socialRecord->total_expenses, 2) : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted my-8"></div>
                                <h6 class="mb-5 fw-bolder text-primary">Situación de Habitabilidad</h6>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Tipo de Vivienda:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($child->socialRecord && $child->socialRecord->housing_type) ? $child->socialRecord->housing_type->label() : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Tenencia:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($child->socialRecord && $child->socialRecord->housing_tenure) ? $child->socialRecord->housing_tenure->label() : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Material de Paredes:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($child->socialRecord && $child->socialRecord->housing_wall_material) ? $child->socialRecord->housing_wall_material->label() : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Material de Piso:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($child->socialRecord && $child->socialRecord->housing_floor_material) ? $child->socialRecord->housing_floor_material->label() : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Acabado:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($child->socialRecord && $child->socialRecord->housing_finish) ? $child->socialRecord->housing_finish->label() : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Número de Dormitorios:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($child->socialRecord && $child->socialRecord->housing_bedrooms) ? $child->socialRecord->housing_bedrooms : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Ambientes:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            @if($child->socialRecord && $child->socialRecord->housing_rooms && is_array($child->socialRecord->housing_rooms) && count($child->socialRecord->housing_rooms) > 0)
                                                {{ implode(', ', array_map('ucfirst', $child->socialRecord->housing_rooms)) }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Servicios Públicos:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            @if($child->socialRecord && $child->socialRecord->housing_utilities && is_array($child->socialRecord->housing_utilities) && count($child->socialRecord->housing_utilities) > 0)
                                                {{ implode(', ', array_map(function($util) {
                                                    $enum = \App\Containers\Monitoring\Child\Enums\HousingUtility::tryFrom($util);
                                                    return $enum ? $enum->label() : ucfirst(str_replace('_', ' ', $util));
                                                }, $child->socialRecord->housing_utilities)) }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Tenencia de Mascotas:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($child->socialRecord && $child->socialRecord->pets) ? $child->socialRecord->pets : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted my-8"></div>
                                <h6 class="mb-5 fw-bolder text-primary">Transporte</h6>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Tipo de Transporte:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($child->socialRecord && $child->socialRecord->transport_type) ? $child->socialRecord->transport_type->label() : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Tiempo de Viaje:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($child->socialRecord && $child->socialRecord->travel_time) ? $child->socialRecord->travel_time->label() : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted my-8"></div>
                                <h6 class="mb-5 fw-bolder text-primary">Historia y Valoración</h6>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-start justify-content-end text-end pt-4">
                                        <label class="fw-semibold fs-7 text-gray-600">Historia Familiar:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($child->socialRecord && $child->socialRecord->family_history) ? $child->socialRecord->family_history : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-start justify-content-end text-end pt-4">
                                        <label class="fw-semibold fs-7 text-gray-600">Valoración Profesional:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($child->socialRecord && $child->socialRecord->professional_assessment) ? $child->socialRecord->professional_assessment : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-start justify-content-end text-end pt-4">
                                        <label class="fw-semibold fs-7 text-gray-600">Historial de Incidentes:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($child->socialRecord && $child->socialRecord->incident_history) ? $child->socialRecord->incident_history : '-' }}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!--end::Ficha Social-->

                        <!--begin::Ficha de Inscripción-->
                        @php
                            $activeEnrollment = $child->activeEnrollment;
                        @endphp
                        <div class="card card-flush mb-6 mb-xl-9">
                            <div class="card-header mt-6">
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">Ficha de Inscripción</h2>
                                    <div class="fs-6 fw-semibold text-muted">
                                        Información de inscripción del Infante
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-9 pt-4">
                                <h6 class="mb-5 fw-bolder text-primary">Datos registrados</h6>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Estado:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($activeEnrollment && $activeEnrollment->status) ? $activeEnrollment->status->label() : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Centro Infantil:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($activeEnrollment && $activeEnrollment->childcareCenter) ? $activeEnrollment->childcareCenter->name : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Sala/Grupo:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($activeEnrollment && $activeEnrollment->room) ? $activeEnrollment->room->name : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Fecha de Inscripción:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($activeEnrollment && $activeEnrollment->enrollment_date) ? $activeEnrollment->enrollment_date->locale('es')->translatedFormat('d \de F \de Y') : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                        <label class="fw-semibold fs-7 text-gray-600">Fecha de Retiro:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($activeEnrollment && $activeEnrollment->withdrawal_date) ? $activeEnrollment->withdrawal_date->locale('es')->translatedFormat('d \de F \de Y') : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted"></div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-start justify-content-end text-end pt-4">
                                        <label class="fw-semibold fs-7 text-gray-600">Observaciones:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="form-control form-control-plaintext">
                                            {{ ($activeEnrollment && $activeEnrollment->observations) ? $activeEnrollment->observations : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="separator separator-dashed border-muted my-8"></div>
                                <h6 class="mb-5 fw-bolder text-primary">Documentos</h6>

                                @php
                                    $documents = [
                                        [
                                            'label' => 'Carta de Solicitud de Admisión',
                                            'file' => $activeEnrollment?->admissionRequestFile,
                                            'field' => 'file_admission_request'
                                        ],
                                        [
                                            'label' => 'Compromiso',
                                            'file' => $activeEnrollment?->commitmentFile,
                                            'field' => 'file_commitment'
                                        ],
                                        [
                                            'label' => 'Certificado de Nacimiento',
                                            'file' => $activeEnrollment?->birthCertificateFile,
                                            'field' => 'file_birth_certificate'
                                        ],
                                        [
                                            'label' => 'Carnet de Vacunas',
                                            'file' => $activeEnrollment?->vaccinationCardFile,
                                            'field' => 'file_vaccination_card'
                                        ],
                                        [
                                            'label' => 'Documento de Identidad (Padre/Madre)',
                                            'file' => $activeEnrollment?->parentIdFile,
                                            'field' => 'file_parent_id'
                                        ],
                                        [
                                            'label' => 'Certificado de Constancia Laboral',
                                            'file' => $activeEnrollment?->workCertificateFile,
                                            'field' => 'file_work_certificate'
                                        ],
                                        [
                                            'label' => 'Recibo de Agua y Luz',
                                            'file' => $activeEnrollment?->utilityBillFile,
                                            'field' => 'file_utility_bill'
                                        ],
                                        [
                                            'label' => 'Croquis de Domicilio Actual',
                                            'file' => $activeEnrollment?->homeSketchFile,
                                            'field' => 'file_home_sketch'
                                        ],
                                        [
                                            'label' => 'Constancia de Vivienda',
                                            'file' => $activeEnrollment?->residenceCertificateFile,
                                            'field' => 'file_residence_certificate'
                                        ],
                                        [
                                            'label' => 'Autorización de Recojo',
                                            'file' => $activeEnrollment?->pickupAuthorizationFile,
                                            'field' => 'file_pickup_authorization'
                                        ],
                                    ];
                                @endphp

                                @foreach($documents as $index => $doc)
                                    @if($index > 0)
                                    <div class="separator separator-dashed border-muted"></div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                            <label class="fw-semibold fs-7 text-gray-600">{{ $doc['label'] }}:</label>
                                        </div>
                                        <div class="col-md-8">
                                            @if($doc['file'])
                                                <div class="d-flex align-items-center">
                                                    <div class="py-4 flex-grow-1">
                                                        <a href="{{ $doc['file']->getDownloadUrl() }}" 
                                                           target="_blank" 
                                                           class="text-gray-800 text-hover-primary fw-semibold">
                                                            <i class="ki-outline ki-file fs-4 me-2"></i>
                                                            {{ $doc['file']->original_name ?? $doc['file']->name }}
                                                        </a>
                                                        <span class="text-muted fs-8 ms-2">
                                                            ({{ $doc['file']->human_readable_size }})
                                                        </span>
                                                    </div>
                                                    <a href="{{ $doc['file']->getDownloadUrl() }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-light-primary"
                                                       title="Descargar">
                                                        <i class="ki-outline ki-arrow-down p-0 fs-3"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <p class="form-control form-control-plaintext">-</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        <!--end::Ficha de Inscripción-->

            </div>
            <!--end::Content-->

        </div>

    </div>
</div>
@endsection

@section('modals')
<!-- Modal: Detalle de Miembro de Familia -->
<div class="modal fade" id="kt_modal_family_member" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Detalle del Miembro de Familia</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15">
                <div id="kt_modal_family_member_content">
                    <!-- Los datos se cargarán aquí dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
@endsection

@section('scripts')
<script src="{{ asset('themes/admin/js/custom/child/show.js') }}"></script>
@endsection
