<h1>Вход в администраторскую панель</h1>
<form action="/admin/login" method="post">
	<div class="form-group row">
        <label for="login" class="col-sm-2 col-form-label">Логин</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="login" name="login" placeholder="Логин">
        </div>
    </div>
    <div class="form-group row">
        <label for="password" class="col-sm-2 col-form-label">Пароль</label>
        <div class="col-sm-4">
            <input type="password" class="form-control" id="password" name="password" placeholder="Пароль">
        </div>
    </div>
	<button type="submit" class="btn btn-primary">Войти</button>
</form>