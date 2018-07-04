<div class="list_entities_wrapper">
<?php if (!empty($mtemplate)) { ?>
<script id="mustache-template" type="x-tmpl-mustache"><?= $mtemplate; ?></script>
<?php } ?>
<div id="list_entities" class="content" data-baseurl="<?= $atts['url']; ?>" data-url="<?= $url; ?>" data-entity="<?= $entity; ?>" data-filters="<?= $filters; ?>" <?= $pagination; ?> <?= $limit; ?>>
	<div class="row top">
		<?php if (!is_null($filters_input)) { ?>
		<form id="filters_input"><span class='toggle_filters'></span>
			<?php
			$filters_input_col = explode(";",$filters_input);
			foreach ($filters_input_col as $filters_input_el) {
				$aux_el = explode(",",$filters_input_el);
				switch($aux_el[1])
				{
					case 'text':
					echo "<input id='".$aux_el[0]."' type='text' placeholder='".$aux_el[2]."'>";
					break;

					case 'select':
					echo "<select id='".$aux_el[0]."'>";
					$aux_opts = explode("+",$aux_el[2]);
					foreach ($aux_opts as $aux_opt) {
						$aux_aux_opt = explode(":",$aux_opt);
						echo "<option value='".$aux_aux_opt[0]."'>".$aux_aux_opt[1]."</input>";
					}

					echo "</select>";
					break;
					case 'submit':
					echo "<button id=".$aux_el[0]." type='submit'>".$aux_el[2]."</button>";
					break;
				}
			}
			?>
		</form>
		<?php } ?>
	</div>
	<?php if (!is_null($pagination)) { ?>
	<div class="pagination top"><button type="button" id="page-before"><</button><span class="numEntities"><span class="from"></span><span class="to"></span><span class="outOf"></span></span><button type="button" id="page-after">></button></div>
	<?php } ?>
	<div id="loading" class="spinner" style="display:none;"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>

	<div class="list_entities"></div>

	<?php if (!is_null($pagination)) { ?>
	<div class="pagination bottom"><button type="button" id="page-before"><</button><span class="numEntities"><span class="from"></span><span class="to"></span><span class="outOf"></span></span><button type="button" id="page-after">></button></div>
	<?php } ?>
</div>
