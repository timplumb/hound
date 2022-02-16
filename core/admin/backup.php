<?php
session_start();

include '../config.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];

$which = '';
if (isset($_GET['which'])) {
    $which = houndSanitizeString($_GET['which']);
}

if ((string) $temppass === HOUND_PASS) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <?php
            function zipData($source, $destination) {
                if (extension_loaded('zip')) {
                    if (file_exists($source)) {
                        $zip = new ZipArchive();
                        if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
                            $source = realpath($source);
                            if (is_dir($source)) {
                                $iterator = new RecursiveDirectoryIterator($source);
                                $iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
                                $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
                                foreach ($files as $file) {
                                    $file = realpath($file);
                                    if (is_dir($file)) {
                                        $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                                    } else if (is_file($file)) {
                                        $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                                    }
                                }
                            } else if (is_file($source)) {
                                $zip->addFromString(basename($source), file_get_contents($source));
                            }
                        }
                        return $zip->close();
                    }
                }
                return false;
            }


			if (isset($_GET['op']) && (string) $_GET['op'] === 'del') {
                $file = '../../content/backup/' . $which . '.zip';

                if (unlink($file)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">' . ucwords($which) . ' deleted successfully.</div>';
                    ?>
<script type="text/javascript">
const myTimeout = setTimeout(redirectToMenu, 3000);
function redirectToMenu(){ window.location = "backup.php"; }
</script>
                    <?php
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while deleting ' . $type . '.</div>';
                }
            }


            if (isset($_POST['backup'])) {
                $backupSite = 'backup-site-' . date('Y-m-d-') . substr(md5(microtime()), rand(0, 26), 5) . '.zip';
                $backupFiles = 'backup-files-' . date('Y-m-d-') . substr(md5(microtime()), rand(0, 26), 5) . '.zip';

                if (zipData('../../content/site/', '../../content/backup/' . $backupSite) !== false) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">Site backup completed successfully.</div>';
                    ?>
<script type="text/javascript">
const myTimeout = setTimeout(redirectToMenu, 3000);
function redirectToMenu(){ window.location = "backup.php"; }
</script>
                    <?php
                }
                if (zipData('../../content/files/', '../../content/backup/' . $backupFiles) !== false) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">Files backup completed successfully.</div>';
                }
            }
            ?>

            <h2>Backup</h2>

            <p>Backup your site's content and templates. Note that you should regularly remove the files in your <code>/backup/</code> directory and store them offsite.</p>

            <table data-table-theme="default zebra">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>File Details</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $files = glob('../../content/backup/*.zip');
                    usort($files, 'hound_compare');

                    for($i=0; $i<count($files); $i++) {
                        $backup = $files[$i];
                        $baseName = basename($files[$i]);
                        $zipName = basename($files[$i],".zip");
                        echo '<tr>
                            <td data-label="Title"><a href="' . $backup . '">' . $baseName . '</a></td>
                            <td data-label="File Details"><small>' . date('F d Y H:i:s', filemtime($backup)) . '<br>' . formatSizeUnits(filesize($backup)) . '</small></td>
                            <td data-label="Action"><a style="color: red" onclick="return confirm(\'Are you sure?\');" href="backup.php?op=del&which=' . $zipName . '">Delete</a></td>
                        </tr>';
                    }
                    ?>
                </tbody>
            </table>

            <form action="" method="post">
                <p><button type="submit" class="thin-ui-button thin-ui-button-primary" name="backup">Backup</button></p>
            </form>
        </div>
    </div>
    <?php
    include 'includes/footer.php';
}
else {
    php_redirect('index.php?err=1');
}
