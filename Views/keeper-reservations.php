<?php

use Models\ReservationState;

require_once(VIEWS_PATH . "back-nav.php");

?>

<script>document.title = "Reservations / Pet Hero" </script>
<div class="container overflow-hidden">

    <div class="centered-wrapper">
        <div id="filter-row" class="row justify-content-center unselectable" style="flex-direction: column">
            <div class="dropdown unselectable">
                <button class="btn btn-primary dropdown-toggle unselectable" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Filter</button>
                <div class="dropdown-menu allow-focus unselectable" aria-labelledby="filter-btn">
                    <?php foreach (ReservationState::GetStates() as $state) { ?>
                        <a class="dropdown-item unselectable" id="filter_<?php echo ReservationState::StateAsId($state) ?>"><?php echo $state ?></a>
                    <?php } ?>
                </div>
            </div>
            <script>
                let selectedFilters = getSelectedIds();

                $(document).ready(function() {
                    showSeletedIds();
                })

                $('.allow-focus').on('click', function(e) {
                    const target = $(e.target);
                    target.hasClass('selected') ? target.removeClass('selected') : target.addClass('selected');
                    showSeletedIds();
                    e.stopPropagation();
                });

                function showSeletedIds() {
                    selectedFilters = getSelectedIds();
                    const allStates = getAllStates();
                    let shown = 0;
                    allStates.forEach(state => {
                        const target = $(`.${state}`);
                        if (selectedFilters.includes(state) || selectedFilters.length == 0) {
                            shown += target.length;
                            target.show();
                        } else {
                            target.hide();
                        }
                    });


                    if (shown == 0) {

                        const total = <?php echo count($reservations) ?>;
                        if (total != 0) {
                            $('#empty-title').html('no reservations match your filter!');
                            $('#empty-message-a').attr('href', '');
                            $('#empty-message-a-btn').text('Clear filters');
                            $('#empty-message-a-btn').click(function() {
                                $('.dropdown').show();
                                $('.dropdown-item').removeClass('selected');
                                showSeletedIds();
                            });
                        } else {
                            $('#empty-message-a').attr('href', '<?php echo FRONT_ROOT ?>');
                            $('#empty-message-a-btn').text('Go back');
                            $('.dropdown').hide();
                        }

                        $('#empty-message').show();
                    } else {
                        $('.dropdown').show();
                        $('#empty-message').hide();
                    }
                }

                function getSelectedIds() {
                    let selectedFilters = [];
                    $('.dropdown-item.selected').each(function() {
                        selectedFilters.push($(this).attr('id').split('_')[1]);
                    });
                    return selectedFilters;
                }

                function getAllStates() {
                    let states = <?php echo json_encode(ReservationState::GetStates()) ?>;
                    return states.map(state => state.toLowerCase().replace(' ', '_'));
                }
            </script>
        </div>
    </div>

    <div class="centered-wrapper" id="empty-message">
        <div class="vertical-center">
            <div class="row justify-content-center">
                <div class="col-md-auto">
                    <h2 id="empty-title">your booking history is empty!</h2>
                </div>
            </div>
            <div class="row mt-1 justify-content-center">
                <div class="col-md-auto">
                    <h3>go check on your pending reservations to see if you have some!</h3>
                </div>
            </div>
            <div class="row mt-1 justify-content-center">
                <div class="col-md-auto">
                    <h4><span style="font-size: 20px">TIP: </span> try adding more days to your stay to be more attractive</h4>
                </div>
            </div>
            <div class="row mt-5 justify-content-center">
                <div class="col-md-auto">
                    <a id="empty-message-a" href="<?php echo FRONT_ROOT ?>">
                        <button id="empty-message-a-btn" class="btn btn-primary">Go back</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php foreach ($reservations as $reservation) {
        $pet = $reservation->getPet();
        $owner = $pet->getOwner();
    ?>
        <div class="row mt-4 justify-content-center <?php echo ReservationState::StateAsId($reservation->getState()) ?>">
            <div class="kpr-card-box">
                <div class="row justify-content-center">
                    <div class="col-md-auto">
                        <div class="input-underline">
                            <p><span style="font-weight:bold; font-size: 24px"><?php echo $reservation->getState() ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 justify-content-center" style="padding: 0px 28px 0px 28px; width: fit-content; min-width: 44em">
                    <!-- Photo -->
                    <div class="col-md-auto">
                        <div class="row justify-content-center" style="margin-top: 4px; padding-right: 14px; padding-bottom: 14px;">
                            <img id="pet-image" class="cover" src="<?php echo FRONT_ROOT . UPLOADS_PATH . $pet->getImage() ?>" width="200px" height="200px">
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="col-md-auto">
                        <div class="row" style="width: 12em">
                            <div class="col-md-auto wrap-text wrap-text-max-width">
                                <p>Name</p>
                                <p title="<?php echo ucwords($pet->getName()) ?>"><span class="pet-data"><?php echo $pet->getName() ?></span></p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-auto wrap-text wrap-text-max-width">
                                <p>Species</p>
                                <p title="<?php echo ucwords($pet->getSpecies()) ?>"><span class="pet-data"><?php echo $pet->getSpecies() ?></span></p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-auto wrap-text wrap-text-max-width">
                                <p>Animal</p>
                                <p title="<?php echo ucwords($pet->getSpecies()) ?>"><span class="pet-data"><?php echo $pet->getSpecies() ?></span></p>
                                <p title="<?php echo ucwords($pet->getBreed()) ?>"><span class="pet-data"><?php echo $pet->getBreed() ?></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- More info -->
                    <div class="col-md-auto wrap-text wrap-text-max-width">
                        <div class="row" style="width: 10em">
                            <div class="col-md-auto wrap-text wrap-text-max-width">
                                <p>Age</p>
                                <p title="<?php echo ucwords($pet->getAge()) ?>"><span class="pet-data"><?php echo $pet->getAge() ?></span></p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-auto wrap-text wrap-text-max-width">
                                <?php $sex = $pet->getSex() == 'F' ? "Female" : "Male"; ?>
                                <p>Sex</p>
                                <p title="<?php echo $sex ?>"><span class="pet-data"><?php echo $sex ?></span></p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-auto wrap-text wrap-text-max-width">
                                <p>Vaccines</p>
                                <a href="<?php echo $pet->getVaccine() ?>">
                                    <p><span class="pet-data">Click to see</span></p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Book data -->
                    <div class="col-md-auto">
                        <div class="row justify-content-center">
                            <h2>Since/Until</h2>
                        </div>
                        <div class="row justify-content-center">
                            <!-- Since -->
                            <p><?php echo $reservation->getSince() ?></p>
                        </div>
                        <div class="row justify-content-center">
                            <!-- Until -->
                            <div class="input-underline">
                                <p><?php echo $reservation->getUntil() ?></p>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <!-- Price -->
                            <p>$<?php echo $reservation->getPrice() ?></p>
                        </div>
                    </div>
                </div>
                <div class="row mt-2 justify-content-between" style="padding-left: 16px;">
                    <div class="col-md-auto">
                        <?php $owner = $reservation->getPet()->getOwner(); ?>
                        <div class="row mt-3">
                            <div class="col-md-auto wrap-text" style="max-width: 20em">
                                <p>Owner: <span class="pet-data"><?php echo $owner->getFullname(); ?></span></p>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-md-auto wrap-text" style="max-width: 50em">
                                <a href="mailto:<?php echo $owner->getEmail(); ?>">
                                    <p>Email: <span class="pet-data"><?php echo $owner->getEmail() ?></span></p>
                                </a>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-md-auto wrap-text" style="max-width: 20em">
                                <p>Phone: <span class="pet-data"><?php echo $owner->getPhone() ?></span></p>
                            </div>
                        </div>
                    </div>
                    <?php $state = $reservation->getState();
                    if ($state === ReservationState::PENDING) { ?>
                        <div class="col-md-auto align-self-end">
                            <div class="row justify-content-center">
                                <div class="col-md-auto">
                                    <a href="<?php echo FRONT_ROOT ?>Keeper/ConfirmReservation?id=<?php echo $reservation->getId() ?>">
                                        <button class="btn btn-secondary" style="font-size: 22px; min-width: 150px">Accept</button>
                                    </a>
                                </div>
                            </div>
                            <div class="row mt-2 justify-content-center">
                                <div class="col-md-auto">
                                    <a href="<?php echo FRONT_ROOT ?>Keeper/RejectReservation?id=<?php echo $reservation->getId() ?>">
                                        <button class=" btn btn-secondary" style="font-size: 22px; min-width: 150px">Reject</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php } else if ($state === ReservationState::PAID) { ?>
                        <div class="col-md-auto align-self-end">
                            <div class="row justify-content-center">
                                <div class="col-md-auto">
                                    <a href="<?php echo FRONT_ROOT ?>Keeper/VerifyPayment?id=<?php echo $reservation->getId() ?>">
                                        <button class=" btn btn-secondary" style="font-size: 22px; min-width: 150px">Verify Payment</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>