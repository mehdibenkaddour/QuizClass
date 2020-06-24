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
    public function index() {
        $topics = Auth::user()->topics;

        return View('teacher.results.index')
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
        $foundSection=Section::where('topic_id','=',$topic_id)->where('id','=',$section_id)->get()->toArray();
        $result = array();
        if(count($foundTopic) > 0 && count($foundSection) > 0 ){
            $result=Progress::where('section_id','=',$section_id)->rightJoin('users', 'progresses.user_id', '=', 'users.id')
                    ->select('users.name','users.email','progresses.score')->whereRaw("progresses.created_at = (select min(`created_at`) from progresses where user_id = users.id AND section_id = '$section_id')");
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
            return '
            <div class="media align-items-center">
                <div class="media-body">
                  <span class="name mb-0 text-sm" id="sectionLabel">' . $model->score . '</span>
                </div>
            </div>
            ';
        })
        
        // to interpret html and not considering it as text
        ->rawColumns(['name', 'score'])

        ->toJson();
    }
}
