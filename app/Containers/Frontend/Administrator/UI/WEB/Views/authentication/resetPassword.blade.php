@extends('vendor@template::admin.layouts.auth')

@section('content')
    <div class="d-flex flex-center flex-column flex-lg-row-fluid">
        <div class="w-lg-500px p-10">
            <form class="form w-100" novalidate="novalidate" id="kt_password_reset_form" action="{{ route('auth.reset_password_post') }}" method="post" autocomplete="off">
                {{ csrf_field() }}


                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="text-center mb-11">
                    <h1 class="text-gray-900 fw-bolder mb-3">Restablecer contraseña</h1>
                    <div class="text-gray-500 fw-semibold fs-6">Ingresa tu nueva contraseña para restablecer tu contraseña</div>
                </div>

                @if (session('error') || $errors->any())
                <div class="alert alert-danger d-flex align-items-center p-5 mb-15">
                    <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-danger">Advertencia</h4>
                        <span>
                            @if(session('error'))
                                {{ session('error') }}
                            @elseif($errors->has('password'))
                                {{ $errors->first('password') }}
                            @else
                                Ocurrió un error al restablecer la contraseña
                            @endif
                        </span>
                    </div>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success d-flex align-items-center p-5 mb-15">
                    <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-success">Restablecimiento de contraseña</h4>
                        <span>La contraseña ha sido restablecida con satisfactoriamente. Intente ingresar nuevamente</span>
                    </div>
                </div>
                @endif

                <div class="fv-row mb-8">
                    <input type="text" placeholder="Correo electrónico" name="email" value="{{ $email }}" autocomplete="off" class="form-control bg-transparent" />
                </div>

                <div class="mb-8 fv-row" data-kt-password-meter="true">
                    <div class="mb-1">
                        <label class="form-label fw-bold text-dark fs-6">Contraseña</label>
                        <div class="position-relative mb-3">
                            <input class="form-control form-control-lg" type="password" placeholder="" name="password" autocomplete="off" tabindex="1" />
                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                        <i class="bi bi-eye-slash fs-2"></i>
                        <i class="bi bi-eye fs-2 d-none"></i>
                    </span>
                        </div>
                        <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                        </div>
                    </div>
                    <div class="text-muted">Use 6 o más caracteres con una combinación de letras, números & símbolos.</div>
                </div>

                <div class="fv-row mb-8">
                    <label class="form-label fw-bold text-dark fs-6">Confirmar contraseña</label>
                    <input class="form-control form-control-lg" type="password" placeholder="" name="confirm_password" autocomplete="off" tabindex="2" />
                </div>

                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                    
                </div>
                <div class="d-grid mb-10">
                    <button type="submit" id="kt_password_reset_submit" class="btn btn-primary">
                        <span class="indicator-label">Enviar</span>
                        <span class="indicator-progress">Espere por favor...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
                <div class="text-gray-500 text-center fw-semibold fs-6"><a href="{{ route('auth.login') }}" class="link-primary ps-2">Volver a Ingresar</a></div>
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
    <script src="{{ asset('themes/admin/js/custom/auth/reset-password.js') }}"></script>
@endsection