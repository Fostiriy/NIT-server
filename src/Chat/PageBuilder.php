<?php

namespace Chat;
class PageBuilder
{
    public function buildChatPage()
    {
        echo '<link rel="stylesheet" href="../public/style.css">
<div>
    <p>Введите имя пользователя и пароль, опционально сообщение. Введённое сообщение будет сохранено, и вы увидите все
        сообщения пользователя.</p>
    <p>Чтобы увидеть сообщения всех пользователей, оставьте поле "Имя пользователя" пустым или введите туда "default".</p>
    <p>Если введённого пользователя нет в системе, то он будет добавлен.</p>
</div>

<form method="get">
    <div>
        <label>
            Имя пользователя:
            <input type="text" name="user" placeholder="user" id="login">
        </label>
    </div>
    <div>
        <label>
            Пароль:
            <input type="text" name="password" placeholder="123" id="password">
        </label>
    </div>
    <div class="input-wrapper">
        <label>
            <input class="input-area" type="text" name="message" placeholder="Напишите сообщение..." id="message">
        </label>
        <button class="input-button">Отправить</button>
    </div>
</form>';
    }
}