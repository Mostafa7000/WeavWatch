<?php

namespace App\Filament\Resources\BatchResource\Widgets;

use App\Service\ConstantData;
use DateTime;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PreparationDefectsReport extends Widget
{
    protected static string $view = 'filament.resources.batch-resource.widgets.preparation-defects-report';

    public ?Model $record = null;

    public const TABLE = 'preparation_defects';
    const PREPARATION_DEFECTS = ConstantData::PREPARATION_DEFECTS;

    public function getDefect(bool $max)
    {
        $sums = [];
        for ($i = 1; $i <= 21; $i++) {
            $sum = DB::table(self::TABLE)
                ->where('batch_id', $this->record->id)
                ->sum("a$i");
            $sums[self::PREPARATION_DEFECTS[$i]] = $sum;
        }

        $func = $max ? "max" : "min";
        $maxValue = $func($sums);
        $maxDefects = array_keys($sums, $maxValue);
        return ['value' => $maxValue, 'defects' => $maxDefects];
    }

    public function getDress(bool $max)
    {
        $statement = DB::table('preparation_defects')
            ->where('preparation_defects.batch_id', $this->record->id)
            ->join('dresses', 'preparation_defects.dress_id', '=', 'dresses.id')
            ->join('colors', 'dresses.color_id', '=', 'colors.id')
            ->groupBy('dress_id')
            ->select(DB::raw('SUM(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10 + a11 + a12 + a13 + a14 + a15 + a16 + a17 + a18 + a19 + a20 + a21) as number, code, title'));
        if ($max) {
            $statement->orderBy('number', 'DESC');
        } else {
            $statement->orderBy('number', 'ASC');
        }

        $rows = $statement->get();

        $firstRow = $rows->first();

        if ($firstRow == null) {
            return [];
        }

        $matchingRows = $rows->filter(fn ($row) => $row->number == $firstRow->number);

        $dresses = array_map(
            fn($matchingRow) => ['code' => $matchingRow->code, 'color' => $matchingRow->title],
            $matchingRows->toArray());

        return ['value' => $firstRow->number, 'dresses' => $dresses];
    }

    public function getDate()
    {
        $date = DB::table('preparation_defects')->min('created_at');
        if ($date) {
            $oDate = new DateTime($date);
            return $oDate->format("Y-m-d");
        } else {
            return null;
        }
    }
}
