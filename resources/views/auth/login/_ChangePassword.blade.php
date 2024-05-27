<div id="modalMantenimientoChangePassword" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form enctype="multipart/form-data" action="{{ route('auth.login.change_password') }}" id="changePassword" method="POST"
              data-ajax="true" data-close-modal="true" data-ajax-loading="#loading" data-ajax-success="OnSuccessChangePassword"
              data-ajax-failure="OnFailureChangePassword">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modificar Contraseña</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="password_old">Contraseña Actual</label>
                                <input type="password" class="form-input" name="password_old" id="password_old" autocomplete="off" required>
                                <span data-valmsg-for="password_old"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="password">Contraseña Nueva</label>
                                <input type="password" class="form-input" name="password" id="password" autocomplete="off" required>
                                <span data-valmsg-for="password"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="password_confirmation">Repita Contraseña Nueva</label>
                                <input type="password" class="form-input" name="password_confirmation" id="password_confirmation" autocomplete="off" required>
                                <span data-valmsg-for="password_confirm"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-bold btn-pure btn-primary">Modificar Contraseña</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="{{ asset('auth/js/login/_ChangePassword.min.js') }}"></script>
