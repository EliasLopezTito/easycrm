<?php


namespace easyCRM\Http\Controllers\Auth;

use easyCRM\Historial;
use Illuminate\Http\Request;
use easyCRM\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class HistorialController extends Controller
{
    public function index()
    {
        return view('auth.reportehistorial.index');
    }

    /* public function list_all()
    {
        return response()->json(['data' => Historial::orderby('id', 'desc')->get()]);
    } */

   /*  public function list_all()
{
    $historial = Historial::with(['cliente', 'users', 'vendedores'])
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('cliente_id')
        ->map(function ($items, $index) {
            return [
                'contador' => $index + 1,
                'cliente_id' => $items->first()->cliente_id,
                'cliente' => $items->first()->cliente,
                'historial' => $items->map(function ($item, $key) use ($items) {
                    return [
                        'usuario' => $item->users ? $item->users->name : 'Desconocido',
                        'vendedor' => $item->vendedores ? $item->vendedores->name : 'No asignado',
                        'fecha' => Carbon::parse($item->created_at)->format('d/m/Y H:i:s'),
                        'tipo' => $key === 0 ? 'Registro' : 'Reasignación' // El primer evento es "Registro", los demás "Reasignación"
                    ];
                })->toArray()
            ];
        })->values();

    return response()->json(['data' => $historial]);
}
 */
public function list_all(Request $request)
{
    $desde = $request->input('desde');
    $hasta = $request->input('hasta');

    // Verifica si se pasan las fechas y ajusta la consulta
    $query = Historial::with(['cliente', 'users', 'vendedores'])
        ->orderBy('created_at', 'desc');

    if ($desde) {
        $query->where('created_at', '>=', Carbon::parse($desde)->startOfDay());
    }

    if ($hasta) {
        $query->where('created_at', '<=', Carbon::parse($hasta)->endOfDay());
    }

    $historial = $query->get()
        ->groupBy('cliente_id')
        ->map(function ($items, $index) {
            return [
                'contador' => $index + 1,
                'cliente_id' => $items->first()->cliente_id,
                'cliente' => $items->first()->cliente,
                'historial' => $items->map(function ($item, $key) use ($items) {
                    $usuario = $item->users ? $item->users->name : 'Usuario eliminado';
                    $vendedor = $item->vendedores ? $item->vendedores->name : 'no existe';

                    // Para el primer evento (registro inicial), mostrar también el vendedor
                    return [
                        'usuario' => $usuario,
                        'vendedor' => $vendedor, // Mostrar el vendedor en caso de registro
                        'fecha' => Carbon::parse($item->created_at)->format('d/m/Y H:i:s'),
                        'tipo' => $key === 0 ? 'Registro' : 'Reasignación'
                    ];
                })->toArray()
            ];
        })->values();

    return response()->json(['data' => $historial]);
}


}
