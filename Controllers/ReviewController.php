<?php

namespace Controllers;

use DAO\SQLDAO\KeeperDAO as KeeperDAO;
use DAO\SQLDAO\ReservationDAO as ReservationDAO;
use DAO\SQLDAO\ReviewsDAO as ReviewsDAO;
use Exception;
use Models\ReservationState;
use Models\Reviews;
use Utils\LoginMiddleware;
use Utils\Session;
use Utils\TempValues;

class ReviewController
{
    private ReviewsDAO $reviewsDAO;
    private KeeperDAO $keeperDAO;
    private ReservationDAO $reservationDAO;

    public function __construct()
    {
        $this->reviewsDAO = new ReviewsDAO();
        $this->keeperDAO = new KeeperDAO();
        $this->reservationDAO = new ReservationDAO();
    }

    /**
     * @throws Exception
     */
    public function ListKeeperReviews($id): void
    {
        LoginMiddleware::VerifyOwner();
        $keeper = $this->keeperDAO->GetById($id);
        if ($keeper == null) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }
        $reviews = $this->reviewsDAO->GetByKeeperId($id);

        // because it can came from reservations view or from keepers list view
        if (!TempValues::ValueExist("back-page")) TempValues::InitValues(["back-page" => FRONT_ROOT . "Owner/KeepersListView"]);
        require_once(VIEWS_PATH . "keeper-reviews.php");
    }

    // TODO: Display Review errors on Reservations

    /**
     * @throws Exception
     */
    public function Review(int $id): void
    {
        LoginMiddleware::VerifyOwner();
        $reservation = $this->reservationDAO->GetById($id);
        if ($reservation == null) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }

        if ($reservation->getPet()->getOwner()->getId() != Session::Get("owner")->getId()) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }

        if ($reservation->getState() != ReservationState::FINISHED) {
            Session::Set("error", "The reservation is not in a valid state");
            header("location:" . FRONT_ROOT . "Reservation/Reservations");
            exit;
        }

        $review = $this->reviewsDAO->GetByReservationId($id);
        if ($review != null) {
            TempValues::InitValues(["back-page" => FRONT_ROOT . "Reservation/Reservations"]);
            header("location:" . FRONT_ROOT . "Review/ListKeeperReviews?id=" . $reservation->getKeeper()->getId() . "#review-" . $review->getId());
            exit;
        }

        $keeper = $reservation->getKeeper();
        $reviews = $this->reviewsDAO->GetByKeeperId($keeper->getId());
        TempValues::InitValues(["back-page" => FRONT_ROOT . "Reservation/Reservations"]);
        require_once(VIEWS_PATH . "owner-review.php");
    }

    // TODO: Display Review errors on Reservations

    /**
     * @throws Exception
     */
    public function PlaceReview(string $comment, int $rating, int $reservationId): void
    {
        LoginMiddleware::VerifyOwner();
        $reservation = $this->reservationDAO->GetById($reservationId);
        if ($reservation == null) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }
        if ($reservation->getPet()->getOwner()->getId() != Session::Get("owner")->getId()) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }

        if ($reservation->getState() != ReservationState::FINISHED) {
            Session::Set("error", "The reservation is not in a valid state");
            header("location:" . FRONT_ROOT . "Reservation/Reservations");
            exit;
        }
        if ($this->reviewsDAO->GetByReservationId($reservationId) != null) {
            Session::Set("error", "You already placed a review for this reservation");
            header("location:" . FRONT_ROOT . "Reservation/Reservations");
            exit;
        }

        $review = new Reviews();
        $review->setComment($comment);
        $review->setRating($rating);
        $review->setReservation($reservation);
        $review->setDate(date("m-d-Y"));

        $this->reviewsDAO->Add($review);

        Session::Set("success", "Review placed successfully");

        header("location:" . FRONT_ROOT . "Reservation/Reservations");
    }
}