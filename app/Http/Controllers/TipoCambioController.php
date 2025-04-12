<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipoCambioRequest;
use App\Http\Requests\UpdateTipoCambioRequest;
use App\Models\TipoCambio;
use App\Services\SunatTipoCambioService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TipoCambioController extends Controller
{

    /**
     * Find Tipo de Cambio Sunat.
     */
    public function find(Request $request)
    {
        $service = new SunatTipoCambioService();
        $date = $request->input('date');
        $tipoCambio = $service->getByDate($date);
        if ($tipoCambio) {
            return response()->json([
                'compra' => $tipoCambio['compra'],
                'venta' => $tipoCambio['venta'],
                'moneda' => $tipoCambio['moneda'],
                'date' => $tipoCambio['date']
            ]);
        }
        return response()->json([
            'error' => 'Tipo de cambio no encontrado para la date proporcionada.',
        ], 404);

    }

    public function rango(Request $request)
    {
        $service = new SunatTipoCambioService();
        
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $from = Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d');

        $dates = collect();
        $current = Carbon::parse($from);

        while ($current->lte($to)) {
            $date = $current->format('Y-m-d');
            $exists = TipoCambio::where('fecha', $date)->first();

            if (!$exists) {
                $data = $service->getByDate($date);
                if ($data) {
                    $exists = TipoCambio::create($data);
                }
                sleep(3);
            }

            if ($exists) {
                $dates->push($exists);
            }

            $current->addDay();
        }

        return response()->json($dates->sortBy('date')->values());
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoCambioRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoCambio $tipoCambio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoCambio $tipoCambio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoCambioRequest $request, TipoCambio $tipoCambio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoCambio $tipoCambio)
    {
        //
    }
}
