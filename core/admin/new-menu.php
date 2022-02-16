<?php
session_start();

include '../config.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];

if ((string) $temppass === HOUND_PASS) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">

          <?php
          if(isset($_POST['op']) && ($_POST['op']=="mod")){

                $order=$_POST['order'];
                $item=$_POST['item'];
                $link=$_POST['link'];
                $slug=trim($item);

                $file="../../content/site/pages/menu-$slug.txt";

                //create file
                $myfile = fopen($file, "a") or die("Unable to open file!");
                fclose($myfile);

                $arrayvalue = array(
                    'Order' => $order,
                    'Item' => $item,
                    'Link' => $link
                );
                if (writeParam($arrayvalue, $file)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">Changes saved successfully.</div>';
                    ?>
<script type="text/javascript">
const myTimeout = setTimeout(redirectToMenu, 3000);
function redirectToMenu(){ window.location = "menu.php"; }
</script>
                    <?php
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while saving changes.</div>';
                }
          }
          ?>

          <h2>New menu</h2>
<?php
/*
          <form role="form" name="form1" id="form1" action="new-menu.php" method="post">
          <input type="hidden" value="mod" name="op">

          <div class="form-group form-group-lg">
              <b>Order</b>
              <label class="sr-only" for="inputHelpBlock">Order</label>
              <span class="help-block">Order of item in menu (from 0 to 100)</span>
              <input name="order"  required type="text" class="form-control">
          </div>


          <div class="form-group form-group-lg">
              <b>Item</b>
              <label class="sr-only" for="inputHelpBlock">Item</label>
              <span class="help-block">Menu</span>
              <input name="item" required type="text" class="form-control">
          </div>

          <div class="form-group form-group-lg">
              <b>Link</b>
              <label class="sr-only" for="inputHelpBlock">link</label>
              <span class="help-block">Absolute link of page</span>
              <input name="link" required type="text" class="form-control">
          </div>

          <br>

          <button type="submit" class="thin-ui-button thin-ui-button-primary">Create menu item</button> or <u><a href="pages.php" class="thin-ui-button thin-ui-button-secondary">Cancel</a></u>
          </form>
*/
?>

<form role="form" id="form1" action="new-menu.php" method="post">
                <input type="hidden" value="mod" name="op">

                <p>
                    <b>Menu item</b><br>
                    <input name="item" value="" type="text" class="thin-ui-input" size="64" required>
                    <br><small>Menu item title</small>
                </p>

                <p>
                    <b>Menu item link</b><br>
                    <input name="link" value="" type="url" class="thin-ui-input" size="64" required>
                    <br><small>Page link (absolute URI)</small>
                </p>

                <p>
                    <b>Order</b><br>
                    <input name="order" value="" type="number" min="0" class="thin-ui-input" required>
                    <br><small>Order of item in menu</small>
                </p>

                <p>
                	<button type="submit" class="thin-ui-button thin-ui-button-primary">Save Changes</button>
                	<a href="menu.php" class="thin-ui-button thin-ui-button-secondary">Cancel</a>
                </p>
            </form>

      </div> <!-- container-fluid -->


  </div>  <!-- page-content-wrapper-->

    <?php
    include 'includes/footer.php';
} else {
    php_redirect('index.php?err=1');
}
