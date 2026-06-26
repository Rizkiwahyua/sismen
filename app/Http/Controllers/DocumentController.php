<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentCode;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DocumentsExport;
use App\Exports\DeletedDocumentsExport;
use setasign\Fpdi\Tcpdf\Fpdi;


class DocumentController extends Controller
{

    public function index(Request $request)
    {
        $query = Document::with(['category', 'code', 'department', 'details.department', 'uploader', 'updater']);

        if ($request->filled('category') && $request->category != 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('department') && !empty($request->department)) {
            $departmentsInput = array_filter((array) $request->department);
            if (count($departmentsInput) > 0) {
                $query->whereIn('department_id', $departmentsInput);
            }
        }

        if ($request->has('code') && !empty($request->code)) {
            $codesInput = array_filter((array) $request->code);
            if (count($codesInput) > 0) {
                $query->whereIn('document_code_id', $codesInput);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $chartQuery = clone $query;
        $chartDocs = $chartQuery->get();
        
        $documents = $query->latest()->paginate(10)->withQueryString();

        $departments = Department::all();
        $codes = DocumentCode::all();

        // STATISTIK (biarkan seperti sebelumnya)
        $totalDocuments = Document::count();
        $ratifikasi = Document::whereHas('category', fn($q) => $q->where('slug', 'ratifikasi'))->count();
        $pedoman = Document::whereHas('category', fn($q) => $q->where('slug', 'pedoman'))->count();
        $prosedur = Document::whereHas('category', fn($q) => $q->where('slug', 'prosedur'))->count();
        $instruksikerja = Document::whereHas('category', fn($q) => $q->where('slug', 'instruksikerja'))->count();
        $formulir = Document::whereHas('category', fn($q) => $q->where('slug', 'formulir'))->count();

        $totalDepartments = Department::count();
        $totalUsers = User::count();

        return view('admin.documents.index', compact(
            'documents',
            'chartDocs',
            'departments',
            'codes',
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

    private function rotateText($pdf, $angle, $x, $y, $text)
    {
        $pdf->StartTransform();
        $pdf->Rotate($angle, $x, $y);
        $pdf->Text($x, $y, $text);
        $pdf->StopTransform();
    }
    public function byCategory($slug)
    {
        if ($slug == 'all') {
            $documents = Document::with(['category', 'code', 'department'])
                ->latest()
                ->get();
        } else {
            $documents = Document::with(['category', 'code', 'department'])
                ->whereHas('category', function ($q) use ($slug) {
                    $q->where('slug', $slug);
                })
                ->latest()
                ->get();
        }

        $title = ucfirst(str_replace('-', ' ', $slug));

        return view('admin.documents.category', compact('documents', 'title'));
    }
    public function create()
    {
        return view('admin.documents.create', [
            'categories'  => DocumentCategory::all(),
            'codes'       => DocumentCode::all(),
            'departments' => Department::all(),
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title'                => 'required|string|max:300',
            'document_category_id' => 'required',
            'document_code_id'     => 'required',
            'department_id'        => 'required',
            'document_number'      => 'nullable|string|max:255',
            'document_number_prefix' => 'nullable|string|max:100',
            'document_number_suffix' => 'nullable|string|max:100',
            'revision'             => 'nullable|string|max:50',
            'document_date'        => 'nullable|date',
            'description'          => 'nullable|string',
            'file_document'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:50000',
        ]);

        $data = $request->except('file_document');

        if ($request->hasFile('file_document')) {

            $file = $request->file('file_document');

            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('documents');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);

            $data['file_document'] = 'documents/' . $filename;
        }

        // 🔥 SIMPAN DOKUMEN
        $document = Document::create($data);

        // 🔥 SIMPAN SUB DETAIL URAIAN (Untuk semua kategori)
        if ($request->has('sub_title')) {
            foreach ($request->sub_title as $i => $sub) {
                if (!empty($sub)) {
                    \App\Models\DocumentDetail::create([
                        'document_id' => $document->id,
                        'sub_title' => $sub,
                        'department_ids' => json_decode($request->sub_department[$i] ?? '[]'),
                        'description' => $request->sub_description[$i] ?? null,
                    ]);
                }
            }
        }

        // 🔥 WAJIB ADA RETURN
        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil ditambahkan');
    }


    public function edit(Document $document)
    {
        return view('admin.documents.edit', [
            'document'    => $document,
            'categories'  => DocumentCategory::all(),
            'codes'       => DocumentCode::all(),
            'departments' => Department::all(),
        ]);
    }

    public function preview(Document $document)
    {
        return view('admin.documents.preview', compact('document'));
    }

    private function decompressPdf($inputFile, $outputFile)
    {
        $content = file_get_contents($inputFile);
        if ($content === false) {
            throw new \Exception("Cannot read input file");
        }
        
        $xrefOffsets = [];
        $startXrefPos = strrpos($content, 'startxref');
        if ($startXrefPos === false) {
            throw new \Exception("startxref not found");
        }
        
        $offsetLine = substr($content, $startXrefPos + 9);
        $offsetLine = trim(explode('%%EOF', $offsetLine)[0]);
        $nextXrefOffset = (int)$offsetLine;
        
        while ($nextXrefOffset > 0) {
            $xrefOffsets[] = $nextXrefOffset;
            
            $objPart = substr($content, $nextXrefOffset, 1000);
            $dictStart = strpos($objPart, '<<');
            $dictEnd = strpos($objPart, '>>', $dictStart);
            if ($dictStart === false || $dictEnd === false) {
                break;
            }
            $dictStr = substr($objPart, $dictStart, $dictEnd - $dictStart + 2);
            
            if (preg_match('/\/Prev\s+(\d+)/', $dictStr, $matches)) {
                $nextXrefOffset = (int)$matches[1];
            } else {
                $nextXrefOffset = 0;
            }
        }
        
        $objectsMap = [];
        $trailerKeys = [];
        
        foreach (array_reverse($xrefOffsets) as $xrefOffset) {
            $objPart = substr($content, $xrefOffset, 1000);
            $dictStart = strpos($objPart, '<<');
            $dictEnd = strpos($objPart, '>>', $dictStart);
            if ($dictStart === false || $dictEnd === false) {
                continue;
            }
            $dictStr = substr($objPart, $dictStart, $dictEnd - $dictStart + 2);
            
            foreach (['/Root', '/Info', '/ID', '/Size'] as $key) {
                if (preg_match('/' . preg_quote($key, '/') . '\s+([^\/]+)/', $dictStr, $matches)) {
                    $val = trim($matches[1]);
                    if (str_ends_with($val, '>>')) $val = substr($val, 0, -2);
                    $trailerKeys[$key] = trim($val);
                }
            }
            
            if (strpos($dictStr, '/Type /XRef') !== false || strpos($dictStr, '/XRef') !== false) {
                if (!preg_match('/\/W\s*\[\s*(\d+)\s+(\d+)\s+(\d+)\s*\]/', $dictStr, $matches)) {
                    continue;
                }
                $w = [(int)$matches[1], (int)$matches[2], (int)$matches[3]];
                $entrySize = array_sum($w);
                
                preg_match('/\/Size\s+(\d+)/', $dictStr, $matches);
                $size = $matches ? (int)$matches[1] : 0;
                
                preg_match('/\/Index\s*\[\s*([^\]]+)\s*\]/', $dictStr, $matches);
                $index = [];
                if ($matches) {
                    $parts = preg_split('/\s+/', trim($matches[1]));
                    for ($i = 0; $i < count($parts); $i += 2) {
                        $index[] = [(int)$parts[$i], (int)$parts[$i+1]];
                    }
                } else {
                    $index[] = [0, $size];
                }
                
                $streamStartPos = strpos($content, 'stream', $xrefOffset);
                if ($streamStartPos === false) continue;
                $streamDataPos = $streamStartPos + 6;
                if ($content[$streamDataPos] == "\r") $streamDataPos++;
                if ($content[$streamDataPos] == "\n") $streamDataPos++;
                
                $endstreamPos = strpos($content, 'endstream', $streamDataPos);
                if ($endstreamPos === false) continue;
                
                $streamData = substr($content, $streamDataPos, $endstreamPos - $streamDataPos);
                if (str_ends_with($streamData, "\n")) $streamData = substr($streamData, 0, -1);
                if (str_ends_with($streamData, "\r")) $streamData = substr($streamData, 0, -1);
                
                $decompressed = @gzuncompress($streamData);
                if ($decompressed === false) {
                    $decompressed = @gzinflate($streamData);
                }
                if ($decompressed === false) {
                    continue;
                }
                
                $offset = 0;
                foreach ($index as $range) {
                    $startObj = $range[0];
                    $count = $range[1];
                    for ($i = 0; $i < $count; $i++) {
                        $objNum = $startObj + $i;
                        if ($offset + $entrySize > strlen($decompressed)) {
                            break;
                        }
                        $entry = substr($decompressed, $offset, $entrySize);
                        $offset += $entrySize;
                        
                        $type = 1;
                        if ($w[0] > 0) {
                            $type = 0;
                            for ($j = 0; $j < $w[0]; $j++) {
                                $type = ($type << 8) | ord($entry[$j]);
                            }
                        }
                        
                        $f2 = 0;
                        for ($j = 0; $j < $w[1]; $j++) {
                            $f2 = ($f2 << 8) | ord($entry[$w[0] + $j]);
                        }
                        
                        $f3 = 0;
                        for ($j = 0; $j < $w[2]; $j++) {
                            $f3 = ($f3 << 8) | ord($entry[$w[0] + $w[1] + $j]);
                        }
                        
                        if ($type === 0) {
                            unset($objectsMap[$objNum]);
                        } else {
                            $objectsMap[$objNum] = [
                                'type' => $type,
                                'f2' => $f2,
                                'f3' => $f3
                            ];
                        }
                    }
                }
            }
        }
        
        $sortedObjs = [];
        foreach ($objectsMap as $id => $info) {
            if ($info['type'] == 1) {
                $sortedObjs[] = [
                    'id' => $id,
                    'offset' => $info['f2']
                ];
            }
        }
        
        usort($sortedObjs, function($a, $b) {
            return $a['offset'] <=> $b['offset'];
        });
        
        $objectsData = [];
        $numSorted = count($sortedObjs);
        for ($i = 0; $i < $numSorted; $i++) {
            $id = $sortedObjs[$i]['id'];
            $offset = $sortedObjs[$i]['offset'];
            
            if ($i + 1 < $numSorted) {
                $end = $sortedObjs[$i+1]['offset'];
            } else {
                $end = min($xrefOffsets);
            }
            
            $rawObj = substr($content, $offset, $end - $offset);
            $objectsData[$id] = $rawObj;
        }
        
        $decompressedObjects = [];
        foreach ($objectsMap as $id => $info) {
            if ($info['type'] == 2) {
                $containerId = $info['f2'];
                $index = $info['f3'];
                
                if (!isset($objectsData[$containerId])) {
                    continue;
                }
                
                $containerContent = $objectsData[$containerId];
                
                $dictStart = strpos($containerContent, '<<');
                $dictEnd = strpos($containerContent, '>>', $dictStart);
                $dictStr = substr($containerContent, $dictStart, $dictEnd - $dictStart + 2);
                
                preg_match('/\/N\s+(\d+)/', $dictStr, $mN);
                preg_match('/\/First\s+(\d+)/', $dictStr, $mFirst);
                
                $n = $mN ? (int)$mN[1] : 0;
                $first = $mFirst ? (int)$mFirst[1] : 0;
                
                $streamStart = strpos($containerContent, 'stream');
                if ($streamStart === false) continue;
                $streamDataPos = $streamStart + 6;
                if ($containerContent[$streamDataPos] == "\r") $streamDataPos++;
                if ($containerContent[$streamDataPos] == "\n") $streamDataPos++;
                
                $endstreamPos = strpos($containerContent, 'endstream', $streamDataPos);
                if ($endstreamPos === false) continue;
                
                $streamData = substr($containerContent, $streamDataPos, $endstreamPos - $streamDataPos);
                if (str_ends_with($streamData, "\n")) $streamData = substr($streamData, 0, -1);
                if (str_ends_with($streamData, "\r")) $streamData = substr($streamData, 0, -1);
                
                $decompressedStream = @gzuncompress($streamData);
                if ($decompressedStream === false) {
                    $decompressedStream = @gzinflate($streamData);
                }
                if ($decompressedStream === false) {
                    continue;
                }
                
                $headerPart = substr($decompressedStream, 0, $first);
                $pairs = preg_split('/\s+/', trim($headerPart));
                
                for ($k = 0; $k < $n * 2; $k += 2) {
                    $childObjNum = (int)$pairs[$k];
                    $relOffset = (int)$pairs[$k+1];
                    
                    $start = $first + $relOffset;
                    if ($k + 2 < $n * 2) {
                        $nextRelOffset = (int)$pairs[$k+3];
                        $childLen = $nextRelOffset - $relOffset;
                        $childContent = substr($decompressedStream, $start, $childLen);
                    } else {
                        $childContent = substr($decompressedStream, $start);
                    }
                    
                    $decompressedObjects[$childObjNum] = trim($childContent);
                }
            }
        }
        
        $newContent = "%PDF-1.4\n";
        $newOffsets = [];
        
        $allObjIds = array_keys($objectsMap);
        sort($allObjIds);
        
        foreach ($allObjIds as $id) {
            if (isset($objectsData[$id])) {
                $raw = $objectsData[$id];
                if (strpos($raw, '/Type /ObjStm') !== false || strpos($raw, '/Type /XRef') !== false) {
                    continue;
                }
                
                $newOffsets[$id] = strlen($newContent);
                $newContent .= trim($raw) . "\n";
            } else if (isset($decompressedObjects[$id])) {
                $newOffsets[$id] = strlen($newContent);
                $newContent .= "{$id} 0 obj\n" . $decompressedObjects[$id] . "\nendobj\n";
            }
        }
        
        $xrefStart = strlen($newContent);
        $newContent .= "xref\n";
        
        $maxId = max(array_keys($newOffsets));
        $newContent .= "0 " . ($maxId + 1) . "\n";
        $newContent .= "0000000000 65535 f \n";
        
        for ($id = 1; $id <= $maxId; $id++) {
            if (isset($newOffsets[$id])) {
                $newContent .= sprintf("%010d 00000 n \n", $newOffsets[$id]);
            } else {
                $newContent .= "0000000000 00000 f \n";
            }
        }
        
        $newContent .= "trailer\n<<\n";
        $newContent .= "/Size " . ($maxId + 1) . "\n";
        foreach ($trailerKeys as $k => $v) {
            if ($k !== '/Size') {
                $newContent .= "$k $v\n";
            }
        }
        $newContent .= ">>\n";
        $newContent .= "startxref\n$xrefStart\n%%EOF\n";
        
        file_put_contents($outputFile, $newContent);
    }

    private function applyWatermark(Document $document)
    {
        $path = public_path($document->file_document);

        if (!file_exists($path)) {
            abort(404);
        }

        $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();

        $pdf->SetAutoPageBreak(false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        $tempPath = null;
        try {
            $pageCount = $pdf->setSourceFile($path);
        } catch (\Exception $e) {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            if (strtolower($extension) === 'pdf') {
                try {
                    $tempDir = storage_path('app/temp_pdfs');
                    if (!file_exists($tempDir)) {
                        mkdir($tempDir, 0755, true);
                    }
                    $tempPath = $tempDir . '/' . uniqid('decompressed_') . '.pdf';
                    $this->decompressPdf($path, $tempPath);
                    
                    $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();
                    $pdf->SetAutoPageBreak(false);
                    $pdf->SetPrintHeader(false);
                    $pdf->SetPrintFooter(false);
                    $pageCount = $pdf->setSourceFile($tempPath);
                    
                    register_shutdown_function(function() use ($tempPath) {
                        if (file_exists($tempPath)) {
                            @unlink($tempPath);
                        }
                    });
                } catch (\Exception $decompressException) {
                    throw $e;
                }
            } else {
                throw $e;
            }
        }

        for ($i = 1; $i <= $pageCount; $i++) {
            $tpl = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);

            // 🔥 WATERMARK TRANSPARAN
            $pdf->SetAlpha(0.15);

            $pdf->StartTransform();
            $pdf->Rotate(45, $size['width'] / 2, $size['height'] / 2);

            $pdf->SetFont('helvetica', 'B', 18);
            $pdf->SetTextColor(150, 150, 150);

            $pdf->SetXY(0, $size['height'] / 2);
            $pdf->Cell(0, 10, 'Dokumen tidak terkendali milik - PT Pupuk Iskandar Muda', 0, 1, 'C');

            $pdf->StopTransform();
            $pdf->SetAlpha(1);
        }

        return $pdf;
    }


    public function stream($id)
    {
        $document = Document::findOrFail($id);
        $path = public_path($document->file_document);

        if (!file_exists($path)) {
            abort(404);
        }

        // Hanya apply watermark jika bertipe PDF
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        if (strtolower($extension) === 'pdf') {
            try {
                $pdf = $this->applyWatermark($document);
                
                $tempDir = storage_path('app/temp_streams');
                if (!file_exists($tempDir)) {
                    mkdir($tempDir, 0755, true);
                }
                $tempPath = $tempDir . '/' . uniqid('stream_') . '.pdf';
                $pdf->Output($tempPath, 'F');
                
                register_shutdown_function(function() use ($tempPath) {
                    if (file_exists($tempPath)) {
                        @unlink($tempPath);
                    }
                });

                return response()->file($tempPath);
            } catch (\Exception $e) {
                // Fallback jika terjadi error FPDI
                return response()->file($path);
            }
        }

        // Fallback untuk berkas non-PDF (docx, xlsx, dll)
        return response()->file($path);
    }

    public function download($id)
    {
        $document = Document::findOrFail($id);
        $path = public_path($document->file_document);

        if (!file_exists($path)) {
            abort(404);
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $cleanTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $document->title);
        $filename = $cleanTitle . '.' . $extension;

        // Hanya apply watermark jika bertipe PDF
        if (strtolower($extension) === 'pdf') {
            try {
                $pdf = $this->applyWatermark($document);
                $pdfContent = $pdf->Output('', 'S');
                
                return response($pdfContent)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                    ->header('Cache-Control', 'private, must-revalidate, post-check=0, pre-check=0')
                    ->header('Pragma', 'public');
            } catch (\Exception $e) {
                // Fallback jika terjadi error FPDI
                return response()->download($path, $filename);
            }
        }

        return response()->download($path, $filename);
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title'                => 'required|string|max:300',
            'document_category_id' => 'required',
            'document_code_id'     => 'required',
            'department_id'        => 'required',
            'document_number'      => 'nullable|string|max:255',
            'document_number_prefix' => 'nullable|string|max:100',
            'document_number_suffix' => 'nullable|string|max:100',
            'revision'             => 'nullable|string|max:50',
            'document_date'        => 'nullable|date',
            'description'          => 'nullable|string',
            'file_document'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:50000',
        ]);

        $data = $request->except('file_document');

        if ($request->hasFile('file_document')) {

            // Hapus file lama
            if ($document->file_document && file_exists(public_path($document->file_document))) {
                unlink(public_path($document->file_document));
            }

            $file = $request->file('file_document');

            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('documents');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);

            $data['file_document'] = 'documents/' . $filename;
        }

        $document->update($data);

        // 🔥 UPDATE SUB DETAIL URAIAN EXISTING (Jika ada)
        if ($request->has('existing_sub_title')) {
            foreach ($request->existing_sub_title as $id => $sub) {
                if (!empty($sub)) {
                    $detail = \App\Models\DocumentDetail::find($id);
                    if ($detail) {
                        $detail->update([
                            'sub_title' => $sub,
                            'department_ids' => json_decode($request->existing_sub_department[$id] ?? '[]'),
                            'description' => $request->existing_sub_description[$id] ?? null,
                        ]);
                    }
                }
            }
        }

        // 🔥 SIMPAN SUB DETAIL URAIAN BARU (Jika ada)
        if ($request->has('sub_title')) {
            foreach ($request->sub_title as $i => $sub) {
                if (!empty($sub)) {
                    \App\Models\DocumentDetail::create([
                        'document_id' => $document->id,
                        'sub_title' => $sub,
                        'department_ids' => json_decode($request->sub_department[$i] ?? '[]'),
                        'description' => $request->sub_description[$i] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil diperbarui');
    }


    public function destroy(Request $request, Document $document)
    {
        $request->validate([
            'delete_reason' => 'required|string|max:1000'
        ]);

        $document->delete_reason = $request->delete_reason;
        $document->deleted_by = auth()->id();
        $document->save();

        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil dihapus');
    }

    public function trash(Request $request)
    {
        $query = Document::onlyTrashed()->with(['category', 'code', 'department', 'deleter', 'uploader']);

        if ($request->filled('category') && $request->category != 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('department') && !empty($request->department)) {
            $departmentsInput = array_filter((array) $request->department);
            if (count($departmentsInput) > 0) {
                $query->whereIn('department_id', $departmentsInput);
            }
        }

        if ($request->has('code') && !empty($request->code)) {
            $codesInput = array_filter((array) $request->code);
            if (count($codesInput) > 0) {
                $query->whereIn('document_code_id', $codesInput);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $documents = $query->latest('deleted_at')->paginate(10)->withQueryString();

        $categories = DocumentCategory::all();
        $departments = Department::orderBy('name')->get();
        $codes = DocumentCode::orderBy('code')->get();

        return view('admin.documents.trash', compact('documents', 'categories', 'departments', 'codes'));
    }

    public function restore($id)
    {
        $document = Document::onlyTrashed()->findOrFail($id);
        $document->restore();

        return redirect()->route('admin.documents.trash')
            ->with('success', 'Dokumen berhasil direstore');
    }

    public function forceDelete($id)
    {
        $document = Document::onlyTrashed()->findOrFail($id);

        // Hapus file permanen
        if ($document->file_document && file_exists(public_path($document->file_document))) {
            unlink(public_path($document->file_document));
        }

        $document->forceDelete();

        return redirect()->route('admin.documents.trash')
            ->with('success', 'Dokumen dihapus permanen');
    }

    public function export(Request $request)
    {
        $query = Document::with(['category', 'code', 'department', 'details.department']);

        // Kalau ada filter category dan bukan all
        if ($request->filled('category') && $request->category != 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('department') && !empty($request->department)) {
            $departmentsInput = array_filter((array) $request->department);
            if (count($departmentsInput) > 0) {
                $query->whereIn('department_id', $departmentsInput);
            }
        }

        if ($request->has('code') && !empty($request->code)) {
            $codesInput = array_filter((array) $request->code);
            if (count($codesInput) > 0) {
                $query->whereIn('document_code_id', $codesInput);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $documents = $query->latest()->get();

        return Excel::download(new DocumentsExport($documents), 'Data_Dokumen.xlsx');
    }

    public function exportTrash(Request $request)
    {
        $query = Document::onlyTrashed()->with(['category', 'code', 'department', 'deleter', 'uploader']);

        $activeFilters = [
            'category' => $request->category ?? 'all',
            'departments' => [],
            'codes' => [],
            'search' => $request->search ?? null,
            'operator' => auth()->check() ? auth()->user()->name : 'Sistem',
        ];

        if ($request->filled('category') && $request->category != 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('department') && !empty($request->department)) {
            $departmentsInput = array_filter((array) $request->department);
            if (count($departmentsInput) > 0) {
                $query->whereIn('department_id', $departmentsInput);
                $activeFilters['departments'] = \App\Models\Department::whereIn('id', $departmentsInput)->pluck('name')->toArray();
            }
        }

        if ($request->has('code') && !empty($request->code)) {
            $codesInput = array_filter((array) $request->code);
            if (count($codesInput) > 0) {
                $query->whereIn('document_code_id', $codesInput);
                $activeFilters['codes'] = \App\Models\DocumentCode::whereIn('id', $codesInput)->pluck('code')->toArray();
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $documents = $query->latest('deleted_at')->get();

        return Excel::download(new DeletedDocumentsExport($documents, $activeFilters), 'Data_Dokumen_Terhapus.xlsx');
    }
}
