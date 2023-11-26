<x-filament-widgets::widget>
    <x-filament::section>
        <div dir="rtl">
            @php
                $minResult = $this->getDress(0);
                $minDressId = $minResult['dress'] ?? null;
                if ($minDressId) {
                    $minColor = \App\Models\Dress::query()->where('id', $minDressId)->first()->color->title;
                }

                $maxResult = $this->getDress(1);
                $maxDressId = $maxResult['dress'] ?? null;
                if ($maxDressId) {
                    $maxColor = \App\Models\Dress::query()->where('id', $maxDressId)->first()->color->title;
                }

                $maxDefect = $this->getDefect(1);
                $minDefect = $this->getDefect(0);
            @endphp
            <h1 class="text-center font-medium mb-2">تقرير عيوب الصباغة والتجهيز</h1>
            @if(isset($minDressId))
                <div>- أعلى ثوب في العيوب : {{$maxDressId}} - {{$maxColor}}  -  {{$maxResult['value']}} مرة </div>
                <div>- أقل ثوب في العيوب : {{$minDressId}} - {{$minColor}}  -  {{$minResult['value']}} مرة </div>
                <hr class="mt-2 mb-2">
                <div>- أعلى عيب ظهورا : {{$maxDefect['defect']}}  -  {{$maxDefect['value']}} مرة </div>
                <div>- أقل عيب ظهورا : {{$minDefect['defect']}}  -  {{$minDefect['value']}} مرة </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
