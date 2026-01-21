<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    public function index(){ return response()->json(Report::all()); }
    public function store(Request $request){
        $data = $request->validate([
            'company_id'=>'required|exists:companies,id',
            'type'=>'required|in:monthly,quarterly,custom',
            'period_start'=>'required|date',
            'period_end'=>'required|date',
            'file_path'=>'required|string'
        ]);
        $report = Report::create($data);
        return response()->json($report,201);
    }
    public function show($id){ return response()->json(Report::findOrFail($id)); }
    public function update(Request $request,$id){
        $report = Report::findOrFail($id);
        $data = $request->validate([
            'type'=>'sometimes|in:monthly,quarterly,custom',
            'period_start'=>'sometimes|date',
            'period_end'=>'sometimes|date',
            'file_path'=>'sometimes|string'
        ]);
        $report->update($data);
        return response()->json($report);
    }
    public function destroy($id){
        Report::findOrFail($id)->delete();
        return response()->json(['message'=>'Report deleted']);
    }
}
