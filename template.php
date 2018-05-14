<?php if (!is_null($mtemplate)) { ?>
<script>
<?= 'var mustache_template = `'.$mtemplate.'`;'; ?>
</script>
<?php } ?>
<div id="list_entities" class="content" data-baseurl="<?= $atts['url']; ?>" data-url="<?= $url; ?>" data-entity="<?= $entity; ?>" <?= $pagination; ?> <?= $limit; ?>>
	<div class="row top">
		<?php if (!is_null($pagination)) { ?>
		<button id="page-before"><</button><button id="page-after">></button>
		<?php } ?>
	</div>

	<div id="loading" class="spinner" style="display:none;"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>

	<div class="list_entities"></div>
</div>
