<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>出席管理システム</title>
</head>
<body>
    <h1>{{ $lectitle }} 出席者一覧</h1>

    <?php
    	// 出席した学生を , で分割
    	$student = explode(",", $attendall);

    	// 出席者数を取得
    	$num = (count($student)-1)/2;
    	echo $num." / ".$lecnum." 人"."<br>";

    	// 1人ずつ学生を表示
    	// 名前,学生番号,名前,学生番号 の順に並んでいる
    	$cnt = 1;
     	foreach($student as $s) {
     		echo $s." ";
     		$cnt += 1;
     		if($cnt % 2 == 0){
     			echo "<br>";
     		}
     	}
     ?>

    <br>
    <br>
    <a href="#", onclick="window.history.back(); return false;">
    	戻る
 	</a>


</body>
</html>
