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
            <h1 class="text-center font-medium mb-2">تقرير عيوب التطريز</h1>
            @if(isset($date))
                <h2 class="text-center font-light text-xl mb-2" style="color: #c44e47">{{$date}}</h2>
            @endif
            <div class="scrollable-table">
                @if(!empty($minDress))
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
                                <td>{{$data['dress']}} - {{$data['color']}} <br> {{$data['value']}} عيب</td>
                                <td>{{$maxDress[$size]['dress']}} - {{$maxDress[$size]['color']}}
                                    <br> {{$maxDress[$size]['value']}} عيب
                                </td>
                                <td>{{$maxDefect[$size]['defect']}} <br> {{$maxDefect[$size]['value']}} مرة</td>
                                <td>{{$minDefect[$size]['defect']}} <br> {{$minDefect[$size]['value']}} مرة</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
