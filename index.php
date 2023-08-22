<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Basic Config
$botName = "JREbot";
$botPrefix = "."; // Bot's command prefix
$botPrefixName = "period"; // prefix spelled out
$botPrefixShow = false; // Show prefix on all commands
$logo = "app/img/discord.png";
$header = "All commands begin with the $botPrefix ($botPrefixName) prefix. Most commands can only be used in the #bots channel."; // Optional
$favicon = "faviconit"; // Default: faviconit (https://faviconit.com). Can be custom URL/file.
$footerLeft = "<a href='https://discord.com/invite/joerogan'>The Joe Rogan Experience Discord</a>"; // Optional
$footerCenter = ""; // Optional
$footerRight = "&copy; 2023"; // Optional
$modCode = "lol"; // Mod commands password (?mod=password)
$adminCode = "woo"; // Admin commands password (?admin=password)

// Theme
#$background = 

// Advanced Config
$cachePage = true; // Cache index.php for faster loading
$cacheExpires = 15; // Default: 3600 (1 Hour). 0 for never (must flush manually)
$metaAuthor = "Catalyst";
$metaDescription = "Commands and information for {$botName} Discord Bot";
$metaDescriptionEmbed = ""; // Social media embed description. leave blank to use $metaDescription

include("app/data/main.php");