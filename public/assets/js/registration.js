// Knockout-Validationの初期設定
ko.validation.init({
    // エラーメッセージをDOMに自動挿入する
    insertMessages: true, 
    // エラーのある入力フィールドに適用するCSSクラス
    errorClass: 'error-field', 
    // エラーメッセージ自体に適用するCSSクラス
    messageClass: 'error-message' 
});

// View Modelの定義
const RegistrationViewModel = function() {
    const self = this;
    
    // ユーザー名: 必須 (required) かつ 3文字以上 (minLength)
    self.username = ko.observable('').extend({
        required: { message: 'ユーザー名は必須項目です' },
        minLength: { params: 3, message: '3文字以上で入力してください' }
    });

    // 【追加】メールアドレスの定義 (Controller/Modelで処理するため必須)
    self.email = ko.observable('').extend({
        //required: { message: 'メールアドレスは必須です' },
        email: { message: '有効なメールアドレス形式ではありません' }
    });

    // パスワード: 必須かつ 8文字以上 (minLength)
    self.password = ko.observable('').extend({
        required: { message: 'パスワードは必須です' },
        minLength: { params: 8, message: '8文字以上で設定してください' }
    });

    // パスワード確認: 必須かつ passwordと同じ (equal)
    self.passwordConfirm = ko.observable('').extend({
        required: { message: '確認用パスワードは必須です' },
        equal: { params: self.password, message: 'パスワードが一致しません' }
    });
    
    // View Model全体のエラーをグループ化し、フォーム全体が有効か判定
    self.errors = ko.validation.group(self);
    
    // 登録ボタンの有効/無効を制御するためのプロパティ
    self.isFormValid = ko.computed(function() {
        // エラーリストの数が0であれば、フォームは有効 (true)
        return self.errors().length === 0;
    });

    // 登録処理
    self.register = function() {
         if (!self.isFormValid()) {
             // エラーがあれば、すべてのメッセージを表示して処理を中断
             self.errors.showAllMessages(true);
             alert('入力エラーがあります。');
             return;
         }

        // --- ここからAJAX通信ロジック ---

        // 0.【追加】HTMLからCSRFトークンを取得
        const csrfToken = $('#csrf_token').val();

        // 1. 送信するデータを準備
        const dataToSend = {
            username: self.username(),
            email: self.email(),
            password: self.password(),
            
            // 2. 【追加】CSRFトークンをデータに含める
            csrf_token: csrfToken 
        };
        
        // サーバーに送る必要のない確認用パスワードを削除
        delete dataToSend.passwordConfirm; 
        console.log(dataToSend.username)

        // 3. AJAXリクエストの実行
        $.ajax({
            // 作成したAPIコントローラーのURIを指定
            url: '/api/user/register', 
            type: 'POST',
            data: dataToSend, // 送信するデータ
            dataType: 'json', // サーバーからの応答をJSON形式で期待
            
            success: function(response) {
                if (response.success) {
                    alert('登録成功！ログイン画面へ移動します。');
                    // 成功したらログイン画面などにリダイレクト
                    window.location.href = '/user/login'; 
                } else {
                    // サーバー側バリデーション（例：ユーザー名重複）によるエラー
                    alert('登録エラー: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                // 通信エラーやサーバーエラー（500など）
                // サーバーから返されたエラーメッセージがあれば表示
                if (xhr.status === 403) {
                    alert('セキュリティエラー：ページを更新します。');
                    window.location.reload(); // 🚨 403エラー時に強制リロード
                    return;
                }
                const message = xhr.responseJSON ? xhr.responseJSON.message : '予期せぬ通信エラーが発生しました。';
                alert('登録失敗: ' + message);
            }
        });
    };
};

// HTMLのDOM全体が読み込まれた後にバインディングを適用
$(document).ready(function() {
    ko.applyBindings(new RegistrationViewModel());
});

// Note: 既存のコードがconstを使っていたため、Modelの定義もconstに修正しました。
// また、メールアドレスのバリデーションをModelとControllerの要件に合わせて追加しました。
