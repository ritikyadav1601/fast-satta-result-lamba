<div class="panel-body" style="overflow: auto; width: 100%;">
    <table width="100%" class="table" style="text-align: center; cursor: default;">
        <thead>
            <tr style="line-height: 1; background-color: rgb(255, 216, 0); color: rgb(0, 0, 0);">
                <th class="fs-6">{{ $game->year }}</th>
                <th class="fs-6">JAN</th>
                <th class="fs-6">FEB</th>
                <th class="fs-6">MAR</th>
                <th class="fs-6">APR</th>
                <th class="fs-6">MAY</th>
                <th class="fs-6">JUN</th>
                <th class="fs-6">JUL</th>
                <th class="fs-6">AUG</th>
                <th class="fs-6">SEP</th>
                <th class="fs-6">OCT</th>
                <th class="fs-6">NOV</th>
                <th class="fs-6">DEC</th>
            </tr>
        </thead>
        <tbody>
            @for ($day = 1; $day <= 31; $day++)
            <tr>
                <td>{{ $day }}</td>
                    @for ($month = 1; $month <= 12; $month++)
                    @php
                        $value = '--'; 
                        foreach ($result as $res) {
                            $resDate = \Carbon\Carbon::parse($res->result_date); 
                            if ($resDate->day == $day && $resDate->month == $month) {
                                $value = $res->result; 
                                break; 
                            }
                        }
                    @endphp
                    <td style="color: blue; font-size: 17px;">
                        @if($value == 100)
                        00
                        @else
                        {{ str_pad($value, 2, '0', STR_PAD_LEFT) }}
                        @endif
                        
                        </td>
                @endfor
            </tr>
            @endfor
        </tbody>
    </table>
</div>