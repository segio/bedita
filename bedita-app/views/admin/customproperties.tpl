{$html->css('module.superadmin')}
{$javascript->link("form", false)}
{$javascript->link("jquery/jquery.form", false)}
{$javascript->link("jquery/jquery.cmxforms", false)}
{$javascript->link("jquery/jquery.metadata", false)}
{$javascript->link("jquery/jquery.validate", false)}
{$javascript->link("jquery/jquery.changealert", false)}

</head>

<body>

{include file="../common_inc/modulesmenu.tpl"}

{include file="inc/menuleft.tpl" method="customproperties"}

<div class="head">
	<div class="toolbar" style="white-space:nowrap">
	<h2>{t}Custom properties{/t}</h2>
	
	{*include file="./inc/toolbar.tpl" label_items='custom properties'*}
	</div>
</div>


{include file="inc/menucommands.tpl" method="customproperties" fixed=false}

<div class="mainfull">


	<table class="indexlist">
		<tr>
			<th>property name</th>
			<th>for object type</th>
			<th>data type</th>
			<th>option list</th>
			<th>data attributes</th>
			<th></th>
		</tr>
		
		<tr id="row_0">
			<td>
				<input type="text" value="" name="" />
			</td>
			<td>
				<select>
				{foreach from=$conf->objectTypes key="key" item="objectTypes"}
				{if !empty($objectTypes.model) && is_numeric($key)}
					<option value="{$objectTypes.id}" class="{$objectTypes.model|lower}" style="padding-left:5px"> {$objectTypes.model|lower}</option>
				{/if}
				{/foreach}
				</select>
				
			</td>
			<td>
				<select class="datatype">
					<option>number</option>
					<option>date</option>
					<option>text</option>
					<option>options</option>
				</select>
			</td>
			<td class="optd">
				<input ctype="text" value="" name="" />
			</td>
 			<td class="optd">
				<input type="checkbox" /> multiple choice
			</td>
			<td>
				<input type="submit" value="{t}save{/t}" />
			</td>
		</tr>
			
	</table>
</div>

