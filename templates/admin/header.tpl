<!DOCTYPE html>
<html>
<head>
<title>{$site_name} - Admin</title>
<link rel='shortcut icon' type='image/png' href='{$web_root}img/{$favicon}' />
<link rel='stylesheet' type='text/css' href='{$web_root}css/main.css?20120815' />
<link rel='stylesheet' type='text/css' href='{$web_root}css/ui-theme/jquery-ui-1.8.21.custom.css' />
<link rel='stylesheet' type='text/css' href='{$web_root}css/jquery.noty.css' />
<link rel='stylesheet' type='text/css' href='{$web_root}css/noty_theme_default.css' />
</head>

<body>
<div id='search'>
 {if $user.username}
  <img src='http://www.gravatar.com/avatar/{$user.email_hash}?s=16&d=retro&r=pg'> <a href='/s/'>{$user.username}</a> | <a href='' id='logout'>Logout</a>
 {else}
  Not logged in
 {/if}
</div>