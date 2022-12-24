<?php

use Models\MessageState;

require_once(VIEWS_PATH . "back-nav.php");
?>
<script>
    $(document).ready(function() {
        const chat = document.getElementsByClassName("card-body")[0];
        chat.scrollTo({
            top: $(".card-body")[0].scrollHeight,
            behavior: 'instant'
        });

        $('form.message-form').submit(function(e) {
            if ($('#message').val() == '') {
                e.preventDefault();
                return;
            }
        })
    });
</script>
<div class="container">
    <div class="card">
        <div class="participant-info">
            <p><?php echo $otherParticipant->getFullname(); ?></p>
        </div>
        <div class="card-body overflow-auto">
            <?php
            $previousMessageFromOtherParticipant = false;
            foreach ($chat->getMessages() as $row) {
                echo "<div class='message'>";
                if ($row->getSender() == $session) {
                    echo "<div class='message text-right'>";
                    echo "<p>" . $row->getText() . "</p>";
                    echo "<div>";
                    echo "<span class='time'>" . $row->getDate() . "</span>";
                    if (
                        $row->getState() == MessageState::READ
                    ) {
                        echo "<img width='24px' src='" . VIEWS_PATH . "/img/double-tick-blue.png' alt='Mensaje leído'>";
                    } else if ($row->getState() == MessageState::RECEIVED) {
                        echo "<img width='24px' src='" . VIEWS_PATH . "/img/double-tick-gray.png' alt='Mensaje recibido'>";
                    } else {
                        echo "<img width='24px' src='" . VIEWS_PATH . "/img/tick-gray.png' alt='Mensaje pendiente'>";
                    }
                    echo "</div>";
                    echo "</div>";
                    $previousMessageFromOtherParticipant = false;
                } else {
                    echo "<div class='message text-left'>";
                    if (!$previousMessageFromOtherParticipant) echo "<p class='chat-other-user'>" . $row->getSender()->getFirstName() . "</p>";
                    echo "<p>" . $row->getText() . "</p>";
                    echo "<span class='time'>" . $row->getDate() . "</span>";
                    echo "</div>";
                    $previousMessageFromOtherParticipant = true;
                }
                echo "</div>";
            }
            ?>
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