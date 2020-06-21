<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Section;
use App\Models\Enroll;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

   /**
     * This method is for ajax only
     */
    public function ajaxTopics(Request $request) {
        return Datatables::of(Topic::latest('created_at')->select('*'))

        // add actions collumn
        ->addColumn('actions', function (Topic $topic) {
            return '
            <div class="dropdown">
                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <button
                data-id="' . $topic->id . '"
                class="edit dropdown-item">Modifier</button>
                <button
                data-id="' . $topic->id .'"
                class="delete dropdown-item">Supprimer</button>
                </div>
            </div>';
        })

        ->addColumn('code', function (Topic $topic) {
            return '
            <div class="media align-items-center">
                <div class="media-body">
                  <span class="name mb-0 text-sm" id="sectionLabel">' . $topic->code . '</span>
                </div>
            </div>';
        })

        ->addColumn('topic', function (Topic $topic) {
            $url=route('sections.index');
            return '
            <div class="media align-items-center">
                <a href="#" class="avatar rounded-circle mr-3">
                    <img alt="Image placeholder" src="/uploads/topics/' . $topic->image . '">
                </a>
                <div class="media-body">
                <a style="color: inherit" href="' . $url .'?topic_id=' . $topic->id .'"><span class="name mb-0 text-sm" id="TopicLabel">' . $topic->label . '</span></a>
                </div>
            </div>
            ';
        })
        
        // to interpret html and not considering it as text
        ->rawColumns(['actions','topic','code'])

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
        //
    }
}
