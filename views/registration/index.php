<main class="container container--registration">
    <div class="center-block">
        <div class="registration-form regular-form">
            <form>
                <h3 class="head-main head-task">Регистрация нового пользователя</h3>
                <div class="form-group">
                    <label class="control-label" for="username">Ваше имя</label>
                    <input id="username" type="text">
                    <span class="help-block">Error description is here</span>
                </div>
                <div class="half-wrapper">
                    <div class="form-group">
                        <label class="control-label" for="email-user">Email</label>
                        <input id="email-user" type="email">
                        <span class="help-block">Error description is here</span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="town-user">Город</label>
                        <select id="town-user">
                            <option>Москва</option>
                            <option>Санкт-Петербург</option>
                            <option>Владивосток</option>
                        </select>
                        <span class="help-block">Error description is here</span>
                    </div>
                </div>
                <div class="half-wrapper">
                <div class="form-group">
                    <label class="control-label" for="password-user">Пароль</label>
                    <input id="password-user" type="password">
                    <span class="help-block">Error description is here</span>
                </div>
                </div>
                <div class="half-wrapper">

                <div class="form-group">
                    <label class="control-label" for="password-repeat-user">Повтор пароля</label>
                    <input id="password-repeat-user" type="password">
                    <span class="help-block">Error description is here</span>
                </div>
                </div>
                <div class="form-group">
                    <label class="control-label checkbox-label" for="response-user">
                        <input id="response-user" type="checkbox" checked>
                        я собираюсь откликаться на заказы</label>
                </div>
                <input type="submit" class="button button--blue" value="Создать аккаунт">
            </form>
        </div>
    </div>
</main>
