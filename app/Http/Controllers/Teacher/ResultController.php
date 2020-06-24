<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Section;
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
}
