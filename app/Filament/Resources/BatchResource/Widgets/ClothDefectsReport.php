<?php

namespace App\Filament\Resources\BatchResource\Widgets;

use App\Service\ConstantData;
use DateTime;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\select;

class ClothDefectsReport extends Widget
{
    protected static string $view = 'filament.resources.batch-resource.widgets.cloth-defects-report';
    public ?Model $record = null;
    private const CLOTH_DEFECTS = ConstantData::CLOTH_DEFECTS;

    public function getDefect(bool $max)
    {
        $sums = [];
        for ($i = 1; $i <= 19; $i++) {
            $sum = DB::table('cloth_defects')
                ->where('batch_id', $this->record->id)
                ->sum("a$i");
            $sums[self::CLOTH_DEFECTS[$i]] = $sum;
        }

        $func = $max ? "max" : "min";
        $maxValue = $func($sums);
        $maxDefect = array_keys($sums, $maxValue)[0];
        return ['value' => $maxValue, 'defect' => $maxDefect];
    }

    public function getDress(bool $max)
    {
        $statement = DB::table('cloth_defects')
            ->where('cloth_defects.batch_id', $this->record->id)
            ->join('dresses', 'cloth_defects.dress_id', '=', 'dresses.id')
            ->join('colors', 'dresses.color_id', '=', 'colors.id')
            ->groupBy('dress_id')
            ->select(DB::raw('SUM(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10 + a11 + a12 + a13 + a14 + a15 + a16 + a17 + a18 + a19) as number, code, title'));
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

    /**
     * @throws \Exception
     */
    public function getDate()
    {
        $date = DB::table('cloth_defects')->min('created_at');
        if ($date) {
            $oDate = new DateTime($date);
            return $oDate->format("Y-m-d");
        } else {
            return null;
        }
    }
}
