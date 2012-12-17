<nav>
	<ul>
		{if $user.username}<li><a href='/u/{$user.username}'><img src='http://www.gravatar.com/avatar/{$user.email_hash}?s=24&amp;d=retro&amp;r=pg' alt='User avatar'></a></li>
		<li id="username" class='nomobile' title="View Profile"><a href='/u/{$user.username}'>{$user.username}</a></li>{/if}
		{if $image}
		<li id="add_image" class='icon'>
			<ul>
				<li><a href='/upload'>Upload Image</a></li>
				<li><a href='' id='addInternet'>Add Image from URL</a></li>
			</ul>
		</li>
		<li id='save_image' class='{if !$image.saved}not_{/if}saved icon' title='Save Image'></li>
		<li id="share_image" class='icon'>
			<ul>
				<li><a id='facebook_menu' href="http://api.addthis.com/oexchange/0.8/forward/facebook/offer?pubid=ra-4f95e38340e66b80&amp;url={$current_url}" rel="nofollow" target='_blank'>Facebook</a></li>
				<li><a id='twitter_menu' href="http://api.addthis.com/oexchange/0.8/forward/twitter/offer?pubid=ra-4f95e38340e66b80&amp;url={$current_url}&via=JohnVanOrange&related=JohnVanOrange" rel="nofollow" target='_blank'>Twitter</a></li>
				<li><a id='reddit_menu' href="http://www.reddit.com/submit?url={$current_url}" rel="nofollow" target='_blank'>Reddit</a></li>
				<li><a id='email_menu' href="http://api.addthis.com/oexchange/0.8/forward/email/offer?pubid=ra-4f95e38340e66b80&amp;url={$current_url}" rel="nofollow" target='_blank'>E-Mail</a></li>
			</ul>
		</li>
		<li id="report_image" title='Report Image' class='icon'><a href='' id='report'></a></li>
		<li id="search_image" class='icon nomobile'>
			<ul>
				<li><a id='googlesearch' href='http://www.google.com/searchbyimage?image_url={$image.image_url}'>Search using Google</a></li>
				<li><a id='tineye' href='http://tineye.com/search?url={$image.image_url}'>Search using Tineye</a></li>
				<li><a href='/info/{$image.uid}'>Details</a></li>
				<li><a id='fullscreen' href='{$image.image_url}'>View Fullscreen</a></li>
				{if $is_admin}<li><a id='admin_link' href='/admin/image/{$image.uid}'>Image Admin</a></li>{/if}
			</ul>
		</li>
		{/if}
		<li class='nomobile'><form action='' id='search'><input id='tag_search' placeholder='View by tag'><button type='submit'>Search</button></form></li>
		<li id="menu" class='icon'>
			<ul>
                {if !$user.username}<li><a href='' id='login'>Login</a></li>
                <li><a href='' class='create_acct'>Create Account</a></li>{/if}
				<li><a href='/tags'>Tag Cloud</a></li>
				{if $app_link}<li><a id='android_menu' href='{$app_link}'>Android App</a></li>{/if}
                <li><a href='https://github.com/cbulock/JohnVanOrange/issues/new'>Suggestions/Bugs</a></li>
                <li><a href='' id='keyboard'>Keyboard Shortcuts</a></li>
                {if $user.username}<li><a href='' id='logout'>Logout</a></li>{/if}
			</ul>
		</li>

	</ul>
</nav>