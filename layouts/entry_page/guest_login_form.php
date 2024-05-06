<form class="guest_login_form form_element d-none" id="guest_login_form">
    <div class="d-none">
        <input type="hidden" name="add" value="guest_user" />

        <?php if (isset($_GET['redirect'])) {
            ?>
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_GET['redirect']) ?>" />
            <?php
        } ?>

    </div>
    <div class="field">
        <label><?php echo Registry::load('strings')->nickname ?></label>
        <input type="text" name="nickname" />
    </div>

    <div class="field checkbox">
        <label>
            <input type="checkbox" name="terms_agreement" value="agreed">
            <span class="checkmark"></span>
            <span class="text"><?php echo Registry::load('strings')->signup_agreement ?> 
            <span class="load_page" page_id=2>[<?php echo Registry::load('strings')->read_terms ?>]</span>
            </span>
        </label>
    </div>

    <div class="captcha_validation">
        <?php include 'layouts/entry_page/captcha_validation.php'; ?>
    </div>
</form>