<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;
use App\Http\Requests\HabitRequest ;

class HabitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            $habit= auth()->user()->habits ;
            return response()->json([
                'success'=>true ,
                'data' => $habit ,
                'message' => 'Habits retrieved'  ,

            ] ,200) ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HabitRequest $request)
    {
        $request->validated() ;
        $habit = $request->user()->habits()->create($request->validated()) ;
        return response()->json(['succes'=>true  , 'data'=>$habit ,'message' =>'action succseful'] ,201) ;

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request , string $id)
    {
        $habit = $request ->user()->habits()->find($id) ;
        if(!$habit)
        {
            return response()->json([
                "success" => false,
                "data" => null ,
                "message"=>'there no habit here' ,
            ],404) ;
        }
        return response()->json([
            "success" => true,
                "data" => $habit ,
                "message"=>'success' ,
        ],200) ;
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(HabitRequest $request, string $id)
    {
        $request ->validated()  ;
        $habit = $request->user()->habits()->find($id)  ;
        if(!$habit)
        {
            return response()->json([
                "success" => false,
                "data" => null ,
                "message"=>'there no habit here' ,
            ],404) ;
        }
        $habit ->update($request->validated()) ;
        return response()->json([
            "success" => true,
                "data" => $habit ,
                "message"=>'habit uodated suucesfully' ,
        ],200) ;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request  , string $id)
    {
       $habit =  $request->user()->habits()->find($id) ;
       if(!$habit)
       {
        return response()->json([
            "success" => false   ,
            'data' => null  ,
            'message' => 'there no  habit with this id'
        ] ,404) ;
       }
       $habit->delete() ;
       return response()->json([
        "success" => true   ,
        'data' => null  ,
        'message' => 'habit deleted suucesfully'
    ] ,200) ;

    }
}
