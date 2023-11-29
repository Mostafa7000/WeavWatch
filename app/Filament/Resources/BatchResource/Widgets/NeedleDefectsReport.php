<?php

namespace App\Filament\Resources\BatchResource\Widgets;

use DateTime;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NeedleDefectsReport extends Widget
{
    protected static string $view = 'filament.resources.batch-resource.widgets.needle-defects-report';

    public ?Model $record = null;

    const NEEDLE_DEFECTS = [
        1 => 'تفويت غرزة',
        2 => 'ظهور خيط المكوك على خيط الحرير',
        3 => 'بقع زيت',
        4 => 'ترحيل الرسمة',
        5 => 'ترحيل الأبليك',
        6 => 'تنسيل',
        7 => 'عدم ضبط الشد',
        8 => 'عدم ضبط ألوان الفيلم',
        9 => 'كثافة الغرز',
        10 => 'ترحيل في مكان التطريز',
        11 => 'تشطيب أبليك سيء',
    ];
    const SIZES = [
        1 => 'S',
        2 => 'M',
        3 => 'L',
        4 => 'XL',
        5 => '2XL',
        6 => '3XL',
        7 => '4XL',
    ];


    public function getDefect(bool $max)
    {
        $sums = [];
        for ($i = 1; $i <= 11; $i++) {
            $query = DB::table('needle_defects')
                ->where('batch_id', $this->record->id)
                ->groupBy('size_id')
                ->select(DB::raw("SUM(a$i) as sum, size_id"))->get();

            foreach ($query as $row) {
                $sums[self::SIZES[$row->size_id]][self::NEEDLE_DEFECTS[$i]] = $row->sum;
            }
        }

        $func = $max ? "max" : "min";

        $result = [];
        foreach ($sums as $size => $sum) {
            $maxValue = $func($sum);
            $maxDefect = array_keys($sum, $maxValue)[0];
            $result[$size] = ['value' => $maxValue, 'defect' => $maxDefect];
        }
        return $result;
    }


    public function getDress(bool $max)
    {
        $statement = DB::table('needle_defects')
            ->where('needle_defects.batch_id', $this->record->id)
            ->join('dresses', 'needle_defects.dress_id', '=', 'dresses.id')
            ->join('colors', 'dresses.color_id', '=', 'colors.id')
            ->groupBy('size_id', 'dress_id')
            ->select(DB::raw('SUM(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10 + a11) as number, code, title, size_id'));

        $func = $max ? "max" : "min";

        $result = [];
        foreach ($statement->get() as $row) {
            // Check if the size already exists in the result array
            if (isset($result[self::SIZES[$row->size_id]])) {
                // Compare the current value with the existing value and keep the smaller one
                $min_value = $func($result[self::SIZES[$row->size_id]]['value'], $row->number);
                // If the current value is smaller, update all the attributes
                if ($min_value < $result[self::SIZES[$row->size_id]]['value']) {
                    $result[self::SIZES[$row->size_id]] = ['value' => $min_value, 'dress' => $row->code, 'color' => $row->title];
                }
            } else {
                // Add the size and the attributes to the result array
                $result[self::SIZES[$row->size_id]] = ['value' => $row->number, 'dress' => $row->code, 'color' => $row->title];
            }
        }
        return $result;
    }
    public function getDate()
    {
        $date = DB::table('needle_defects')->min('created_at');
        if ($date) {
            $oDate = new DateTime($date);
            return $oDate->format("Y-m-d");
        } else {
            return null;
        }
    }
}

