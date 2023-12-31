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
            <h1 class="text-center font-medium mb-2">تقرير عيوب الكي</h1>
            @if(isset($date))
                <h2 class="text-center font-light text-xl mb-2" style="color: #c44e47">{{$date}}</h2>
            @endif
            <div class="scrollable-table">
                @if(!empty($minDress))
                    <table dir="rtl">
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
                                <td>
                                    @php
                                        $data = $minDress[$size];
                                    @endphp
                                    عدد العيوب: {{$data['value']}}
                                    <br>
                                    اللون:
                                    @for($i=0; $i<count($data['dresses']);$i++)
                                        @php
                                            $dress = $data['dresses'][$i];
                                        @endphp
                                        {{$dress['code']}} - {{$dress['color']}}
                                        @if($i != count($data['dresses'])-1)
                                            ,
                                        @endif
                                    @endfor
                                </td>
                                <td>
                                    @php
                                        $data = $maxDress[$size];
                                    @endphp
                                    عدد العيوب: {{$data['value']}}
                                    <br>
                                    اللون:
                                    @for($i=0; $i<count($data['dresses']); $i++)
                                        @php
                                            $dress = $data['dresses'][$i];
                                        @endphp
                                        {{$dress['code']}} - {{$dress['color']}}
                                        @if($i != count($data['dresses'])-1)
                                            ,
                                        @endif
                                    @endfor
                                </td>
                                <td>
                                    @php
                                        $data = $maxDefect[$size];
                                    @endphp
                                    عدد المرات: {{$data['value']}}
                                    <br>
                                    العيب:
                                    @for($i=0; $i<count($data['defects']); $i++)
                                        {{$data['defects'][$i]}}
                                        @if($i != count($data['defects'])-1)
                                            ,
                                        @endif
                                    @endfor
                                </td>
                                <td>
                                    @php
                                        $data = $minDefect[$size];
                                    @endphp
                                    عدد المرات: {{$data['value']}}
                                    <br>
                                    العيب:
                                    @for($i=0; $i<count($data['defects']); $i++)
                                        {{$data['defects'][$i]}}
                                        @if($i != count($data['defects'])-1)
                                            ,
                                        @endif
                                    @endfor
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
