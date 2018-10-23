<?php
//	学生が出席ボタンを押したとき, 教員が確認ボタンを押したときに
//	呼ばれるコントローラー

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Lecture;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;


class LectureController extends Controller
{
	// 教員が出席者を確認するとき
    public function showStudent($lecture) {

        // 出席した学生数
        $lecnum = \DB::table('lecture_students')->where('lid',$lecture)->count();
        // 登録履修者数
        $allnum = \DB::table('lectures')->where('id',$lecture)->value('number');

        // 授業名を取得
        $lectitle = Lecture::where('id', $lecture)
            ->value('title');

        // 出席した学生の名前と学生番号を取得
        $attendallname = \DB::table('lecture_students')->where('lid',$lecture)->pluck('sname');
        $attendallid = \DB::table('lecture_students')->where('lid',$lecture)->pluck('sid');


    	// ユーザーが教員のときのみ, 出席管理画面にアクセスできる
    	if(Auth::user()->student_id == 'teacher'){
    		// showStudentを表示
	    	return view('showStudent', ['attendallname' => $attendallname,
                                        'attendallid' => $attendallid,
									   'lectitle' => $lectitle,
									   'lecnum' => $lecnum,
                                        'lecture' => $lecture,
                                        'allnum' => $allnum,
									]);
    	}else{
    		return redirect('/');
    	}
    }

    // 教員が出席者データをcsvでダウンロードするとき
    public function downloadCSV($lecture) {

        // 出席者を取得
        $attendall = Lecture::where('id', $lecture)
            ->value('attendstudent');
        // 授業名を取得
        $title = Lecture::where('id', $lecture)
            ->value('title');

        // 時刻取得
        $time = new Carbon();
        $time->setTimezone('Asia/Tokyo');

        $students = explode(",", $attendall);
        # 先頭を1つ取り除く
        array_shift($students);
        # 文字コード変換
        mb_convert_variables('SJIS-win', 'UTF-8', $students); 
          
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename={$title}_出席者.csv");

        $stream = fopen('php://output', 'w');

        $cnt = 0;
        while(count($students) > 0){

            // タイトル付与
            if($cnt == 0){
                $t1 = $time->year;
                $t2 = $time->month;
                $t3 = $time->day;
                $t4 = $time->hour;
                $t5 = $time->minute;
                $now = $t1."年".$t2."月".$t3."日".$t4."時".$t5."分";

                mb_convert_variables('SJIS-win', 'UTF-8', $title);
                mb_convert_variables('SJIS-win', 'UTF-8', $now);
                $data = [$title, $now];
                fputcsv($stream, $data);
                // 改行させる
                $data = [""];
                fputcsv($stream, $data);
                $cnt += 1;
            }else{
                $data = [$cnt, array_shift($students), array_shift($students)];
                fputcsv($stream,$data);
                $cnt += 1;
            }
        }
    }

    // 教員が出席者データをtxtでダウンロードするとき
    public function downloadTxt($lecture) {

        // 出席者を取得
        $attendall = Lecture::where('id', $lecture)
            ->value('attendstudent');
        // 授業名を取得
        $title = Lecture::where('id', $lecture)
            ->value('title');

        // 時刻取得
        $time = new Carbon();
        $time->setTimezone('Asia/Tokyo');

        $students = explode(",", $attendall);
        # 先頭を1つ取り除く
        array_shift($students);
        # 文字コード変換
        mb_convert_variables('SJIS-win', 'UTF-8', $students); 
        $title = $title." 出席者一覧";
        mb_convert_variables('SJIS-win', 'UTF-8', $title);
        mb_convert_variables('SJIS-win', 'UTF-8', $time);
          
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename={$title}_出席者");

        $stream = fopen('php://output', 'w');

        $cnt = 0;
        while(count($students) > 0){

            // タイトルと時刻付与
            if($cnt == 0){
                $data = [$title, $time];
                fputcsv($stream, $data);
                // 改行させる
                $data = [""];
                fputcsv($stream, $data);
                $cnt += 1;
            }else{
                $data = [$cnt, array_shift($students), array_shift($students)];
                fputcsv($stream,$data);
                $cnt += 1;
            }
        }
    }

    // 学生が出席をクリックしたとき
    public function clickUser(Request $request, $lecture) {
    	$user = Auth::user();

        \DB::table('lecture_students')->insert([
            'lid' => $lecture,
            'sname' => $user->name,
            'sid' => $user->student_id,
        ]);

        return redirect('/')->with('my_status', __('出席完了'));


    }
}
