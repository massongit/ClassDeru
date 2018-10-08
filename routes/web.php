<?php

use App\Lecture;
use Illuminate\Http\Request;


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

Route::group(['middleware' => ['web']], function () {

	Route::get('/', 'PagesController@show')->middleware('auth');

	Route::post('/lecture', ['middleware' => 'auth',function(Request $request) {

		$lecture = new Lecture;
		$lecture->title = $request->title;
		$lecture->univ = $request->univ;
		$lecture->gra = $request->gra;
		$lecture->dep = $request->dep;
		$lecture->number = $request->number;
		$lecture->date = $request->date;
		$lecture->user_id = "123456";
		$lecture->save();

		return redirect('/');

	}]);

	// 学生が出席ボタンを押したとき
	Route::post('/lecture/{lecture}', 'LectureController@clickUser')->middleware('auth');


	// 教員が確認ボタンを押したとき
	Route::get('/lecture/{lecture}', 'LectureController@showStudent')->middleware('auth');


	// 学生が削除ボタンを押したとき
	Route::delete('/lecture/{lecture}', ['middleware' => 'auth',function(Lecture $lecture) {
		$lecture->delete();
		return redirect('/');

	}]);


	// すべての階層に共通する
	// ページにリクエストがきたら認証させる
	Auth::routes();

});
