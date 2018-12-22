## ClassDeru
大学で授業の出席を簡単に取ることのできるWebアプリです。

<img width="500" alt="2018-12-06 21 47 39" src="https://user-images.githubusercontent.com/31591102/49585184-07c1ea80-f9a1-11e8-95d6-062a5337c173.png">

## 機能
学生は授業のパスワードを入力することで簡単に出席できます。
<img width="500" alt="2018-12-06 21 48 39" src="https://user-images.githubusercontent.com/31591102/49585313-5bcccf00-f9a1-11e8-941a-58a9c8ff772c.png">

教員は出席している学生の一覧をcsv,txt形式でDLすることができ、リアルタイムに出席者を確認できます。
<img width="500" alt="2018-12-06 21 49 46" src="https://user-images.githubusercontent.com/31591102/49585356-799a3400-f9a1-11e8-9a3f-06a83ec5f200.png">


## URL
https://classderu.herokuapp.com/  

## Deploying to Heroku
1. Push [![Deploy](https://www.herokucdn.com/deploy/button.png)](https://heroku.com/deploy?template=https://github.com/kons16/ClassDeru)
1. Install the Heroku CLI (See [The Heroku CLI | Heroku Dev Center](https://devcenter.heroku.com/articles/heroku-cli#download-and-install))
1. Run `heroku login` (Login to Heroku)
1. Run `heroku git:clone -a {App name}` (Clone git repository in heroku)
1. Run `cd {App name}` (Move to the cloned directory)
2. Run `eval $(heroku config:get DATABASE_URL | awk '{print gensub(/postgres:\/\/(.+):(.+)@(.+):5432\/(.+)/, "heroku config:set DB_USERNAME=\"\\1\" DB_PASSWORD=\"\\2\" DB_HOST=\"\\3\" DB_DATABASE=\"\\4\"", "g")}')` (Set config vars)
