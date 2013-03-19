<?php 

defined('C5_EXECUTE') or die(_("Access Denied."));

class KapowPackage extends Package {

	protected $pkgHandle = 'kapow';
	protected $appVersionRequired = '5.5.0b1';
	protected $pkgVersion = '1.1';
	
	public function getPackageDescription() {
		return t("Adds kaPoW puzzles as a captcha alternative.");
	}
	
	public function getPackageName() {
		return t("kaPoW");
	}
	
	public function install() {
		$pkg = parent::install();
		Loader::model('system/captcha/library');
		SystemCaptchaLibrary::add('kaPoW', t('kaPoW'), $pkg);		
	}
	
}
