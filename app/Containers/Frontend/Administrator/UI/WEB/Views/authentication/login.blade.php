@extends('vendor@template::admin.layouts.auth')

@section('content')
    <div class="d-flex flex-center flex-column flex-lg-row-fluid">
        <div class="w-lg-500px p-10">
            <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" action="" method="post" autocomplete="off">
                {{ csrf_field() }}
                <div class="text-center mb-11">
                    <h1 class="text-gray-900 fw-bolder mb-3">INGRESO</h1>
                    <div class="text-gray-500 fw-semibold fs-6">Sistema de Monitoreo</div>
                </div>

                @if (session('status') || $errors->any())
                <div class="alert alert-danger d-flex align-items-center p-5 mb-15">
                    <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-danger">Advertencia</h4>
                        <span>
                            @if(session('status'))
                                {{ session('status') }}
                            @elseif($errors->has('email'))
                                {{ $errors->first('email') }}
                            @else
                                El usuario y/o contraseña son incorrectos
                            @endif
                        </span>
                    </div>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success d-flex align-items-center p-5 mb-15">
                    <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-success">Ingreso satisfactorio</h4>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
                @endif

                <div class="fv-row mb-8">
                    <input type="text" placeholder="Correo electrónico" name="email" value="{{ old('email') }}" autocomplete="off" class="form-control bg-transparent" />
                </div>
                <div class="fv-row mb-3">
                    <input type="password" placeholder="Contraseña" name="password" autocomplete="off" class="form-control bg-transparent" />
                </div>
                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                    <div></div>
                    <a href="{{ route('auth.forgot_password') }}" class="link-primary">¿Olvidaste tu contraseña ?</a>
                </div>
                <div class="d-grid mb-10">
                    <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                        <span class="indicator-label">Ingresar</span>
                        <span class="indicator-progress">Espere por favor...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
                {{--<div class="text-gray-500 text-center fw-semibold fs-6">¿No tienes una cuenta?<a href="#" class="link-primary">Regístrate</a></div>--}}
            </form>
        </div>
    </div>

    <div class="w-lg-500px d-flex flex-stack px-10 mx-auto">
        <div class="me-10">
        </div>
        <div class="d-flex fw-semibold text-primary fs-base gap-5">
            <a href="#" target="_blank" rel="noopener">Contáctanos</a>
        </div>
    </div>
@endsection

@section('styles')
@endsection

@section('scripts')
    <script src="{{ asset('themes/admin/js/custom/auth/login.js') }}"></script>
@endsection