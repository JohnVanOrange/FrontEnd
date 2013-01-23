<!DOCTYPE html>
<html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
    <title>{$site_name} Admin</title>
    <link rel='shortcut icon' type='image/png' href='{$web_root}icons/{$icon_set}/16.png' />
    <link rel='stylesheet' type='text/css' href='{$web_root}css/themes/{$site_theme}/{$site_theme}.css?20121002' />
    <link rel='stylesheet' type='text/css' href='{$web_root}css/ui-theme/jquery-ui-1.8.21.custom.css' />
	<link rel='stylesheet' type='text/css' href='{$web_root}css/themes/admin/admin.css' />
</head>

<body>

<header>
    <a href='/'><h1>{$site_name} Admin</h1></a>
</header>

<nav>
	<ul>
		{if $user.username}<li><img src='http://www.gravatar.com/avatar/{$user.email_hash}?s=24&amp;d=retro&amp;r=pg' alt='User avatar'></li>
		<li id="username" title="View Profile"><a href='/u/{$user.username}'>{$user.username}</a></li>{/if}
        {if $image}
		<li id='save_image' class='{if !$data.save}not_{/if}saved icon' title='Save Image'></li>
        {/if}
	</ul>
</nav>