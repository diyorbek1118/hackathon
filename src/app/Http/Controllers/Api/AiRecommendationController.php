<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AiRecommendation;

class AiRecommendationController extends Controller
{
    public function index(){ return response()->json(AiRecommendation::all()); }
    public function store(Request $request){
        $data = $request->validate([
            'company_id'=>'required|exists:companies,id',
            'title'=>'required|string',
            'description'=>'required|string',
            'priority'=>'required|in:low,medium,high'
        ]);
        $rec = AiRecommendation::create($data);
        return response()->json($rec,201);
    }
    public function show($id){ return response()->json(AiRecommendation::findOrFail($id)); }
    public function update(Request $request,$id){
        $rec = AiRecommendation::findOrFail($id);
        $data = $request->validate([
            'title'=>'sometimes|string',
            'description'=>'sometimes|string',
            'priority'=>'sometimes|in:low,medium,high'
        ]);
        $rec->update($data);
        return response()->json($rec);
    }
    public function destroy($id){
        AiRecommendation::findOrFail($id)->delete();
        return response()->json(['message'=>'AI recommendation deleted']);
    }
}
