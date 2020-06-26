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

class ProgressController extends Controller
{
   public function ajaxProgress(Request $request){
      $resultToReturn = NULL;
      
      $user_id = $request->query('user_id');
      $section_id = $request->query('section_id');

      $result=Progress::where('user_id',$user_id)->where('section_id',$section_id)->pluck('score');

      $questionsCount = Section::find($section_id)->questions()->count();

      $resultToReturn["questionsCount"] = $questionsCount;
      $resultToReturn["result"]         = $result;

      return $resultToReturn;
   }
}
