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

        return View('teacher.results.index')
        ->with('topics', $topics)
        ->with('topic_id', $request->query('topic_id'))
        ->with('section_id', $request->query('section_id'));
    }

    public function getSections($id) {
        $imgUrl = url('/uploads/sections/');

        $sections= DB::table('sections')
        ->selectRaw("id as value, label as text, concat('" . $imgUrl . "/', image) as \"imageSrc\"")
        ->where("topic_id",$id)
        ->get();

        return $sections;
    }
    public function ajaxResults(Request $request) {
        $topic_id = $request->query('topic_id');
        $section_id = $request->query('section_id');
        $questionsCount;
        $foundTopic = Topic::where('user_id', '=', $request->user()->id)->where('id', '=', $topic_id)->get()->toArray();
        $foundSection=Section::where('topic_id','=',$topic_id)->where('id','=',$section_id)->get()->toArray();
        $result = array();
        $mami=[];
        $tableIfSectionDiffrent=[];
        $test=Enroll::where('topic_id','=',$topic_id)->leftJoin('progresses','enrolls.user_id','=','progresses.user_id')
        ->join('users', 'enrolls.user_id', '=', 'users.id')
        ->select('users.id','users.name','users.email','progresses.score','progresses.created_at','enrolls.topic_id','progresses.section_id')->get();
        $etat=0;
        foreach($test as $te){
            if($te->topic_id==$topic_id && $te->section_id==$section_id){
            foreach($mami as $ma){
                if($ma->id == $te->id){
                    $etat=1;
                    break;
                }else{
                    $etat=0;
                }
            }
            if($etat==1){
                continue;
            }else{
                foreach($test as $tam){
                  if($tam->topic_id==$topic_id && $tam->section_id==$section_id){
                    if($tam->id == $te->id && $tam->created_at < $te->created_at){
                        $te->score=$tam->score;
                        $te->created_at=$tam->created_at;
                    }
                 }
                }
                $mami[]=$te;
                }
            }
        }

        foreach($test as $te){
            if($te->topic_id==$topic_id && $te->section_id!=$section_id && $te->section_id!=null){
                $status=0;
                foreach($mami as $ma){
                    if($ma->id == $te->id){
                        $status=1;
                    }
                }
                if($status!=1){
                    $te->score=null;
                    $te->section_id=-1;
                    $mami[]=$te;
                }
            }else if($te->topic_id==$topic_id && $te->section_id==null){
                    $mami[]=$te;
            }
        }
        if(count($foundTopic) > 0 && count($foundSection) > 0 ){
            $result=$mami;
            $questionsCount=Section::find($section_id)->questions->count();

        }
        foreach($result as $re){
            $re->questionsCount= $questionsCount;
        }
        return Datatables::of($result)

        ->addColumn('name', function ($model) {
    
            return '
            <div class="media align-items-center">
                <div class="media-body">
                    <a href="#" class="chartUser" data-toggle="modal" data-target="#modChart" data-user="' . $model->id . '" data-section="' . $model->section_id . '">
                        <span class="name mb-0 text-sm" id="sectionLabel">' . $model->name . '</span>
                    </a>
                </div>
            </div>
            ';
        })

        ->addColumn('score', function ($model) {
            $result = 0;

            if($model->questionsCount > 0)
                $result = $model->score / $model->questionsCount;

            if ($result < 0.5) {
                $status = 'status-bad';

            } else if ($result > 0.5 && $result < 0.7) {
                $status = 'status-not-bad';

            } else if ($result >= 0.7 && $result < 0.8) {
                $status = 'status-good';

            } else {
                $status = 'status-excelent';
            }

            if ($model->score == null)
                return '
                <div class="media align-items-center">
                    <div class="media-body">
                    <span class="name mb-0 status-not-yet text-sm" id="sectionLabel"> Pas encore </span>
                    </div>
                </div>
                ';


                return '
                <div class="media align-items-center">
                    <div class="media-body">
                    <span class="name mb-0 '. $status . ' text-sm" id="sectionLabel">' . $model->score . ' / '. $model->questionsCount .'</span>
                    </div>
                </div>
                ';
        })
        
        // to interpret html and not considering it as text
        ->rawColumns(['name', 'score'])

        ->toJson();
    }
}