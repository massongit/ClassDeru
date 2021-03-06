<?php
//	学生が出席ボタンを押したとき, 教員が確認ボタンを押したときに
//	呼ばれるコントローラー

namespace App\Http\Controllers;

use App\Lecture;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LectureController extends Controller
{
    // 教員が授業を追加する
    public function addLecture(Request $request)
    {
        // 記入漏れがあった場合の処理
        if ($request->title == "" or $request->univ == "" or $request->gra == "" or $request->dep == "" or $request->number == "" or $request->date == "") {
            return redirect('/user')->with('my_status_2', __('未記入項目があります。'));
        } else {
            // 正しく記入されている場合の処理
            $lecture = new Lecture;

            $lecture->title = $request->title;
            $lecture->univ = $request->univ;
            $lecture->gra = $request->gra;
            $lecture->dep = $request->dep;
            $lecture->number = $request->number;
            $lecture->date = $request->date;

            // パスワードが記入されていたら代入する
            if ($request->lecpass != "") {
                $lecture->lecpass = $request->lecpass;
            } else {
                $lecture->lecpass = "";
            }

            $lecture->user_id = $request->user()->id;
            $lecture->save();

            return redirect('/user');
        }
    }


    // 教員が出席者を確認するとき
    public function showStudent($lecture)
    {
        // 出席した学生数
        $lecnum = \DB::table('lecture_students')->where('lid', $lecture)->count();

        // 登録履修者数
        $allnum = \DB::table('lectures')->where('id', $lecture)->value('number');

        // 授業名を取得
        $lectitle = Lecture::where('id', $lecture)
            ->value('title');

        // 出席した学生の名前と学生番号を取得
        $attendallname = \DB::table('lecture_students')->where('lid', $lecture)->pluck('sname');
        $attendallid = \DB::table('lecture_students')->where('lid', $lecture)->pluck('sid');


        // ユーザーが教員のときのみ, 出席管理画面にアクセスできる
        if (Auth::user()->student_id == 'teacher') {
            // showStudentを表示
            return view('showStudent', ['attendallname' => $attendallname,
                'attendallid' => $attendallid,
                'lectitle' => $lectitle,
                'lecnum' => $lecnum,
                'lecture' => $lecture,
                'allnum' => $allnum,
            ]);
        } else {
            return redirect('/');
        }
    }


    // 教員が出席者データをcsvでダウンロードするとき
    public function downloadCSV($lecture)
    {
        // 出席者を取得
        $attendallname = \DB::table('lecture_students')->where('lid', $lecture)->pluck('sname');
        $attendallid = \DB::table('lecture_students')->where('lid', $lecture)->pluck('sid');

        // 全出席者数を取得
        $syuseki_num = \DB::table('lecture_students')->where('lid', $lecture)->count();

        // 履修者数を取得
        $risyu_num = \DB::table('lectures')->where('id', $lecture)->value('number');

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
        $now = $t1 . "年" . $t2 . "月" . $t3 . "日" . $t4 . "時" . $t5 . "分";

        // 出席者数
        $num = $syuseki_num . "/" . $risyu_num . " 人";

        mb_convert_variables('SJIS-win', 'UTF-8', $num);
        mb_convert_variables('SJIS-win', 'UTF-8', $title);
        mb_convert_variables('SJIS-win', 'UTF-8', $now);
        $data = [$title, $num, $now];
        fputcsv($stream, $data);

        // 改行させる
        $data = [""];
        fputcsv($stream, $data);

        // 出席番号,学生番号,名前 で格納していく
        for ($i = 0; $i < count($attendallname); $i++) {
            $data = [$i + 1, $attendallid[$i], $attendallname[$i]];
            fputcsv($stream, $data);
        }
    }


    // 教員が出席者データをtxtでダウンロードするとき
    public function downloadTxt($lecture)
    {
        // 出席者の名前と学生番号を取得
        $attendallname = \DB::table('lecture_students')->where('lid', $lecture)->pluck('sname');
        $attendallid = \DB::table('lecture_students')->where('lid', $lecture)->pluck('sid');

        // 全出席者数を取得
        $syuseki_num = \DB::table('lecture_students')->where('lid', $lecture)->count();

        // 履修者数を取得
        $risyu_num = \DB::table('lectures')->where('id', $lecture)->value('number');

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
        $now = $t1 . "年" . $t2 . "月" . $t3 . "日" . $t4 . "時" . $t5 . "分";

        // 出席者数
        $num = $syuseki_num . "/" . $risyu_num . " 人";

        mb_convert_variables('SJIS-win', 'UTF-8', $num);
        mb_convert_variables('SJIS-win', 'UTF-8', $title);
        mb_convert_variables('SJIS-win', 'UTF-8', $now);
        $data = [$title, $now];
        fputcsv($stream, $data);

        // 改行させる
        $data = [""];
        fputcsv($stream, $num, $data);

        // 出席番号,学生番号,名前 で格納していく
        for ($i = 0; $i < count($attendallname); $i++) {
            $data = [$i + 1, $attendallid[$i], $attendallname[$i]];
            fputcsv($stream, $data);
        }
    }

    // IPアドレス判定
    private function checkip(string $ip, string $ansip, string $d1, string $d2, string $d3)
    {
        list($accept_ip, $mask) = explode("/", $ansip);
        list($deny_ip1, $m1) = explode("/", $d1);
        list($deny_ip2, $m2) = explode("/", $d2);
        list($deny_ip3, $m3) = explode("/", $d3);

        $accept_long = ip2long($accept_ip) >> (32 - $mask);
        $user_long = ip2long($ip) >> (32 - $mask);

        $deny_long1 = ip2long($deny_ip1) >> (32 - $m1);
        $user_long1 = ip2long($ip) >> (32 - $m1);
        $deny_long2 = ip2long($deny_ip2) >> (32 - $m2);
        $user_long2 = ip2long($ip) >> (32 - $m2);
        $deny_long3 = ip2long($deny_ip3) >> (32 - $m3);
        $user_long3 = ip2long($ip) >> (32 - $m3);

        return $accept_long == $user_long and $deny_long1 != $user_long1 and $deny_long2 != $user_long2 and $deny_long3 != $user_long3;
    }

    // 学生が出席をクリックしたとき
    public function clickUser(Request $request, $lecture)
    {
        $user = Auth::user();
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        //$allow_ip = config('app.allow_ip'); //ローカルの場合
        //$d1_ip = config('app.deny1_ip');
        //$d2_ip = config('app.deny2_ip');
        //$d3_ip = config('app.deny3_ip');
        $allow_ip = getenv('ALLOW_IP');       //herokuから取得
        $d1_ip = getenv('DENY1_IP');
        $d2_ip = getenv('DENY1_IP');
        $d3_ip = getenv('DENY1_IP');

        if (self::checkip($ip, $allow_ip, $d1_ip, $d2_ip, $d3_ip)) {
            // 授業のパスワードを取得
            $pass = \DB::table('lectures')->where('id', $lecture)->value('lecpass');

            // 授業のパスワードと学生が入力したパスワードが一致しているか
            if ($pass == $request->userpass || $pass == '000') {
                // 授業の出席者の学生番号を配列で取得
                $s = \DB::table('lecture_students')->where('lid', $lecture)->pluck('sid')->toArray();

                // 出席クリックした学生が既に出席者配列に含まれていたとき
                if (in_array($user->student_id, (array)$s)) {
                    return redirect('/user')->with('my_status_2', __('出席済みです。'));
                } else {
                    // 出席者配列に新しく追加
                    \DB::table('lecture_students')->insert([
                        'lid' => $lecture,
                        'sname' => $user->name,
                        'sid' => $user->student_id,
                    ]);
                }

                return redirect('/user')->with('my_status', __('出席完了'));
            } else {
                return redirect('/user')->with('my_status_2', __('パスワードが違います。'));
            }

        }

        return redirect('/user')->with('my_status_2', __('教室内から出席してください。'));
    }
}

