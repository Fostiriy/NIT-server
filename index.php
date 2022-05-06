<link rel="stylesheet" href="style.css">

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
</form>

<?php

function print_messages($user)
{
    $users_json = json_decode(file_get_contents("users.json"), true);
    $messages = $users_json["messages"];

    foreach ($messages as $message) {
        if ($message['user'] == $user || $user == "default") {
            echo "<div class = \"message-wrapper\">";
            echo "<p class=\"message-info\">[" . $message["date"] . "] " . $message["user"] . "</p>";
            echo "<p class=\"message-text\">" . $message["message"] . "</p>";
            echo "</div>";
        }
    }
}

function add_message($user)
{
    $message = $_GET["message"];
    $users_json = json_decode(file_get_contents("users.json"), true);

    // adding message
    if (isset($message) && $message !== "") {
        $users_json["messages"][] = [
            "date" => date('m-d-Y H:i', time()),
            "user" => $user,
            "message" => $message
        ];
        file_put_contents("users.json", json_encode($users_json));
    }
}

function is_user_exists($user)
{
    $result = false;

    $users_json = json_decode(file_get_contents("users.json"), true);
    foreach ($users_json["users"] as $item) {
        if ($item["user"] == $user) {
            $result = true;
        }
    }

    return $result;
}

function get_password($user)
{
    $result = "";

    $users_json = json_decode(file_get_contents("users.json"), true);
    foreach ($users_json["users"] as $item) {
        if ($item["user"] == $user) {
            $result = $item["password"];
        }
    }

    return $result;
}


// ===== main =====

$user = $_GET["user"];
$password = $_GET["password"];

if (!isset($user) || $user == "" || $user == "default") {
    add_message("default");
    print_messages("default");
} elseif (isset($password) && $password != "") {
    $users_json = json_decode(file_get_contents("users.json"), true);

    // adding user
    if (!is_user_exists($user)) {
        echo "<p><i>Создан пользователь <b>$user</b></i></p>";
        $users_json["users"][] = [
            "user" => $user,
            "password" => $password
        ];
        file_put_contents("users.json", json_encode($users_json));
        add_message($user);
        print_messages($user);
    } else { // checking password
        $proper_password = get_password($user);

        if ($password == $proper_password) {
            add_message($user);
            print_messages($user);
        } else {
            echo "<p style='color: darkred'><i>Неверный пароль</i></p>";
        }
    }
} else {
    echo "<p style='color: darkred'><i>Введите пароль</i></p>";
}
