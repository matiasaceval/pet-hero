<?php

namespace Models;

class ReservationState {
    const PENDING = "PENDING";          // when created the reservation
    const ACCEPTED = "ACCEPTED";        // when the reservation is accepted by the keeper
    const REJECTED = "REJECTED";        // when the reservation is rejected by the keeper

    const CANCELED = "CANCELED";        // when the reservation is canceled because the time to upload the payment has expired

    const PAID = "PAID";                // when the payment is uploaded
    const CONFIRMED = "CONFIRMED";      // when the payment is confirmed by the keeper
    const IN_PROGRESS = "IN PROGRESS";  // when the reservation is in progress
    const FINISHED = "FINISHED";        // when the reservation is finished

    /* Pets with these states can't be hosted */
    public static function GetDisablingStates(): array {
        return [
            ReservationState::PENDING,
            ReservationState::ACCEPTED,
            ReservationState::PAID,
            ReservationState::CONFIRMED,
            ReservationState::IN_PROGRESS
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

    public static function StateAsId(string $state): string
    {
        return strtolower(implode('_', explode(" ", $state)));
    }
}

/**     
 *      Pending ---> Accepted --> Paid --> Confirmed --> In Progress --> Finished
 *        v              v          v
 *      Rejected    Canceled   Rejected
 * 
 */