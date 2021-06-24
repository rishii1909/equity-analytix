<div class="modal modal-login modal-register">
    <div class="modal__bg"></div>
    <div class="scrollbar-dynamic modal__flex">
        <div class="modal__wrapper modal-login__wrapper modal-login__wrapper--register tr-04s move">
            <div class="modal-login__header modal-login__header--register">
                <div class="modal-login__icon-wrapper modal-login__icon-wrapper--register">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo.png" alt="" class="header-nav__icon">
                </div>
            </div>
            <div class="modal-login__register-title-wrapper">
                <h2 class="modal-login__register-title headline-3"> Monthly Plan 299
                    <span class="modal-login__register-title--t">99</span>/Mo. Auto-Renews Monthly</h2>
                <p class="modal-login__register-subtitle body-2">No Commitment. Cancel Anytime.</p>
            </div>
            <form action="<?php echo get_home_url()?>/wp-login.php?action=register"
                  class="modal-login__form modal-login__form--register" name="registerform" method="POST">
                <div class="modal-login__input-group">
                    <label for="registerFirstName" class="modal-login__label modal-login__label--register body-1">First
                        Name:</label>
                    <input type="text" class="modal-login__input modal-login__input--register body-1"
                           id="registerFirstName" name="first_name">
                </div>
                <div class="modal-login__input-group">
                    <label for="registerLastName" class="modal-login__label modal-login__label--register body-1">Last
                        Name:</label>
                    <input type="text" class="modal-login__input modal-login__input--register body-1"
                           id="registerLastName" name="last_name">
                </div>
                <div class="modal-login__input-group">
                    <label for="registerUserName" class="modal-login__label
                    modal-login__label--register body-1">Username:</label>
                    <input type="text" class="modal-login__input modal-login__input--register body-1"
                           id="registerUserName" name="user_login">
                </div>
                <div class="modal-login__input-group">
                    <label for="registerEmail"
                           class="modal-login__label modal-login__label--register body-1">E-mail:</label>
                    <input type="email" class="modal-login__input modal-login__input--register body-1"
                           id="registerEmail" name="user_email">
                </div>
                <div class="modal-login__input-group">
                    <label for="registerPassword"
                           class="modal-login__label modal-login__label--register body-1">Password:</label>
                    <input type="password" class="modal-login__input modal-login__input--register body-1"
                           id="registerPassword" name="password">
                </div>

                <div class="modal-login__checkbox-group modal-login__checkbox-group--register">
                    <input type="checkbox" class="modal-login__checkbox" id="terms">
                    <label for="terms"
                           class="modal-login__label modal-login__checkbox-label modal-login__checkbox-label--register body-1">I have read and agree to Equity Analytix&nbsp;
                        <a target="_blank" href="<?php echo get_home_url(); ?>/terms"
                           class="modal-login__bold-link">Terms of Service</a>
                        &nbsp;and
                        <button type="button" class="modal-login__bold-link refund">Refund Policy</button>
                        <input type="hidden" name="redirect_to" value="<?php echo
                            get_home_url()?>/registration-success">
                        <input type="hidden" name="testcookie" value="1">
                    </label>
                </div>
                <div class="modal-login__group">
                    <input type="submit" value="Register"
                           class="modal-login__btn modal-login__btn--register modal-login__btn--disabled body-1">
                </div>
                <p class="modal-login__register-label body-1">Registration confirmation will be emailed to you

                </p>
                <p class="modal-login__prev body-1">Already have an account? <span
                        class="modal-login__link modal-close login">Log in</span></p>
                <i class="modal__close"></i>
            </form>

        </div>
    </div>
</div>