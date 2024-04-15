<div class="page-break">
    <div class="report-container">
        <h1 class="text-center font-medium mb-2">جدول القطع والمقاسات</h1>
        <div class="scrollable-table">
            @if($data)
                <table dir="rtl" class="report mx-auto">
                    <thead>
                    <tr>
                        <th>المقاس</th>
                        <th>عدد القطع</th>
                    </tr>
                    </thead>
                    @foreach($data as $entry)
                        <tr>
                            <td>{{$entry['size']}}</td>
                            <td>{{$entry['quantity']}}</td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>
    </div>
</div>
