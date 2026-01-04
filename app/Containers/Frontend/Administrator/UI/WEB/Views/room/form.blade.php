@extends('vendor@template::admin.layouts.master', ['page' => 'index'])

@section('breadcrumbs')
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold">
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a href="{{ route('admin.room.manage') }}" class="text-white text-hover-secondary">
                <i class="ki-outline ki-home text-white fs-3"></i>
            </a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a href="{{ route('admin.room.manage') }}" class="text-white text-hover-secondary">Grupos/Salas</a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a class="text-white text-hover-secondary">{{ $room->id ? 'Editar' : 'Nuevo' }}</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            {{ $room->id ? 'EDITAR GRUPO/SALA' : 'NUEVO GRUPO/SALA' }}
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">{{ $page_title }}</span>
        </h1>
    </div>
    <div class="d-flex gap-4 gap-lg-13">
    </div>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content">

        <form id="kt_room_form" class="form d-flex flex-column flex-lg-row" method="post" 
              action="{{ $room->id ? route('admin.room.update', $room->id) : route('admin.room.store') }}" 
              autocomplete="off">
            @csrf
            @if($room->id)
                <input type="hidden" name="id" value="{{ $room->id }}">
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
                            <label class="required form-label">Centro de Cuidado Infantil</label>
                            <select class="form-select mb-2" data-control="select2" data-hide-search="false" 
                                    data-placeholder="Seleccione un centro" name="childcare_center_id" required>
                                <option></option>
                                @foreach($childcare_centers as $center)
                                    <option value="{{ $center->id }}" 
                                            {{ old('childcare_center_id', $room->childcare_center_id) == $center->id ? 'selected' : '' }}>
                                        {{ $center->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('childcare_center_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-10 fv-row">
                            <label class="required form-label">Nombre del Grupo/Sala</label>
                            <input type="text" name="name" class="form-control mb-2" placeholder="Ingrese el nombre del grupo/sala" 
                                   value="{{ old('name', $room->name) }}" required />
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-10 fv-row">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" rows="3" name="description" placeholder="Ingrese una descripción">{{ old('description', $room->description) }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-10 row g-9">
                            <div class="col-md-6 fv-row">
                                <label class="required form-label">Capacidad</label>
                                <input type="number" class="form-control" name="capacity" placeholder="Ej: 20" 
                                       value="{{ old('capacity', $room->capacity) }}" min="1" required />
                                @error('capacity')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required form-label">Estado</label>
                                <select class="form-select mb-2" data-control="select2" data-hide-search="true" 
                                        data-placeholder="Seleccione un estado" name="is_active" required>
                                    <option></option>
                                    @php
                                        $isActiveValue = old('is_active', $room->id ? ($room->is_active ? '1' : '0') : '1');
                                    @endphp
                                    <option value="1" {{ $isActiveValue == '1' ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ $isActiveValue == '0' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                @error('is_active')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7">

                <div class="card card-flush py-4">
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column gap-5">
                            <button type="submit" id="kt_room_submit" class="btn btn-primary">
                                <span class="indicator-label">{{ $room->id ? 'Actualizar' : 'Guardar' }}</span>
                                <span class="indicator-progress">Espere por favor...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                            <a href="{{ route('admin.room.manage') }}" class="btn btn-light">Cancelar</a>
                        </div>
                    </div>
                </div>

            </div>

        </form>

    </div>
</div>
@endsection

@section('styles')
@endsection

@section('scripts')
    <script src="{{ asset('themes/admin/js/custom/room/form.js') }}"></script>
@endsection

