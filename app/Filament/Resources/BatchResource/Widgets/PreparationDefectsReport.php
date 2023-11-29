<?php

namespace App\Filament\Resources\BatchResource\Widgets;

use DateTime;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PreparationDefectsReport extends Widget
{
    protected static string $view = 'filament.resources.batch-resource.widgets.preparation-defects-report';

    public ?Model $record = null;

    public const TABLE = 'preparation_defects';
    const PREPARATION_DEFECTS = [
        1 => 'خطأ نمرة رفيعة',
        2 => 'خطأ نمرة سميكة',
        3 => 'فتلة مخلوطة',
        4 => 'فتلة محلولة',
        5 => 'عقد تراجي',
        6 => 'نقط سواء في الفتل',
        7 => 'نسبة الخلط غير منتظمة',
        8 => 'لحمة متباعدة',
        9 => 'حدفات غير منتظمة المسافات بين الخطوط',
        10 => 'فراغ خال من اللحمات',
        11 => 'دقات',
        12 => 'اختلاف لحمة',
        13 => 'ثقوب',
        14 => 'عقد لحمة',
        15 => 'قطع وصل',
        16 => 'لحمة مقوسة',
        17 => 'لحمة ليست على استقامة واحدة في طريق البرسل',
        18 => 'اختلاف الشد على الخيوط',
        19 => 'فتل زائدة',
        20 => 'خطأ لقي',
        21 => 'خطأ تطريح',
    ];

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
        $maxDefect = array_keys($sums, $maxValue)[0];
        return ['value' => $maxValue, 'defect' => $maxDefect];
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

        $row = $statement->first();

        if ($row !== null) {
            return ['value' => $row->number, 'dress' => $row->code, 'color' => $row->title];
        } else {
            return [];
        }
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
