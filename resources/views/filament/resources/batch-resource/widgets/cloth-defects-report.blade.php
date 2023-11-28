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
        <h1 class="text-center font-medium mb-2">تقرير عيوب القماش</h1>
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
                    <td>{{$maxDress['dress']}} - {{$maxDress['color']}} <br> {{$maxDress['value']}} مرة</td>
                    <td>{{$minDress['dress']}} - {{$minDress['color']}} <br> {{$minDress['value']}} مرة</td>
                    <td>{{$maxDefect['defect']}} <br> {{$maxDefect['value']}} مرة</td>
                    <td>{{$minDefect['defect']}} <br> {{$minDefect['value']}} مرة</td>
                </tr>
            </tbody>
        </table>
    </x-filament::section>
</x-filament-widgets::widget>
