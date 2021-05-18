<?php
$title = "Edit Listing";
include("includes/init.php");
define("MAX_FILE_SIZE", 10000000); // 10MB
$show_form = 'hidden';
$upload_failure_feedback = 'hidden';
$upload_success_feedback = 'hidden';
$delete_failure_feedback = 'hidden';
$delete_success_feedback = 'hidden';
$current_user_id = $current_user['id'];
$feedback = '';
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
        <?php if (is_user_logged_in()) { ?>
            <h2 class="main-title">Edit Listing</h2>
            <?php $listing_id = intval(trim(($_GET['id']))); // untrusted
            if (($listing_id) > 0) { // make sure int > 0
                $sql_search_params = array();
                $sql_select_query = "SELECT listings.id, seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext, email FROM listings INNER JOIN users on listings.seller = users.id WHERE (listings.id = " . strval($listing_id) . ")";
                $initial_records = exec_sql_query($db, $sql_select_query, $sql_search_params)->fetchAll();
                // if the record exists, display it
                if (count($initial_records) > 0) { ?>
                    <?php foreach ($initial_records as $initial_record) {
                        if (($current_user['id'] == $initial_record['seller']) || ($is_admin)) { ?>
                            <div class="show_form">
                                <form class='edit-container' action="/edit-listing?id=<?php echo htmlspecialchars($initial_record['id']); ?>" method="post" enctype="multipart/form-data" novalidate>
                                    <fieldset class="edit-address-container">
                                        <label class="edit-address-label" for="address">Address: </label>
                                        <input name="address" class='edit-text-input' type='text' value="<?php echo htmlspecialchars($initial_record['street_address']); ?>">
                                    </fieldset>
                                    <div class="edit-info-container">
                                        <fieldset class="edit-image-box">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
                                            <label class='edit-image-label' for="image-file">Image: </label>
                                            <input class="edit-text-input" type="file" name="image-file" accept=".jpg, .jpeg, .png" />
                                        </fieldset>
                                        <fieldset class="edit-details-box">
                                            <label for="bed">Bedrooms: </label>
                                            <select class="detail-btn" name="bed" required>
                                                <option <?php if ($initial_record['bed'] == '1') echo 'selected="selected"'; ?> value='1'>1</option>
                                                <option <?php if ($initial_record['bed'] == '2') echo 'selected="selected"'; ?> value='2'>2</option>
                                                <option <?php if ($initial_record['bed'] == '3') echo 'selected="selected"'; ?> value='3'>3</option>
                                                <option <?php if ($initial_record['bed'] == '4') echo 'selected="selected"'; ?> value='4'>4</option>
                                                <option <?php if ($initial_record['bed'] == '5') echo 'selected="selected"'; ?> value='5'>5+</option>
                                            </select>
                                            <label for="bath">Bathrooms: </label>
                                            <select class="detail-btn" name="bath" required>
                                                <option <?php if ($initial_record['bath'] == '1') echo 'selected="selected"'; ?> value='1'>1</option>
                                                <option <?php if ($initial_record['bath'] == '2') echo 'selected="selected"'; ?> value='2'>2</option>
                                                <option <?php if ($initial_record['bath'] == '3') echo 'selected="selected"'; ?> value='3'>3</option>
                                                <option <?php if ($initial_record['bath'] == '4') echo 'selected="selected"'; ?> value='4'>4</option>
                                                <option <?php if ($initial_record['bath'] == '5') echo 'selected="selected"'; ?> value='5'>5+</option>
                                            </select>
                                            <label for="price">Price: </label>
                                            <input name="price" class='edit-text-input' type='number' value=<?php echo htmlspecialchars($initial_record['price']); ?> required>
                                            <label for="sqft">Sqft: </label>
                                            <input name="sqft" class='edit-text-input' type='number' value=<?php echo htmlspecialchars($initial_record['sqft']); ?> required>
                                            <label for="descript">Description: </label>
                                            <textarea id="description-input" name="descript" class='edit-text-input' type='textarea' required><?php echo htmlspecialchars($initial_record['descript']); ?></textarea>
                                            <label for="contact">Contact Email: </label>
                                            <input name="contact" class='edit-text-input' type='text' value=<?php echo htmlspecialchars($initial_record['email']); ?> required>
                                            <?php if ($is_admin) { ?>
                                                <label for="value_score">Value Score: </label>
                                                <input name="value_score" class='edit-text-input' type='number' value=<?php echo htmlspecialchars($initial_record['value_score']); ?>>
                                                <label for="value_rating">Value Rating: </label>
                                                <select class="detail-btn" name="value_rating" required>
                                                    <option <?php if ($initial_record['value_rating'] == 'poor') echo 'selected="selected"'; ?> value='poor'>Poor</option>
                                                    <option <?php if ($initial_record['value_rating'] == 'fair') echo 'selected="selected"'; ?> value='fair'>Fair</option>
                                                    <option <?php if ($initial_record['value_rating'] == 'good') echo 'selected="selected"'; ?> value='good'>Good</option>
                                                    <option <?php if ($initial_record['value_rating'] == 'great') echo 'selected="selected"'; ?> value='great'>Great</option>
                                                </select>
                                            <?php } ?>
                                        </fieldset>
                                    </div>
                                    <fieldset class="options-container">
                                        <input name="edit-submit" class='detail-btn' type='submit' value="Submit Changes">
                                        <input name="delete-submit" class='detail-btn' type='submit' value="Delete Listing">

                                    </fieldset>
                                </form>
                            </div>
                            <?php }

                        if (isset($_POST['delete-submit'])) {
                            if ((($is_manager) && ($initial_record['seller'] == $current_user_id)) || ($is_admin)) {
                                $db->beginTransaction();
                                $delete_result_1 = exec_sql_query(
                                    $db,
                                    "DELETE FROM listings WHERE (id = :listing_id);",
                                    array(
                                        'listing_id' => $initial_record['id']
                                    )
                                );
                                $db->commit();
                                $db->beginTransaction();
                                $delete_result_2 = exec_sql_query(
                                    $db,
                                    "DELETE FROM saves WHERE (listing_id = :listing_id);",
                                    array(
                                        'listing_id' => $initial_record['id']
                                    )
                                );
                                $db->commit();
                                if ($delete_result_1 && $delete_result_2) {
                                    $delete_failure_feedback = 'hidden';
                                    $delete_success_feedback = 'upload_feedback';
                                } else {
                                    $delete_success_feedback = 'hidden';
                                    $delete_failure_feedback = 'upload_feedback';
                                }
                            }
                            // get updated edit page
                            $records = exec_sql_query(
                                $db,
                                "SELECT DISTINCT * FROM listings INNER JOIN users on users.id = listings.seller WHERE listings.id = :id;",
                                array(':id' => $listings_id)
                            )->fetchAll();
                        }
                        // edit listing form
                        if (isset($_POST["edit-submit"])) {
                            $address_input = trim($_POST['address']); // untrusted
                            $price_input = $_POST['price']; // untrusted
                            $sqft_input = $_POST['sqft']; // untrusted
                            $descript_input = trim($_POST['descript']); // untrusted
                            $contact_input = trim($_POST['contact']); // untrusted
                            $bed_input = $_POST['bed']; // untrusted
                            $bath_input = $_POST['bath']; // untrusted
                            $image_input = $_FILES['image-file']; // untrusted
                            $image_name = basename($image_input['name']); // untrusted
                            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION)); // untrusted
                            $image_size = $image_input['size'];
                            $seller_input = $initial_record['seller'];

                            // only admins can add score/value rating
                            if ($is_admin) {
                                $score_input = $_POST['value_score']; // untrusted
                                $rating_input = trim($_POST['value_rating']); // untrusted
                            }

                            // form validation
                            $form_valid = True;
                            if (empty($address_input)) {
                                $form_valid = False;
                                $feedback = $feedback . 'Address Invalid, ';
                            }
                            if (empty($descript_input)) {
                                $form_valid = False;
                                $feedback = $feedback . 'Description Invalid, ';
                            }
                            if (empty($sqft_input)) {
                                $form_valid = False;
                                $feedback = $feedback . 'Sqft Invalid, ';
                            }
                            if (empty($price_input)) {
                                $form_valid = False;
                                $feedback = $feedback . 'Price Invalid, ';
                            }
                            if (empty($contact_input)) {
                                $form_valid = False;
                                $feedback = $feedback . 'Email Invalid, ';
                            }

                            if (empty($seller_input)) {
                                $form_valid = False;
                                $feedback = $feedback . 'Seller ID Invalid, ';
                            }

                            if (!(empty($image_name))) {
                                if (!(($image_input['error'] == UPLOAD_ERR_OK) && ($image_size < 10000000))) {
                                    $form_valid = False;
                                    $feedback = $feedback . 'Image Invalid, Please Select Another File Under 10MB. ';
                                }
                                if (!in_array($image_ext, array('png', 'jpg', 'jpeg'))) {
                                    $form_valid = False;
                                    $feedback = $feedback . 'Image Extension Invalid, Valid Types: .jpg, .jpeg, .png ';
                                }
                            } else {
                                $image_ext = $initial_record['image_ext'];
                            }


                            // validate bed input
                            if (!in_array($bed_input, array('1', '2', '3', '4', '5'))) {
                                $form_valid = False;
                                $feedback = $feedback . 'Bedrooms Invalid, ';
                            }

                            // validate bath input
                            if (!in_array($bath_input, array('1', '2', '3', '4', '5'))) {
                                $form_valid = False;
                                $feedback = $feedback . 'Bathrooms Invalid, ';
                            }

                            if ($form_valid) {
                                if ((!($is_manager)) && ($initial_record['seller'] == $current_user['id'])) {
                                    $db->beginTransaction();
                                    $edit_result = exec_sql_query(
                                        $db,
                                        "UPDATE listings SET seller = :seller, street_address = :street_address, price = :price, bed = :bed, bath = :bath, sqft = :sqft, descript = :descript, image_ext = :image_ext WHERE (listings.id = :id);",
                                        array(
                                            'id' => $listing_id,
                                            'seller' => $seller_input,
                                            'street_address' => $address_input,
                                            'price' => $price_input,
                                            'bed' => $bed_input,
                                            'bath' => $bath_input,
                                            'sqft' => $sqft_input,
                                            'descript' => $descript_input,
                                            'image_ext' => $image_ext
                                        )
                                    );

                                    $destination = 'public/uploads/images/' . strval($listing_id) . '.' . $image_ext;
                                    move_uploaded_file($image_input['tmp_name'], $destination);
                                    $db->commit();

                                    if ($edit_result) {
                                        $upload_failure_feedback = 'hidden';
                                        $upload_success_feedback = 'upload_feedback';
                                    } else {
                                        $upload_success_feedback = 'hidden';
                                        $upload_failure_feedback = 'upload_feedback';
                                    }
                                    // get updated edit page
                                    $records = exec_sql_query(
                                        $db,
                                        "SELECT DISTINCT * FROM listings INNER JOIN users on users.id = listings.seller WHERE listings.id = :id;",
                                        array(':id' => $listings_id)
                                    )->fetchAll();
                                } elseif (($is_admin)) {
                                    // only admins can alter value information
                                    $db->beginTransaction();
                                    $edit_result = exec_sql_query(
                                        $db,
                                        "UPDATE listings SET seller = :seller, street_address = :street_address, price = :price, bed = :bed, bath = :bath, sqft = :sqft, descript = :descript, value_score = :value_score, value_rating = :value_rating, image_ext = :image_ext WHERE (id = :id);",
                                        array(
                                            'id' => $listing_id,
                                            'seller' => $seller_input,
                                            'street_address' => $address_input,
                                            'price' => $price_input,
                                            'bed' => $bed_input,
                                            'bath' => $bath_input,
                                            'sqft' => $sqft_input,
                                            'descript' => $descript_input,
                                            'value_score' => $score_input,
                                            'value_rating' => $rating_input,
                                            'image_ext' => $image_ext
                                        )
                                    );
                                    $db->commit();
                                    $db->beginTransaction();
                                    $destination = 'public/uploads/images/' . $listing_id . '.' . $image_ext;
                                    move_uploaded_file($image_input['tmp_name'], $destination);
                                    $db->commit();

                                    if ($edit_result) {
                                        $upload_failure_feedback = 'hidden';
                                        $upload_success_feedback = 'upload_feedback';
                                    } else {
                                        $upload_success_feedback = 'hidden';
                                        $upload_failure_feedback = 'upload_feedback';
                                    }

                                    // get updated edit page
                                    $records = exec_sql_query(
                                        $db,
                                        "SELECT DISTINCT * FROM listings INNER JOIN users on users.id = listings.seller WHERE listings.id = :id;",
                                        array(':id' => $listings_id)
                                    )->fetchAll();
                                } else { ?>
                                    <p>Must be manager of this listing or an admin to edit</p>
                    <?php }
                            } else {
                                $upload_failure_feedback = "upload_feedback";
                            }
                        }
                    } ?>
                <?php } else { ?>
                    <p>Listing not found</p>
                <?php } ?>
                <p class="<?php echo $delete_success_feedback; ?>">Listing Deleted</p>
                <p class="<?php echo $delete_failure_feedback; ?>">Unable to Delete Listing <?php echo $feedback; ?></p>
                <p class="<?php echo $upload_success_feedback; ?>">Changes Saved</p>
                <p class="<?php echo $upload_failure_feedback; ?>">Failed to Save Changes: <?php echo $feedback; ?></p>
            <?php } else { ?>
                <p>Listing ID invalid</p>
            <?php } ?>
        <?php } else { ?>
            <p>Please Log-In to View/Edit Listings</p>
        <?php } ?>
    </main>
    <?php include("includes/footer.php"); ?>
</body>

</html>
