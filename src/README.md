# Coachtech FleaMarket

## 環境構築  

Dockerビルド  

1. git clone git@github.com:kanae9427/coachtech-fleamarket.git
2. docker-compose up -d --build  

＊MySQLは、OSによって起動しない場合があるのでそれぞれのｐｃに合わせてdocker-compose.ymlファイルを編集してください。  

Laravel環境構築  

1. docker-compose exec php bash  
2. composer install
3. env.exampleファイルから.envを作成し、環境変数を変更
4. php artisan key:generate
5. php artisan migrate
6. php artisan storage:link
7. php artisan db:seed  

## メール送信（Mailtrap）  

開発環境でのメール送信確認には [Mailtrap](https://mailtrap.io/) を使用しています。  

### 設定手順
`.env` に以下の設定を追加してください：
MAIL_MAILER=smtp  
MAIL_HOST=sandbox.smtp.mailtrap.io  
MAIL_PORT=2525  
MAIL_USERNAME=your-mailtrap-username
（Mailtrap の SMTP ユーザー名）  
MAIL_PASSWORD=your-mailtrap-password
（Mailtrap の SMTP パスワード）  
MAIL_ENCRYPTION=null  
MAIL_FROM_ADDRESS=example@example.com  
MAIL_FROM_NAME="Example App"  
  
MAIL_ENCRYPTION=null は Mailtrap の仕様上、暗号化が不要なためです。もし送信に失敗する場合は tls を試してください。  

## 💳 決済機能（Stripe）  

本プロジェクトは [Stripe](https://stripe.com/jp) を使用してテストモードのクレジットカード決済とコンビニ決済を実装しています。
> ※ コンビニ決済はテストモードでは有効化せずに使用できます

`.env` に以下を追加してください：  
STRIPE_KEY=pk_test_あなたの公開キー  
STRIPE_SECRET=sk_test_あなたのシークレットキー  

テスト用カード番号：4242 4242 4242 4242  
有効期限：未来の日付（例：12/34）  
CVC：任意（例：123）  
> Stripe はテストモードで動作しています。  

## テスト環境のセットアップ

本プロジェクトでは、PHPUnit による単体テストが整備されています。  
テスト実行時には `.env.testing` が読み込まれますので、以下のように作成してください：  
cp .env .env.testing  

### テストの実行方法

```bash
php artisan test
```

## 使用技術  
* PHP 7.4.9
* Laravel 8.83.8
* MySQL（実体は MariaDB 10.3.39）

## URL  
* 開発環境：http://localhost/
* phpMyAdmin:http://localhost:8080/
