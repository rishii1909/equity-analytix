<?php
/**
 * The sidebar containing the EA Messenger widget area
 */

if ( ! is_active_sidebar( 'ea_chat' ) ) {
    return;
}
?>

<div id="ea_chat" class="sidebar">
    <?php if ( is_active_sidebar( 'ea_chat' ) ) : ?>
        <?php dynamic_sidebar( 'ea_chat' ); ?>
    <?php else : ?>
         Widget is not added!
    <?php endif; ?>
</div>
