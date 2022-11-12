<?php

namespace Controllers;

use DAO\KeeperDAOJson as KeeperDAO;
use DAO\PetDAOJson as PetDAO;
use DAO\ReservationDAOJson as ReservationDAO;
use DAO\ReviewsDAOJson as ReviewsDAO;
use Models\Reservation;
use Models\ReservationState;
use Utils\LoginMiddleware;
use Utils\Session;
use Utils\TempValues;

class ReservationController {
    private KeeperDAO $keeperDAO;
    private ReservationDAO $reservationDAO;
    private ReviewsDAO $reviewsDAO;
    private PetDAO $petDAO;

    public function __construct() {
        $this->keeperDAO = new KeeperDAO();
        $this->reservationDAO = new ReservationDAO();
        $this->reviewsDAO = new ReviewsDAO();
        $this->petDAO = new PetDAO();
    }

    public function PlaceReservationView(int $id) {
        LoginMiddleware::VerifyOwner();
        $keeper = $this->keeperDAO->GetById($id);
        if ($keeper == null) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }
        $pets = $this->AvailablePets();
        $reservations = $this->reservationDAO->GetByKeeperId($keeper->getId());

        // filter reservations that are cancelled or rejected by the keeper, so they can be reused
        $reservations = array_filter($reservations, function (Reservation $reservation) {
            return $reservation->getState() != ReservationState::CANCELED && $reservation->getState() != ReservationState::REJECTED;
        });

        $reviews = $this->reviewsDAO->GetByKeeperId($keeper->getId());
        TempValues::InitValues(["back-page" => FRONT_ROOT . "Owner/KeepersListView"]);
        require_once(VIEWS_PATH . "owner-place-reservation.php");

    }

    private function AvailablePets(): null|array {
        $pets = $this->petDAO->GetPetsByOwnerId(Session::Get("owner")->getId());
        $reservationsOfOwner = $this->reservationDAO->GetByOwnerIdAndStates(Session::Get("owner")->getId(), ReservationState::GetDisablingStates());
        if ($pets == null && $reservationsOfOwner == null) {
            return null;
        }

        foreach ($pets as $key => $pet) {
            foreach ($reservationsOfOwner as $reservation) {
                if ($reservation->getPet()->getId() == $pet->getId()) {
                    unset($pets[$key]);
                }
            }
        }
        return $pets;
    }

    public function PlaceReservation(int $petId, int $keeperId, string $since, string $until) {
        LoginMiddleware::VerifyOwner();

        $pet = $this->petDAO->GetById($petId);
        $keeper = $this->keeperDAO->GetById($keeperId);

        if (!$pet || !$keeper) {
            Session::Set("error", "Invalid data");
            header("location:" . FRONT_ROOT . "Owner/KeepersListView");
            exit;
        }
        if (!$keeper->isDateAvailable($since, $until)) {
            Session::Set("error", "The keeper is not available in that date");
            header("location:" . FRONT_ROOT . "Owner/KeepersListView");
            exit;
        }

        $reservation = new Reservation();
        $reservation->setPet($pet);
        $reservation->setKeeper($keeper);
        $reservation->setSince($since);
        $reservation->setUntil($until);
        $reservation->setState(ReservationState::PENDING);

        $reservation->setPrice($keeper->calculatePrice($since, $until));

        $this->reservationDAO->Add($reservation);

        Session::Set("success", "Reservation placed successfully");

        header("location:" . FRONT_ROOT . "Owner/KeepersListView");
    }

    public function Reservations() {
        LoginMiddleware::VerifyOwner();
        $reservations = $this->reservationDAO->GetByOwnerId(Session::Get("owner")->getId());
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "owner-reservations.php");
    }
}