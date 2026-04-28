<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\TnaExport;
use Maatwebsite\Excel\Facades\Excel;

class TnaExportController extends Controller
{
    /**
     * Export TNA proposals to XLSX based on active filters using Maatwebsite Excel.
     */
    public function export(Request $request)
    {
        $status = $request->input('status', 'all');
        $search = $request->input('search', '');
        $category = $request->input('category', 'all');
        $dateFrom = $request->input('date_from', '');
        $dateTo = $request->input('date_to', '');

        $filename = 'Export_TNA_' . now()->format('Y-m-d') . '.xlsx';

        // We pass the filters to the Export class
        return Excel::download(new TnaExport($status, $search, $category, $dateFrom, $dateTo), $filename);
    }
}
