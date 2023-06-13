<?php

namespace App\Http\Controllers;

use App\Imports\StagiaresImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Stagiaire;
class ExcelImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        try {
            Excel::import(new StagiaresImport, $request->file('file'));
            return response()->json(['message' => 'Import successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Import failed: ' . $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        $stagiaires = Stagiaire::all();
        return response()->json($stagiaires);
    }
}

