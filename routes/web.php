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
        return redirect('/teacher/profile');;
    })->name('teacher');

    // result
    Route::get('/results', 'Teacher\ResultController@index')->name('results');
    
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
    // Results table [ajax only]
    Route::get('/ajax/results', 'Teacher\ResultController@ajaxResults')->name('ajax.results');
    
    Route::get('/sections/get/{id}', 'Teacher\QuestionController@getSections');

    Route::get('/ajax/sections/ddslick/{id}', 'Teacher\ResultController@getSections')->name('ajax.sections.ddslick');
    
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
    )->names([
        'index' => 'topics.index'
    ]);
    Route::resource('sections','Teacher\SectionController')->parameters(
        ['sections' => 'id']
    )->names([
        'index' => 'sections.index'
    ]);
    Route::resource('questions','Teacher\QuestionController')->parameters(
        ['questions' => 'id']
    )->names([
        'index' => 'questions.index'
    ]);
    Route::resource('students','Teacher\StudentController')->parameters(
        ['students' => 'id']
    )->names([
        'index' => 'students.index'
    ]);;
    Route::resource('teacher/profile','Teacher\ProfileController')->parameters(
        ['profile' => 'id']
    );
});
