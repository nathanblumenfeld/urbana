<?php
include_once("includes/sessions.php");
include_once("includes/db.php");
?>
<div id="header-container" class="header-container">
    <!-- SITE TITLE -->
    <a id='title-link' href='/home'>
        <h1 class="header-element">Urbana</h1>
    </a>
    <!-- LOGIN -->
    <?php if (!is_user_logged_in()) {
        // if not, show login form in header
        echo_login_form('/', $session_messages);
    } else { ?>
        <!-- if yes, show account dropdown menu in header -->
        <div class="dropdown">
            <button class="drop-button">Hello, <?php echo htmlspecialchars($current_user['name']); ?></button>
            <div class="dropdown-links">
                <a href="/home?bedrooms=all&amp;bathrooms=all&amp;rating=all&amp;saved=1">Saved Listings</a>
                <a href="/add-listing">Add Listing</a>
                <a href=<?php echo logout_url(); ?>>Sign Out</a>
            </div>
        </div>
    <?php } ?>
</div>
