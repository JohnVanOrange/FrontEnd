<!DOCTYPE html>
<html>
<head>
<title>John VanOrange</title>
<link rel='shortcut icon' type='image/png' href='{$web_root}img/Nyan-Cat-Original_032x032_32.png' />
<link rel='stylesheet' type='text/css' href='{$web_root}css/main.css' />
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>
<script src='{$web_root}js/jquery.cookie.js'></script>
<script src='{$web_root}js/main.js'></script>
</head>

<body class='{$theme}'>
<div id='set_theme'></div>
<a href='../random'>
 <img id='main_image' src='{if $brazzify}http://brazzify.me/?s={/if}{$image}' height='{$height}' width='{$width}' />
</a>
<p>Click picture for more{if $type != 'gif' AND !$brazzify}, or <a href='{$web_root}brazzify/{$image_name}'>Brazzify</a>{/if}</p>

<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4f95e38340e66b80"></script>
<!-- AddThis Button END -->
</body>
</html>
