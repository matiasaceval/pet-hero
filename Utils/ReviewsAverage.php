<?php namespace Utils;

class ReviewsAverage {
    public static function getReviewsAverage(array $reviews): float {
        $countReviews = count($reviews);
        if ($countReviews == 0) {
            return -1;
        }

        $total = 0;
        foreach ($reviews as $review) {
            $total += $review->getRating();
        }
        return $total / $countReviews;
    }
}
?>