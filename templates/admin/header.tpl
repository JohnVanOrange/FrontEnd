<!DOCTYPE html>
<html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
    <title>{$site_name} Admin</title>
    <link rel='shortcut icon' type='image/png' href='{$web_root}icons/{$icon_set}/16.png' />
    <link rel='stylesheet' type='text/css' media='screen' href='{$web_root}css/themes/{$site_theme}/{$site_theme}.css?20130121a' />
    <link rel='stylesheet' type='text/css' media='handheld, only screen and (max-width: 600px), only screen and (max-device-width: 600px)' href='{$web_root}css/mobile.css?20121216' />
    <link rel='stylesheet' type='text/css' href='{$web_root}css/fontello/fontello.css?20130128a' />
    <link rel='stylesheet' type='text/css' href='{$web_root}css/fontello/fontello-codes.css?20130128' />
    <link rel='stylesheet' type='text/css' href='{$web_root}css/ui-theme/jquery-ui-1.8.21.custom.css' />
	<link rel='stylesheet' type='text/css' href='{$web_root}css/themes/admin/admin.css' />
</head>

<body>

<header>
    <a href='/'><h1>{$site_name} Admin</h1></a>
</header>

<nav>
	<ul>
		{if isset($user.username)}<li id='user_avatar'><a href='/u/{$user.username}'><img src='http://www.gravatar.com/avatar/{$user.email_hash}?s=48&amp;d=retro&amp;r=pg' alt='User avatar' height='24' width='24'></a></li>
		<li id="username" class='nomobile' title="View Profile"><a href='/u/{$user.username}'>{$user.username}</a></li>{/if}
        {if isset($image)}
		<li id='save_image' class='icon-star-1 icon{if isset($image.saved)} highlight{/if}' title='Save Image'></li>
        {/if}
	</ul>
</nav>