<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentCategory;
class UserController extends Controller
{
    public function index(Request $request)
    {
   $totalDokumen = Document::count();

// Misal hitung kategori tertentu
$totalRatifikasi = Document::whereHas('category', function($q) {
    $q->where('name', 'ratifikasi');
})->count();

$totalPedoman = Document::whereHas('category', function($q) {
    $q->where('name', 'pedoman');
})->count();

$totalProsedur = Document::whereHas('category', function($q) {
    $q->where('name', 'prosedur');
})->count();

$totalInstruksi = Document::whereHas('category', function($q) {
    $q->where('name', 'instruksi');
})->count();

$totalFormulir = Document::whereHas('category', function($q) {
    $q->where('name', 'formulir');
})->count();

// Departemen dan Users
$totalDepartemen = Department::count();
$totalUsers = User::count();
$query = Document::with(['category', 'code', 'department']);

        if ($request->has('category') && $request->category != 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        $documents = $query->latest()->get();

        // ===== HITUNG STATISTIK =====
        $totalDocuments = Document::count();

        $ratifikasi = Document::whereHas('category', fn($q) =>
        $q->where('slug', 'ratifikasi'))->count();

        $pedoman = Document::whereHas('category', fn($q) =>
        $q->where('slug', 'pedoman'))->count();

        $prosedur = Document::whereHas('category', fn($q) =>
        $q->where('slug', 'prosedur'))->count();

        $instruksikerja = Document::whereHas('category', fn($q) =>
        $q->where('slug', 'instruksikerja'))->count();

        $formulir = Document::whereHas('category', fn($q) =>
        $q->where('slug', 'formulir'))->count();

        $totalDepartments = Department::count();
        $totalUsers = User::count();

        $total = max($totalDocuments, 1);

        $ratifikasiPercent = round(($ratifikasi / $total) * 100);
        $pedomanPercent = round(($pedoman / $total) * 100);
        $prosedurPercent = round(($prosedur / $total) * 100);
        $instruksikerjaPercent = round(($instruksikerja / $total) * 100);
        $formulirPercent = round(($formulir / $total) * 100);

        return view('user.index', compact(
        'totalDokumen', 'totalRatifikasi', 'totalPedoman', 'totalProsedur',
    'totalInstruksi', 'totalFormulir', 'totalDepartemen', 'totalUsers','documents',
            'totalDocuments',
            'ratifikasi',
            'pedoman',
            'prosedur',
            'instruksikerja',
            'formulir',
            'totalDepartments',
            'totalUsers'
        ));
    }

}
