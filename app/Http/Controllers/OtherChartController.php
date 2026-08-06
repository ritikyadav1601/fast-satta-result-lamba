<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OtherChart;
use App\Models\Game;
class OtherChartController extends Controller
{
    public function otherChart()
    {
        $otherCharts = OtherChart::all();
        $games = Game::all();
        return view('admin.other-chart.index',compact('otherCharts','games'));
    }

    public function otherChartStore(Request $request)
    {$otherChart = new OtherChart();
    $otherChart->khaiwal_name = $request->input('khaiwal_name');
    $otherChart->whatsapp_numbers = $request->input('whatsapp_number');
    $otherChart->chart_content = $request->input('chart_content'); // CKEditor content
    $otherChart->save();;
        return redirect()->route('admin.other.chart')->with('success','Other Chart Created Successfully');
    }

    public function otherChartEdit($id)
    {
        $otherChart = OtherChart::find($id);
        $games = Game::all();
        return view('admin.other-chart.edit',compact('otherChart','games'));
    }

    public function otherChartUpdate(Request $request, $id)
    {
        $otherChart = OtherChart::find($id);
        $otherChart->khaiwal_name = $request->input('khaiwal_name');
        $otherChart->whatsapp_numbers = $request->input('whatsapp_number');
        $otherChart->chart_content = $request->input('chart_content'); // CKEditor content
        $otherChart->save();;
        return redirect()->route('admin.other.chart')->with('success','Other Chart Updated Successfully');
    }

    public function otherChartDelete($id)
    {
        $otherChart = OtherChart::find($id);
        $otherChart->delete();
        return redirect()->route('admin.other.chart')->with('success','Other Chart Deleted Successfully');
    }
}
