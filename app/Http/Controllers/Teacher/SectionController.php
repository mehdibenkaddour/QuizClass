<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Topic;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $sections=Section::orderBy('id')->paginate(10);
        // $topics=Topic::where('user_id','=',$request->user()->id)->get();
        $topic_id = (!empty($_GET["topic_id"])) ? ($_GET["topic_id"]) : ('');
        
        return View('teacher.sections.index')->with('topics', Auth::user()->topics)
                                             ->with('topic_id', $topic_id);
    }

    /**
     * This method is for ajax only
     */
    public function ajaxSections(Request $request) {
        $sectionOfUser=Section::whereIn('topic_id',$request->user()->topics()->select('id')->get()->toArray());
        $topic_id = (!empty($_GET["topic_id"])) ? ($_GET["topic_id"]) : ('');
        if($topic_id){
            $sectionOfUser->whereRaw("sections.topic_id = '" . $topic_id . "'");
        }
        $sections=$sectionOfUser->latest('created_at')->select('*');
        return Datatables::of($sections)

        // add actions collumn
        ->addColumn('actions', function (Section $section) {
            return '
            <div class="dropdown">
                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <button
                data-id="' . $section->id . '"
                class="edit dropdown-item">Modifier</button>
                <button
                data-id="' . $section->id .'"
                class="delete dropdown-item">Supprimer</button>
                </div>
            </div>';
        })

        ->addColumn('section', function (Section $section) {
            $url=route('questions.index');
            return '
            <div class="media align-items-center">
                <a href="#" class="avatar rounded-circle mr-3">
                    <img alt="Image placeholder" src="/uploads/sections/' . $section->image . '">
                </a>
                <div class="media-body">
                <a style="color: inherit" href="' . $url .'?section_id=' . $section->id .'"><span class="name mb-0 text-sm" id="sectionLabel">' . $section->label . '</span></a>
                </div>
            </div>
            ';
        })

        ->addColumn('topic', function(Section $section) {
            return $section->topic->label;
        })
        
        // to interpret html and not considering it as text
        ->rawColumns(['actions', 'section'])

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
        $validator = Validator::make($request->all(), [
            'label' => ['required', 'string', 'max:255'],
            'topic'=>['required'],
            'image' => ['required','image','mimes:jpeg,png,jpg,gif', 'max:2084'],
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        $section = new Section();

        $section->label=$request->input('label');
        $section->topic_id=$request->input('topic');

        if($request->hasfile('image')) {
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();
            $filename=time() . '.' . $extension;
            $file->move('uploads/sections/',$filename);
            $section->image=$filename;

        } else {
            return $request;
            $section->image="default_image";
        }
        $section->save();

        return response()->json(['alert' => 'Section has been Added with success']);

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
        $section = Section::find($id);

        $validator = Validator::make($request->all(), [
            'label' => ['required', 'string', 'max:255'],
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $section->label=$request->input('label');
        $section->topic_id=$request->input('topic');

        if($request->hasfile('image')){
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();
            $filename=time() . '.' . $extension;
            $file->move('uploads/sections/',$filename);
            $section->image=$filename;
        }
        $section->update();
        return response()->json(['alert' => 'Section has been updated with success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $section=Section::findOrFail($id);
        $section->delete();
        return redirect('sections');
    }
}
