<!DOCTYPE html>
<html>
<head>
<title>{$site_name}{if $uid} - {$uid}{/if}</title>
<link rel='shortcut icon' type='image/png' href='{$web_root}img/{$favicon}' />
<link rel='stylesheet' type='text/css' href='{$web_root}css/main.css?20120612' />
<link rel='stylesheet' type='text/css' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/base/jquery-ui.css' />
<link rel='stylesheet' type='text/css' href='{$web_root}css/jquery.noty.css' />
<link rel='stylesheet' type='text/css' href='{$web_root}css/noty_theme_default.css' />
{if $image_name}
{include file='image_header.tpl'}
{/if}
</head>

<body class='{$theme}'>
<div id='set_theme'></div>
{if $show_scorn}<div id='scorn'><a href='http://scornondemand.com'>Demand Scorn</a></div>{/if}
