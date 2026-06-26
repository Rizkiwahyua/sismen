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

class DeletedDocumentsExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithEvents,
    WithCustomStartCell,
    WithColumnWidths
{
    protected $documents;
    protected $activeFilters;
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
            'C' => 45,  // Judul Dokumen
            'D' => 18,  // Kategori
            'E' => 30,  // Unit Kerja
            'F' => 35,  // Keterangan Hapus
            'G' => 45,  // Dihapus Oleh (combined info)
        ];
    }

    public function collection()
    {
        $rows = collect();
        $no = 1;
        $currentRowIndex = 6; // Data starts at row 6 since startRow is 5, headings are row 5

        foreach ($this->documents as $doc) {
            $rows->push([
                'No'               => $no++,
                'Nomor Dokumen'    => $doc->document_number ?? '-',
                'Judul Dokumen'    => $doc->title,
                'Kategori'         => $doc->category->name ?? '-',
                'Unit Kerja'       => $doc->department->name ?? '-',
                'Keterangan Hapus' => $doc->delete_reason ?? '-',
                'Dihapus Oleh'     => "• Uploader: " . ($doc->uploader->name ?? '-') . "\n" .
                                      "Hapus " . ($doc->deleter->name ?? '-') . " : " . ($doc->deleted_at ? $doc->deleted_at->format('d-m-Y H:i') : '-'),
            ]);
            $currentRowIndex++;
        }

        // Tambah baris total footer
        $rows->push([
            'No'               => 'TOTAL',
            'Nomor Dokumen'    => ($no - 1) . ' Dokumen Terhapus',
            'Judul Dokumen'    => '',
            'Kategori'         => '',
            'Unit Kerja'       => '',
            'Keterangan Hapus' => '',
            'Dihapus Oleh'     => '',
        ]);
        $this->totalRowIndex = $currentRowIndex;

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Dokumen',
            'Judul Dokumen',
            'Kategori',
            'Unit Kerja',
            'Keterangan Hapus',
            'Dihapus Oleh',
        ];
    }

    // STYLE HEADER & ALIGNMENTS
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A:G')->getAlignment()->setWrapText(true);

        // Alignments: No, Nomor, Kategori -> Center
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B:B')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D:D')->getAlignment()->setHorizontal('center');

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
                        'rgb' => 'DC2626', // Crimson Red untuk Recycle Bin
                    ],
                ],
            ],
        ];
    }

    // EVENT HANDLERS (KOP BLOCK, FONTS, BORDERS, HIGHLIGHTS, ETC)
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // 0. SET FONT KELUARGA MODERN "Segoe UI" UNTUK SELURUH DOKUMEN
                $sheet->getStyle('A1:G' . $highestRow)->getFont()->setName('Segoe UI');
                
                // Set perataan vertikal ke tengah untuk seluruh tabel data
                $sheet->getStyle('A5:G' . $highestRow)->getAlignment()->setVertical('center');

                // 1. TULIS BLOCK KOP JUDUL LAPORAN DI BARIS 1-3
                // Baris 1: Judul Laporan
                $sheet->setCellValue('A1', 'LAPORAN DOKUMEN TERHAPUS (RECYCLE BIN) SMTI');
                $sheet->mergeCells('A1:G1');
                $sheet->getStyle('A1')->getFont()->setSize(15)->setBold(true)->getColor()->setRGB('DC2626');
                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getStyle('A1')->getAlignment()->setVertical('center');

                // Baris 2: Tanggal Cetak & Operator
                $printDate = date('d-m-Y H:i');
                $operatorName = $this->activeFilters['operator'] ?? 'Sistem';
                $sheet->setCellValue('A2', "Tanggal Cetak: {$printDate} WIB | Operator: {$operatorName}");
                $sheet->mergeCells('A2:G2');
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

                $codesLabel = 'Semua';
                if (!empty($this->activeFilters['codes'])) {
                    $codesLabel = implode(', ', $this->activeFilters['codes']);
                }
                
                $filterText = "Kategori: {$categoryLabel} | Unit Kerja: {$deptsLabel} | Kode Dokumen: {$codesLabel}";
                if (!empty($this->activeFilters['search'])) {
                    $filterText .= " | Pencarian: \"{$this->activeFilters['search']}\"";
                }

                $sheet->setCellValue('A3', "Filter Kriteria — {$filterText}");
                $sheet->mergeCells('A3:G3');
                $sheet->getStyle('A3')->getFont()->setSize(9)->getColor()->setRGB('64748B');
                $sheet->getRowDimension(3)->setRowHeight(18);
                $sheet->getStyle('A3')->getAlignment()->setVertical('center');

                // 2. BORDER SEMUA SEL TABEL (Baris 5 ke bawah)
                $sheet->getStyle('A5:G' . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()->setRGB('E2E8F0'); // soft light borders

                // 3. FREEZE PANE BARIS 5
                $sheet->freezePane('A6');

                // 4. STYLE ALTERNATE ROW COLORS UNTUK DOKUMEN UTAMA
                $mainRowNo = 0;
                for ($row = 6; $row < $this->totalRowIndex; $row++) {
                    $mainRowNo++;
                    if ($mainRowNo % 2 === 0) {
                        $sheet->getStyle('A' . $row . ':G' . $row)
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('F8FAFC'); // very soft light gray alternate rows
                    }
                }

                // 5. STYLE TOTAL ROW (Baris paling bawah dengan bottom double border)
                if ($this->totalRowIndex) {
                    $tr = $this->totalRowIndex;
                    $sheet->getStyle('A' . $tr . ':G' . $tr)
                        ->getFont()
                        ->setBold(true);
                    
                    $sheet->getStyle('A' . $tr . ':G' . $tr)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('F1F5F9');
                        
                    $sheet->getStyle('A' . $tr . ':B' . $tr)
                        ->getAlignment()
                        ->setHorizontal('center');

                    // Double bottom accounting borders
                    $sheet->getStyle('A' . $tr . ':G' . $tr)
                        ->getBorders()
                        ->getTop()
                        ->setBorderStyle(Border::BORDER_THIN)
                        ->getColor()->setRGB('94A3B8');
                    $sheet->getStyle('A' . $tr . ':G' . $tr)
                        ->getBorders()
                        ->getBottom()
                        ->setBorderStyle(Border::BORDER_DOUBLE)
                        ->getColor()->setRGB('94A3B8');
                }

                // 6. SET DATA ROW HEIGHTS (Slightly taller for multiline "Dihapus Oleh")
                for ($row = 6; $row <= $highestRow; $row++) {
                    if ($row !== $this->totalRowIndex) {
                        $sheet->getRowDimension($row)->setRowHeight(34);
                    } else {
                        $sheet->getRowDimension($row)->setRowHeight(22);
                    }
                }
            },
        ];
    }
}
