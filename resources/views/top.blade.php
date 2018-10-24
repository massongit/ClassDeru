<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF トークン -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ClassDeru</title>

    <!-- スクリプト -->
    <script src="{{ asset('js/app.js') }}" defer>
    </script>

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
                    ClassDeru
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
                                    <a class="dropdown-item" href="{{ url('/user') }}">
                                        出席ページ
                                    </a>
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
            <div class="border">
                <img alt="study" src="{{ asset('/img/study_icon.png') }}" class="img1">
                <div class="title_name">ClassDeru</div>
                <div class="content_text">
                    &nbsp;ClassDeru(クラスデル)は授業の出席を簡単に取ることのできるWebアプリです。
                </div>

                <div class="teacher_detail">
                    <B>教員側</B><br>
                    &nbsp;教員は授業に出席している学生をリアルタイムで確認でき、
                    csvやtxt形式で出席データをダウンロードできます。
                    <img alt="csv" src="{{ asset('/img/csvicon.png') }}" class="csv">
                    <img alt="csv" src="{{ asset('/img/txticon.png') }}" class="txt">
                </div>

                <div class="student_detail">
                    <B>学生側</B><br>
                    &nbsp;学生はPCやスマホから1タップで授業に出席できます。
                    <img alt="pc" src="{{ asset('/img/pc.png') }}" class="pc">
                    <img alt="sm" src="{{ asset('/img/smaph.png') }}" class="smaph">
                </div>

            </div>
        </main>
        <br>

        <a href="https://github.com/kons16/ClassDeru" target="_blank">
            <img alt="sm" src="{{ asset('/img/github_logo.png') }}" class="github">
        </a>
        <div class="copy_right">&copy;2018 ClassDeru</div>

    </div>

</body>

</html>
