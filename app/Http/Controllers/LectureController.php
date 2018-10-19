<?php
//	学生が出席ボタンを押したとき, 教員が確認ボタンを押したときに
//	呼ばれるコントローラー

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Lecture;
use Symfony\Component\HttpFoundation\StreamedResponse;


class LectureController extends Controller
{

	// 教員が出席者を確認するとき
    public function showStudent($lecture) {
    	// Lectureモデルから$lecture(番号)のlectureを検索して取得
    	// 出席した学生一覧
    	$attendall = Lecture::where('id', $lecture)
    		->value('attendstudent');

    	// 授業名を取得
    	$lectitle = Lecture::where('id', $lecture)
    		->value('title');

    	// 授業の全履修者数を取得
    	$lecnum = Lecture::where('id', $lecture)
    		->value('number');

    	// ユーザーが教員のときのみ, 出席管理画面にアクセスできる
    	if(Auth::user()->student_id == 'teacher'){
    		// showStudentを表示
	    	return view('showStudent', ['attendall' => $attendall,
									'lectitle' => $lectitle,
									'lecnum' => $lecnum,
                                    'lecture' => $lecture,
									]);
    	}else{
    		return redirect('/');
    	}
    }

    // 教員が出席者データをcsvでダウンロードするとき
    public function downloadCSV($lecture) {
        $attendall = Lecture::where('id', $lecture)
            ->value('attendstudent');
        $cnt = 1;
        $student = explode(",", $attendall);

        $headers = array(
          "Content-type" => "text/csv",
          "Content-Disposition" => "attachment; filename=student.csv",
          "Pragma" => "no-cache",
          "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
          "Expires" => "0"
        );

        $callback = function() {
            $handle = fopen('php://output', 'w');

            $columns = [
                '学生番号',
                '名前',
              ] ;
            mb_convert_variables('SJIS-win', 'UTF-8', $columns);

            fputcsv($handle, $columns);

            $csv = [$attendall];

            mb_convert_variables('SJIS-win', 'UTF-8', $csv);
            fputcsv($handle, $csv);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // 学生が出席をクリックしたとき
    public function clickUser(Request $request, $lecture) {
    	$user = Auth::user();

    	// Lectureモデルから$lecture(番号)のlectureを検索して取得
    	$setpos = Lecture::where('id', $lecture)
    		->value('attendstudent');

		// ,名前,学生番号,名前,学生番号... の順に格納していく
    	$setpos = $setpos.",".$user->student_id;
    	$setpos = $setpos.",".$user->name;

    	// 取得したlectureのattendstudentにuserのnameとstudent_idを格納
    	Lecture::where('id', $lecture)
    		->update([
    			'attendstudent' => $setpos
    		]);

    	return redirect('/');
    }
}
