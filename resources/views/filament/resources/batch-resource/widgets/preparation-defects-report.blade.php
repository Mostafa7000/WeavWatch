<x-filament-widgets::widget>
    <x-filament::section>
        <style>
            table.report {
                border-collapse: separate;
                border-spacing: 10px;
            }
            table.report tr td {
                border: 2px solid burlywood;
                border-radius: 5px;
                padding: 10px;
            }
        </style>
        @php
            $minDress = $this->getDress(0);
            $maxDress = $this->getDress(1);

            $minDefect = $this->getDefect(0);
            $maxDefect = $this->getDefect(1);
        @endphp
        <h1 class="text-center font-medium mb-2">تقرير عيوب الصبغة والتجهيز</h1>
        @if(isset($date))
            <h2 class="text-center font-light text-xl mb-2" style="color: #c44e47">{{$date}}</h2>
        @endif
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
    </x-filament::section>
</x-filament-widgets::widget>
