# FNS

## 必要な機能

* ログイン
* チャット
    * slackのチャンネルみたいな
* 企画参加者を募れる
* 友達機能

## サーバー

* lolipop
* PHP 8.1

## development

### setup

* install php 8.1.*
* install composer
* install symfony
* run install task
* copy `.env` to `.env.local`
* write database setting to `.env.local`
```.env.local
DATABASE_URL="mysql://fns:password@127.0.0.1:3306/fns?serverVersion=15&charset=utf8"
```
* run migration task
* run server task
* access [http://127.0.0.1:8000](http://127.0.0.1:8000)