@extends('layouts.store.app')

@section('title')
    <title> Comprando | Mi cuenta </title>
@endsection

@section('main')

    <!-- MI CUENTA -->
    <section class="account-wrapp">
        <div class="account-div">
            <ul>
                <li><a href="{{ route('client.account') }}">Perfil</a></li>
                <li><a href="{{ route('client.shopping') }}">Órdenes</a></li>
                <li><a href="{{ route('client.favorite') }}">Recomendados</a></li>
                <li><a href="{{ route('client.storeFavorite') }}">Tiendas</a></li>
            </ul>
        </div>
        <form action="{{ route('client.information') }}", method="POST">
            {{ csrf_field() }}
        <div class="account-div">
            <p class="account-tit">Perfil</p>
            <div class="account-datos">
                <p class="account-stit">Datos de la cuenta</p>
                <a href="javascript:void(0)" class="edit-value">
                    <div class="account-items">
                        <div class="account-item">Usuario:</div>
                        <div class="account-item {{ $errors->has('email') ? ' has-error' : '' }} ">
                            <input type="email" name="email" id="email"  class="form-control" value="{{ Auth::guard('client')->user()->email  }}"  readonly="readonly" required>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="account-item"></div>
                    </div>
                </a>

                <a href="javascript:void(0)" class="edit-value">
                    <div class="account-items">
                        <div class="account-item">Contraseña:</div>
                        <div class="account-item {{ $errors->has('password') ? ' has-error' : '' }}">
                            <input id="password" type="password" name="password" placeholder="********">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="account-item"><i class="fas fa-edit"></i></div>
                    </div>
                </a>

            </div>
            <div class="account-datos">
                <p class="account-stit">Datos personales</p>

                <a href="javascript:void(0)" class="edit-value">
                    <div class="account-items">
                        <div class="account-item">Nombres:</div>
                        <div class="account-item {{ $errors->has('name') ? ' has-error' : '' }}">
                            <input type="text" name="name" id="name"  class="form-control"
                                   value="{{ Auth::guard('client')->user()->name}}" required>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="account-item"><i class="fas fa-edit"></i></div>
                    </div>
                </a>

                <a href="javascript:void(0)" class="edit-value">
                    <div class="account-items">
                        <div class="account-item">Apellidos:</div>
                        <div class="account-item {{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <input type="text" name="last_name" id="last_name"  class="form-control"
                                   value="{{ Auth::guard('client')->user()->last_name}}" required>
                            @if ($errors->has('last_name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="account-item"><i class="fas fa-edit"></i></div>
                    </div>
                </a>

                <a href="javascript:void(0)" class="edit-value">
                    <div class="account-items">
                        <div class="account-item">Departamento:</div>
                        <div class="account-item {{ $errors->has('departament_id') ? ' has-error' : '' }} ">
                            <select name="departament_id" id="departament_id" class="form-control" style="width: 100%" required>
                                <option value="">--NINGUNO--</option>
                                @foreach($Departaments as $q)
                                    <option value="{{ $q->id }}" {{ Auth::guard('client')->user() != null && Auth::guard('client')->user()->departament_id == $q->id ? "selected" : "" }}>{{ $q->name  }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="account-item"><i class="fas fa-edit"></i></div>
                    </div>
                </a>

                <a href="javascript:void(0)" class="edit-value">
                    <div class="account-items">
                        <div class="account-item">Ciudad:</div>
                        <div class="account-item {{ $errors->has('city_id') ? ' has-error' : '' }} ">
                            <select name="city_id" id="city_id" class="form-control" style="width: 100%" required>
                                <option value="">--NINGUNO--</option>
                                @foreach($Cities as $q)
                                    <option value="{{ $q->id }}" {{ Auth::guard('client')->user() != null && Auth::guard('client')->user()->city_id == $q->id ? "selected" : "" }}>{{ $q->name  }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="account-item"><i class="fas fa-edit"></i></div>
                    </div>
                </a>

                <a href="javascript:void(0)" class="edit-value">
                    <div class="account-items">
                        <div class="account-item">Teléfono:</div>
                        <div class="account-item {{ $errors->has('phone') ? ' has-error' : '' }} ">
                            <input type="text" name="phone" id="phone"
                                   value="{{ Auth::guard('client')->user()->phone }}" onkeypress="return isNumberKey(event)" minlength="7" maxlength="9"  placeholder="-" required>
                            @if ($errors->has('phone'))
                                <span class="help-block">
                            <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="account-item"><i class="fas fa-edit"></i></div>
                    </div>
                </a>

                <a href="javascript:void(0)" class="edit-value">
                    <div class="account-items">
                        <div class="account-item">DNI:</div>
                        <div class="account-item {{ $errors->has('dni') ? ' has-error' : '' }}">
                            <input type="text" name="dni" id="dni" class="input-full"
                                   value="{{ Auth::guard('client')->user()->dni }}" onkeypress="return isNumberKey(event)"  minlength="8" maxlength="8"  placeholder="-">
                            @if ($errors->has('dni'))
                                <span class="help-block">
                            <strong>{{ $errors->first('dni') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="account-item"><i class="fas fa-edit"></i></div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="edit-value">
                    <div class="account-items">
                        <div class="account-item">Fecha de nacimiento:</div>
                        <div class="account-item {{ $errors->has('date_birthday') ? ' has-error' : '' }}">
                            <input type="date" name="date_birthday" id="date_birthday" class="input-full"
                                   value="{{ \Carbon\Carbon::parse(Auth::guard('client')->user()->date_birthday)->format('Y-m-d') }}" placeholder="-">
                            @if ($errors->has('date_birthday'))
                                <span class="help-block">
                            <strong>{{ $errors->first('date_birthday') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="account-item"><i class="fas fa-edit"></i></div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="edit-value">
                    <div class="account-items">
                        <div class="account-item">Intereses:</div>
                        <div class="account-item {{ $errors->has('categorie_id') ? ' has-error' : '' }}">
                            <select name="categorie_id[]" id="categorie_id" style="width: 100%" class="form-control" required data-initial="{{ ( $UserCategorie != null && count($UserCategorie) > 0 ) ?  implode(",", $UserCategorie)  : "" }}" multiple="multiple">
                                @foreach($Categories as $q)
                                    <option value="{{ $q->id }}">{{ $q->name  }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="account-item"><i class="fas fa-edit"></i></div>
                    </div>
                </a>
            </div>
            <button type="submit" class="botones"> Guardar</button>
            <!--<p class="account-stit">Direcciones</p>
            <a href="">
                <div class="account-items">
                    <div class="account-item">Dirección</div>
                    <div class="account-item">-</div>
                    <div class="account-item"><i class="fas fa-edit"></i></div>
                </div>
            </a>-->
        </div>
        </form>
    </section>
@endsection

@section('scripts')
    {{ Html::script('/auth/plugins/select2/js/select2.js') }}
    {{ Html::script('/js/account/index.js') }}
@endsection