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

intelliJ ideaを使用してください

### setup

* install mysql

mysqlをインストール

* install php 8.1.*

php8.1をインストール

* install composer

composerをインストール

* install symfony
  
symfonyをインストール

* install node 18.*
  
node18をインストール

* run install task x 2
  
installタスクを2種類実行する

* run watch task
  
watchタスクを実行する

* copy `.env` to `.env.local`
  
`.env`をコピーして`.env.local`を作成する

* write database setting to `.env.local`
  
databaseの設定を`.env.local`に書き換える

```.env.local
DATABASE_URL="mysql://fns:password@127.0.0.1:3306/fns?serverVersion=15&charset=utf8"
```
* run migration task

migrationタスクを実行する

* run server task

serverタスクを実行する

* access http://127.0.0.1:8000

https://127.0.0.1:8000 を開く

## Rest API
