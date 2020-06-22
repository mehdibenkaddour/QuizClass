<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Section;
use App\Models\Enroll;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topic_id = (!empty($_GET["topic_id"])) ? ($_GET["topic_id"]) : ('');
        return View('teacher.students.index')->with('topics', Auth::user()->topics)
                                             ->with('topic_id', $topic_id);
    }

   /**
     * This method is for ajax only
     */
    public function ajaxStudents(Request $request) {
        $teacherTopics=$request->user()->topics()->select('id')->get()->toArray();

        $topic_id = (!empty($_GET["topic_id"])) ? ($_GET["topic_id"]) : null;

        $result = Enroll::whereIn('topic_id',$teacherTopics)->join('users', 'enrolls.user_id', '=', 'users.id')->join('topics', 'enrolls.topic_id', '=', 'topics.id')->select('enrolls.id','users.name','users.email','topics.label');
        
        if($topic_id) {
            $result=Enroll::where('topic_id','=',$topic_id)->join('users', 'enrolls.user_id', '=', 'users.id')->join('topics', 'enrolls.topic_id', '=', 'topics.id')->select('enrolls.id','users.name','users.email','topics.label');
        }

        return Datatables::of($result)

        // add actions collumn
        ->addColumn('actions', function ($model) {
            return '
            <div class="dropdown">
                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <button
                data-id="' . $model->id .'"
                class="delete dropdown-item">Supprimer</button>
                </div>
            </div>';
        })

        ->addColumn('name', function ($model) {
            return '
            <div class="media align-items-center">
                <div class="media-body">
                  <span class="name mb-0 text-sm" id="userName">' . $model->name . '</span>
                </div>
            </div>';
        })

        ->addColumn('email', function ($model) {
            return '
            <div class="media align-items-center">
                <div class="media-body">
                  <span class="email mb-0 text-sm" id="userEmail">' . $model->email . '</span>
                </div>
            </div>
            ';
        })

        ->addColumn('topic', function ($model) {
            return '
            <div class="media align-items-center">
                <div class="media-body">
                  <span class="name mb-0 text-sm" id="topicLabel">' . $model->label . '</span>
                </div>
            </div>';
        })
        
        // to interpret html and not considering it as text
        ->rawColumns(['actions','name','email','topic'])

        ->toJson();
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
        $enroll=Enroll::findOrFail($id);
        $enroll->delete();
        return redirect('students');
    }
}
