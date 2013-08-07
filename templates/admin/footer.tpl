<footer>
    <a href="/tos">Legal</a> | 
    <a href="/privacy">Privacy</a> | 
    &copy;{$smarty.now|date_format:"%Y"} {if isset($show_jvon)}<a href='/jvon'>{/if}John VanOrange Network{if isset($show_jvon)}</a>{/if}
</footer>

<script type='text/javascript'>
 var web_root = '{$web_root}';
 var site_name = '{$site_name}';
 var icon_set = '{$icon_set}';
</script>

<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js'></script>
<script src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js'></script>
<script async src='{$web_root}components/history.js/scripts/bundled/html5/jquery.history.js'></script>
<script src='{$web_root}components/noty/js/noty/jquery.noty.js'></script>
<script src='{$web_root}components/noty/js/noty/layouts/topLeft.js'></script>
<script src='{$web_root}components/noty/js/noty/themes/default.js'></script>
<script src='{$web_root}js/ui2.js?20130325'></script>
<script src='{$web_root}js/admin.js'></script>

</body>
</html>