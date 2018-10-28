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
    // 教員が授業を追加する
    public function addLecture(Request $request) {
        $lecture = new Lecture;
    
        $lecture->title = $request->title;
        $lecture->univ = $request->univ;
        $lecture->gra = $request->gra;
        $lecture->dep = $request->dep;
        $lecture->number = $request->number;
        $lecture->date = $request->date;

        // パスワードが記入されていたら代入する
        if($request->lecpass != ""){
            $lecture->lecpass = $request->lecpass;
        }else{
            $lecture->lecpass = "";
        }

        $lecture->user_id = $request->user()->id;
        $lecture->save();

        return redirect('/user');
    }


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
        $attendallname = \DB::table('lecture_students')->where('lid',$lecture)->pluck('sname');
        $attendallid = \DB::table('lecture_students')->where('lid',$lecture)->pluck('sid');

        // 全出席者数を取得
        $syuseki_num = \DB::table('lecture_students')->where('lid',$lecture)->count();
        // 履修者数を取得
        $risyu_num = \DB::table('lectures')->where('id',$lecture)->value('number');

        // 授業名を取得
        $title = Lecture::where('id', $lecture)
            ->value('title');

        // 時刻取得
        $time = new Carbon();
        $time->setTimezone('Asia/Tokyo');

        # 文字コード変換
        mb_convert_variables('SJIS-win', 'UTF-8', $attendallname);
        mb_convert_variables('SJIS-win', 'UTF-8', $attendallid); 
          
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename={$title}_出席者.csv");

        $stream = fopen('php://output', 'w');

        // 時刻付与
        $t1 = $time->year;
        $t2 = $time->month;
        $t3 = $time->day;
        $t4 = $time->hour;
        $t5 = $time->minute;
        $now = $t1."年".$t2."月".$t3."日".$t4."時".$t5."分";

        // 出席者数
        $num = $syuseki_num."/".$risyu_num." 人";

        mb_convert_variables('SJIS-win', 'UTF-8', $num); 
        mb_convert_variables('SJIS-win', 'UTF-8', $title);
        mb_convert_variables('SJIS-win', 'UTF-8', $now);
        $data = [$title, $num, $now];
        fputcsv($stream, $data);

        // 改行させる
        $data = [""];
        fputcsv($stream, $data);
        
        // 出席番号,学生番号,名前 で格納していく
        for($i=0; $i<count($attendallname); $i++){
            $data = [$i+1, $attendallid[$i], $attendallname[$i]];
            fputcsv($stream,$data);
        }
    }


    // 教員が出席者データをtxtでダウンロードするとき
    public function downloadTxt($lecture) {
        // 出席者の名前と学生番号を取得
        $attendallname = \DB::table('lecture_students')->where('lid',$lecture)->pluck('sname');
        $attendallid = \DB::table('lecture_students')->where('lid',$lecture)->pluck('sid');
        
        // 全出席者数を取得
        $syuseki_num = \DB::table('lecture_students')->where('lid',$lecture)->count();
        // 履修者数を取得
        $risyu_num = \DB::table('lectures')->where('id',$lecture)->value('number');

        // 授業名を取得
        $title = Lecture::where('id', $lecture)
            ->value('title');

        // 時刻取得
        $time = new Carbon();
        $time->setTimezone('Asia/Tokyo');

        # 文字コード変換
        mb_convert_variables('SJIS-win', 'UTF-8', $attendallname); 
        mb_convert_variables('SJIS-win', 'UTF-8', $attendallid); 
          
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename={$title}_出席者");

        $stream = fopen('php://output', 'w');

        // 時刻付与
        $t1 = $time->year;
        $t2 = $time->month;
        $t3 = $time->day;
        $t4 = $time->hour;
        $t5 = $time->minute;
        $now = $t1."年".$t2."月".$t3."日".$t4."時".$t5."分";

        // 出席者数
        $num = $syuseki_num."/".$risyu_num." 人";

        mb_convert_variables('SJIS-win', 'UTF-8', $num); 
        mb_convert_variables('SJIS-win', 'UTF-8', $title);
        mb_convert_variables('SJIS-win', 'UTF-8', $now);
        $data = [$title, $now];
        fputcsv($stream, $data);

        // 改行させる
        $data = [""];
        fputcsv($stream, $num, $data);
        
        // 出席番号,学生番号,名前 で格納していく
        for($i=0; $i<count($attendallname); $i++){
            $data = [$i+1, $attendallid[$i], $attendallname[$i]];
            fputcsv($stream,$data);
        }
    }


    // 学生が出席をクリックしたとき
    public function clickUser(Request $request, $lecture) {
    	$user = Auth::user();

        // 授業のパスワードを取得
        $pass = \DB::table('lectures')->where('id',$lecture)->value('lecpass');

        // 授業のパスワードと学生が入力したパスワードが一致しているかどうかどうか
        if($pass == $request->userpass || $pass=='000'){
            \DB::table('lecture_students')->insert([
                'lid' => $lecture,
                'sname' => $user->name,
                'sid' => $user->student_id,
            ]);

            return redirect('/user')->with('my_status', __('出席完了'));
        }else{
            return redirect('/user')->with('my_status_2', __('パスワードが違います。'));
        }
    }
}


