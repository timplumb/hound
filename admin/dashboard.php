<?php
declare (strict_types = 1);

session_start();

include '../config.php';
include '../libs/hound.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];

if ((string) $temppass === (string) $password) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <h2>Dashboard</h2>

            <?php houndUpdateCheck(); ?>

            <div class="hound-at-a-glance thin-ui-infobox">
                <h3>At a Glance</h3>
                <div class="inside">
                    <strong><?php echo houndCountContent('page'); ?></strong> pages<br>
                    <strong><?php echo houndCountContent('menu'); ?></strong> menu items<br>
                    <strong><?php echo houndCountContent('backup'); ?></strong> backup files<br>
                    <strong><?php echo houndCountContent('asset'); ?></strong> assets (documents and images)
                </div>
            </div>

            <?php
            if (houndCheckWritePermissions('../site')) {
                echo '<div><i class="fa fa-fw fa-check" aria-hidden="true"></i> Content (templates and pages) folder is writable.</div>';
            } else {
                echo '<div><i class="fa fa-fw fa-times" aria-hidden="true"></i> Content (templates and pages) folder is not writable.</div>';
            }

            if (houndCheckWritePermissions('../files')) {
                echo '<div><i class="fa fa-fw fa-check" aria-hidden="true"></i> Uploads folder is writable.</div>';
            } else {
                echo '<div><i class="fa fa-fw fa-times" aria-hidden="true"></i> Uploads folder is not writable.</div>';
            }

            if (houndCheckWritePermissions('../files/update')) {
                echo '<div><i class="fa fa-fw fa-check" aria-hidden="true"></i> Update folder is writable.</div>';
            } else {
                echo '<div><i class="fa fa-fw fa-times" aria-hidden="true"></i> Update folder is not writable.</div>';
            }

            if (class_exists('ZipArchive') || extension_loaded('zip')) {
                echo '<div><i class="fa fa-fw fa-check" aria-hidden="true"></i> Zip functionality is available.</div>';
            } else {
                echo '<div><i class="fa fa-fw fa-times" aria-hidden="true"></i> Zip functionality is not available. Backups and automatic updates will not work.</div>';
            }
            ?>

            <br>
            <?php
            echo '<div><i class="fa fa-fw fa-info" aria-hidden="true"></i> cURL is ', function_exists('curl_version') ? 'enabled (' . curl_version()['version'] . '/' . curl_version()['host'] . '/' . curl_version()['ssl_version'] . ')</div>' : 'disabled</div>';
            echo '<div><i class="fa fa-fw fa-info" aria-hidden="true"></i> <code>file_get_contents()</code> is ', file_get_contents(__FILE__) ? 'enabled</div>' : 'disabled</div>';
            echo '<div><i class="fa fa-fw fa-info" aria-hidden="true"></i> Current theme folder is <code>/' . houndGetParameter('template') . '/</code></div>';
            ?>

            <p>
                You are using Hound <strong><?php echo houndGetParameter('version'); ?></strong> on PHP <?php echo PHP_VERSION; ?>.
                <br><small>Current memory usage is <?php echo houndGetMemory('usage'); ?> (<?php echo houndGetMemory('peak'); ?>) out of <?php echo houndGetMemory('available'); ?> allocated.</small>
                <?php if (function_exists('sys_getloadavg')) {
                    $load = sys_getloadavg();
                    ?><br><small><?php echo 'Current CPU load is ' . implode(', ', $load); ?></small>
                <?php } ?>
            </p>

            <hr>
            <p>Thank you for using <a href="https://getbutterfly.com/hound/" rel="external">Hound</a> by getButterfly.</p>
        </div>
    </div>
    <?php
    include 'includes/footer.php';
} else {
    php_redirect('index.php?err=1');
}
