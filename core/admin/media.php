<?php
session_start();

include '../config.php';

include 'includes/functions.php';

houndCheckLogin();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="content">
    <div class="content main">
        <h2>Media</h2>
        <div>
            <a class="thin-ui-button thin-ui-button-secondary" href="media.php"><i class="fa fa-refresh" aria-hidden="true"></i></a>
        </div>
        <br>

        <?php
        $folder = '../../content/files/images/';

        if (isset($_POST['op']) && (string) $_POST['op'] === 'insx') {
            $filename = basename($_FILES['foto1']['name']);
            //$uploadfile = $folder . $ante . $_FILES['foto1']['name'];
			$uploadfile = $folder . $_FILES['foto1']['name'];

            if (move_uploaded_file($_FILES['foto1']['tmp_name'], $uploadfile)) {
                // success
            } else {
                // error
                echo  "Error<br>";
            }
        }

        if (isset($_GET['op']) && (string) $_GET['op'] === 'del') {
            $file = $_GET['file'];
            $delfile = makeSafe($file);
            if(file_exists('../' . $delfile)) {
                echo "<script>alert('$delfile deleted!');</script>";
                unlink("../".$delfile );
            }
        }

        $primavolta = isset($_GET['pv']) ? $_GET['pv'] : 1;
        $perpagina = 12;

        $s = isset($_GET['s']) ? $_GET['s'] : 0;
        $e = isset($_GET['e']) ? $_GET['e'] : $perpagina;
        if (strlen($s) <= 0 && strlen($e) <= 0) {
            echo '<script>location.href="' . $_REQUEST['PHP_SELF'] . '?s=0&e=' . $perpagina . '&pv=1";</script>';
        }

        $files = glob($folder . '*');

        $so = count($files);
        $totale = $so;
        $ss = $s + 1;
        $st = $so -1;
        $so -= $s;
        $ee = $e;

        if ($e > $totale) {
            $ee = $totale;
        };

        echo '<p>' . $totale . ' images found.</p>';

        // show pictures
        $sn = $s;        // next button start
        $en = $e;        // next button end
        $sp = $s;        // prev button start
        $ep = $e;        //prev button end
        ?>
        <table data-table-theme="default zebra hd-sortable">
            <thead>
                <tr>
                    <th></th>
                    <th>Media</th>
                    <th>Media Details</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($s!=$e && $so!=0) {
                    $ext = pathinfo($files[$s], PATHINFO_EXTENSION);
                    $fileinfo = stat($files[$s]);
                    $fileName = str_replace('../content/files/images/', '', $files[$s]);
					$baseName = basename($files[$s]);
					$fileRef = basename($files[$s],'.'.$ext);

                    if ($fileName !== 'index.php') {
                        echo '<tr>'.PHP_EOL;
                            if ((string) $ext === 'jpg' || (string) $ext === 'jpeg' || (string) $ext === 'png' || (string) $ext === 'gif') {
                                echo '<td data-label="Thumbnail"><img src="' . $files[$s] . '" alt="" height="40"></td>'.PHP_EOL;
                            } else {
                                echo '<td></td>.PHP_EOL';
                            }
                            echo '<td data-label="Media">' . $baseName . '
                            	<br><code>' . $files[$s] . '</code>
                                </td>
                                <td data-label="Details"><small>' . date('F d Y H:i:s', filemtime($files[$s])) . ' <code>' . formatSizeUnits($fileinfo['size']) . '</code></small></td>
                                <td data-label="Action">
                                    <!-- <a href="' . $files[$s] . '" target="_blank">View</a> -->
                                    <a href="#' . $fileRef . '">View</a> |
                                    <a style="color: red;" onclick="return confirm(\'are you sure?\');" href="media.php?op=del&file=' . $files[$s] . '">Delete</a>
                                	<!-- lightbox container hidden with CSS -->
									<a href="#" class="lightbox" id="'.$fileRef.'">
									  <span style="background-image: url(\''.$files[$s].'\')"></span>
									</a>
                                </td>
                            </tr>'.PHP_EOL;
                    }

                    $s++;
                    $so--;
                }
                ?>
            </tbody>
        </table>

        <?php
        if (strlen($primavolta) > 0) {
            // next & prev buttons
            $sn += $perpagina;
            $en += $perpagina;
            $sp -= $perpagina;
            $ep -= $perpagina;
        } else {
            // next & prev buttons
            $sn += 0;
            $en += $perpagina;
            $sp -= $perpagina;
            $ep -= $perpagina;
        }

		$prevDisabled = "";
        if ($sp < 0 ) {
            $prevDisabled = " disabled";
        }

		$nextDisabled = "";
        if ($sn > $st ) {
            $nextDisabled = " disabled";
        }

        $prev = "<a class=\"thin-ui-button thin-ui-button-primary".$prevDisabled."\" href=".$_SERVER['PHP_SELF']."?s=".$sp."&e=".$ep.">previous page</a>";
        $next = "<a class=\"thin-ui-button thin-ui-button-primary".$nextDisabled."\" href=".$_SERVER['PHP_SELF']."?s=".$sn."&e=".$en."&pv=1>next page</a>";

        $seperator = "";
        /*
        if ( ($sp >= 0) && ($sn <= $st) ){
        	$seperator = " | ";
        }
        */

        echo '<p>' . $prev . $seperator . $next . '</p>';
        ?>

        <h4>Upload new image</h4>
        <form action="media.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="op" value="insx">
            <input type="file" name="foto1">
            <br><input type="submit" class="thin-ui-button thin-ui-button-secondary" value="Upload">
        </form>
    </div>
</div>
<?php
include 'includes/footer.php';
