<?php

    $html_control_panel_items ??= [];
    $html_account_sub_menu_items ??= [];
    $html_notification_sub_menu_items ??= [];
    $user_icon_path ??= '';
    $user_name ??= '';
    $has_push ??= false;
?>

<header>
    <section class="control-panel">
        <?php foreach ($html_control_panel_items as $html_fragment) : ?>
            <?= $html_fragment ?>
        <?php endforeach; ?>
    </section>
    <section class="account-control">


    <?php if ($has_push === true) : ?>
        <div class="notification-block __push">
    <?php else : ?>
        <div class="notification-block">
    <?php endif; ?>

            <div class="notifications" value="55">
                <div class="icon-header-notification">
                    <i></i>
                </div>
            </div>
            <div class="sub-menu">
                <?php foreach ($html_notification_sub_menu_items as $html_fragment) : ?>
                    <?= $html_fragment ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="account-block">
            <div class="account-block-main">
                <img src="<?= $user_icon_path ?>" alt="profile">
                <div class="account-name">
                    <span><?= $user_name ?></span>
                    <div class="icon-triangle">
                        <i></i>
                    </div>
                </div>
            </div>
            <div class="sub-menu">
                <menu>
                    <?php foreach ($html_account_sub_menu_items as $html_fragment) : ?>
                        <?= $html_fragment ?>
                    <?php endforeach; ?>
                </menu>
            </div>
        </div>
    </section>
</header>