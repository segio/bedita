{assign_associative var="cssOptions" inline=false}
{$html->css("tree", null, $cssOptions)}
{$html->script("jquery.treeview", false)}
{$html->script("jquery.changealert", false)}


{assign var="p" value=$beToolbar->params}
{assign var="toolbarstring" value=$p.named}


{$view->element('modulesmenu')}

{include file="inc/menuleft.tpl"}

{include file="inc/menucommands.tpl"}

{$view->element('toolbar')}

<div class="mainfull">

	{include file="inc/list_objects.tpl"}
	
</div>

