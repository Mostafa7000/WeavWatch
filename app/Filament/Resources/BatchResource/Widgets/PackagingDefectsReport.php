<?php

namespace App\Filament\Resources\BatchResource\Widgets;

use App\Service\ConstantData;
use DateTime;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PackagingDefectsReport extends Widget
{
    protected static string $view = 'filament.resources.batch-resource.widgets.packaging-defects-report';

    public ?Model $record = null;
    private const PACKAGING_DEFECTS = ConstantData::PACKAGING_DEFECTS;

    private const SIZES = ConstantData::SIZES;
    public function getDefect(bool $max)
    {
        $sums = [];
        for ($i = 1; $i <= 18; $i++) {
            $query = DB::table('packaging_defects')
                ->where('batch_id', $this->record->id)
                ->groupBy('size_id')
                ->select(DB::raw("SUM(a$i) as sum, size_id"))->get();

            foreach ($query as $row) {
                $sums[self::SIZES[$row->size_id]][self::PACKAGING_DEFECTS[$i]] = $row->sum;
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
        $statement = DB::table('packaging_defects')
            ->where('packaging_defects.batch_id', $this->record->id)
            ->join('dresses', 'packaging_defects.dress_id', '=', 'dresses.id')
            ->join('colors', 'dresses.color_id', '=', 'colors.id')
            ->groupBy('size_id', 'dress_id')
            ->select(DB::raw('SUM(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10 + a11 + a12 + a13 + a14 + a15 + a16 + a17 + a18) as number, code, title, size_id'));

        $func = $max ? "max" : "min";

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
        $date = DB::table('packaging_defects')->min('created_at');
        if ($date) {
            $oDate = new DateTime($date);
            return $oDate->format("Y-m-d");
        } else {
            return null;
        }
    }
}
