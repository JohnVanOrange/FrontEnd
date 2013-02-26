<!DOCTYPE html>
<html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
    <title>{if $image.page_title}{$image.page_title}{else}{$site_name}{if $title_text} - {$title_text}{/if}{/if}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3">
    <link rel='shortcut icon' type='image/png' href='{$web_root}icons/{$icon_set}/16.png' />
    <link rel='stylesheet' type='text/css' media='screen' href='{$web_root}css/themes/{$site_theme}/{$site_theme}.css?20130121a' />
    <link rel='stylesheet' type='text/css' media='handheld, only screen and (max-width: 600px), only screen and (max-device-width: 600px)' href='{$web_root}css/mobile.css?20121216' />
    <link rel='stylesheet' type='text/css' href='{$web_root}css/fontello/fontello.css?20130128a' />
    <link rel='stylesheet' type='text/css' href='{$web_root}css/fontello/fontello-codes.css?20130128' />
    <link rel='stylesheet' type='text/css' href='{$web_root}css/ui-theme/jquery-ui-1.8.21.custom.css' />
    
    <!--[if lt IE 9]>
    <script src="{$web_root}components/html5shiv/dist/html5shiv.js"></script>
    <![endif]-->

{if $image}{include file='image_header.tpl'}{/if}
</head>
{include file='header.tpl'}