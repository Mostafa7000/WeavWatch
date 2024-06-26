<?php

namespace App\Filament\Resources\BatchResource\Widgets;

use App\Service\ConstantData;
use DateTime;
use Illuminate\Support\Facades\DB;

class ClothDefectsReport extends AbstractWidget
{
    protected static string $view = 'filament.resources.batch-resource.widgets.cloth-defects-report';
    private const CLOTH_DEFECTS = ConstantData::CLOTH_DEFECTS;

    public function getDefect(bool $max): array
    {
        $sums = [];
        for ($i = 1; $i <= 40; $i++) {
            $sum = DB::table('cloth_defects')
                ->where('batch_id', $this->record->id)
                ->sum("a$i");
            $sums[self::CLOTH_DEFECTS[$i]] = $sum;
        }

        $func = $max ? "max" : "min";
        $maxValue = $func($sums);
        $maxDefects = array_keys($sums, $maxValue);
        return ['value' => $maxValue, 'defects' => $maxDefects];
    }

    public function getDress(bool $max): array
    {
        $statement = DB::table('cloth_defects')
            ->where('cloth_defects.batch_id', $this->record->id)
            ->join('dresses', 'cloth_defects.dress_id', '=', 'dresses.id')
            ->join('colors', 'dresses.color_id', '=', 'colors.id')
            ->groupBy('dress_id')
            ->select(DB::raw('SUM(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10 + a11 + a12 + a13 + a14 + a15 + a16 + a17 + a18 + a19 + a20 + a21 + a22 + a23 + a24 + a25 + a26 + a27 + a28 + a29 + a30 + a31 + a32 + a33 + a34 + a35 + a36 + a37 + a38 + a39 + a40) as number, code, title'));
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

        $matchingRows = $rows->filter(function ($row) use ($firstRow) {
            return $row->number == $firstRow->number;
        });

        $dresses = array_map(
            fn($matchingRow) => ['code' => $matchingRow->code, 'color' => $matchingRow->title],
            $matchingRows->toArray());

        return ['value' => $firstRow->number, 'dresses' => $dresses];
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
