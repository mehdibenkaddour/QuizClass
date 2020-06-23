<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\Section;
use App\Models\Enroll;
use App\Models\Profile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=Auth::user();
        $topics=$user->topics()->withCount('sections')->get();
        $NombreSections=0;
        foreach($topics as $topic){
            $NombreSections+=$topic->sections_count;
        }
        $sectionOfUser=Section::whereIn('topic_id',$user->topics()->select('id')->get()->toArray())->select('id')->get()->toArray();
        $questionQuery=Question::whereIn('section_id',$sectionOfUser)->count();
        $teacherTopics=$user->topics()->select('id')->get()->toArray();
        $result=Enroll::whereIn('topic_id',$teacherTopics)
        ->join('users', 'enrolls.user_id', '=', 'users.id')
        ->join('topics', 'enrolls.topic_id', '=', 'topics.id')
        ->select('enrolls.id','users.name','users.email','topics.label')->count();
        return View('teacher.profiles.index')->with('user',$user)->with('students_count',$result)->with('sections_count',$NombreSections)->with('questions_count',$questionQuery);
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
        $user=Auth::user();
        $topics=$user->topics()->withCount('sections')->get();
        $NombreSections=0;
        foreach($topics as $topic){
            $NombreSections+=$topic->sections_count;
        }
        $teacherTopics=$user->topics()->select('id')->get()->toArray();
        $result=Enroll::whereIn('topic_id',$teacherTopics)
        ->join('users', 'enrolls.user_id', '=', 'users.id')
        ->join('topics', 'enrolls.topic_id', '=', 'topics.id')
        ->select('enrolls.id','users.name','users.email','topics.label')->count();
        return View('teacher.profiles.edit')->with('user',$user)->with('students_count',$result)->with('sections_count',$NombreSections);
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
        $profile = Profile::find($id);
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',Rule::unique('users')->ignore($profile->user->id)],
            'speciality' => ['required', 'string', 'max:255'],
            'university' => ['required', 'string', 'max:255'],
            'image' => ['image','mimes:jpeg,png,jpg,gif', 'max:2084'],
        ]);
        if($validator->fails()) {
            return \Redirect::back()->withErrors($validator);
        }
        $user = $profile->user()
         ->update([
             'name'=> $request->name,
             'email'=> $request->email,
         ]);
        $profile->speciality=$request->speciality;
        $profile->university=$request->university;
        $profile->about=$request->about;
        if($request->hasfile('image')){
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();
            $filename=time() . '.' . $extension;
            $file->move('uploads/profiles/',$filename);
            $profile->image=$filename;
        }
        $profile->update();
        return redirect('/teacher/profile');

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
