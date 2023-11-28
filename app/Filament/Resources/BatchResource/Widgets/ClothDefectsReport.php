<?php

namespace App\Filament\Resources\BatchResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClothDefectsReport extends Widget
{
    protected static string $view = 'filament.resources.batch-resource.widgets.cloth-defects-report';
    public ?Model $record = null;
    const CLOTH_DEFECTS = [
        1 => 'الريجة',
        2 => 'البانشر',
        3 => 'العقدة',
        4 => 'الطيرة',
        5 => 'الثقوب',
        6 => 'تسقيط',
        7 => 'تنسيل',
        8 => 'ثبوت اللون',
        9 => 'الوصلات',
        10 => 'الاتساخ',
        11 => 'فلوك',
        12 => 'عرض البرسل',
        13 => 'الزيت',
        14 => 'التيك الأسود',
        15 => 'عروض مختلفة',
        16 => 'الصداء',
        17 => 'تنميل الصبغة',
        18 => 'اختلاف اللون العرضي',
        19 => 'اختلاف اللون الطولي',
    ];

    public function getDefect(bool $max)
    {
        $sums = [];
        for ($i = 1; $i <= 19; $i++) {
            $sum = DB::table('cloth_defects')
                ->where('batch_id', $this->record->id)
                ->sum("a$i");
            $sums[self::CLOTH_DEFECTS[$i]] = $sum;
        }

        if ($max) {
            $maxValue = max($sums);
            $maxDefect = array_keys($sums, $maxValue)[0];
            return ['value' => $maxValue, 'defect' => $maxDefect];
        } else {
            $minValue = min($sums);
            $minDefect = array_keys($sums, $minValue)[0];
            return ['value' => $minValue, 'defect' => $minDefect];
        }
    }


    public function getDress(bool $max)
    {
        $statement = DB::table('cloth_defects')
            ->where('cloth_defects.batch_id', $this->record->id)
            ->join('dresses', 'cloth_defects.dress_id', '=', 'dresses.id')
            ->join('colors', 'dresses.color_id', '=', 'colors.id')
            ->groupBy('dress_id');
        if ($max) {
            $statement->select(DB::raw('MAX(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10 + a11 + a12 + a13 + a14 + a15 + a16 + a17 + a18 + a19) as number, code, title'));
        } else {
            $statement->select(DB::raw('MIN(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10 + a11 + a12 + a13 + a14 + a15 + a16 + a17 + a18 + a19) as number, code, title'));
        }
        if ($statement->first() !== null) {
            return ['value' => $statement->first()->number, 'dress' => $statement->first()->code, 'color' => $statement->first()->title];
        } else {
            return [];
        }
    }

}
