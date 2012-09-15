<body>

<header>
    <h1>John VanOrange</h1>
</header>

<nav id="actionbar">

    <!--<div class="dropdown">
    <a class='menu'>Menu</a>


        <div class="submenu">
            <ul class="root">
                <li><a>Android App</a></li>
                <li><a>Suggestions/Bugs</a></li>
                <li><a>Keyboard Shortcuts</a></li>
                <li><a>Logout</a></li>
            </ul>
        </div>

    </div>-->
    <img src='http://www.gravatar.com/avatar/{$user.email_hash}?s=24&d=retro&r=pg'>
    <span id="username" title="View Profile"><a href='/s/'>{$user.username}</a></span>
    <span id="add_image" title="Add Image"><img src="/img/add.png" alt="Add Image"></span>
    <span id='save_image' class='{if !$data.save}not_{/if}saved' title='Save Image'></span>
    <span id="share_image" title="Share Image"><img src="/img/share.png" alt="Share Image"></span>
    <span id="report_image" title='Report Image'><img src="/img/report.png" alt="Report Image"></span>
    <input id='tag_search' placeholder='View by tag' />
    <span id="menu" title='Menu'><img src="/img/menu.png" alt="Menu"></span>

</nav>