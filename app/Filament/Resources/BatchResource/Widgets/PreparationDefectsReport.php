<?php

namespace App\Filament\Resources\BatchResource\Widgets;

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
        $statement = DB::table(self::TABLE)
            ->where('batch_id', $this->record->id)
            ->groupBy('dress_id');
        if ($max) {
            $statement->select(DB::raw('MAX(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10 + a11 + a12 + a13 + a14 + a15 + a16 + a17 + a18 + a19 + a20 + a21) as number, dress_id'));
        } else {
            $statement->select(DB::raw('MIN(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10 + a11 + a12 + a13 + a14 + a15 + a16 + a17 + a18 + a19 + a20 + a21) as number, dress_id'));
        }
        if ($statement->first() !== null) {
            return ['value' => $statement->first()->number, 'dress' => $statement->first()->dress_id];
        } else {
            return [];
        }
    }

}
