<x-filament-widgets::widget>
    <x-filament::section>
        <link rel="stylesheet" href="{{asset('css/filament/widgets/table.css')}}">
        @php
            $minHour = $this->getHour(0);
            $maxHour = $this->getHour(1);

            $minDefect = $this->getDefect(0);
            $maxDefect = $this->getDefect(1);

            $date = $this->getDate();
        @endphp
        <div class="report-container">
            <h1 class="text-center font-medium mb-2">تقرير عيوب التشغيل</h1>
            @if(isset($date))
                <h2 class="text-center font-light text-xl mb-2" style="color: #c44e47">{{$date}}</h2>
            @endif
            <div class="scrollable-table">
                @if(!empty($minHour))
                    <table dir="rtl" class="report">
                        <thead>
                        <tr>
                            <th>المقاس</th>
                            <th>الثوب</th>
                            <th>أقل عيب</th>
                            <th>أعلى عيب</th>
                            <th>أفضل ساعة</th>
                            <th>أسوأ ساعة</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($minDefect as $size => $data)
                            @foreach($data as $code => $data2)
                                <tr>
                                    <td>{{ $size }}</td>
                                    <td>{{$code}} - {{$data2['color']}}</td>

                                    <td>{{$minDefect[$size][$code]['defect']}} <br> {{$minDefect[$size][$code]['sum']}}
                                        مرة
                                    </td>
                                    <td>{{$maxDefect[$size][$code]['defect']}} <br> {{$maxDefect[$size][$code]['sum']}}
                                        مرة
                                    </td>

                                    <td>{{$minHour[$size][$code]['hour']}} <br> {{$minHour[$size][$code]['sum']}} مرة
                                    </td>
                                    <td>{{$maxHour[$size][$code]['hour']}} <br> {{$maxHour[$size][$code]['sum']}} مرة
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
