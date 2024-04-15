<?php

namespace App\Http\Controllers;

use App\Filament\Resources\BatchResource\Widgets\ClothDefectsReport;
use App\Filament\Resources\BatchResource\Widgets\CuttingDefectsReport;
use App\Filament\Resources\BatchResource\Widgets\IronDefectsReport;
use App\Filament\Resources\BatchResource\Widgets\NeedleDefectsReport;
use App\Filament\Resources\BatchResource\Widgets\OperationDefectsReport;
use App\Filament\Resources\BatchResource\Widgets\PackagingDefectsReport;
use App\Filament\Resources\BatchResource\Widgets\PrintDefectsReport;
use App\Models\Batch;
use App\Models\BatchSize;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Blade;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class PDFController extends Controller
{
    public function __invoke(Batch $record): void
    {
        $mpdf = new Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;

        $mpdf->WriteHTML(
            $this->getHTML($record)
        );

        $mpdf->Output($record->batch_number.'.pdf', Destination::INLINE);
    }

    private function getStyle(): string
    {
        return '<head>
    <style>
         .page-break {
            page-break-after: always;
        }
        body {
            direction: rtl;
        }
        .scrollable-table th:nth-child(1),
        .scrollable-table th:nth-child(2) {
            width: 120px; /* Adjust the width as needed */
        }
        .scrollable-table {
            font-family: Arial, sans-serif;
            border-radius: 15px;
        }

        .scrollable-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .scrollable-table th,
        .scrollable-table td {
            padding: 8px;
            text-align: center;
            border: 1px solid #000; /* Adjust border color as needed */
        }

        .scrollable-table th {
            background-color: #f2f2f2; /* Header background color */
            font-weight: bold;
        }

        .scrollable-table tbody tr:nth-child(even) {
            background-color: #f9f9f9; /* Alternate row color */
        }

        .scrollable-table thead th {
            position: sticky;
            top: 0;
            background-color: #94c1ff; /* Adjust the background color as needed */
        }

    </style>
</head>';
    }

    private function getHTML(Batch $record): string
    {
        return
            $this->getStyle().
            Blade::render('pdf.header', ['record' => $record]).
            Blade::render('pdf.size-quantity-report', ['data' => $this->getBatchSize($record)]).
            Blade::render('pdf.cloth-defects-report', ['parent' => new ClothDefectsReport($record)]).
            Blade::render('pdf.cutting-defects-report', ['parent' => new CuttingDefectsReport($record)]).
            Blade::render('pdf.needle-defects-report', ['parent' => new NeedleDefectsReport($record)]).
            Blade::render('pdf.print-defects-report', ['parent' => new PrintDefectsReport($record)]).
            Blade::render('pdf.operation-defects-report', ['parent' => new OperationDefectsReport($record)]).
            Blade::render('pdf.iron-defects-report', ['parent' => new IronDefectsReport($record)]).
            Blade::render('pdf.packaging-defects-report', ['parent' => new PackagingDefectsReport($record)]).
            Blade::render('pdf.signature');
    }

    private function getBatchSize(Batch $batch): array
    {
        /** @var Collection $collection */
        $collection = $batch->sizeQuantity;
        return $collection
            ->sortBy(fn(BatchSize $batchSize) => $batchSize->size->id)
            ->map(fn(BatchSize $batchSize) => [
                'size' => $batchSize->size->title, 'quantity' => $batchSize->quantity
            ])->toArray();
    }
}
