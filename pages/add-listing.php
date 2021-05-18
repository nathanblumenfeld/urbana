<?php
$title = "Add Listing";
include("includes/init.php");
define("MAX_FILE_SIZE", 10000000); // 10MB
$show_form = 'hidden';
$upload_failure_feedback = 'hidden';
$upload_success_feedback = 'hidden';
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
    <?php if ((is_user_logged_in()) && ($is_manager || $is_admin)) { ?>
      <h2 class="main-title">Add Listing</h2>
      <form class='edit-container' action="/add" method="post" enctype="multipart/form-data" novalidate>
        <fieldset class="edit-address-container">
          <label class="edit-address-label" for="address">Address: </label>
          <input name="address" class='edit-text-input' type='text'>
        </fieldset>
        <div class="edit-info-container">
          <fieldset class="edit-image-box">
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>">
            <label class='edit-image-label' for="image-file">Image: </label>
            <input class="edit-text-input" type="file" name="image-file" accept=".jpg, .jpeg, .png" required>
          </fieldset>
          <fieldset class="edit-details-box">
            <label for="bed">Bedrooms: </label>
            <select class="detail-btn" name="bed" required>
              <option value='1'>1</option>
              <option value='2'>2</option>
              <option value='3'>3</option>
              <option value='4'>4</option>
              <option value='5'>5+</option>
            </select>
            <label for="bath">Bathrooms: </label>
            <select class="detail-btn" name="bath" required>
              <option value='1'>1</option>
              <option value='2'>2</option>
              <option value='3'>3</option>
              <option value='4'>4</option>
              <option value='5'>5+</option>
            </select>
            <label for="price">Price: </label>
            <input name="price" class='edit-text-input' type='number' required>
            <label for="sqft">Sqft: </label>
            <input name="sqft" class='edit-text-input' type='number' required>
            <label for="descript">Description: </label>
            <textarea id="description-input" name="descript" class='edit-text-input' type='textarea' required></textarea>
            <label for="contact">Contact Email: </label>
            <input name="contact" class='edit-text-input' type='text' required>
            </select>
            <?php if ($is_admin) { ?>
              <label for="value_score">Value Score: </label>
              <input name="value_score" class='edit-text-input' type='number'>
              <label for="value_rating">Value Rating: </label>
              <select class="detail-btn" name="value_rating">
                <option value='poor'>Poor</option>
                <option value='fair'>Fair</option>
                <option value='good'>Good</option>
                <option value='great'>Great</option>
              </select>
            <?php } ?>
          </fieldset>
        </div>
        <fieldset class="options-container">
          <input name="addlisting" class='detail-btn' type='submit' value="Create Listing">
        </fieldset>
      </form>
      <?php
      // edit listing form
      if (isset($_POST["addlisting"])) {
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
        $seller_input = $current_user_id;
        // only admins can add score/value rating
        if ($is_admin) {
          $score_input = $_POST['value_score']; // untrusted
          $rating_input = trim($_POST['value_rating']); // untrusted
        }
        // form validation
        $form_valid = True;
        if (empty($address_input)) {
          $feedback = $feedback . 'Address Invalid, ';
        }

        if (empty($descript_input)) {
          $feedback = $feedback . 'Description Invalid, ';
          $form_valid = False;
        }
        if (empty($sqft_input)) {
          $feedback = $feedback . 'Sqft Invalid, ';
          $form_valid = False;
        }
        if (empty($price_input)) {
          $feedback = $feedback . 'Price Invalid, ';

          $form_valid = False;
        }
        if (empty($contact_input)) {
          $feedback = $feedback . 'Email Invalid, ';
          $form_valid = False;
        }

        if (empty($seller_input)) {
          $feedback = $feedback . 'Seller ID Invalid, ';
          $form_valid = False;
        }

        if (($image_input['error'] == UPLOAD_ERR_OK) && ($image_size < MAX_FILE_SIZE)) {
        } else {
          $form_valid = False;
          $feedback = $feedback . 'Image Invalid, ';
        }
        if (!in_array($image_ext, array('png', 'jpg', 'jpeg'))) {
          $feedback = $feedback . 'Image Extension Invalid, ';
          $form_valid = False;
        }

        // validate bed input
        if (!in_array($bed_input, array('1', '2', '3', '4', '5'))) {
          $feedback = $feedback . 'Bedrooms Invalid, ';
          $form_valid = False;
        }

        // validate bath input
        if (!in_array($bath_input, array('1', '2', '3', '4', '5'))) {
          $feedback = $feedback . 'Bathrooms Invalid, ';
          $form_valid = False;
        }

        if ($form_valid) {
          if ($is_manager) {
            $db->beginTransaction();
            $add_result = exec_sql_query(
              $db,
              "INSERT INTO listings (seller, street_address, price, bed, bath, sqft, descript, image_ext VALUES (:seller, :street_address, :price, :bed, :bath, :sqft, :descript, :image_ext);",
              array(
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

            if ($add_result) {
              $upload_failure_feedback = 'hidden';
              $upload_success_feedback = 'upload_feedback';
              $added_id = $db->lastInsertId('id');
              $destination = 'public/uploads/images/' . strval($added_id) . '.' . $image_ext;
              move_uploaded_file($image_input['tmp_name'], $destination);
            } else {
              $upload_success_feedback = 'hidden';
              $upload_failure_feedback = 'upload_feedback';
            }
            $db->commit();

          } elseif ($is_admin) {
            // only admins can alter value information
            $db->beginTransaction();
            $add_result = exec_sql_query(
              $db,
              "INSERT INTO listings (seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext) VALUES (:seller, :street_address, :price, :bed, :bath, :sqft, :descript, :value_score, :value_rating, :image_ext);",
              array(
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

            if ($add_result) {
              $upload_failure_feedback = 'hidden';
              $upload_success_feedback = 'upload_feedback';
              $added_id = $db->lastInsertId('id');
              $destination = 'public/uploads/images/' . strval($added_id). '.' . $image_ext;
              move_uploaded_file($image_input['tmp_name'], $destination);
            } else {
              $upload_failure_feedback = 'upload_feedback';
              $upload_success_feedback = 'hidden';
            }
            $db->commit();
          } else { ?>
            <p>Must be Manager or Admin to Create Listings</p>
        <?php }
        } else {
          $upload_failure_feedback = 'upload_feedback';
          $upload_success_feedback = 'hidden';
        } ?>
      <?php } ?>
      <p class="<?php echo $upload_success_feedback; ?>">Listing Created</p>
      <p class="<?php echo $upload_failure_feedback; ?>">Failed to Create Listing: <?php echo $feedback; ?></p>
    <?php } else { ?>
      <p>Please Log-In to View/Edit Listings</p>
    <?php } ?>
  </main>
  <?php include("includes/footer.php"); ?>
</body>

</html>
