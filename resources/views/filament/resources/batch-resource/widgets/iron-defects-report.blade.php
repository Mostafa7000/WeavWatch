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
        <h1 class="text-center font-medium mb-2">تقرير عيوب الكي</h1>
        <table dir="rtl" class="report mx-auto">
            <thead>
            <tr>
                <th>المقاس</th>
                <th>أفضل لون</th>
                <th>أسوأ لون</th>
                <th>أعلى عيب</th>
                <th>أقل عيب</th>
            </tr>
            </thead>
            <tbody>
            @foreach($minDress as $size => $data)
                <tr>
                    <td>{{ $size }}</td>
                    <td>{{$maxDress[$size]['dress']}} - {{$maxDress[$size]['color']}} <br> {{$maxDress[$size]['value']}} مرة</td>
                    <td>{{$data['dress']}} - {{$data['color']}} <br> {{$data['value']}} مرة</td>
                    <td>{{$maxDefect[$size]['defect']}} <br> {{$maxDefect[$size]['value']}} مرة</td>
                    <td>{{$minDefect[$size]['defect']}} <br> {{$minDefect[$size]['value']}} مرة</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-filament::section>
</x-filament-widgets::widget>
