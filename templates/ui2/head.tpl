<!DOCTYPE html>
<html>
<head>
    <title>{$site_name}{if $tags} -{foreach from=$tags item=tag} {$tag.name}{/foreach}{/if}</title>
    <link rel='shortcut icon' type='image/png' href='{$web_root}img/{$favicon}' />
    <link rel='stylesheet' type='text/css' href='{$web_root}css/themes/jvo/jvo.css' />
    <link rel='stylesheet' type='text/css' href='{$web_root}css/ui-theme/jquery-ui-1.8.21.custom.css' />
    <!--<link rel='stylesheet' type='text/css' href='{$web_root}css/jquery.noty.css' />
    <link rel='stylesheet' type='text/css' href='{$web_root}css/noty_theme_default.css' />-->
</head>
{include file='header.tpl'}