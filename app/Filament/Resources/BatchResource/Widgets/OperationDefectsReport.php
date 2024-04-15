<?php

namespace App\Filament\Resources\BatchResource\Widgets;

use App\Service\ConstantData;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OperationDefectsReport extends AbstractWidget
{
    protected static string $view = 'filament.resources.batch-resource.widgets.operation-defects-report';

    public ?Model $record = null;

    private const SIZES = ConstantData::SIZES;

    public function getDress(bool $max)
    {
        $statement = DB::table('operation_defect_reports')
            ->where('operation_defect_reports.batch_id', $this->record->id)
            ->join('dresses', 'operation_defect_reports.dress_id', '=', 'dresses.id')
            ->join('colors', 'dresses.color_id', '=', 'colors.id')
            ->groupBy('size_id', 'dress_id')
            ->select(DB::raw('SUM(quantity) as number, code, title, size_id'));

        $maxResult = [];
        foreach ($statement->get() as $row) {
            // Check if the size already exists in the maxResult array
            if (isset($maxResult[self::SIZES[$row->size_id]])) {
                if ($max && $row->number > $maxResult[self::SIZES[$row->size_id]]['value']) {
                    $maxResult[self::SIZES[$row->size_id]] = ['value' => $row->number, 'dress' => $row->code, 'color' => $row->title];
                } elseif (!$max && $row->number < $maxResult[self::SIZES[$row->size_id]]['value']) {
                    $maxResult[self::SIZES[$row->size_id]] = ['value' => $row->number, 'dress' => $row->code, 'color' => $row->title];
                }
            } else {
                // Add the size and the attributes to the maxResult array
                $maxResult[self::SIZES[$row->size_id]] = ['value' => $row->number, 'dress' => $row->code, 'color' => $row->title];
            }
        }

        foreach ($maxResult as $size => $entry) {
            $matchingRows = $statement->get()->filter(fn($row)=> self::SIZES[$row->size_id] == $size && $row->number == $entry['value']);
            $dresses = array_map(
                fn($matchingRow) => ['code' => $matchingRow->code, 'color' => $matchingRow->title],
                $matchingRows->toArray());
            $dresses = array_values($dresses);
            $maxResult[$size] = ['value' => $entry['value'], 'dresses' => $dresses];
        }

        return $maxResult;
    }

    public function getDefect(bool $max)
    {
        $statement = DB::table('operation_defect_reports')
            ->where('operation_defect_reports.batch_id', $this->record->id)
            ->join('operation_defects', 'operation_defect_reports.defect_id', '=', 'operation_defects.id')
            ->groupBy('size_id', 'defect_id')
            ->select(DB::raw('SUM(quantity) as sum, size_id, operation_defects.title as defect'));

        $sums = [];
        foreach ($statement->get() as $row) {
            $size = self::SIZES[$row->size_id];
            $sum = $row->sum;
            $defect = $row->defect;

            $sums[$size][$defect] = $sum;
        }

        $func = $max ? "max" : "min";

        $result = [];
        foreach ($sums as $size => $sum) {
            $maxValue = $func($sum);
            $maxDefects = array_keys($sum, $maxValue);
            $result[$size] = ['value' => $maxValue, 'defects' => $maxDefects];
        }
        return $result;
    }

    public function getDate()
    {
        $date = DB::table('operation_defect_reports')->min('created_at');
        if ($date) {
            $oDate = new \DateTime($date);
            return $oDate->format("Y-m-d");
        } else {
            return null;
        }
    }
}
