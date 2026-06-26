<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Events\AfterSheet;

class DocumentsExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithEvents,
    WithCustomStartCell,
    WithColumnWidths
{
    protected $documents;
    protected $activeFilters;
    protected $detailRows = [];
    protected $totalRowIndex = null;

    public function __construct($documents, array $activeFilters = [])
    {
        $this->documents = $documents;
        $this->activeFilters = $activeFilters;
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // No
            'B' => 25,  // Nomor Dokumen
            'C' => 45,  // Judul
            'D' => 10,  // Revisi
            'E' => 18,  // Kategori
            'F' => 30,  // Unit Kerja
            'G' => 18,  // Tanggal Dokumen
            'H' => 45,  // Keterangan
        ];
    }

    public function collection()
    {
        $rows = collect();
        $no = 1;
        $currentRowIndex = 6; // Data starts at row 6 since startRow is 5, headings are row 5

        foreach ($this->documents as $doc) {
            // ✅ DOKUMEN UTAMA
            $rows->push([
                'No'               => $no++,
                'Nomor Dokumen'    => $doc->document_number,
                'Judul'            => $doc->title,
                'Revisi'           => $doc->revision ?? 0,
                'Kategori'         => $doc->category->name ?? '-',
                'Unit Kerja'       => $doc->department->name ?? '-',
                'Tanggal Dokumen'  => \Carbon\Carbon::parse($doc->document_date)->format('d-m-Y'),
                'Keterangan'       => $doc->description ?? '-',
            ]);
            $currentRowIndex++;

            // ✅ SUB JUDUL KHUSUS RATIFIKASI
            if ($doc->category && $doc->category->slug == 'ratifikasi') {
                foreach ($doc->details as $d) {
                    $rows->push([
                        'No'               => '',
                        'Nomor Dokumen'    => '',
                        'Judul'            => '   - ' . $d->sub_title,
                        'Revisi'           => '',
                        'Kategori'         => '',
                        'Unit Kerja'       => !empty($d->department_ids)
                            ? implode(
                                ", ",
                                \App\Models\Department::whereIn(
                                    'id',
                                    is_array($d->department_ids)
                                        ? $d->department_ids
                                        : json_decode($d->department_ids, true)
                                )->pluck('name')->toArray()
                            )
                            : '-',
                        'Tanggal Dokumen'  => '',
                        'Keterangan'       => $d->description ?? '-',
                    ]);
                    $this->detailRows[] = $currentRowIndex;
                    $currentRowIndex++;
                }
            }
        }

        // ✅ TAMBAH BARIS FOOTER TOTAL DI AKHIR TABEL
        $rows->push([
            'No'               => 'TOTAL',
            'Nomor Dokumen'    => ($no - 1) . ' Dokumen',
            'Judul'            => '',
            'Revisi'           => '',
            'Kategori'         => '',
            'Unit Kerja'       => '',
            'Tanggal Dokumen'  => '',
            'Keterangan'       => '',
        ]);
        $this->totalRowIndex = $currentRowIndex;

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Dokumen',
            'Judul',
            'Revisi',
            'Kategori',
            'Unit Kerja',
            'Tanggal Dokumen',
            'Keterangan',
        ];
    }

    // STYLE HEADER
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A:H')->getAlignment()->setWrapText(true);

        // Alignments: No, Nomor, Revisi, Tanggal -> Center
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B:B')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D:D')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('G:G')->getAlignment()->setHorizontal('center');

        // Set row heights
        $sheet->getRowDimension(5)->setRowHeight(28); // Header row height

        return [
            5 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical'   => 'center',
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '0F3C7A', // SMTI Navy Corporate Blue
                    ],
                ],
            ],
        ];
    }

    // BORDER + FREEZE HEADER + TITLES + ALTERNATE ROW COLORS
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // 0. SET FONT KELUARGA MODERN "Segoe UI" UNTUK SELURUH DOKUMEN
                $sheet->getStyle('A1:H' . $highestRow)->getFont()->setName('Segoe UI');
                
                // Set perataan vertikal ke tengah untuk seluruh tabel data
                $sheet->getStyle('A5:H' . $highestRow)->getAlignment()->setVertical('center');

                // 1. TULIS BLOCK KOP JUDUL LAPORAN DI BARIS 1-3
                // Baris 1: Judul Laporan
                $sheet->setCellValue('A1', 'LAPORAN REKAPITULASI DOKUMEN CORPORATE SMTI');
                $sheet->mergeCells('A1:H1');
                $sheet->getStyle('A1')->getFont()->setSize(15)->setBold(true)->getColor()->setRGB('0F3C7A');
                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getStyle('A1')->getAlignment()->setVertical('center');

                // Baris 2: Tanggal Cetak & Operator
                $printDate = date('d-m-Y H:i');
                $operatorName = $this->activeFilters['operator'] ?? 'Sistem';
                $sheet->setCellValue('A2', "Tanggal Cetak: {$printDate} WIB | Operator: {$operatorName}");
                $sheet->mergeCells('A2:H2');
                $sheet->getStyle('A2')->getFont()->setSize(10)->setItalic(true)->getColor()->setRGB('475569');
                $sheet->getRowDimension(2)->setRowHeight(18);
                $sheet->getStyle('A2')->getAlignment()->setVertical('center');

                // Baris 3: Kriteria Filter
                $categoryLabel = 'Semua Kategori';
                if (!empty($this->activeFilters['category']) && $this->activeFilters['category'] !== 'all') {
                    $categoryLabel = ucfirst($this->activeFilters['category']);
                }
                
                $deptsLabel = 'Semua';
                if (!empty($this->activeFilters['departments'])) {
                    $deptsLabel = implode(', ', $this->activeFilters['departments']);
                }
                
                $statusLabel = 'Semua Berkas';
                if (!empty($this->activeFilters['file_status'])) {
                    if ($this->activeFilters['file_status'] === 'lengkap') {
                        $statusLabel = 'Lengkap (Ada File)';
                    } elseif ($this->activeFilters['file_status'] === 'belum_upload') {
                        $statusLabel = 'Belum Upload (Kosong)';
                    }
                }
                
                $filterText = "Kategori: {$categoryLabel} | Unit Kerja: {$deptsLabel} | Status Berkas: {$statusLabel}";
                if (!empty($this->activeFilters['search'])) {
                    $filterText .= " | Pencarian: \"{$this->activeFilters['search']}\"";
                }
                if (!empty($this->activeFilters['start_date']) || !empty($this->activeFilters['end_date'])) {
                    $start = $this->activeFilters['start_date'] ?? '-';
                    $end = $this->activeFilters['end_date'] ?? '-';
                    $filterText .= " | Rentang Tanggal: {$start} s/d {$end}";
                }

                $sheet->setCellValue('A3', "Filter Kriteria — {$filterText}");
                $sheet->mergeCells('A3:H3');
                $sheet->getStyle('A3')->getFont()->setSize(9)->getColor()->setRGB('64748B');
                $sheet->getRowDimension(3)->setRowHeight(18);
                $sheet->getStyle('A3')->getAlignment()->setVertical('center');

                // 2. BORDER SEMUA SEL TABEL (Baris 5 ke bawah)
                $sheet->getStyle('A5:H' . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()->setRGB('E2E8F0'); // very soft light borders

                // 3. FREEZE PANE BARIS 5 (sehingga baris 1-5 diam saat di-scroll)
                $sheet->freezePane('A6');

                // 4. STYLE DETAIL ROWS (Baris detail sub-judul ratifikasi dengan light gray fill)
                foreach ($this->detailRows as $r) {
                    $sheet->getStyle('A' . $r . ':H' . $r)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('F8FAFC');
                    
                    $sheet->getStyle('C' . $r) // Kolom Judul (italic)
                        ->getFont()
                        ->setItalic(true)
                        ->getColor()->setRGB('475569');
                        
                    $sheet->getStyle('H' . $r) // Kolom Keterangan (italic)
                        ->getFont()
                        ->setItalic(true)
                        ->getColor()->setRGB('475569');
                }

                // 5. STYLE ALTERNATE ROW COLORS UNTUK DOKUMEN UTAMA
                $mainRowNo = 0;
                for ($row = 6; $row < $this->totalRowIndex; $row++) {
                    if (in_array($row, $this->detailRows)) {
                        continue;
                    }
                    $mainRowNo++;
                    if ($mainRowNo % 2 === 0) {
                        $sheet->getStyle('A' . $row . ':H' . $row)
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('F1F5F9'); // slate-100 alternate rows
                    }
                }

                // 6. STYLE TOTAL ROW (Baris paling bawah dengan bottom double border)
                if ($this->totalRowIndex) {
                    $tr = $this->totalRowIndex;
                    $sheet->getStyle('A' . $tr . ':H' . $tr)
                        ->getFont()
                        ->setBold(true);
                    
                    $sheet->getStyle('A' . $tr . ':H' . $tr)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('E2E8F0');
                        
                    $sheet->getStyle('A' . $tr . ':B' . $tr)
                        ->getAlignment()
                        ->setHorizontal('center');

                    // Double bottom accounting borders
                    $sheet->getStyle('A' . $tr . ':H' . $tr)
                        ->getBorders()
                        ->getTop()
                        ->setBorderStyle(Border::BORDER_THIN)
                        ->getColor()->setRGB('94A3B8');
                    $sheet->getStyle('A' . $tr . ':H' . $tr)
                        ->getBorders()
                        ->getBottom()
                        ->setBorderStyle(Border::BORDER_DOUBLE)
                        ->getColor()->setRGB('94A3B8');
                }

                // 7. SET DATA ROW HEIGHTS
                for ($row = 6; $row <= $highestRow; $row++) {
                    if ($row !== $this->totalRowIndex) {
                        $sheet->getRowDimension($row)->setRowHeight(20);
                    } else {
                        $sheet->getRowDimension($row)->setRowHeight(22);
                    }
                }
            },
        ];
    }
}
