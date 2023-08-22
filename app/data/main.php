<?php
/////////////////////////////////////////
//     DO NOT edit below this line     //
// Unless you know what you are doing! //
/////////////////////////////////////////

function cacheClear() {
  $cacheFiles = ["app/data/cache.html", "app/data/cacheMod.html", "app/data/cacheAdmin.html"];
  foreach($cacheFiles as $cacheFile) {
    if (file_exists($cacheFile))
      unlink($cacheFile);
  }
}



// Check if ?mod or ?admin or ?flush was set and code is correct
$authMod = ($_GET['mod'] ?? false) && ($_GET['mod'] == $modCode) ? true : false;
$authAdmin = ($_GET['admin'] ?? false) && ($_GET['admin'] == $adminCode) ? true : false;
$cacheFlush = $_GET['flush'] ?? false;
$full = ($fullscreen == true) ? "container-fluid" : "container";

if ($cacheFlush != false)
  cacheClear();

if ($cachePage == true) {

  // Make sure app/data directory is writable
  if (!is_writable("app/data")) 
    die("<p>To use caching, the /app/data folder must be writable.</p><p>To disable caching, set \$cachePage = false; in index.php</p>");

  if ($authAdmin == true && file_exists("app/data/cacheAdmin.html")) {
    $file = "app/data/cacheAdmin.html";
  } else if ($authMod == true && file_exists("app/data/cacheMod.html")) {
    $file = "app/data/cacheMod.html";
  } else if ($authMod == false && $authAdmin == false && file_exists("app/data/cache.html")) {
    $file = "app/data/cache.html";
  } else {
    $file = false;
  }

  if ($file != false) {

    // Check if fileModified time is expired
    $fileModified = filemtime($file);
    if ($fileModified < time() - $cacheExpires) {
      cacheClear();
    } else {
      include($file);
      die();
    }

  } 

  ob_start(); // Start page cache

}

// Load commands.json
$fo = fopen("app/data/commands.json", 'r');
$fr = fread($fo, filesize("app/data/commands.json"));
fclose($fo);
$cmds = json_decode($fr, true);

// Check whether config $metaDescriptionEmbed is blank
$metaDescriptionEmbed = ($metaDescriptionEmbed != "") ? $metaDescriptionEmbed : $metaDescription;
?>

<!doctype html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?php echo $metaDescription; ?>">
  <meta property="og:description" content="<?php echo $metaDescriptionEmbed; ?>" />
  <meta name="author" content="<?php echo $metaAuthor; ?>">
  
  <title><?php echo $botName; ?></title>

  <?php
  if ($favicon == "faviconit") {
    // By default, this script uses the filenames that are given
    // when you use https://faviconit.com
    ?>
    <!-- Favicons -->
    <link rel="shortcut icon" href="app/img/favicons/favicon.ico">
    <link rel="icon" sizes="16x16 32x32 64x64" href="app/img/favicons/favicon.ico">
    <link rel="icon" type="image/png" sizes="196x196" href="app/img/favicons/favicon-192.png">
    <link rel="icon" type="image/png" sizes="160x160" href="app/img/favicons/favicon-160.png">
    <link rel="icon" type="image/png" sizes="96x96" href="app/img/favicons/favicon-96.png">
    <link rel="icon" type="image/png" sizes="64x64" href="app/img/favicons/favicon-64.png">
    <link rel="icon" type="image/png" sizes="32x32" href="app/img/favicons/favicon-32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="app/img/favicons/favicon-16.png">
    <link rel="apple-touch-icon" href="app/img/favicons/favicon-57.png">
    <link rel="apple-touch-icon" sizes="114x114" href="app/img/favicons/favicon-114.png">
    <link rel="apple-touch-icon" sizes="72x72" href="app/img/favicons/favicon-72.png">
    <link rel="apple-touch-icon" sizes="144x144" href="app/img/favicons/favicon-144.png">
    <link rel="apple-touch-icon" sizes="60x60" href="app/img/favicons/favicon-60.png">
    <link rel="apple-touch-icon" sizes="120x120" href="app/img/favicons/favicon-120.png">
    <link rel="apple-touch-icon" sizes="76x76" href="app/img/favicons/favicon-76.png">
    <link rel="apple-touch-icon" sizes="152x152" href="app/img/favicons/favicon-152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="app/img/favicons/favicon-180.png">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="msapplication-TileImage" content="app/img/favicons/favicon-144.png">
    <meta name="msapplication-config" content="app/img/favicons/browserconfig.xml">
    <?php
  } else {
    // Use custom URL
    echo '<link rel="shortcut icon" href="'.$favicon.'">';
  }
  ?>

  <!-- CSS -->
  <link href="app/bootstrap.min.css" rel="stylesheet">
  <link id="theme" href="app/theme.css" rel="stylesheet">

</head>

<body>

<header>
  <nav class="<?php echo $full; ?> navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="col-12 ps-3">
        <a class="navbar-brand" href="#"><img src="<?php echo $logo; ?>" height="30"  /> &nbsp;<?php echo $botName; ?> Commands</a>
      </div>
    </nav>
</header>

<div id="pluginMenu" class="<?php echo $full; ?> py-1 ps-3">

  <div class="row">

    <?php
    foreach ($cmds as $category => $cmdVals) {

      // Check if this is a mod or admin only command section
      if ((isset($cmdVals['mod']) && $authMod !== true && $authAdmin != true) || (isset($cmdVals['admin']) && $authAdmin != true))
        continue;

      echo '<div class="col-auto m-2 p-1"><a href="#'.$category.'">'.$category.'</a></div>';
    }
    ?>

  </div>

</div>

<div id="main" class="<?php echo $full; ?>">

  <?php
  if ($header != "") {
    echo "
    <div id='header' class='row'>
      <div class='col-12 p-1 pt-3 px-3 pluginCol'>
        <p>$header</p>
      </div>
    </div>";
  }
  ?>

  <div id="commandsBox">

    <?php
    // Loop through command.json
    $rows = 0; // count rows to color background

    $prefix = ($botPrefixShow == true) ? $botPrefix : null;

    // Create 
    foreach ($cmds as $category => $cmdVals) {

      // Check if this is a mod or admin only command section
      // Skip it if no ?mod or ?admin was give
      if ((isset($cmdVals['mod']) && $authMod !== true && $authAdmin != true) || (isset($cmdVals['admin']) && $authAdmin != true))
        continue;

      $x = 1; // Amount of commands in this section

      // Create category section
      echo "<a name='{$category}'></a>
      <div class='row pluginRow'>
        <div class='col-12 pt-1 p-1 pt-3 pt-lg-3 px-3 pluginCol'>
              <h3>{$category}</h3>
          </div>
        <div class='col-12 col-md-6 col-lg-5 col-xl-5 pb-lg-4'>";

        // Count how many commands are in this category and cut in half
        $catCount = ceil(count($cmdVals['commands']) / 2);

        // Loop through each command in this category
        foreach($cmdVals['commands'] as $key => $val) {

          // Check if this is a mod or admin only command
          if ((isset($val['mod']) && $authMod !== true && $authAdmin != true) || (isset($val['admin']) && $authAdmin != true)) {
            continue;
          } elseif ((isset($val['mod']) && ($authMod == true && $authAdmin != true)) || (isset($val['admin']) && $authAdmin == true)) {
            $modAsterisk = "<span class='badge bg-primary'>Mod</span>";
          } else {
            $modAsterisk = "";
          }

          // Print the command and description
          echo "<p><strong>{$prefix}{$val['cmd']}</strong> {$val['desc']} {$modAsterisk}</p>";

          if ($x == $catCount) { // new column if over half of commands are printed
            echo "</div>
            <div class='col-12 col-md-6 col-lg-5 col-xl-5 mt-0 mt-lg-0 pb-3'>";
          }
          $x++;
          $rows++;
      }

      // End category section
      echo "
        </div>
      </div>";

    }
    ?>


  </div>
</div>

<button onclick="topFunction()" id="scrollButton" title="Go to top">&uarr;</button> 

<!-- Footer -->
<?php
if ($footerLeft != "" || $footerCenter != "" || $footerRight != "") {
  echo "
  <footer class='{$full} pt-3'>
    <div class='row'>
      <div id='footerLeft' class='col-4 ps-3'><p class=''>$footerLeft</p></div>
      <div id='footerCenter' class='col-4'><p>$footerCenter</p></div>
      <div id='footerRight' class='col-4 pe-3'><p class=''>$footerRight</p></div>
    </div>
  </footer>";
}
?>

<script src="app/bootstrap.min.js"></script>

<!-- Scroll to top button -->
<script type="text/javascript">

    // Get the button:
    let scrollButton = document.getElementById("scrollButton")

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()}

    function scrollFunction() {
      if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        scrollButton.style.display = "block"
      } else {
        scrollButton.style.display = "none"
      }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
      document.body.scrollTop = 0 // For Safari
      document.documentElement.scrollTop = 0 // For Chrome, Firefox, IE and Opera
    } 
  </script>

</body>
</html>

<?php
if ($cachePage == true) {
  // Finish caching
  // Capture the contents of the output buffer
  $page = ob_get_contents();

  // End output buffering
  ob_end_clean();

  // Save the captured contents to a cache file
  if ($authAdmin == true) {
    $file = "app/data/cacheAdmin.html";
  } else if ($authMod == true) {
    $file = "app/data/cacheMod.html";
  } else {
    $file = "app/data/cache.html";
  }

  file_put_contents($file, $page);

  echo $page;
}
?>
