<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StressTest;

class StressTestController extends Controller
{
    public function index(){ return response()->json(StressTest::all()); }
    public function store(Request $request){
        $data = $request->validate([
            'company_id'=>'required|exists:companies,id',
            'scenario_name'=>'required|string',
            'parameters'=>'nullable|json',
            'result_balance'=>'required|numeric',
            'survival_days'=>'required|integer'
        ]);
        $stress = StressTest::create($data);
        return response()->json($stress,201);
    }
    public function show($id){ return response()->json(StressTest::findOrFail($id)); }
    public function update(Request $request,$id){
        $stress = StressTest::findOrFail($id);
        $data = $request->validate([
            'scenario_name'=>'sometimes|string',
            'parameters'=>'nullable|json',
            'result_balance'=>'sometimes|numeric',
            'survival_days'=>'sometimes|integer'
        ]);
        $stress->update($data);
        return response()->json($stress);
    }
    public function destroy($id){
        StressTest::findOrFail($id)->delete();
        return response()->json(['message'=>'Stress test deleted']);
    }
}
