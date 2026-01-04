@extends('vendor@template::admin.layouts.master', ['page' => 'index'])

@section('breadcrumbs')
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold">
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a href="{{ route('admin.educator.manage') }}" class="text-white text-hover-secondary">
                <i class="ki-outline ki-home text-white fs-3"></i>
            </a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a href="{{ route('admin.educator.manage') }}" class="text-white text-hover-secondary">Educadores</a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a class="text-white text-hover-secondary">{{ $educator->id ? 'Editar' : 'Nuevo' }}</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            {{ $educator->id ? 'EDITAR EDUCADOR' : 'NUEVO EDUCADOR' }}
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">{{ $page_title }}</span>
        </h1>
    </div>
    <div class="d-flex gap-4 gap-lg-13">
    </div>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content">

        <form id="kt_educator_form" class="form d-flex flex-column flex-lg-row" method="post" 
              action="{{ $educator->id ? route('admin.educator.update', $educator->id) : route('admin.educator.store') }}" 
              autocomplete="off">
            @csrf
            @if($educator->id)
                <input type="hidden" name="id" value="{{ $educator->id }}">
            @endif

            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 me-lg-10 mb-10">

                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Información Personal</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        @if(!$educator->id)
                        <div class="mb-10 fv-row">
                            <label class="required form-label">Correo electrónico</label>
                            <input type="email" name="email" class="form-control mb-2" placeholder="correo@ejemplo.com" 
                                   value="{{ old('email') }}" required />
                            <div class="text-muted fs-7">Se creará una cuenta de usuario con este correo y una contraseña aleatoria.</div>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        @else
                        <div class="mb-10 fv-row">
                            <label class="form-label">Correo electrónico</label>
                            <input type="text" class="form-control mb-2" value="{{ $educator->user ? $educator->user->email : '-' }}" disabled />
                            <div class="text-muted fs-7">El correo electrónico no se puede modificar después de crear la cuenta.</div>
                        </div>
                        @endif
                        <div class="mb-10 row g-9">
                            <div class="col-md-6 fv-row">
                                <label class="required form-label">Nombre</label>
                                <input type="text" name="first_name" class="form-control mb-2" placeholder="Ingrese el nombre" 
                                       value="{{ old('first_name', $educator->first_name) }}" required />
                                @error('first_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required form-label">Apellido</label>
                                <input type="text" name="last_name" class="form-control mb-2" placeholder="Ingrese el apellido" 
                                       value="{{ old('last_name', $educator->last_name) }}" required />
                                @error('last_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-10 row g-9">
                            <div class="col-md-6 fv-row">
                                <label class="required form-label">Género</label>
                                <select class="form-select mb-2" data-control="select2" data-hide-search="true" 
                                        data-placeholder="Seleccione un género" name="gender" required>
                                    <option></option>
                                    <option value="masculino" {{ old('gender', $educator->gender?->value) == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="femenino" {{ old('gender', $educator->gender?->value) == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                    <option value="no_especificado" {{ old('gender', $educator->gender?->value) == 'no_especificado' ? 'selected' : '' }}>No Especificado</option>
                                </select>
                                @error('gender')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required form-label">Fecha de Nacimiento</label>
                                <input class="form-control datepicker_flatpickr" name="birth" 
                                       placeholder="Seleccione una fecha" 
                                       value="{{ old('birth', $educator->birth ? $educator->birth->format('d/m/Y') : '') }}" required />
                                @error('birth')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-10 row g-9">
                            <div class="col-md-4 fv-row">
                                <label class="form-label">DNI</label>
                                <input type="text" class="form-control" name="dni" placeholder="Ingrese el DNI" 
                                       value="{{ old('dni', $educator->dni) }}" />
                                @error('dni')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="form-label">Departamento</label>
                                <select class="form-select mb-2" data-control="select2" data-hide-search="true" 
                                        data-placeholder="Seleccione un departamento" name="state">
                                    <option></option>
                                    <option value="La Paz" {{ old('state', $educator->state) == 'La Paz' ? 'selected' : '' }}>La Paz</option>
                                    <option value="Cochabamba" {{ old('state', $educator->state) == 'Cochabamba' ? 'selected' : '' }}>Cochabamba</option>
                                    <option value="Santa Cruz" {{ old('state', $educator->state) == 'Santa Cruz' ? 'selected' : '' }}>Santa Cruz</option>
                                    <option value="Potosí" {{ old('state', $educator->state) == 'Potosí' ? 'selected' : '' }}>Potosí</option>
                                    <option value="Oruro" {{ old('state', $educator->state) == 'Oruro' ? 'selected' : '' }}>Oruro</option>
                                    <option value="Beni" {{ old('state', $educator->state) == 'Beni' ? 'selected' : '' }}>Beni</option>
                                    <option value="Pando" {{ old('state', $educator->state) == 'Pando' ? 'selected' : '' }}>Pando</option>
                                    <option value="Chuquisaca" {{ old('state', $educator->state) == 'Chuquisaca' ? 'selected' : '' }}>Chuquisaca</option>
                                    <option value="Tarija" {{ old('state', $educator->state) == 'Tarija' ? 'selected' : '' }}>Tarija</option>
                                </select>
                                @error('state')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="phone" placeholder="Ingrese el teléfono" 
                                       value="{{ old('phone', $educator->phone) }}" />
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Centros de Cuidado Infantil</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-10 fv-row">
                            <label class="required form-label">Seleccione los Centros (mínimo uno)</label>
                            <input type="hidden" name="childcare_center_ids" id="childcare_center_ids_validator" value="" />
                            <div class="form-check form-check-custom form-check-solid mt-3">
                                @php
                                    $selectedCenters = old('childcare_center_ids', $educator->id ? $educator->childcareCenters->pluck('id')->toArray() : []);
                                @endphp
                                @foreach($childcare_centers as $center)
                                    <div class="form-check mb-5">
                                        <input class="form-check-input childcare-center-checkbox" type="checkbox" 
                                               name="childcare_center_ids[]" 
                                               value="{{ $center->id }}" 
                                               id="center_{{ $center->id }}"
                                               {{ in_array($center->id, $selectedCenters) ? 'checked' : '' }} />
                                        <label class="form-check-label" for="center_{{ $center->id }}">
                                            <div class="fw-bold text-gray-800">{{ $center->name }}</div>
                                            @if($center->municipality)
                                                <div class="text-muted fs-7">{{ $center->municipality }}, {{ $center->state }}</div>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('childcare_center_ids')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

            <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7">

                <div class="card card-flush py-4">
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column gap-5">
                            <button type="submit" id="kt_educator_submit" class="btn btn-primary">
                                <span class="indicator-label">{{ $educator->id ? 'Actualizar' : 'Guardar' }}</span>
                                <span class="indicator-progress">Espere por favor...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                            <a href="{{ route('admin.educator.manage') }}" class="btn btn-light">Cancelar</a>
                        </div>
                    </div>
                </div>

            </div>

        </form>

    </div>
</div>
@endsection

@section('styles')
    <link href="{{ asset('themes/common/plugins/custom/flatpickr/flatpickr.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    <script src="{{ asset('themes/common/plugins/custom/flatpickr/flatpickr.bundle.js') }}"></script>
    <script src="{{ asset('themes/admin/js/custom/educator/form.js') }}"></script>
@endsection

