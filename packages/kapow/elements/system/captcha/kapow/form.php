<?php 

defined('C5_EXECUTE') or die(_("Access Denied."));

$form = Loader::helper('form');
$pkg = Package::getByHandle('kapow');
?>

<div class="clearfix">
	<?=$form->label('KAPOW_PUBLIC_KEY', t('Public Key'))?>
	<div class="input">
		<?=$form->text('KAPOW_PUBLIC_KEY', $pkg->config('KAPOW_PUBLIC_KEY'), array('class' => 'span6'))?>
	</div>
</div>

<div class="clearfix">
	<?=$form->label('KAPOW_PRIVATE_KEY', t('Private Key'))?>
	<div class="input">
		<?=$form->text('KAPOW_PRIVATE_KEY', $pkg->config('KAPOW_PRIVATE_KEY'), array('class' => 'span6'))?>
	</div>
</div>
