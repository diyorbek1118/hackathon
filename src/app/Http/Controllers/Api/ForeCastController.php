<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Forecast;

class ForecastController extends Controller
{
    public function index(){ return response()->json(Forecast::all()); }
    public function store(Request $request){
        $data = $request->validate([
            'company_id'=>'required|exists:companies,id',
            'forecast_start'=>'required|date',
            'forecast_end'=>'required|date',
            'predicted_income'=>'required|numeric',
            'predicted_expense'=>'required|numeric',
            'predicted_balance'=>'required|numeric',
            'risk_level'=>'required|in:low,medium,high'
        ]);
        $forecast = Forecast::create($data);
        return response()->json($forecast,201);
    }
    public function show($id){ return response()->json(Forecast::findOrFail($id)); }
    public function update(Request $request,$id){
        $forecast = Forecast::findOrFail($id);
        $data = $request->validate([
            'forecast_start'=>'sometimes|date',
            'forecast_end'=>'sometimes|date',
            'predicted_income'=>'sometimes|numeric',
            'predicted_expense'=>'sometimes|numeric',
            'predicted_balance'=>'sometimes|numeric',
            'risk_level'=>'sometimes|in:low,medium,high'
        ]);
        $forecast->update($data);
        return response()->json($forecast);
    }
    public function destroy($id){
        Forecast::findOrFail($id)->delete();
        return response()->json(['message'=>'Forecast deleted']);
    }
}
