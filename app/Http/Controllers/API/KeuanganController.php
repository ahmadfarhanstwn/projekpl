<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keuangan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KeuanganExport;

class KeuanganController extends Controller
{
    public function get()
    {
        $data = Keuangan::paginate(10)->toArray();

        return response()->json($data);
    }

    public function exportExcel()
    {
        // prettier-ignore
        return Excel::download(new KeuanganExport, 'data-keuangan.xlsx');
    }
}
