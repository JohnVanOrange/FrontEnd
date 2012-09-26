<nav>
	<ul>
		{if $user.username}<li><img src='http://www.gravatar.com/avatar/{$user.email_hash}?s=24&d=retro&r=pg'></li>
		<li id="username" title="View Profile"><a href='/s/'>{$user.username}</a></li>{/if}
		{if $image}
		<li id="add_image"><img src="/img/add.png" alt="Add Image" title="Add Image">
			<ul>
				<li><a href='/upload'>Add from Computer</a></li>
				<li><a href='' id='addInternet'>Add from Internet</a></li>
			</ul>
		</li>
		<li id='save_image' class='{if !$data.save}not_{/if}saved' title='Save Image'></li>
		<li id="share_image"><img src="/img/share.png" alt="Share Image" title='Share Image'>
			<ul>
				<li><a id='facebook_menu' href="http://api.addthis.com/oexchange/0.8/forward/facebook/offer?pubid=ra-4f95e38340e66b80&url={$current_url}" rel="nofollow" target='_blank'>Facebook</a></li>
				<li><a id='twitter_menu' href="http://api.addthis.com/oexchange/0.8/forward/twitter/offer?pubid=ra-4f95e38340e66b80&url={$current_url}&via=JohnVanOrange&related=JohnVanOrange" rel="nofollow" target='_blank'>Twitter</a></li>
				<li><a id='reddit_menu' href="http://www.reddit.com/submit?url={$current_url}" rel="nofollow" target='_blank'>Reddit</a></li>
				<li><a id='email_menu' href="http://api.addthis.com/oexchange/0.8/forward/email/offer?pubid=ra-4f95e38340e66b80&url={$current_url}" rel="nofollow" target='_blank'>E-Mail</a></li>
			</ul>
		</li>
		<li id="report_image" title='Report Image'><a href='' id='report'><img src="/img/report.png" alt="Report Image"></a></li>
		<li id="search_image" title='More Image Information'><img src="/img/image_search.png" alt="More Image Information">
			<ul>
				<li><a href='http://www.google.com/searchbyimage?image_url={$image}'>Search using Google</a></li>
				<li><a href='http://tineye.com/search?url={$image}'>Search using Tineye</a></li>
			</ul>
		</li>
		{/if}
		<li><form action='' id='search'><input id='tag_search' placeholder='View by tag'><button type='submit'>Search</button><form></li>
		<li id="menu"><img src="/img/menu.png" alt="Menu" title='Menu'>
			<ul>
                {if !$user.username}<li><a href='' id='login'>Login</a></li>
                <li><a href='' id='create_acct'>Create Account</a></li>{/if}
				{if $app_link}<li><a id='android_menu' href='{$app_link}'>Android App</a></li>{/if}
                <li><a href='https://github.com/cbulock/JohnVanOrange/issues/new'>Suggestions/Bugs</a></li>
                <li><a href='' id='keyboard'>Keyboard Shortcuts</a></li>
                {if $user.username}<li><a href='' id='logout'>Logout</a></li>{/if}
			</ul>
		</li>

	</ul>
</nav>