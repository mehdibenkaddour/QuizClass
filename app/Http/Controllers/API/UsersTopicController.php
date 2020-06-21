<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Section;
use App\Models\Enroll;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

class UsersTopicController extends ResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $enrolls=Enroll::where('user_id','=',$id)->get();
        $result = [];
        if(!$enrolls->isEmpty()){

            foreach($enrolls as $enroll){
                // Teacher name
                $teacher_name = Topic::find($enroll->topic_id)->user()->first()->name;
                $topic = Topic::withCount('sections')->find($enroll->topic_id);
                $topic->teacher_name = $teacher_name;
                array_push($result, $topic);
            }

            return $this->sendResponse($result);
        }else{
            return $this->sendError("NO_TOPICS_FOUND",404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
