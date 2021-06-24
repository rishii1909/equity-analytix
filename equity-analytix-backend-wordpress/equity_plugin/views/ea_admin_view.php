<div class="wrap">
    <h1>Messages</h1>
    <div class="div-table">
        <div class="div-table-row">
            <div class="div-table-col">Message</div>
            <div class="div-table-col">Status</div>
            <div class="div-table-col">Time</div>
        </div>
        <?php foreach ($messages as $message) : ?>
            <div class="div-table-row">
                <div class="div-table-col"><?php echo $message->text; ?></div>
                <div class="div-table-col"><?php echo $message->status; ?></div>
                <div class="div-table-col"><?php echo $message->created_at; ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ($page_links) : ?>
        <div>
            <div>
                <?php echo $page_links?>
            </div>
        </div>
    <?php endif; ?>

    <h1>Users</h1>
    <div class="div-table">
        <div class="div-table-row">
            <div class="div-table-col">User name</div>
        </div>
		<?php foreach ($users as $user) : ?>
            <div class="div-table-row">
                <div class="div-table-col"><?php echo $user->user_nicename; ?></div>
            </div>
		<?php endforeach; ?>
    </div>
</div>
<div>
    <button rel="noopener" onclick="window.open('<?php echo get_site_url() . '/chat/' ?>','targetWindow','scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=550,height=700,left=100,top=100')">
        Open Chat
    </button>
</div>

