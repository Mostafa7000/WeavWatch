<x-filament-widgets::widget>
    <x-filament::section>
        <link rel="stylesheet" href="{{asset('css/filament/widgets/table.css')}}">
        @php
            $minDress = $this->getDress(0);
            $maxDress = $this->getDress(1);

            $minDefect = $this->getDefect(0);
            $maxDefect = $this->getDefect(1);
            $date = $this->getDate();
        @endphp
        <div class="report-container">
        <h1 class="text-center font-medium mb-2">تقرير عيوب القص</h1>
        @if(isset($date))
            <h2 class="text-center font-light text-xl mb-2" style="color: #c44e47">{{$date}}</h2>
        @endif
        <div class="scrollable-table">
            @if(!empty($minDress))
                <table dir="rtl" class="report mx-auto">
                    <thead>
                    <tr>
                        <th>أفضل لون</th>
                        <th>أسوأ لون</th>
                        <th>أعلى عيب</th>
                        <th>أقل عيب</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{$minDress['dress']}} - {{$minDress['color']}} <br> {{$minDress['value']}} عيب</td>
                        <td>{{$maxDress['dress']}} - {{$maxDress['color']}} <br> {{$maxDress['value']}} عيب</td>
                        <td>{{$maxDefect['defect']}} <br> {{$maxDefect['value']}} مرة</td>
                        <td>{{$minDefect['defect']}} <br> {{$minDefect['value']}} مرة</td>
                    </tr>
                    </tbody>
                </table>
            @endif
        </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
