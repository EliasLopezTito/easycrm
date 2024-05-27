<?php

namespace easyCRM\Http\Controllers\Auth;

use easyCRM\Estado;
use easyCRM\EstadoDetalle;
use easyCRM\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstadoController extends Controller
{
    public function index()
    {
        return view('auth.estado.index');
    }

    public function list_all()
    {
        return response()->json(['data' => Estado::with('estadoDetalles')->orderby('id', 'desc')->get()]);
    }

    public function list_allDetail($id)
    {
        return response()->json(['data' => EstadoDetalle::where('estado_id', $id)->orderby('id', 'desc')->get()]);
    }

    public function filtroEstado($id)
    {
        return response()->json(EstadoDetalle::where('estado_id', $id)
            ->orderBy('name', 'asc')->get());
    }

    public function partialView($id, $type)
    {
        $entity = null;

        $Estados = Estado::orderby('name', 'asc');

        if($id != 0){
            $entity = $type == 0 ? Estado::find($id) : EstadoDetalle::find($id);
            $Estados = $Estados->whereNotIn('id', [$id])->get();
        }else{
            $Estados = $Estados->get();
        }

        return view('auth.estado._Mantenimiento', ['Estados' => $Estados, 'Estado' => $entity, 'Type' => $type == null ? 0 : $type ]);
    }

    public function partialViewDetail($id)
    {
        $entity = Estado::find($id);

        return view('auth.estado._Mantenimiento_Detalle', ['Estado' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false; $messgae = null;

        try{

            if($request->tipo){

                $validator = Validator::make($request->all(), [
                    'estado_id' => 'required',
                    'name' => 'required|unique:estados,name,'.($request->id != 0 ? $request->id : "NULL").',id,deleted_at,NULL'
                ]);

                if ($request->get('id') != 0)
                    $entity = EstadoDetalle::find($request->get('id'));
                else
                    $entity = new EstadoDetalle();

                $entity->estado_id = $request->estado_id;
                $entity->name = $request->name;

                if ($entity->save()) $status = true;

                }else {

                $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:estados,name,'.($request->id != 0 ? $request->id : "NULL").',id,deleted_at,NULL'
                ]);

                if (!$validator->fails()) {

                    if ($request->get('id') != 0)
                        $entity = Estado::find($request->get('id'));
                     else
                        $entity = new Estado();

                    $entity->name = strtoupper(trim($request->name));

                    if ($entity->save()) $status = true;

                }
            }

        }catch (\Exception $e){
            $messgae = $e->getMessage();
        }

        return response()->json(['Success' => $status, 'Message' => $messgae, 'Errors' => $validator->errors()]);
    }

    public function delete(Request $request)
    {
        $status = false;

        $entity = Estado::find($request->get('id'));

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function deleteDetail(Request $request)
    {
        $status = false;

        $entity = EstadoDetalle::find($request->get('id'));

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }

}
