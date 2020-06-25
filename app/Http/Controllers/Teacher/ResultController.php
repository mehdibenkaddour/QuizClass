<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Section;
use App\Models\Enroll;
use App\Models\Progress;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function index(Request $request) {
        $topics = Auth::user()->topics;

        $topic_id = $request->query('topic_id');

        $section_id = $request->query('section_id');

        return View('teacher.results.index')
        ->with('topic_id', $topic_id)
        ->with('section_id', $section_id)
        ->with('topics', $topics);
    }

    public function getSections($id) {
        $imgUrl = url('/uploads/sections/');

        $sections= DB::table('sections')
        ->selectRaw("id as value, label as text, concat('" . $imgUrl . "/', image) as imageSrc")
        ->where("topic_id",$id)
        ->get();

        return $sections;
    }
    public function ajaxResults(Request $request) {
        $topic_id = $request->query('topic_id');

        $section_id = $request->query('section_id');


        $foundTopic = Topic::where('user_id', '=', $request->user()->id)->where('id', '=', $topic_id)->get()->toArray();

        $foundSection = Section::where('topic_id','=',$topic_id)->where('id','=',$section_id)->get()->toArray();

        $result = array();


        if(count($foundTopic) > 0 && count($foundSection) > 0 && Enroll::where('topic_id','=',$topic_id)->count() > 0) {

            $result = Enroll::where('topic_id','=',$topic_id)

            ->leftJoin('progresses', function($join) {
                $join->on('enrolls.user_id','=','progresses.user_id');
            })

            ->join('users', 'enrolls.user_id', '=', 'users.id')

            ->whereRaw("(progresses.attempt = 1 AND progresses.section_id = '$section_id') OR progresses.score IS NULL OR (progresses.attempt = 1 AND progresses.section_id != '$section_id')")

            ->selectRaw('users.id, users.name, users.email, progresses.score, progresses.section_id, progresses.attempt')

            ->get();
        }

        foreach($result as $key => $row) {
            if($row->section_id != $section_id . "") {
                $checkIfUserHasReallyAProgress = Progress::whereRaw("section_id = '$section_id' AND user_id = '$row->id'")->count();

                if($checkIfUserHasReallyAProgress == 0) {
                    $row->score = NULL;
                } else {
                    // remove it from the $result
                    // $row->score = 'REMOVE ME PLEASE';
                    unset($result[$key]);
                }
            }
        } 

        // add question count or the result

        $questionsCount = Section::find($section_id)->questions->count();

        foreach($result as $re){
            $re->questionsCount= $questionsCount;
        }

        return Datatables::of($result)

        ->addColumn('name', function ($model) {
            return '
            <div class="media align-items-center">
                <div class="media-body">
                  <span class="name mb-0 text-sm" id="sectionLabel">' . $model->name . '</span>
                </div>
            </div>
            ';
        })

        ->addColumn('score', function ($model) {
            if($model->score==null)
                    return '
            <div class="media align-items-center">
                <div class="media-body">
                  <span class="name mb-0 text-sm" id="sectionLabel"> Pas encore </span>
                </div>
            </div>
            ';
            return '
            <div class="media align-items-center">
                <div class="media-body">
                  <span class="name mb-0 text-sm" id="sectionLabel">' . $model->score . ' / '. $model->questionsCount .'</span>
                </div>
            </div>
            ';
        })
        
        // to interpret html and not considering it as text
        ->rawColumns(['name', 'score'])

        ->toJson();
    }
}
