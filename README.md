###フリマアプリ
##環境構築
#Dockerビルド
1. git clone git@github.com:差し替えます
2. DockerDesktopアプリを立ち上げる
3. docker-compose up -d --build
#Laravel環境構築
1. docker-compose exec php bash
2. composer install
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
5. アプリケーションキーの作成
```
php artisan key:generate
```
6. マイグレーションの実行
```
php artisan migrate
```
7. シーディングの実行
```
php artisan db:seedphp artisan storage:link
```
8. シンボリックリンク作成
```
php artisan storage:link
```
##使用技術(実行環境)
・PHP8.3.0
・Laravel8.83.27
・MySQL8.0.26
##テーブル設計

![スクリーンショット (8)](https://github.com/user-attachments/assets/17087195-a94d-4540-a74e-27248ff6e5a6)
				
##ER図

![スクリーンショット (6)](https://github.com/user-attachments/assets/32ecfcea-3194-416b-bc0e-98141921ce1f)
![スクリーンショット (9)](https://github.com/user-attachments/assets/cc86a7f4-a1c9-4735-98fe-9a2fa207e550)
![スクリーンショット (9)](https://github.com/user-attachments/assets/f30f3965-e9b5-48a6-9128-fdde5a0c91db)

##URL
・開発環境：http://localhost/
・phpMyAdmin:：http://localhost:8080/
