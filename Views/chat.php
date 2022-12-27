<?php

use Models\MessageState;

require_once(VIEWS_PATH . "back-nav.php");
?>
<script>
    let chat;

    function refreshChat() {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "<?php echo FRONT_ROOT . "Chat/Refresh?id=" . $chat->getId(); ?>", true);
        xhr.onload = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = xhr.response;
                    if (data != "") {
                        console.log("Data fetched");
                        data = data.split("</head>")[1];
                        data = data.split("</body>")[0];
                        data = data.replace(/\r/g, "");

                        data = data.replace(/"/g, "'");
                        let html = $(".card-body").html();
                        html = html.replace(/"/g, "'");

                        if (data != html) {
                            console.log("Data changed");
                            $(".card-body").html(data)
                            chat.scrollTo({
                                top: $(".card-body")[0].scrollHeight,
                                behavior: 'smooth'
                            });
                        }
                    }
                }
            }
        }
        xhr.send();
    }

    $(document).ready(function() {
        chat = document.getElementsByClassName("card-body")[0];
        chat.scrollTo({
            top: $(".card-body")[0].scrollHeight,
            behavior: 'instant'
        });


        $('form.message-form').submit(function(e) {
            e.preventDefault();
        })

        $('.message-form button').click(function(e) {
            e.preventDefault();
            let message = $('.message-form textarea').val();
            if (/([^\s])/.test(message)) {
                $('.card-body').append('<p class="loading"></p>');
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "<?php echo FRONT_ROOT . "Chat/SendMessage" ?>", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            let data = xhr.response;
                            if (data != "") {
                                console.log("Message sent");
                                $('.loading').remove();
                                chat.scrollTo({
                                    top: $(".card-body")[0].scrollHeight,
                                    behavior: 'smooth'
                                });
                            }
                        }
                    }
                }
                xhr.send("chatId=<?php echo $chat->getId(); ?>&message=" + message);
                $('.message-form textarea').val("");
            }
        })

        $(document).keydown(function(event) {
            if (event.keyCode == 13 && !event.shiftKey) {
                event.preventDefault();
                $('.message-form button').click();
            } else if (event.keyCode == 13 && event.shiftKey && $('.message-form textarea').val() != '') {
                event.preventDefault();
                $('.message-form textarea').val($('.message-form textarea').val() + "\r");
            } else if (event.keyCode == 13 && event.shiftKey && $('.message-form textarea').val() == '') {
                event.preventDefault();
            }
        });

        refreshChat();
        setInterval(refreshChat, 500);
    });
</script>
<div class="container">
    <div class="card">
        <div class="participant-info">
            <p><?php echo $otherParticipant->getFullname(); ?></p>
        </div>
        <div class="card-body overflow-auto">

        </div>
        <div class="message-form-div">
            <form class="message-form" action="<?php echo FRONT_ROOT ?>Chat/SendMessage" method="post">
                <input type="hidden" name="chatId" value="<?php echo $chat->getId(); ?>">
                <textarea type="text" name="message" id="message" placeholder="Escribe tu mensaje aquí" ocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"></textarea>
                <button class="message-send-button" type="submit"><img width='32px' src="<?php echo VIEWS_PATH ?>/img/send.png" alt="Enviar mensaje"></button>
            </form>
        </div>
    </div>
</div>