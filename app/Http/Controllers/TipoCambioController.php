<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipoCambioRequest;
use App\Http\Requests\UpdateTipoCambioRequest;
use App\Models\TipoCambio;
use App\Services\SunatTipoCambioService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function exportarExcel(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $from = $request->input('from');
        $to = $request->input('to');

        $datos = TipoCambio::whereBetween('fecha', [$from, $to])
            ->orderBy('fecha')
            ->get(['fecha', 'moneda', 'compra', 'venta']);

        // Crear hoja de cálculo
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $sheet->setCellValue('A1', 'Fecha');
        $sheet->setCellValue('B1', 'Moneda');
        $sheet->setCellValue('C1', 'Compra');
        $sheet->setCellValue('D1', 'Venta');

        // Datos
        $row = 2;
        foreach ($datos as $item) {
            $sheet->setCellValue("A{$row}", $item->fecha);
            $sheet->setCellValue("B{$row}", $item->moneda);
            $sheet->setCellValue("C{$row}", $item->compra);
            $sheet->setCellValue("D{$row}", $item->venta);
            $row++;
        }

        // Exportar como archivo Excel
        $filename = "tipo_cambio_{$from}_{$to}.xlsx";
        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment;filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
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
