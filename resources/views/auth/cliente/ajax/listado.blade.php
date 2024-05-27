<div class="row">
    @foreach($clientes as $q)
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <label for="info-{{ $q->id }}" class="text-uppercase {{  Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_ADMINISTRADOR ? "pl-25" : "pl-0" }}">
                        @if(Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_ADMINISTRADOR)
                            <input type="checkbox" name="" id="info-{{ $q->id }}" value="">
                            {{ $q->nombres." ".$q->apellidos }}
                        <span class="checkmark"></span>
                        @else
                            {{ $q->nombres." ".$q->apellidos }}
                        @endif
                        <span> {{ $i++ }}</span>
                    </label>

                    @if(Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_ADMINISTRADOR  || $name || Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_SUPER_ADMINISTRADOR)
                        <p class="text-uppercase">INTERÉS: {{ $q->carreras != null ? $q->carreras->name : "-"  }}</p>
                        @if($q->users != null )
                            <p>ASESORA: {{ $q->users->name }}</p>
                        @else
                            <p class="text-danger">ASESORA: {{ strtoupper(\Illuminate\Support\Facades\DB::table('users')->where('id', $q->user_id)->first()->name). " (ASESOR ELIMINADO)"  }}</p>
                        @endif

                    @else
                        <p>CEL: {{ $q->celular }}</p>
                        <p class="text-uppercase">INTERÉS: {{ $q->carreras != null ? $q->carreras->name : "-" }}</p>
                    @endif
                    <hr>
                    <p class="text-uppercase">FECHA REGISTRO: {{ \Carbon\Carbon::parse($q->created_at)->format('d-m-Y H:i:s') }}</p>
                    <p class="text-uppercase">ÚLTIMO CONTACTO: {{ $q->ultimo_contacto != null ? \Carbon\Carbon::parse($q->ultimo_contacto)->format('d-m-Y H:i:s') : \Carbon\Carbon::parse($q->created_at)->format('d-m-Y H:i:s')   }}</p>
                    <p class="text-uppercase">ORIGEN: {{ $q->fuentes != null ? $q->fuentes->name : "-" }}

                    {{-- @if(Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_ADMINISTRADOR ) --}}
                    @if(Auth::guard('web')->user()->id == 1 || Auth::guard('web')->user()->id == 18)
                        <button type="button" class="btn-xs btn-danger btn-delete pull-right" data-info="info-{{ $q->id }}" style="background-color: #FF4961"><i class="fa fa-trash"></i></button></p>
                    @endif
                </div>
                <button type="button" class="btn" style="background-color: {{ $q->reasignado ? "#7E57C2" : ($q->estados != null  ? $q->estados->background : "#B91842") }}" data-info="info-{{ $q->id }}" {{ Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_CAJERO ? "disabled" : "" }}>
                    {{ ($q->estados != null ? $q->estados->name : "-"). ": ". ($q->estadoDetalle != null ? $q->estadoDetalle->name : "-") }}</button>
            </div>
        </div>
    @endforeach
</div>
