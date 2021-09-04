# システム開発 前期課題 手順書
# 依存ソフトウェア
事前に以下のソフトウェアを導入しておいてください。
* git
* Docker
* Docker Compose

# 構築手順
1.ソースコード設置
ソースコードを設置します。
GitHub上に公開しているリポジトリ`sakakota/sysDev`にあります。
```
git clone https://github.com/sakakota/sysDev
cd sysDev
```

2.ビルド、起動
Docker Composeで管理するDockerコンテナ上で実行します。
```
docker-compose build
docker-compose up
```

3.テーブルの作成
起動中にMySQLのCLIクライアントを起動する。
```
docker exec -it mysql mysql assignment
```

以下のコードでテーブルを作成する。
```
CREATE TABLE `details` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `body` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `img` TEXT DEFAULT NULL
);
```

4.動作確認
動作確認ページは`/assignment/index.php`です。
ブラウザから`http://サーバーアドレス/index.php`にアクセスして動作確認をします。
構築手順は以上です。
