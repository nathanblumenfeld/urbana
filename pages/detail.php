<?php
$title = "Detail";
include("includes/init.php");
$saved_success_feedback = 'hidden';
$saved_failure_feedback = 'hidden';
$unsaved_success_feedback = 'hidden';
$unsaved_failure_feedback = 'hidden';
$save_listing_class = "hidden";
$unsave_listing_class = "hidden";
$saved_listings = find_saved_listings($db, $current_user['id']);
$is_saved = FALSE;
$listing_id = $_GET['id']; // untrusted

// if the listing ID is valid
if (intval($listing_id) > 0) { // make sure int > 0
    $sql_search_params = array();
    $sql_select_query = "SELECT DISTINCT listings.id, seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext, email FROM listings INNER JOIN users on users.id = listings.seller WHERE (listings.id = " . (strval($listing_id)) . ")";
    $records = exec_sql_query($db, $sql_select_query, $sql_search_params)->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="public/styles/site.css" media="all" />
</head>

<body>
    <?php include("includes/header.php"); ?>
    <main>
        <?php
        if (is_user_logged_in()) {
            // query the database for record
            if (count($records) > 0) { ?>
                <ul class="detail-container">
                    <?php foreach ($records as $record) {
                        // should be only one record in records
                    ?>
                        <li class="address-container">
                            <h3 class='detail-title'><?php echo htmlspecialchars($record['street_address']); ?></h3>
                        </li>
                        <ul class="info-container">
                            <li class='image-box'>
                                <img class="detail-image" src="public/uploads/images/<?php echo $record['id'].'.'.$record['image_ext'] ;?>" alt="<?php echo htmlspecialchars($record['street_address']); ?>" />
                            </li>
                            <li class="detail-info">
                                <h3 class='detail-item'>Beds: <?php echo htmlspecialchars($record['bed']); ?></h3>
                                <h3 class='detail-item'>Bath: <?php echo htmlspecialchars($record['bath']); ?></h3>
                                <h3 class='detail-item'>Sqft: <?php echo htmlspecialchars($record['sqft']); ?></h3>
                                <h3 class='detail-item'>Price: $<?php echo htmlspecialchars($record['price']); ?></h3>
                                <h3 class='detail-item'>Urbana Value Score: <?php echo htmlspecialchars($record['value_score']); ?></h3>
                                <h3 class='detail-item'>Urbana Value Rating: <?php echo htmlspecialchars($record['value_rating']); ?></h3>
                                <p class='detail-item'>Description <br /><?php echo htmlspecialchars($record['descript']); ?></p>
                            </li>
                        </ul>

                        <?php // if any one of user's saved listings is the listing displayed, set $is_saved to True
                        foreach ($saved_listings as $saved_listing) {
                            if ($saved_listing == $record['id']) {
                                $is_saved = TRUE;
                            }
                        }

                        // if the current listing is not saved by the user
                        if (!$is_saved) {
                            // give option to save listing
                            $save_listing_class = "show_form";
                            $unsave_listing_class = "hidden";
                            // if user opts to save listing
                            if (isset($_POST['save_listing'])) {
                                // insert new tag into db
                                $db->beginTransaction();
                                $current_user_id = $current_user['id'];
                                $listing_id = $record['id'];
                                $save_results = exec_sql_query(
                                    $db,
                                    "INSERT INTO saves (listing_id, saved_by) VALUES ($listing_id, $current_user_id)",
                                    array()
                                );
                                $db->commit();
                                // if the insert into saved is successful
                                if ($save_results) { // inform that save successful
                                    $saved_success_feedback = 'saved_feedback';
                                    $saved_failure_feedback = 'hidden';
                                    // inform that save successful
                                } else { // inform that insert failed
                                    $saved_failure_feedback = 'saved_feedback';
                                    $saved_success_feedback = 'hidden';
                                }
                                $records = exec_sql_query($db, $sql_select_query, $sql_search_params)->fetchAll();
                            }
                        } else { // listing already saved
                            // listing already saved, change button
                            $saved_listing_class = "hidden";
                            $unsave_listing_class = "show_form";
                            if (isset($_POST['un_save_listing'])) {
                                // if un-save button pressed
                                $current_user_id = $current_user['id'];
                                $listing_id = $record['id'];
                                $db->beginTransaction();
                                $un_save_results = exec_sql_query(
                                    $db,
                                    "DELETE FROM saves WHERE ((listing_id = $listing_id) AND (saved_by = $current_user_id))",
                                    array()
                                );
                                $db->commit();
                                // if the insert into saved is successful, show feedback
                                if ($un_save_results) {
                                    $unsaved_success_feedback = 'saved_feedback';
                                    $unsaved_failure_feedback = 'hidden';
                                    // inform that save successful
                                } else {
                                    $unsaved_success_feedback = 'hidden';
                                    $unsaved_failure_feedback = 'saved_feedback';
                                    // inform that insert failed
                                }
                                $records = exec_sql_query($db, $sql_select_query, $sql_search_params)->fetchAll();
                            } // unsaved button not clicked

                        } ?>
                        <li class='options-container'>
                            <a href='/home'><button class='detail-btn'>Back to Listings</button></a>
                            <a href='mailto:<?php echo htmlspecialchars($record['email']); ?>'><button class='detail-btn'>Contact Manager</button></a>
                            <form class="<?php echo $save_listing_class; ?>" action="/detail?id=<?php echo htmlspecialchars($record['id']); ?>" method="post">
                                <input class='detail-btn' type="submit" name="save_listing" value="Save Listing" />
                            </form>
                            <form class="<?php echo $unsave_listing_class; ?>" action="/detail?id=<?php echo htmlspecialchars($record['id']); ?>" method="post">
                                <input class='detail-btn' type="submit" name="un_save_listing" value="Un-Save Listing" />
                            </form>
                            <?php
                            // if the user is an admin or is the manager of the current listing
                            if ($is_admin || $current_user['id'] == $record['seller']) {
                                // give option to edit listing
                            ?>
                                <a href='/edit-listing?id=<?php echo htmlspecialchars($record['id']); ?>'><button class='detail-btn'>Edit Listing</button></a>
                            <?php } ?>
                            <p class="<?php echo $saved_success_feedback; ?>">Listing Saved. Please Refresh Page</p>
                            <p class="<?php echo $unsaved_success_feedback; ?>">Listing Removed From Saved. Please Refresh Page</p>
                            <p class="<?php echo $saved_failure_feedback; ?>">Failed to Save Listing</p>
                            <p class="<?php echo $unsaved_failure_feedback; ?>">Failed to Save Listing</p>

                        </li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p>Listing Not Found
                <p>
                <?php }
        } else { ?>
                <p>Please Log-In to View Listings
                <p>
                <?php } ?>
    </main>
    <?php include("includes/footer.php"); ?>
</body>

</html>
