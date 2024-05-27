<?php

namespace easyCRM\Jobs;

use Carbon\Carbon;
use easyCRM\Exports\ClientesExport;
use easyCRM\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use easyCRM\Notification;

class ExportLeadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fechaInicio;
    protected $fechaFinal;
    protected $estado;
    protected $assessor_id;
    protected $modalidad;
    protected $carrera;
    protected $turno;
    protected $usuario_notificacion_id;
    protected $path;
    protected $name_file;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $fechaInicio,
        $fechaFinal,
        $estado,
        $assessor_id,
        $modalidad,
        $carrera,
        $turno,
        $usuario_notificacion_id
    ) {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
        $this->estado = $estado;
        $this->assessor_id = $assessor_id;
        $this->modalidad = $modalidad;
        $this->carrera = $carrera;
        $this->turno = $turno;
        $this->usuario_notificacion_id = $usuario_notificacion_id;

        $this->path =/*  'private' . DIRECTORY_SEPARATOR . */ 'files' . DIRECTORY_SEPARATOR . 'excel-exports' . DIRECTORY_SEPARATOR;
        $this->name_file = time() . "__lista-de-clientes__$fechaInicio" . "__" . "$fechaFinal" . ".xlsx";
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* ini_set('memory_limit', '4096M'); */

        $export_leads =  new ClientesExport($this->fechaInicio, $this->fechaFinal, $this->estado, $this->assessor_id, $this->modalidad, $this->carrera, $this->turno);

        ($export_leads)->store($this->path . $this->name_file, 'public');

        $Notification = new Notification();
        $Notification->user_id = $this->usuario_notificacion_id;
        $Notification->estado = true;
        $Notification->created_at = Carbon::now();
        $Notification->updated_at = Carbon::now();
        $Notification->data_export = json_encode([
            'path' => 'storage' . DIRECTORY_SEPARATOR . $this->path . $this->name_file,
            'name_file' => $this->name_file,
            'fechaInicio' => $this->fechaInicio,
            'fechaFinal' => $this->fechaFinal,
            'estado' => $this->estado,
            'assessor_id' => $this->assessor_id,
            'modalidad' => $this->modalidad,
            'carrera' => $this->carrera,
            'turno' => $this->turno,
        ]);
        $Notification->save();
    }
}
