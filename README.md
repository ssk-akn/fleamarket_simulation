# フリマアプリ
## プロジェクトの目的
- 学習を目的とした、アイテムの出品と購入を行うためのフリマアプリです。
## 主要機能
- ユーザー登録・ログイン（Laravel Fortifyを使用）
- ユーザーメール認証（mailhogを使用）
- 商品の一覧画面、詳細画面は未認証ユーザーも閲覧可能
- ユーザーのみの機能
  - 商品に「いいね」をしてマイリストへ追加
  - 商品に対するコメントを投稿
  - 商品を購入（決済はStripeを使用）
  - 商品を出品
  - マイページにてプロフィールを確認、編集
## 環境構築
### Dockerビルド
1. git clone git@github.com:ssk-akn/fleamarket_simulation.git
2. DockerDesktopアプリを立ち上げる
3. docker-compose up -d --build
### Laravel環境構築
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
php artisan db:seed
```
8. シンボリックリンク作成
```
php artisan storage:link
```
9. Stripeパッケージの導入
```
composer require stripe/stripe-php
```
10. .envにStripe用の環境変数を追加
```
STRIPE_KEY=your_stripe_public_key
STRIPE_SECRET=your_stripe_secret_key
```
11. config/services.phpにStripeの設定を追加
```
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
],
```
## 開発環境
- 商品一覧画面：http://localhost/
- 会員登録：http://localhost/register
- mailhog：http://localhost:8025/
- phpMyAdmin：http://localhost:8080/
## ログイン情報
- 管理者
  - Email: admin@example.com
  - Password: password123
- 一般ユーザー
  - Email: user@example.com
  - Password: password123
## 決済テスト用カード情報
- クレジットカード番号
  - 4242 4242 4242 4242
- 有効期限
  - 有効な将来の日付を使用
- セキュリティコード
  - 任意の 3 桁の数字
## テーブル設計

![スクリーンショット (19)](https://github.com/user-attachments/assets/cc660da8-1829-4002-a1f7-8788e2a5422f)
![スクリーンショット (9)](https://github.com/user-attachments/assets/cc86a7f4-a1c9-4735-98fe-9a2fa207e550)
![スクリーンショット (10)](https://github.com/user-attachments/assets/cb0d6056-5d68-4ea1-9fad-9d46976986a7)


## ER図

![スクリーンショット (1)](https://github.com/user-attachments/assets/03b207bd-cbc7-4edd-9361-bdf191f74d1a)




## 使用技術(実行環境)
- PHP8.3.0
- Laravel8.83.29
- MySQL8.0.26
- nginx1.21.1
- Docker
