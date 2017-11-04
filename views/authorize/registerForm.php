<form class="ajaxForm" action="/authorize/register" method="POST">
    <h4 class="caption text-danger warnMessage"></h4>
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
    <div class="form-group">
        <label for="inputEmail">Имя:</label>
        <input type="email" required="true" class="form-control" name="data" id="inputEmail" value="">
    </div>
    <div class="form-group">
        <label for="inputEmail">Email:</label>
        <input type="email" required="true" class="form-control" name="data" id="inputEmail" value="">
    </div>
    <div class="form-group">
        <label for="inputEmail">Телефон:</label>
        <input type="email" required="true" class="form-control" name="data" id="inputPhone" value="">
    </div>
    <div class="form-group">
        <label for="inputMessage">Пароль:</label>
        <input type="password" required="true" class="form-control" name="password" id="inputPassword">
    </div>
    <div class="form-group">
        <label for="inputMessage">Подтвердить пароль:</label>
        <input type="password" required="true" class="form-control" name="confirm-password" id="inputConfirm">
    </div>
    <div class="container-fluid">
        <div class="col-sm-2">
            <button type="submit" id="login" name="login" value="1"
                    class="btn btn-primary">Зарегистрироваться
            </button>
        </div>
    </div>
</form>
