{*
Template incluso.
Menu a SX valido per tutte le pagine del controller.
*}

<div class="primacolonna">
	
	<div class="modules"><label class="bedita" rel="{$html->url('/')}">{$conf->projectName|default:$conf->userVersion}</label></div>
		
	<ul class="menuleft insidecol">
		<li {if $method eq 'index'}class="on"{/if}>{$tr->link('Users', '/admin/')}</li>
		<li {if $method eq 'viewUser' && (empty($userdetail))}class="on"{/if}>{$tr->link('New user', '/admin/viewUser')}</li>
		<li {if $method eq 'groups'}class="on"{/if}>{$tr->link('User groups', '/admin/groups')}</li>
	</ul>
	
	<ul class="menuleft insidecol">
		<li {if $method eq 'customproperties'}class="on"{/if}>{$tr->link('Custom properties', '/admin/customproperties')}</li>
	</ul>
	
	
	<ul class="menuleft insidecol">
		<li {if $method eq 'systemInfo'}class="on"{/if}>{$tr->link('System Info', '/admin/systemInfo')}</li>						
		<li {if $method eq 'systemEvents'}class="on"{/if}>{$tr->link('System Events', '/admin/systemEvents')}</li>						
	</ul>



	{include file="../common_inc/user_module_perms.tpl"}

</div>