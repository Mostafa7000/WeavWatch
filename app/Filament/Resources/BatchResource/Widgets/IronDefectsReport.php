<?php

namespace App\Filament\Resources\BatchResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IronDefectsReport extends Widget
{
    protected static string $view = 'filament.resources.batch-resource.widgets.iron-defects-report';

    public ?Model $record = null;
    const IRON_DEFECTS = [
        1 => 'اتساخ',
        2 => 'لسعة',
        3 => 'حرق',
        4 => 'كرمشة',
        5 => 'بقع معدنية',
        6 => 'لمعان',
        7 => 'تكسير',
        8 => 'بخار زيادة',
        9 => 'بلل',
        10 => 'عدم ضبط المظهرية',
        11 => 'علامات ضغط',
        12 => 'انكماش',
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
//        $sizes = DB::table('batch_size')
//            ->select('size_id')
//            ->where('batch_id', $this->record->id)
//            ->distinct()->get()->pluck('size_id')->toArray();

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
        $statement = DB::table('iron_defects')
            ->where('iron_defects.batch_id', $this->record->id)
            ->join('dresses', 'iron_defects.dress_id', '=', 'dresses.id')
            ->join('colors', 'dresses.color_id', '=', 'colors.id')
            ->groupBy('size_id', 'dress_id');
        if ($max) {
            $statement->select(DB::raw('MAX(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10 + a11 + a12) as number, code, title, size_id'));
        } else {
            $statement->select(DB::raw('MIN(a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8 + a9 + a10 + a11 + a12) as number, code, title, size_id'));
        }

        $result = [];
        foreach ($statement->get() as $row) {
            $result[self::SIZES[$row->size_id]] = ['value' => $row->number, 'dress' => $row->code, 'color' => $row->title];
        }
        return $result;
    }
}
