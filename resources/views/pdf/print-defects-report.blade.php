<div class="page-break">
    @php
        $minDress = $parent->getDress(0);
        $maxDress = $parent->getDress(1);

        $minDefect = $parent->getDefect(0);
        $maxDefect = $parent->getDefect(1);
        $date = $parent->getDate();
    @endphp
    <div class="report-container">
        <h1 class="text-center font-medium mb-2">تقرير عيوب الطباعة</h1>
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
                        <td>
                            عدد العيوب: {{$minDress['value']}}
                            <br>
                            اللون:
                            @for($i=0; $i<count($minDress['dresses']);$i++)
                                @php
                                    $dress = $minDress['dresses'][$i];
                                @endphp
                                {{$dress['code']}} - {{$dress['color']}}
                                @if($i != count($minDress['dresses'])-1)
                                    ,
                                @endif
                            @endfor
                        </td>
                        <td>
                            عدد العيوب: {{$maxDress['value']}}
                            <br>
                            اللون:
                            @for($i=0; $i<count($maxDress['dresses']);$i++)
                                @php
                                    $dress = $maxDress['dresses'][$i];
                                @endphp
                                {{$dress['code']}} - {{$dress['color']}}
                                @if($i != count($maxDress['dresses'])-1)
                                    ,
                                @endif
                            @endfor
                        </td>
                        <td>
                            عدد المرات: {{$maxDefect['value']}}
                            <br>
                            العيب:
                            @for($i=0; $i<count($maxDefect['defects']); $i++)
                                {{$maxDefect['defects'][$i]}}
                                @if($i != count($maxDefect['defects'])-1)
                                    ,
                                @endif
                            @endfor
                        </td>
                        <td>
                            عدد المرات: {{$minDefect['value']}}
                            <br>
                            العيب:
                            @for($i=0; $i<count($minDefect['defects']); $i++)
                                {{$minDefect['defects'][$i]}}
                                @if($i != count($minDefect['defects'])-1)
                                    ,
                                @endif
                            @endfor
                        </td>
                    </tr>
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
