<nav>
	<ul>
		{if isset($user.username)}<li id='user_avatar'><a href='/u/{$user.username}'><img src='http://www.gravatar.com/avatar/{$user.email_hash}?s=48&amp;d=retro&amp;r=pg' alt='User avatar' height='24' width='24'></a></li>
		<li id="username" class='nomobile' title="View Profile"><a href='/u/{$user.username}'>{$user.username}</a></li>{/if}
		{if isset($image)}
		<li id='dislike_image' class='like icon-thumbs-down icon{if isset($image.data.dislike)} highlight{/if}' title='Dislike Image'></li>
		<li id='like_image' class='like icon-thumbs-up icon{if isset($image.data.like)} highlight{/if}' title='Like Image'></li>
		<li id='add_image' class='icon-plus-circled icon'>
			<ul>
				<li><a href='/add' class='icon-upload'>Upload Image</a></li>
				<li><a href='/form/add_internet' id='addInternet' class='icon-link'>Add Image from URL</a></li>
			</ul>
		</li>
		<li id='save_image' class='icon-star-1 icon{if isset($image.saved)} highlight{/if}' title='Save Image'></li>
		<li id='share_image' class='icon-share icon'>
			<ul>
				<li><a id='facebook_menu' class='icon-facebook-rect' href="http://api.addthis.com/oexchange/0.8/forward/facebook/offer?pubid=ra-4f95e38340e66b80&amp;url={$current_url}" rel="nofollow" target='_blank'>Facebook</a></li>
				<li><a id='twitter_menu' class='icon-twitter-bird' href="http://api.addthis.com/oexchange/0.8/forward/twitter/offer?pubid=ra-4f95e38340e66b80&amp;url={$current_url}&amp;via=JohnVanOrange&amp;related=JohnVanOrange" rel="nofollow" target='_blank'>Twitter</a></li>
				<li><a id='reddit_menu' class='icon-reddit' href="http://www.reddit.com/submit?url={$current_url}" rel="nofollow" target='_blank'>Reddit</a></li>
				<li><a id='email_menu' class='icon-mail-alt' href="http://api.addthis.com/oexchange/0.8/forward/email/offer?pubid=ra-4f95e38340e66b80&amp;url={$current_url}" rel="nofollow" target='_blank'>E-Mail</a></li>
			</ul>
		</li>
		<li id='report_image' title='Report Image' class='icon-flag icon'><a href='' id='report'></a></li>
		<li id='search_image' class='nomobile icon-picture icon'>
			<ul>
				<li><a id='googlesearch' class='icon-google' href='http://www.google.com/searchbyimage?image_url={$image.image_url}'>Search using Google</a></li>
				<li><a id='tineye' class='icon-search' href='http://tineye.com/search?url={$image.image_url}'>Search using Tineye</a></li>
				<li><a class='icon-info-circle' href='/info/{$image.uid}'>Details</a></li>
				<li><a id='fullscreen' class='icon-resize-full' href='{$image.image_url}'>View Fullscreen</a></li>
				{if isset($is_admin)}<li><a id='admin_link' class='icon-cog' href='/admin/image/{$image.uid}'>Image Admin</a></li>{/if}
			</ul>
		</li>
		{/if}
		<li class='nomobile'><form action='' id='search'><input id='tag_search' type='search' results="0" placeholder='View by tag'><button type='submit'>Search</button></form></li>
		<li id='menu' class='icon-menu icon'>
			<ul>
                {if !isset($user.username)}<li><a href='/form/login' id='login' class='icon-login'>Login</a></li>
                <li><a href='/form/account' class='create_acct icon-user-add'>Create Account</a></li>{/if}
				<li><a href='/tags' class='icon-cloud'>Tag Cloud</a></li>
				{if $browser.name == 'firefox'}<li><a id='firefox_menu' href='' class='icon-firefox'>Firefox Web App</a></li>{/if}
				{if isset($app_link)}<li><a id='android_menu' href='{$app_link}' class='icon-android'>Android App</a></li>{/if}
                <li><a href='https://github.com/cbulock/JohnVanOrange/issues/new' class='icon-github'>Suggestions/Bugs</a></li>
                <li><a href='/form/keyboard' id='keyboard' class='icon-keyboard'>Keyboard Shortcuts</a></li>
				<li><a href='/docs/' id='api_docs' class='icon-doc-alt'>API Docs</a></li>
                {if isset($user.username)}<li><a href='' id='logout' class='icon-logout'>Logout</a></li>{/if}
			</ul>
		</li>

	</ul>
</nav>