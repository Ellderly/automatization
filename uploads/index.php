<?php
    session_start();
?><!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanks Page!</title>
    <?php
        require_once 'assets/php/success_pixel.php';
        if (!$_SESSION['visited']) {
    ?>
    <!-- PIXEL -->
    <?php
        }
        $_SESSION['visited'] = true;
    ?>
    <?=(($_GET['url']) ? "<meta http-equiv=\"refresh\" content=\"3; url={$_GET['url']}\" />" : "");?>
 </head>

<body>


    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        body {
            background: url("thanks/bg.jpg") no-repeat center/cover;
        }

        main {
            border-radius: 0.5em;
            background-color: rgba(255, 255, 255, 0.7);
            position: relative;
            padding: 0em 1.5em;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-sizing: border-box;
        }

        

        .title,
        .desc,
        .text {
            line-height: normal;
            margin: 0;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            line-height: 1.2;
            color: rgb(40, 2, 58);
        }

        .text b {
            color: rgb(0, 85, 255);
        }

        @media (max-width: 467px) {
            body {
                background: url("thanks/bg.jpg") no-repeat center/cover;
            }

            main {
                width: 300px;
                /* height: 630px; */
            }

            main::after {
                top: -0.5em;
                right: -3em;
                width: 120px;
                height: 180px;
            }

            .logo {
                width: 150px;
                height: auto;

                margin-top: 20px;
            }

            .title {
                font-size: 32px;
                margin-top: 0px;
                margin-bottom: 15px;
            }

            .desc {
                font-size: 16px;

                margin-bottom: 4px;
            }

            .desc:last-child {
                margin-bottom: 0;
            }

            .text {
                font-size: 14px;
                margin-top: 5px;
                /* margin-bottom: 10px; */
            }
        }

        @media (min-width: 468px) and (max-width: 767px) {
            body {
                background: url("thanks/bg.jpg") no-repeat center/cover;
            }

            main {
                width: 350px;
                /* height: 570px; */
            }

            main::after {
                top: -5em;
                right: -4em;
                width: 170px;
                height: 250px;
            }

            .logo {
                width: 150px;
                height: auto;
            }

            .title {
                font-size: 32px;
                margin-top: 20px;
                margin-bottom: 15px;
            }

            .desc {
                font-size: 16px;

                margin-bottom: 15px;
            }

            .desc:last-child {
                margin-bottom: 0;
            }

            .text {
                font-size: 14px;
                /* margin-bottom: 20px; */
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {
            main {
                width: 500px;
                /* height: 470px; */
                padding: 2em 2em;
            }

            main::after {
                top: -5em;
                left: -9em;
                width: 280px;
                height: 400px;
            }

            .logo {
                width: 200px;
                height: auto;
            }

            .title {
                font-size: 32px;
                margin-top: 20px;
                margin-bottom: 15px;
            }

            .desc {
                font-size: 16px;

                margin-bottom: 15px;
            }

            .desc:last-child {
                margin-bottom: 0;
            }

            .text {
                font-size: 14px;
                /* margin-bottom: 20px; */
            }
        }

        @media (min-width: 992px) and (max-width: 1419px) {
            main {
                width: 650px;
                /* height: 490px; */
                padding: 2em 3em;
            }

            main::after {
                top: -5em;
                left: -9.5em;
                width: 300px;
                height: 400px;
            }

            .logo {
                width: 200px;
                height: auto;
            }

            .title {
                font-size: 36px;
                margin-top: 20px;
                margin-bottom: 15px;
            }

            .desc {
                font-size: 20px;
                margin-bottom: 15px;
            }

            .desc:last-child {
                margin-bottom: 0;
            }

            .text {
                font-size: 18px;
                /* margin-bottom: 20px; */
            }
        }


        @media (min-width: 667px) and (orientation: landscape) {
            body {
                background: url("thanks/bg.jpg") no-repeat center/cover;
            }

            main {
                width: 600px;
                /* height: 380px; */
                padding: 0em 3em;
            }

            main::after {
                top: -2.5em;
                left: -2.5em;
                width: 120px;
                height: 200px;
            }

            .logo {
                width: 100px;
            }

            .title {
                font-size: 24px;
                margin-top: 10px;
                margin-bottom: 10px;
            }

            .desc {
                font-size: 14px;
                margin-bottom: 10px;
            }

            .desc:last-child {
                margin-bottom: 0;
            }

            .text {
                font-size: 14px;
                /* margin-bottom: 10px; */
            }
        }

        @media (orientation: portrait) and (width: 820px) and (orientation: portrait) and (height: 1180px) {
            body {
                background: url("thanks/bg.jpg") no-repeat center/cover;
            }

            main {
                width: 550px;
                /* height: 850px; */
                padding: 2em 3em;
            }

            main::after {
                top: -5em;
                left: -9.5em;
                width: 300px;
                height: 400px;
            }

            .logo {
                width: 200px;
                height: auto;
            }

            .title {
                font-size: 42px;
                margin-top: 20px;
                margin-bottom: 25px;
            }

            .desc {
                font-size: 28px;
                margin-bottom: 25px;
            }

            .desc:last-child {
                margin-bottom: 0;
            }

            .text {
                font-size: 24px;
                /* margin-bottom: 25px; */
            }
        }

        @media (orientation: landscape) and (width: 1180px) and (orientation: landscape) and (height: 820px) {
            main {
                width: 850px;
                /* height: 550px; */
                padding: 0em 5em;
            }

            main::after {
                top: -7em;
                left: -11em;
                width: 350px;
                height: 500px;
            }

            .logo {
                width: 200px;
                height: auto;
            }

            .title {
                font-size: 36px;
                margin-top: 20px;
                margin-bottom: 15px;
            }

            .desc {
                font-size: 22px;
                margin-bottom: 15px;
            }

            .desc:last-child {
                margin-bottom: 0;
            }

            .text {
                font-size: 18px;
                /* margin-bottom: 20px; */
            }
        }

        @media (min-width: 1420px) {
            main {
                width: 900px;
                /* height: 550px; */
                padding: 0em 5em;
            }

            main::after {
                top: -7em;
                left: -11em;
                width: 350px;
                height: 500px;
            }

            .logo {
                width: 200px;
                height: auto;
            }

            .title {
                font-size: 36px;
                margin-top: 20px;
                margin-bottom: 15px;
            }

            .desc {
                font-size: 22px;
                margin-bottom: 15px;
            }

            .desc:last-child {
                margin-bottom: 0;
            }

            .text {
                font-size: 18px;
                /* margin-bottom: 20px; */
            }
        }
    </style>
    <main style="padding-top: 20px; padding-bottom: 20px;">
        <img class="logo" src="thanks/logo.png" width="150" height="80" alt="gazprom logo">
        <h1 class="title">Поздравляем!</h1>
        <p class="desc"><b>Вы стали одним из 500 счастливчиков, которые получили шанс участия в нашей программе.</b></p>
        <p class="text">Команда Газпром уже приняла в работу Вашу заявку! С Вами свяжется менеджер платформы в
            течение
            48
            часов.</p>
        <p class="text">Официальный представитель будет звонить с неизвестного для Вас номера телефона. Вам нужно будет
            <b>ОБЯЗАТЕЛЬНО</b> взять трубку или в случае 3-х пропущенных звонков есть вероятность, что Вы будете
            автоматически удалены с нашей программы и Ваше место займет другой участник!
        </p>
        <p class="desc"><b>НЕ УПУСТИТЕ СВОЙ ШАНС!</b></p>
        <p class="desc" style="color: rgb(255, 59, 59);margin-bottom: 0;margin-top: 10px;"><b>ВНИМАНИЕ!</b></p>
        <p class="text">НЕ оставляйте повторных заявок. Оставляя повторную заявку, вы можете попасть в конец очереди.
            Это увеличит время ожидания звонка специалиста.</p>
    </main>
</body>

</html>