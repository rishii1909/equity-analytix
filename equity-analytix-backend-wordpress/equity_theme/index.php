<?php get_header();?>

    <div class="section">
        <!--    hero block    -->
        <?php get_template_part('template-parts/hero-block/hero-block'); ?>
        <!--    Institutional - Level Analytics for the Retail Trader block    -->
        <?php get_template_part('template-parts/institutional-block/institutional-block'); ?>
        <!--    Data Mining block    -->
        <?php get_template_part('template-parts/data-mining-block/data-mining-block'); ?>
        <!--    Scanners - Indicators - Analytix block    -->
        <?php get_template_part('template-parts/scanners-indicators-analytix-block/scanners-indicators-analytix-block'); ?>
        <!--    Powered by Data, Filtered and Curated by Humans block    -->
        <?php get_template_part('template-parts/powered-by-data-block/powered-by-data-block'); ?>
        <!--    Intelligent, Event-Driven Feeds block -->
        <?php get_template_part('template-parts/intelligent-block/intelligent-block'); ?>
        <!--    Alert, Delivery Demo block    -->
        <?php get_template_part('template-parts/alert-block/alert-block'); ?>
        <!--    Comments block  -->
        <?php get_template_part('template-parts/comments-block/comments-block'); ?>
        <!--    Designed and Developed for Traders block -->
        <?php get_template_part('template-parts/designed-and-developed-block/designed-and-developed-block'); ?>
        <!--    Get Access (Noise) image background-->
        <?php get_template_part('template-parts/get-access-block/get-access-block'); ?>
    </div>

    <?php get_sidebar();?>


    <?php if (is_active_sidebar('ea_chat')) : ?>
        <div class="footer-body">
            <div class="row">
                <?php dynamic_sidebar('ea_chat'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php get_footer();?>
</div>

<!--POP-UPs-->
<!--Our Story pop-up-->
<?php get_template_part('template-parts/modals/modal-story'); ?>

<!--Name Analysis pop-up-->
<?php get_template_part('template-parts/modals/modal-analysis'); ?>

<!--Refund & Billing Policy pop-up-->
<?php get_template_part('template-parts/modals/modal-refund'); ?>

<!--Free Trial? pop-up-->
<?php get_template_part('template-parts/modals/modal-trial'); ?>

<!--Faster Data Interpretation pop-up-->
<?php get_template_part('template-parts/modals/modal-information'); ?>

<!--Privacy policy pop-up (empty)-->
<?php get_template_part('template-parts/modals/modal-privacy-policy'); ?>

<!--Log in form-->
<?php get_template_part('template-parts/modals/modal-sign-in'); ?>

<!--Sign up form-->
<?php get_template_part('template-parts/modals/modal-register'); ?>

<?php wp_footer();?>
