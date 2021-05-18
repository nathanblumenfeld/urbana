<?php
$title = "Home";
include("includes/init.php");
$base_query = "SELECT DISTINCT listings.id, seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext, email FROM listings INNER JOIN users ON users.id = listings.seller INNER JOIN saves on saves.saved_by = users.id";

// bedrooms
$has_filter_bed = FALSE;
$bed_input = $_GET['bedrooms']; // untrusted

if (!empty($bed_input)) {
  $has_filter_bed = TRUE;
  if ($bed_input == "1") {
    $filter_bed_expr = "(listings.bed='1')";
  }
  if ($bed_input == "2") {
    $filter_bed_expr = "(listings.bed='2')";
  }
  if ($bed_input == "3") {
    $filter_bed_expr = "(listings.bed='3')";
  }
  if ($bed_input == "4") {
    $filter_bed_expr = "(listings.bed='4')";
  }
  if ($bed_input == "5") {
    $filter_bed_expr = "(listings.bed='5')";
  }
  if ($bed_input == "all") {
    $filter_bed_expr = "(listings.bed IN ('1','2','3','4','5'))";
  }
}

// bathrooms
$has_filter_bath = False;
$bath_input = $_GET['bathrooms']; // untrusted

if (!empty($bath_input)) {
  $has_filter_bath = True;
  if ($bath_input == "1") {
    $filter_bath_expr = "(listings.bath='1')";
  }
  if ($bath_input == "2") {
    $filter_bath_expr = "(listings.bath='2')";
  }
  if ($bath_input == "3") {
    $filter_bath_expr = "(listings.bath='3')";
  }
  if ($bath_input == "4") {
    $filter_bath_expr = "(listings.bath='4')";
  }
  if ($bath_input == "5") {
    $filter_bath_expr = "(listings.bath='5')";
  }
  if ($bath_input == "all") {
    $filter_bath_expr = "(listings.bath IN ('1','2','3','4','5'))";
  }
}

// value rating
$has_filter_rating = FALSE;
$rating_input = $_GET['value_rating']; // untrusted

if (!empty($rating_input)) {
  $has_filter_rating = TRUE;
  if ($rating_input == "poor") {
    $filter_rating_expr = "(listings.value_rating='poor')";
  }
  if ($rating_input == "fair") {
    $filter_rating_expr = "(listings.value_rating='fair')";
  }
  if ($rating_input == "good") {
    $filter_rating_expr = "(listings.value_rating='good')";
  }
  if ($rating_input == "great") {
    $filter_rating_expr = "(listings.value_rating='great')";
  }
  if ($rating_input == "all") {
    $filter_rating_expr = "(listings.value_rating IN ('poor', 'fair', 'good', 'great'))";
  }
}

// Saved
$has_filter_saved = FALSE;
$saved_input = $_GET['saved']; // untrusted

if (!empty($saved_input)) {
  if ($saved_input == "1") {
    $has_filter_saved = TRUE;
    $filter_saved_expr = "(saves.saved_by =  " . strval($current_user['id']) . ")";
  }
}

// Query Concatenation

// has no filters
if (!$has_filter_bed && !$has_filter_bath && !$has_filter_rating && !$has_filter_saved) {
  $filter_expr = '';
}
// has only bed filter
if ($has_filter_bed && !$has_filter_bath && !$has_filter_rating && !$has_filter_saved) {
  $filter_expr = " WHERE" . $filter_bed_expr;
}
// has only bath filter
if (!$has_filter_bed && $has_filter_bath && !$has_filter_rating && !$has_filter_saved) {
  $filter_expr = " WHERE" . $filter_bath_expr;
}
// has only rating filter
if (!$has_filter_bed && !$has_filter_bath && $has_filter_rating && !$has_filter_saved) {
  $filter_expr = " WHERE" . $filter_rating_expr;
}
// has only saved filter
if (!$has_filter_bed && !$has_filter_bath && !$has_filter_rating && $has_filter_saved) {
  $filter_expr = " WHERE" . $filter_saved_expr;
}
// has bed, bath filters
if ($has_filter_bed && $has_filter_bath && !$has_filter_rating && !$has_filter_saved) {
  $filter_expr = " WHERE" . $filter_bed_expr . " AND " . $filter_bath_expr;
}
// has bed, rating filters
if ($has_filter_bed && !$has_filter_bath && $has_filter_rating && !$has_filter_saved) {
  $filter_expr = " WHERE" . $filter_bed_expr . " AND " . $filter_rating_expr;
}
// has bed, saved filters
if ($has_filter_bed && !$has_filter_bath && !$has_filter_rating && $has_filter_saved) {
  $filter_expr = " WHERE" . $filter_bed_expr . " AND " . $filter_saved_expr;
}
// has bath, rating filters
if (!$has_filter_bed && $has_filter_bath && $has_filter_rating && !$has_filter_saved) {
  $filter_expr = " WHERE" . $filter_bath_expr . " AND " . $filter_rating_expr;
}
// has bath, saved filters
if (!$has_filter_bed && $has_filter_bath && !$has_filter_rating && $has_filter_saved) {
  $filter_expr = " WHERE" . $filter_bath_expr . " AND " . $filter_saved_expr;
}
// has rating, saved filters
if (!$has_filter_bed && !$has_filter_bath && $has_filter_rating && $has_filter_saved) {
  $filter_expr = " WHERE" . $filter_rating_expr . " AND " . $filter_saved_expr;
}
// has bed, bath, and rating filters
if ($has_filter_bed && $has_filter_bath && $has_filter_rating && !$has_filter_saved) {
  $filter_expr = " WHERE" . $filter_bed_expr . " AND " . $filter_bath_expr . " AND " . $filter_rating_expr;
}
// has bed, bath, and save filters
if ($has_filter_bed && $has_filter_bath && !$has_filter_rating && $has_filter_saved) {
  $filter_expr = " WHERE" . $filter_bed_expr . " AND " . $filter_bath_expr . " AND " . $filter_saved_expr;
}
// has bath, rating, and save filters
if (!$has_filter_bed && $has_filter_bath && $has_filter_rating && $has_filter_saved) {
  $filter_expr = " WHERE" . $filter_bath_expr . " AND " . $filter_rating_expr . " AND " . $filter_saved_expr;
}
// has all filters
if ($has_filter_bed && $has_filter_bath && $has_filter_rating && $has_filter_saved) {
  $filter_expr = " WHERE" . $filter_bed_expr . " AND " . $filter_bath_expr . " AND " . $filter_rating_expr . " AND " . $filter_saved_expr;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="public/styles/site.css" media="all" />
  <title><?php echo $title; ?></title>

  <!-- All Photos are From Unsplash, and made available through the Unsplash License -->
  <!-- See init.sql for full citations -->

</head>

<body>
  <!-- HEADER -->
  <?php include("includes/header.php"); ?>
  <main>
    <?php
    // is user logged? If so, show listings
    if (is_user_logged_in()) { ?>
      <div class="filter-bar">
        <!-- FILTER BY TAGS -->
        <form class="filter-container" name="filter-form" id="filter-form" method="get" action="/home" novalidate>
          <fieldset class="filter-item">
            <!-- BEDROOMS -->
            <label for='bedrooms'>Bedrooms:</label>
            <select name='bedrooms'>
              <option <?php if ($bed_input == "1") echo 'selected="selected"'; ?> value='1'>1</option>
              <option <?php if ($bed_input == "2") echo 'selected="selected"'; ?> value='2'>2</option>
              <option <?php if ($bed_input == "3") echo 'selected="selected"'; ?> value='3'>3</option>
              <option <?php if ($bed_input == "4") echo 'selected="selected"'; ?> value='4'>4</option>
              <option <?php if ($bed_input == "5") echo 'selected="selected"'; ?> value='5'>5+</option>
              <option <?php if (($bed_input == "all") || (!$has_filter_bed)) echo "selected='selected'"; ?> value='all'>All</option>
            </select>
          </fieldset>
          <!-- BATHROOMS -->
          <fieldset class="filter-item">
            <label for='bathrooms'>Bathrooms:</label>
            <select name='bathrooms'>
              <option <?php if ($bath_input == "1") echo 'selected="selected"'; ?> value='1'>1</option>
              <option <?php if ($bath_input == "2") echo 'selected="selected"'; ?>value='2'>2</option>
              <option <?php if ($bath_input == "3") echo 'selected="selected"'; ?>value='3'>3</option>
              <option <?php if ($bath_input == "4") echo 'selected="selected"'; ?>value='4'>4</option>
              <option <?php if ($bath_input == "5") echo 'selected="selected"'; ?>value='5'>5+</option>
              <option <?php if (($bath_input == "all") || (!$has_filter_bath)) echo "selected='selected'"; ?>value='all'>All</option>
            </select>
          </fieldset>
          <!-- Value Rating -->
          <fieldset class="filter-item">
            <label for="value_rating">Value:</label>
            <select name="value_rating">
              <option <?php if ($rating_input == "poor") echo 'selected="selected"'; ?>value='poor'>Poor</option>
              <option <?php if ($rating_input == "fair") echo 'selected="selected"'; ?>value='fair'>Fair</option>
              <option <?php if ($rating_input == "good") echo 'selected="selected"'; ?>value='good'>Good</option>
              <option <?php if ($rating_input == "great") echo 'selected="selected"'; ?>value='great'>Great</option>
              <option <?php if (($rating_input == "all") || (!$has_filter_rating)) echo 'selected="selected"'; ?>value='all'>All</option>
            </select>
          </fieldset>
          <!-- SAVED -->
          <fieldset class="filter-item">
            <label for="saved">Saved:</label>
            <input <?php if ($has_filter_saved) echo 'checked="checked"'; ?> type="checkbox" name="saved" value='1'>
          </fieldset>
          <!-- SUBMIT/RESET -->
          <fieldset class="submit-container">
            <input type="submit" class="filter-button" value="Submit">
            <a class="filter-button" href="/home">Reset</a>
          </fieldset>
        </form>
      </div>

      <!-- LISTINGS -->
      <h2 class="main-title">Listings in Ithaca, NY</h2>
      <?php
      // form final sql query string
      $sql_select_query = $base_query . $filter_expr;
      $sql_search_params = array();

      // query the database for records
      $records = exec_sql_query(
        $db,
        $sql_select_query,
        $sql_search_params
      )->fetchAll(); ?>

      <section class="listings">
        <?php if (count($records) > 0) { ?>
          <ul class="listing-container">
            <?php foreach ($records as $record) { ?>
              <li class="listing-card">
                <a href='/detail?id=<?php echo $record['id']; ?>'>
                  <img class="listing-image" src="public/uploads/images/<?php echo $record['id'] . '.' . $record['image_ext']; ?>" alt="<?php echo htmlspecialchars($record['street_address']); ?>" />
                  <h3 class='listing-info'><?php echo htmlspecialchars($record['street_address']) ?> | $<?php echo htmlspecialchars($record['price']); ?> | <?php echo htmlspecialchars($record['value_rating']); ?></h3>
                </a>
              </li>
            <?php } ?>
          </ul>
        <?php } else { ?>
          <p>No Listings Found Matching Conditions.
          <p>
          <?php } ?>
      </section>
    <?php } else { ?>
      <h2 class="main-title">Please Log-In to View Listings</h2>
    <?php }; ?>
  </main>
  <!-- FOOTER -->
  <?php include("includes/footer.php") ?>
</body>

</html>
