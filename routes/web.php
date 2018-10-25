<?php

use App\Lecture;
use App\User;
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

	Route::get('/', function () {
		return view('top');
	});

	Route::get('/user', 'PagesController@show')->middleware('auth');

	// 教員が授業を追加
	Route::post('/user/lecture', ['middleware' => 'auth',function(Request $request) {

		$lecture = new Lecture;
		$lecture->title = $request->title;
		$lecture->univ = $request->univ;
		$lecture->gra = $request->gra;
		$lecture->dep = $request->dep;
		$lecture->number = $request->number;
		$lecture->date = $request->date;
		$lecture->lecpass = $request->lecpass;
		$lecture->user_id = $request->user()->id;
		$lecture->save();

		return redirect('/user');

	}]);

	// 学生が出席ボタンを押したとき
	Route::post('/user/lecture/{lecture}/', 'LectureController@clickUser')->middleware('auth');


	// 教員が確認ボタンを押したとき
	Route::get('/user/lecture/{lecture}/kekka', 'LectureController@showStudent')->middleware('auth');


	// 教員が削除ボタンを押したとき
	Route::delete('/user/lecture/{lecture}', ['middleware' => 'auth',function(Lecture $lecture) {
		$lecture->delete();
		return redirect('/user');
	}]);

	// 教員が CSVダウンロード を押したとき
	Route::get('/user/lecture/{lecture}/csvdownload',
				'LectureController@downloadCSV')->middleware('auth');

	// 教員が txtダウンロード を押したとき
	Route::get('/user/lecture/{lecture}/txtdownload',
				'LectureController@downloadTxt')->middleware('auth');


	// すべての階層に共通する
	// ページにリクエストがきたら認証させる
	Auth::routes();

});
