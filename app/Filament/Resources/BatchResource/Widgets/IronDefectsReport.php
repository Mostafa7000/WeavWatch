<?php

namespace App\Filament\Resources\BatchResource\Widgets;

use App\Service\ConstantData;
use DateTime;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IronDefectsReport extends Widget
{
    protected static string $view = 'filament.resources.batch-resource.widgets.iron-defects-report';

    public ?Model $record = null;
    private const IRON_DEFECTS = ConstantData::IRON_DEFECTS;
    private const SIZES = ConstantData::SIZES;

    public function getDefect(bool $max): array
    {
        $sums = [];
        for ($i = 1; $i <= 12; $i++) {
            $query = DB::table('iron_defects')
                ->where('batch_id', $this->record->id)
                ->groupBy('size_id')
                ->select(DB::raw("SUM(a$i) as sum, size_id"))->get();

            foreach ($query as $row) {
                $sums[self::SIZES[$row->size_id]][self::IRON_DEFECTS[$i]] = $row->sum;
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
        $statement = DB::table('iron_defects')
            ->where('iron_defects.batch_id', $this->record->id)
            ->join('dresses', 'iron_defects.dress_id', '=', 'dresses.id')
            ->join('colors', 'dresses.color_id', '=', 'colors.id')
            ->groupBy('size_id', 'dress_id')
            ->select(DB::raw('SUM(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10 + a11 + a12) as number, code, title, size_id'));

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
        $date = DB::table('iron_defects')->min('created_at');
        if ($date) {
            $oDate = new DateTime($date);
            return $oDate->format("Y-m-d");
        } else {
            return null;
        }
    }
}
