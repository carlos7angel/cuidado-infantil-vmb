@extends('vendor@template::admin.layouts.master', ['page' => 'index'])

@section('breadcrumbs')
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold">
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a href="{{ route('admin.childcare_center.manage') }}" class="text-white text-hover-secondary">
                <i class="ki-outline ki-home text-white fs-3"></i>
            </a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a href="{{ route('admin.childcare_center.manage') }}" class="text-white text-hover-secondary">Centros de Cuidado Infantil</a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a class="text-white text-hover-secondary">{{ $childcare_center->id ? 'Editar' : 'Nuevo' }}</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            {{ $childcare_center->id ? 'EDITAR CENTRO' : 'NUEVO CENTRO' }}
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">{{ $page_title }}</span>
        </h1>
    </div>
    <div class="d-flex gap-4 gap-lg-13">
    </div>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content">

        <form id="kt_childcare_center_form" class="form d-flex flex-column flex-lg-row" method="post" 
              action="{{ $childcare_center->id ? route('admin.childcare_center.update', $childcare_center->id) : route('admin.childcare_center.store') }}" 
              enctype="multipart/form-data" autocomplete="off">
            @csrf
            @if($childcare_center->id)
                <input type="hidden" name="id" value="{{ $childcare_center->id }}">
            @endif

            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 me-lg-10 mb-10">

                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Información General</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-10 fv-row">
                            <label class="required form-label">Nombre del Centro</label>
                            <input type="text" name="name" class="form-control mb-2" placeholder="Ingrese el nombre del centro" 
                                   value="{{ old('name', $childcare_center->name) }}" required />
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-10 fv-row">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" rows="3" name="description" placeholder="Ingrese una descripción">{{ old('description', $childcare_center->description) }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-10 row g-9">
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Tipo</label>
                                <input type="text" class="form-control" name="type" placeholder="Ej: Privado, Público" 
                                       value="{{ old('type', $childcare_center->type) }}" />
                                @error('type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Fecha de Fundación</label>
                                <input class="form-control datepicker_flatpickr" name="date_founded" 
                                       placeholder="Seleccione una fecha" 
                                       value="{{ old('date_founded', $childcare_center->date_founded ? $childcare_center->date_founded->format('d/m/Y') : '') }}" />
                                @error('date_founded')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-0 fv-row">
                            <label class="form-label">Dirección</label>
                            <textarea class="form-control" rows="2" name="address" placeholder="Ingrese la dirección">{{ old('address', $childcare_center->address) }}</textarea>
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Ubicación</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-10 fv-row">
                            <label class="required form-label">Departamento</label>
                            <select class="form-select mb-2" data-control="select2" data-hide-search="true" 
                                    data-placeholder="Seleccione un departamento" name="state" required>
                                <option></option>
                                <option value="La Paz" {{ old('state', $childcare_center->state) == 'La Paz' ? 'selected' : '' }}>La Paz</option>
                                <option value="Cochabamba" {{ old('state', $childcare_center->state) == 'Cochabamba' ? 'selected' : '' }}>Cochabamba</option>
                                <option value="Santa Cruz" {{ old('state', $childcare_center->state) == 'Santa Cruz' ? 'selected' : '' }}>Santa Cruz</option>
                                <option value="Potosí" {{ old('state', $childcare_center->state) == 'Potosí' ? 'selected' : '' }}>Potosí</option>
                                <option value="Oruro" {{ old('state', $childcare_center->state) == 'Oruro' ? 'selected' : '' }}>Oruro</option>
                                <option value="Beni" {{ old('state', $childcare_center->state) == 'Beni' ? 'selected' : '' }}>Beni</option>
                                <option value="Pando" {{ old('state', $childcare_center->state) == 'Pando' ? 'selected' : '' }}>Pando</option>
                                <option value="Chuquisaca" {{ old('state', $childcare_center->state) == 'Chuquisaca' ? 'selected' : '' }}>Chuquisaca</option>
                                <option value="Tarija" {{ old('state', $childcare_center->state) == 'Tarija' ? 'selected' : '' }}>Tarija</option>
                            </select>
                            @error('state')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-10 row g-9">
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Ciudad</label>
                                <input type="text" class="form-control" name="city" placeholder="Ingrese la ciudad" 
                                       value="{{ old('city', $childcare_center->city) }}" />
                                @error('city')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required form-label">Municipio</label>
                                <input type="text" class="form-control" name="municipality" placeholder="Ingrese el municipio" 
                                       value="{{ old('municipality', $childcare_center->municipality) }}" required />
                                @error('municipality')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Contacto</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-10 fv-row">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="phone" class="form-control mb-2" placeholder="Ingrese el teléfono" 
                                   value="{{ old('phone', $childcare_center->phone) }}" />
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-10 fv-row">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="email" class="form-control mb-2" placeholder="correo@ejemplo.com" 
                                   value="{{ old('email', $childcare_center->email) }}" />
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-0 fv-row">
                            <label class="form-label">Redes Sociales</label>
                            <input type="text" name="social_media" class="form-control mb-2" placeholder="URL de redes sociales" 
                                   value="{{ old('social_media', $childcare_center->social_media) }}" />
                            @error('social_media')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Persona de Contacto</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-10 fv-row">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="contact_name" class="form-control mb-2" placeholder="Nombre completo" 
                                   value="{{ old('contact_name', $childcare_center->contact_name) }}" />
                            @error('contact_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-10 row g-9">
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="contact_phone" placeholder="Teléfono de contacto" 
                                       value="{{ old('contact_phone', $childcare_center->contact_phone) }}" />
                                @error('contact_phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" name="contact_email" placeholder="correo@ejemplo.com" 
                                       value="{{ old('contact_email', $childcare_center->contact_email) }}" />
                                @error('contact_email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7">

                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Logo</h2>
                        </div>
                    </div>
                    <div class="card-body text-center pt-0">
                        <style>
                            .image-input-placeholder { background-image: url("{{ asset('themes/common/media/svg/files/blank-image.svg') }}"); }
                            @if($childcare_center->logo)
                            .image-input-wrapper { background-image: url("{{ asset($childcare_center->logo) }}"); }
                            @endif
                        </style>
                        <div class="image-input image-input-outline image-input-placeholder mb-3 fv-row" data-kt-image-input="true">
                            <div class="image-input-wrapper w-200px h-200px bgi-size-contain bgi-position-center"></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" 
                                   data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cambiar">
                                <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                                <input type="file" name="logo" accept=".png, .jpg, .jpeg" />
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" 
                                  data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancelar">
                                <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" 
                                  data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remover">
                                <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                            <input type="hidden" name="logo_remove" />
                        </div>
                        <div class="text-muted fs-7">Logo del centro. Formatos aceptados *.png, *.jpg y *.jpeg</div>
                        @error('logo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card card-flush py-4">
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column gap-5">
                            <button type="submit" id="kt_childcare_center_submit" class="btn btn-primary">
                                <span class="indicator-label">{{ $childcare_center->id ? 'Actualizar' : 'Guardar' }}</span>
                                <span class="indicator-progress">Espere por favor...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                            <a href="{{ route('admin.childcare_center.manage') }}" class="btn btn-light">Cancelar</a>
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
    <script src="{{ asset('themes/admin/js/custom/childcare_center/form.js') }}"></script>
@endsection
