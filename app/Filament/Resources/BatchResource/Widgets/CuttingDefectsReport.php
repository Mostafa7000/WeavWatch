<?php

namespace App\Filament\Resources\BatchResource\Widgets;

use App\Service\ConstantData;
use DateTime;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CuttingDefectsReport extends Widget
{
    protected static string $view = 'filament.resources.batch-resource.widgets.cutting-defects-report';

    public ?Model $record = null;
    const CUTTING_DEFECTS = ConstantData::CUTTING_DEFECTS;

    public function getDefect(bool $max)
    {
        $sums = [];
        for ($i = 1; $i <= count(self::CUTTING_DEFECTS); $i++) {
            $sum = DB::table('cloth_defects')
                ->where('batch_id', $this->record->id)
                ->sum("a$i");
            $sums[self::CUTTING_DEFECTS[$i]] = $sum;
        }

        $func = $max ? "max" : "min";
        $maxValue = $func($sums);
        $maxDefect = array_keys($sums, $maxValue)[0];
        return ['value' => $maxValue, 'defect' => $maxDefect];
    }

    public function getDress(bool $max)
    {
        $statement = DB::table('cutting_defects')
            ->where('cutting_defects.batch_id', $this->record->id)
            ->join('dresses', 'cutting_defects.dress_id', '=', 'dresses.id')
            ->join('colors', 'dresses.color_id', '=', 'colors.id')
            ->groupBy('dress_id')
            ->select(DB::raw('SUM(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10) as number, code, title'));
        if ($max) {
            $statement->orderBy('number', 'DESC');
        } else {
            $statement->orderBy('number', 'ASC');
        }

        $row = $statement->first();

        if ($row !== null) {
            return ['value' => $row->number, 'dress' => $row->code, 'color' => $row->title];
        } else {
            return [];
        }
    }

    public function getDate()
    {
        $date = DB::table('cutting_defects')->min('created_at');
        if ($date) {
            $oDate = new DateTime($date);
            return $oDate->format("Y-m-d");
        } else {
            return null;
        }
    }

}

