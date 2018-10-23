<!-- 教員が出席者を確認するときに表示するbladeファイル
	 LectureControllerのshowStudent関数から呼ばれる
 -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF トークン -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ClassDeru</title>

    <!-- スクリプト -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- フォント -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- スタイル -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    ClassDeru 授業出席システム
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('ログイン') }}</a>
                            </li>
                            <li class="nav-item">
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('新規登録') }}</a>
                                @endif
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        ログアウト
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>


        <main class="py-4">
        	<center>
        		&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
	        	<h1>{{ $lectitle }} 出席者一覧</h1>

	        	&emsp;&emsp;

                <form action="/lecture/{{ $lecture }}/csvdownload" method="GET">
                    <a href="/lecture/{{ $lecture }}/csvdownload">
                        CSVダウンロード
                    </a>
                </form>
			 	&emsp;&emsp;&emsp;&emsp;
                <form action="/lecture/{{ $lecture }}/txtdownload" method="GET">
                    <a href="/lecture/{{ $lecture }}/txtdownload">
                        txtダウンロード
                    </a>
                </form>

	        	<br>
	        	<br>

	        	<h3>
	        		<?php
	        			// 現在時刻を表示させる
	        			$data = new DateTime("now",new DateTimeZone('Asia/Tokyo'));
	        			echo $data->format('m月d日 H時i分');
	        		?>
	        	</h3>

	        	<h2><font color="blue">
				    <?php
				    	echo $lecnum." / ".$allnum." 人"."<br>";
				    ?>
				</font></h2>

				<hr size="5" width="50%" color="green">

			    <B>
			    <?php
                    // 出席した学生の学生番号と名前を表示
                    for($i=0; $i<count($attendallname); $i++){
                        echo $attendallid[$i]."&emsp;".$attendallname[$i];
                        echo "<br>";
                    }
			     ?>
			 	</B>

			    <br>
			    <br>
		 	</center>

        </main>
    </div>

</body>

</html>

