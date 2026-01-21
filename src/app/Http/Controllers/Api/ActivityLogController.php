<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index(){ return response()->json(ActivityLog::all()); }
    public function store(Request $request){
        $data = $request->validate([
            'user_id'=>'required|exists:users,id',
            'action'=>'required|string',
            'meta'=>'nullable|json'
        ]);
        $log = ActivityLog::create($data);
        return response()->json($log,201);
    }
    public function show($id){ return response()->json(ActivityLog::findOrFail($id)); }
    public function update(Request $request,$id){
        $log = ActivityLog::findOrFail($id);
        $data = $request->validate([
            'action'=>'sometimes|string',
            'meta'=>'nullable|json'
        ]);
        $log->update($data);
        return response()->json($log);
    }
    public function destroy($id){
        ActivityLog::findOrFail($id)->delete();
        return response()->json(['message'=>'Activity log deleted']);
    }
}
