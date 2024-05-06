<?php

ini_set('max_execution_time', 0);

include('functions/core_fns/load.php');
$process_file='processes/prompt.php';

if (isset($_GET['process']) && !empty($_GET['process'])) {
    $_GET['process'] = preg_replace("/[^a-zA-Z0-9_]+/", "", $_GET['process']);
    $temp_process_file='processes/'.$_GET['process'].'.php';

    if (isset($_GET['sub_process']) && !empty($_GET['sub_process'])) {
        $_GET['sub_process'] = preg_replace("/[^a-zA-Z0-9_]+/", "", $_GET['sub_process']);
    }

    if (!empty($_GET['process']) && file_exists($temp_process_file)) {
        if (file_exists('upgrade_info.php') || $_GET['process'] === 'system_requirements' || $_GET['process'] === 'initial') {
            $process_file = $temp_process_file;
        }
    }
}

include($process_file);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-title" content="Grupo Upgrade">
    <title>Upgrader - Grupo Pro</title>
    <link href="resources/css/style.css" rel="stylesheet">
    <?php
    if (!empty($redirect)) {
        if (isset($sub_process) && !empty($sub_process)) {
            $redirect.='&sub_process='.$sub_process;
        }
        echo '<meta http-equiv="refresh" content="3; url=index.php?process='.$redirect.'" />';
    }
    ?>
  </head>

  <body translate="no">

    <div class="header">
      <div class="inner-header flex">
        <?php
          if (strpos($system_message, 'Error :') === false) {
              ?>
          <p class="animate-character"><?php echo $system_message; ?></p>
            <?php
          } else {
              ?>
              <p class="animate-character error"><?php echo $system_message; ?></p>
                  <?php
          } ?>
          <?php
            if (isset($list_items)) {
                echo '<ol>'.$list_items.'</ol>';
            }
                ?>
      </div>


    </div>
    <?php
      if (isset($button) && isset($button['text'])) {
          ?>
          <a href="index.php?process=<?php echo $button['process']; ?>">
        <div class="content flex button">
            <h1><?php echo $button['text']; ?></h1>
        </div>
      </a>
        <?php
      } else {
          ?>
        <div class="content flex">
            <h1>Upgrading Grupo</h1>
        </div>
        <?php
      }
      ?>

<?php
if (isset($alert_message) && !empty($alert_message)) {
          ?>
  <script>
  setTimeout(function () {
  alert('<?php echo $alert_message; ?>');
}, 2000);
  </script>
<?php
      }
?>
  </body>

</html>
