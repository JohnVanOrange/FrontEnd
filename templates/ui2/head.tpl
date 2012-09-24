<!DOCTYPE html>
<html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
    <title>{$site_name}{if $title_text} - {$title_text}{/if}</title>
    <link rel='shortcut icon' type='image/png' href='{$web_root}img/{$favicon}' />
    <link rel='stylesheet' type='text/css' href='{$web_root}css/themes/{$site_theme}/{$site_theme}.css' />
    <link rel='stylesheet' type='text/css' href='{$web_root}css/ui-theme/jquery-ui-1.8.21.custom.css' />

{if $image}{include file='image_header.tpl'}{/if}
</head>
{include file='header.tpl'}