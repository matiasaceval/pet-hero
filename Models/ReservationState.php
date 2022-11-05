<?php

namespace Models;

class ReservationState {
    const PENDING = "PENDING";
    const ACCEPTED = "ACCEPTED";
    const REJECTED = "REJECTED";

    const CANCELED = "CANCELED";

    const PAID = "PAID";
    const CONFIRMED = "CONFIRMED";
    const IN_PROGRESS = "IN PROGRESS";
    const FINISHED = "FINISHED";

    /* Pets with these states can't be hosted */
    public static function GetDisablingStates(): array {
        return [
            ReservationState::PENDING,
            ReservationState::ACCEPTED,
            ReservationState::PAID,
            ReservationState::IN_PROGRESS,
            ReservationState::CONFIRMED
        ];
    }

    public static function GetStates(): array {
        return [
            ReservationState::PENDING,
            ReservationState::ACCEPTED,
            ReservationState::REJECTED,
            ReservationState::CANCELED,
            ReservationState::PAID,
            ReservationState::CONFIRMED,
            ReservationState::IN_PROGRESS,
            ReservationState::FINISHED
        ];
    }
}

/**     
 *      Pending ---> Accepted --> Paid --> Confirmed --> In Progress --> Finished
 *        v              v          v
 *      Rejected    Canceled   Rejected
 * 
 */