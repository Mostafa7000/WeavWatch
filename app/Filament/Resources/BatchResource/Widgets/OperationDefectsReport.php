<?php

namespace App\Filament\Resources\BatchResource\Widgets;

use App\Service\ConstantData;
use DateTime;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OperationDefectsReport extends Widget
{
    protected static string $view = 'filament.resources.batch-resource.widgets.operation-defects-report';

    public ?Model $record = null;

    private const SIZES = ConstantData::SIZES;

    private const HOURS = ConstantData::HOURS;

    public function getHour(bool $max)
    {
        $result = [];
        for ($i = 1; $i <= 10; $i++) {
            $query = DB::table('operation_defect_reports')
                ->where('operation_defect_reports.batch_id', $this->record->id)
                ->join('dresses', 'operation_defect_reports.dress_id', '=', 'dresses.id')
                ->join('colors', 'dresses.color_id', '=', 'colors.id')
                ->groupBy('dress_id', 'size_id')
                ->select(DB::raw("SUM(a$i) as sum, size_id, code, title"))->get();

            foreach ($query as $row) {
                $size = self::SIZES[$row->size_id];
                $code = $row->code;
                $sum = $row->sum;

                // If this size and code combination doesn't exist in the sums array, or if the new sum is greater than the existing sum, update the sum
                if (!isset($result[$size][$code]) || ($max && $sum > $result[$size][$code]['sum'])) {
                    $result[$size][$code] = ['color' => $row->title, 'hour' => self::HOURS[$i], 'sum' => $sum];
                } else if (!$max && $sum < $result[$size][$code]['sum']) {
                    $result[$size][$code] = ['color' => $row->title, 'hour' => self::HOURS[$i], 'sum' => $sum];
                }
            }
        }

        return $result;
    }


    public function getDefect(bool $max)
    {
        $statement = DB::table('operation_defect_reports')
            ->where('operation_defect_reports.batch_id', $this->record->id)
            ->join('dresses', 'operation_defect_reports.dress_id', '=', 'dresses.id')
            ->join('colors', 'dresses.color_id', '=', 'colors.id')
            ->join('operation_defects', 'operation_defect_reports.defect_id', '=', 'operation_defects.id')
            ->groupBy('size_id', 'dress_id', 'defect_id')
            ->select(DB::raw('SUM(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10) as sum, code, colors.title as color,size_id, operation_defects.title as defect'));

        $result = [];
        foreach ($statement->get() as $row) {
            $size = self::SIZES[$row->size_id];
            $code = $row->code;
            $sum = $row->sum;
            $defect = $row->defect;

            if (!isset($result[$size][$code]) || ($max && $sum > $result[$size][$code]['sum'])) {
                $result[$size][$code] = ['color' => $row->color, 'defect' => $defect, 'sum' => $sum];
            } else if (!$max && $sum < $result[$size][$code]['sum']) {
                $result[$size][$code] = ['color' => $row->color, 'defect' => $defect, 'sum' => $sum];
            }
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
