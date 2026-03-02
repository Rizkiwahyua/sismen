<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Department;
use App\Models\Document;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
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

return view('admin.index', compact(
    'totalDokumen', 'totalRatifikasi', 'totalPedoman', 'totalProsedur',
    'totalInstruksi', 'totalFormulir', 'totalDepartemen', 'totalUsers'
));
    }
}
