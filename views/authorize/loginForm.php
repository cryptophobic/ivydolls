<form class="ajaxForm" action="/authorize/login" method="POST">
    <h4 class="caption text-danger warnMessage"></h4>
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
    <div class="form-group">
        <label for="inputEmail">Email или Телефон:</label>
        <input type="email" required="true" class="form-control" name="data" id="inputEmail" value="">
    </div>
    <div class="form-group">
        <label for="inputMessage">Пароль:</label>
        <input type="password" required="true" class="form-control" name="password" id="inputMessage">
    </div>
    <div class="container-fluid">
        <div class="col-sm-2">
            <button type="submit" id="login" name="login" value="1"
                    class="btn btn-primary">Войти
            </button>
        </div>
        <div class="col-sm-4">
            <button type="button" data-target="#myModal" data-content="/authorize/register-form"
                    class="modal-load btn btn-primary">Новый пользователь
            </button>
        </div>
        <div class="col-sm-4">
            <a href="#">Забыли пароль?</a>
        </div>
    </div>
</form>
