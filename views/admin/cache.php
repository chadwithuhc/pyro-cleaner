<div id="cleaner_m" class="cache_cleaner">

<h1>Cache Cleaner</h1>

<p>Clean certain parts of the cache . If you have recently made changes to a page and they are not showing up, you may need to clean the cache.</p>

<? if (isset($files) && count($files) > 0) : ?>

<?= form_open('admin/cleaner/cache/clean'); ?>
<div id="file_folders" class="clearfix">
	<p>Select the folders / files you wish to clean:</p>
	<? foreach ($files as $key => $value) : ?>
		<label><?= form_checkbox($key, $key, TRUE); ?> <?= ucwords(str_replace('_m', '', $key)) ?></label><br/>
	<? endforeach; ?>
	<br/><a href="#" id="file_toggle">View filelist</a>
</div>
<pre id="file_array"><? print_r($files); ?></pre>

<p class="buttons">
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save' => 'clean', 'cancel') )); ?>
</p>

<?= form_close(); ?>

<script type="text/javascript">
jQuery(function($) {
	var file_array = $('#file_array');
	var toggler = $('#file_toggle').click(function(e) {
		e.preventDefault();
		file_array.slideToggle();
	});
});
</script>

<? else : // no cache files ?>

	<div class="blank-slate">
		<h2><?php echo lang('cleaner.no_cache_folders');?></h2>
	</div>

<? endif; // end files ?>

</div>