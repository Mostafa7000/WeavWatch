<?php

namespace App\Filament\Resources\BatchResource\Widgets;

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

        $result = [];
        if ($max) {
            foreach ($sums as $size => $sum) {
                $maxValue = max($sum);
                $maxDefect = array_keys($sum, $maxValue)[0];
                $result[$size] = ['value' => $maxValue, 'defect' => $maxDefect];
            }
        } else {
            foreach ($sums as $size => $sum) {
                $minValue = min($sum);
                $minDefect = array_keys($sum, $minValue)[0];
                $result[$size] = ['value' => $minValue, 'defect' => $minDefect];
            }
        }
        return $result;
    }


    public function getDress(bool $max)
    {
        $statement = DB::table('needle_defects')
            ->where('needle_defects.batch_id', $this->record->id)
            ->join('dresses', 'needle_defects.dress_id', '=', 'dresses.id')
            ->join('colors', 'dresses.color_id', '=', 'colors.id')
            ->groupBy('size_id', 'dress_id');
        if ($max) {
            $statement->select(DB::raw('MAX(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10 + a11) as number, code, title, size_id'));
        } else {
            $statement->select(DB::raw('MIN(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10 + a11) as number, code, title, size_id'));
        }

        $result = [];
        foreach ($statement->get() as $row) {
            $result[self::SIZES[$row->size_id]] = ['value' => $row->number, 'dress' => $row->code, 'color' => $row->title];
        }
        return $result;
    }
}

