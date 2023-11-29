<?php

namespace App\Filament\Resources\BatchResource\Widgets;

use DateTime;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PackagingDefectsReport extends Widget
{
    protected static string $view = 'filament.resources.batch-resource.widgets.packaging-defects-report';

    public ?Model $record = null;
    const PACKAGING_DEFECTS = [
        1 => 'مظهرية سيئة',
        2 => 'عدم وجود نكت عناية',
        3 => 'عدم وجود نكت مقاس',
        4 => 'عدم وجود نكت أساسي عميل',
        5 => 'نسبة رشيو خطأ',
        6 => 'زيادة أو نقص في العدد الكلي للمقاس',
        7 => 'مكواه سيئة',
        8 => 'تطبيق سيئ',
        9 => 'اكسسوار مفقود',
        10 => 'دبوس ناقص',
        11 => 'بقعة زيت أو عرق أو اتساخ',
        12 => 'قطع مبللة داخل الكيس',
        13 => 'زيادة او نقص فى العدد الكلى للون',
        14 => 'تعبئة خطأ',
        15 => 'كارت علامة تجارية خطأ',
        16 => 'كارت سعر خطأ',
        17 => 'عدم توافق المقاسات',
        18 => 'شماعة خطأ',
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
            $maxDefect = array_keys($sum, $maxValue)[0];
            $result[$size] = ['value' => $maxValue, 'defect' => $maxDefect];
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
        $date = DB::table('packaging_defects')->min('created_at');
        if ($date) {
            $oDate = new DateTime($date);
            return $oDate->format("Y-m-d");
        } else {
            return null;
        }
    }
}
