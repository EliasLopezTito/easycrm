<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Incidencias\App;
use Incidencias\Sector;
use Incidencias\SubSector;

class SectorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listadoFiltro(Request $request){

        $data = array(); $sectores = null;

        if(!in_array(Auth::user()->perfil_id, [\Incidencias\App::$PERFIL_ADMINISTRADOR, \Incidencias\App::$PERFIL_JEFEOPERACION, App::$PERFIL_SUPERVISORCECOM1,
            App::$PERFIL_SUPERVISORCECOM2, App::$PERFIL_SUPERVISORCECOM3])){
            $sectores = Sector::where('macro_id', Auth::user()->macro_id)
                    ->where('name', 'like', '%' .$request->get('name') . '%')->get();
        }else{
            $sectores = Sector::where('name', 'like', '%' .$request->get('name') . '%')->get();
        }

        foreach ($sectores as $t){
            $array  = array('id' => $t->id, 'text' => $t->name);
            array_push($data, $array);
        }

        return response()->json(['data' => $data]);
    }

    public function list_sector($id)
    {
        $sectors = Sector::where('macro_id', $id)->get();
        return response()->json($sectors);
    }

    public function list_subsector($id)
    {
        $subsectors = SubSector::where('sector_id', $id)->get();
        return response()->json($subsectors);
    }
}
