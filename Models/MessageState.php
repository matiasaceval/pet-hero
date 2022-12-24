<?php

namespace Models;

class MessageState
{
    const PENDING = "PENDING";      // in the moment of sending the message
    const RECEIVED = "RECEIVED";    // when the receiver logs in the system
    const READ = "READ";            // when the receiver open the current chat
}
