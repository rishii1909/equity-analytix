<div class="modal modal-login modal-sign-in">
    <div class="modal__bg"></div>
    <div class="scrollbar-dynamic modal__flex">
        <div class="modal__wrapper modal-login__wrapper tr-04s move">
            <div class="modal-login__header">
                <div class="modal-login__icon-wrapper">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo.png" alt="" class="header-nav__icon">
                </div>
                <h2 class="modal-login__title body-1">Log in</h2>
            </div>

            <form action="" class="modal-login__form" id="modalLoginForm">
                <div class="modal-login__row">
                    <div class="modal-login__col modal-login__col--input">
                        <div class="modal-login__input-group">
                            <label for="logInEmail" class="modal-login__label body-1">E-mail:</label>
                            <input type="email" name="user_login" class="modal-login__input body-1" id="logInEmail">
                        </div>
                        <div class="modal-login__input-group">
                            <label for="logInPass" class="modal-login__label body-1">Password:</label>
                            <input type="password" name="user_password" class="modal-login__input body-1" id="logInPass">
                        </div>
                        <a href="#" class="modal-login__link body-2">Forgot your password?</a>
                        <div class="modal-login__checkbox-group modal-login__checkbox-group--remember">
                            <input type="checkbox" class="modal-login__checkbox" id="remember" name="remember" value="forever">
                            <label for="remember" class="modal-login__label modal-login__checkbox-label body-1">Remember Me</label>
                        </div>
                    </div>
                    <div class="modal-login__col modal-login__col--terms">
                        <p class="modal-login__terms body-2">
                            By clicking on the “I AGREE” button below, you agree to not make any investment or trading
                            decisions based upon any data, analysis, software, materials or opinions distributed via
                            this
                            website or our messenger. You agree to do your own research and due diligence before making
                            any
                            investment or trading decision with respect to the data and securities mentioned herein. You
                            accept that Equity Analytix, its owner as well as our employees offer no warranty, liability
                            or
                            guarantee for the suitability, completeness, reliability, current relevance, availability,
                            timeliness, accuracy, or omissions of any and all data distributed via this website or our
                            messenger. You agree to not take any legal action against Equity Analytix, its owner or any
                            of
                            our employees for any direct or indirect loss or damage or lost profit, which you may incur.
                        </p>
                    </div>

                </div>
                <div class="modal-login__row">
                    <div class="modal-login__col modal-login__col--submit mo-2">
                        <span class="modal-login__btn modal-login__btn--outline body-1 modal-close register">Sign up</span>
                        <input type="submit" value="Log in" class="modal-login__btn--disabled modal-login__btnbody-1">
                    </div>
                    <div class="modal-login__col modal-login__col--center mo-1">
                        <div class="modal-login__checkbox-group">
                            <input type="checkbox" class="modal-login__checkbox" id="agree" name="agree">
                            <label for="agree" class="modal-login__label modal-login__checkbox-label body-1">I AGREE</label>
                        </div>
                    </div>

                </div>
            </form>
            <i class="modal__close"></i>
        </div>
    </div>
</div>