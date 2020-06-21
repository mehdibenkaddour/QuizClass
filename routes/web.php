<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',  function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => ['auth','admin']], function () {
    Route::get('/teacher', function(){
        return view('teacher.index');
    })->name('teacher');
    
    // users table [ajax only]
    Route::get('/ajax/users', 'Teacher\UserController@ajaxUsers')->name('ajax.users');

    // topics table [ajax only]
    Route::get('/ajax/topics', 'Teacher\TopicController@ajaxTopics')->name('ajax.topics');

    // sections table [ajax only]
    Route::get('/ajax/sections', 'Teacher\SectionController@ajaxSections')->name('ajax.sections');
    // Questions table [ajax only]
    Route::get('/ajax/questions', 'Teacher\QuestionController@ajaxQuestions')->name('ajax.questions');
    // Students table [ajax only]
    Route::get('/ajax/students', 'Teacher\StudentController@ajaxStudents')->name('ajax.students');
    
    Route::get('/sections/get/{id}', 'Teacher\QuestionController@getSections');
    
    Route::resource('users','Teacher\UserController',['names' => [
        'index' => 'users',
        'update' => 'users.update',
        'destroy' => 'users.delete'
        ],'only'=> [
            'index','update','destroy'
        ]
    ])->parameters(
        ['users' => 'id'
    ]);
    Route::resource('topics','Teacher\TopicController')->parameters(
        ['topics' => 'id']
    );
    Route::resource('sections','Teacher\SectionController')->parameters(
        ['sections' => 'id']
    );
    Route::resource('questions','Teacher\QuestionController')->parameters(
        ['questions' => 'id']
    );
    Route::resource('students','Teacher\StudentController')->parameters(
        ['students' => 'id']
    );
});
