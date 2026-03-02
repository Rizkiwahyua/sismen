<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Events\AfterSheet;

class DocumentsExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    ShouldAutoSize,
    WithEvents
{
    protected $documents;

    public function __construct($documents)
    {
        $this->documents = $documents;
    }

    public function collection()
    {
        return $this->documents->values()->map(function ($doc, $index) {
            return [
                'No'               => $index + 1,
                'Nomor Dokumen'    => $doc->document_number,
                'Judul'            => $doc->title,
                'Revisi'           => $doc->revision ?? 0,
                'Kategori'         => $doc->category->name ?? '-',
                'Kode Dokumen'     => $doc->code->code ?? '-',
                'Unit Kerja'       => $doc->department->name ?? '-',
                'Tanggal Dokumen'  => \Carbon\Carbon::parse($doc->document_date)->format('d-m-Y'),
                'Keterangan'       => $doc->description ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Dokumen',
            'Judul',
            'Revisi',
            'Kategori',
            'Kode Dokumen',
            'Unit Kerja',
            'Tanggal Dokumen',
            'Keterangan',
        ];
    }

    // STYLE HEADER
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // baris header
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
                        'rgb' => '1E40AF', // biru elegan
                    ],
                ],
            ],
        ];
    }

    // BORDER + FREEZE HEADER
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Border semua tabel
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Freeze header
                $sheet->freezePane('A2');
            },
        ];
    }
}
