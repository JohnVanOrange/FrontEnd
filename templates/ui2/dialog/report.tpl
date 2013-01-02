<div id='report_dialog' class='dialog'>
<form>
 {foreach from=$report_types item=report}
  <input type='radio' name='report_type' value='{$report.id}'>{$report.value}<br>
 {/foreach}
 <span class='submit'><button type='submit'>Report</button></span>
</form>
</div>