<?php

namespace App\Filament\Resources\BatchResource\Widgets;

use App\Service\ConstantData;
use DateTime;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CuttingDefectsReport extends AbstractWidget
{
    protected static string $view = 'filament.resources.batch-resource.widgets.cutting-defects-report';

    public ?Model $record = null;
    const CUTTING_DEFECTS = ConstantData::CUTTING_DEFECTS;
    private const SIZES = ConstantData::SIZES;

    public function getDefect(bool $max)
    {
        $sums = [];
        for ($i = 1; $i <= count(self::CUTTING_DEFECTS); $i++) {
            $query = DB::table('cutting_defects')
                ->where('batch_id', $this->record->id)
                ->groupBy('size_id')
                ->select(DB::raw("SUM(a$i) as sum, size_id"))->get();

            foreach ($query as $row) {
                $sums[self::SIZES[$row->size_id]][self::CUTTING_DEFECTS[$i]] = $row->sum;
            }
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

    public function getDress(bool $max)
    {
        $statement = DB::table('cutting_defects')
            ->where('cutting_defects.batch_id', $this->record->id)
            ->join('dresses', 'cutting_defects.dress_id', '=', 'dresses.id')
            ->join('colors', 'dresses.color_id', '=', 'colors.id')
            ->groupBy('size_id', 'dress_id')
            ->select(DB::raw('SUM(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10) as number, code, title, size_id'));

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

