<body>

<header>
    <a href='/'><h1>John VanOrange</h1></a>
</header>

<nav>
	<ul>
		<li><img src='http://www.gravatar.com/avatar/{$user.email_hash}?s=24&d=retro&r=pg'></li>
		<li id="username" title="View Profile"><a href='/s/'>{$user.username}</a></li>
		<li id="add_image"><img src="/img/add.png" alt="Add Image" title="Add Image">
			<ul>
				<li><a href='/upload'>Add from Computer</a></li>
				<li><a href='' id='addInternet'>Add from Internet</a></li>
			</ul>
		</li>
		<li id='save_image' class='{if !$data.save}not_{/if}saved' title='Save Image'></li>
		<li id="share_image"><img src="/img/share.png" alt="Share Image" title='Share Image'>
			<ul>
				<li><a>Facebook</a></li>
				<li><a>Google+</a></li>
				<li><a>Twitter</a></li>
			</ul>
		</li>
		<li id="report_image" title='Report Image'><img src="/img/report.png" alt="Report Image"></li>
		<li><input id='tag_search' placeholder='View by tag'></li>
		<li id="menu"><img src="/img/menu.png" alt="Menu" title='Menu'>
			<ul>
				<li><a>Android App</a></li>
                <li><a>Suggestions/Bugs</a></li>
                <li><a>Keyboard Shortcuts</a></li>
                <li><a href='' id='logout'>Logout</a></li>
			</ul>
		</li>

	</ul>
</nav>