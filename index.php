<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Basic Config
$botName = "JREbot";
$botPrefix = "."; // Bot's command prefix
$botPrefixName = "period"; // prefix spelled out
$botPrefixShow = false; // Show prefix on all commands
$logo = "app/img/logo.png";
$header = "All commands begin with the $botPrefix ($botPrefixName) prefix.
<p>Most commands can only be used in the #bots channel unless labeled otherwise.</p>"; // Optional
$menubar = true; // Show quick menubar of categories
$menubarHideSmall = true; // Hide categories with the "header": "small" tag
$favicon = "faviconit"; // Default: faviconit (https://faviconit.com). Can be custom URL/file.
$footerLeft = "<a href='https://discord.com/invite/joerogan'>The Joe Rogan Experience Discord</a>"; // Optional
$footerCenter = ""; // Optional
$footerRight = "&copy; 2023"; // Optional
$fullscreen = true;  // Website takes full screen on desktop
$modCode = "lol"; // Mod commands password (?mod=password)
$adminCode = "woo"; // Admin commands password (?admin=password)

// Theme
#$background = 

// Advanced Config
// cache page requires /app/data to be writable
$cachePage = true; // Cache index.php for faster loading
$cacheExpires = 3600; // Default: 3600 (1 Hour). 0 for never (must flush manually)
$metaAuthor = "Catalyst";
$metaDescription = "Commands and information for {$botName} Discord Bot";
$metaDescriptionEmbed = ""; // Social media embed description. leave blank to use $metaDescription

/////////////////////////////////////////
//     DO NOT edit below this line     //
/////////////////////////////////////////
include("app/data/main.php");