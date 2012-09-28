{*
** nesletter view template
*}

{assign_associative var="cssOptions" inline=false}
{$this->Html->css("ui.datepicker", null, $cssOptions)}
{$this->Html->css("jquery.timepicker.css", null, $cssOptions)}

{$this->Html->script("jquery/jquery.form", false)}
{$this->Html->script("jquery/jquery.selectboxes.pack", false)}

{$this->Html->script("jquery/ui/jquery.ui.sortable", true)}
{$this->Html->script("jquery/ui/jquery.ui.datepicker", false)}
{if $currLang != "eng"}
	{$this->Html->script("jquery/ui/i18n/ui.datepicker-$currLang.js", false)}
{/if}

{$this->Html->script("jquery/jquery.placement.below")}
{$this->Html->script("jquery/jquery.timepicker-list")}

{$this->Html->script("jquery/jquery.validate")}

<script type="text/javascript">
	$(document).ready(function () {
		openAtStart("#contents, #invoice");
		$("#timeStart, #timeEnd").timePicker({ startTime: "00:00", endTime: "23:30" });
		$("#updateForm").validate();
	});
</script>

{$view->element('form_common_js')}


{$view->element('modulesmenu')}

{include file="inc/menuleft.tpl" method="view"}

<div class="head">
	
	<h1>{if !empty($object)}{$object.title|default:"<i>[no title]</i>"}{else}<i>[{t}New item{/t}]</i>{/if}</h1>
	
</div>

{assign var=objIndex value=0}

{include file="inc/menucommands.tpl" method="view" fixed = true}

<div class="main">	
	
	{include file="inc/form.tpl"}
		
</div>

{$view->element('menuright')}