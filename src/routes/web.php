<?php

Route::group(['prefix' => 'yonetim/gorev', 'namespace' => 'ayzamodul\projectmanagement\Http\Controllers', 'middleware' => ['web', 'auth','permission:proje-calisani']], function () {
    Auth::check();

    Route::match(['get'], '/', 'ProjectController@index')->name('yonetim.gorev');

    Route::get('/yeni', 'ProjectController@form')->name('yonetim.gorev.yeni');
    Route::get('/duzenle/{id}', 'ProjectController@form')->name('yonetim.gorev.duzenle');
    Route::post('/kaydet/{id?}', 'ProjectController@kaydet')->name('yonetim.gorev.kaydet');
    Route::post('/sil/', 'ProjectController@sil')->name('yonetim.gorev.sil');

    Route::post('/{post}/comments/', 'CommentController@store');

    Route::post('/comments/{type}/{id}', 'CommentController@store');

    Route::post('comments/{post_id?}', 'CommentController@store')->name('comments.store');

    Route::get('comments/{id}/edit', 'CommentController@edit')->name('comments.edit');
    Route::put('comments/{id}', 'CommentController@update')->name('comments.update');
    Route::post('/commentsDelete', 'CommentController@destroy')->name('comments.destroy');

    Route::get('comments/ekle/{id}', 'CommentController@ekle')->name('comments.ekle');



    Route::get('listele/{id}', 'GorevControlleer@index')->name('gorev.listele');
    Route::post('view/{id}', 'GorevController@list')->name('gorev.view');
    Route::get('list/{id}', 'GorevController@taskList')->name('gorev.list');
    Route::get('clist/{id}', 'CommentController@commentList')->name('comment.list');
    Route::post('commentview/{id}', 'CommentController@commentView')->name('comment.view');
    Route::post('taskview/{id}', 'TaskController@taskView')->name('task.view');


    Route::post('tasks/{gorev_id?}', 'TaskController@store')->name('tasks.store');
    Route::get('tasks/{id}/edit', 'TaskController@edit')->name('tasks.edit');
    Route::put('tasks/{id}', 'TaskController@update')->name('tasks.update');

    Route::post('/taskDelete','TaskController@taskDelete');
    Route::get('tasks/ekle/{id}', 'TaskController@ekle')->name('tasks.ekle');
    Route::resource('work', 'WorkController');
    Route::get('/work/aktif/{id}', 'WorkController@aktif')->name('yonetim.work.aktif');
    Route::get('/work/pasif/{id}', 'WorkController@pasif')->name('yonetim.work.pasif');
    Route::get('/work/bitti/{id}', 'WorkController@bitti')->name('yonetim.work.bitti');
    Route::post('/work/sil/', 'WorkController@sil')->name('yonetim.work.sil');
    Route::post('work/detailWork/{id}', 'WorkController@detailWork')->name('work.view');
    Route::post('image/{id}/delete', 'WorkController@imageDelete')->name('work.imageDelete');
    Route::resource('employee', 'EmployeeController');
    Route::get('/datatable', 'EmployeeController@dataTable');
    Route::post('/feedback', 'EmployeeController@feedback');
    Route::post('/notesFeedback', 'WorkController@notesFeedback');
    Route::post('/feedbackPositive', 'EmployeeController@feedbackPositive');
    Route::post('/newNote', 'CommentController@new_note');

});

