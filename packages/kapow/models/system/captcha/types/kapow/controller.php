<?

class KapowSystemCaptchaTypeController extends SystemCaptchaTypeController {
	
	public function saveOptions($args) {
		$pkg = Package::getByHandle('kapow');
		$pkg->saveConfig('KAPOW_PUBLIC_KEY', $args['KAPOW_PUBLIC_KEY']);
		$pkg->saveConfig('KAPOW_PRIVATE_KEY', $args['KAPOW_PRIVATE_KEY']);
	}

	public function display() {
		$pkg = Package::getByHandle('kapow');
		Loader::library('3rdparty/headwinds2lib', 'kapow');
		$procURL = Loader::helper('concrete/urls')->getToolsURL('process_url', 'kapow');
		echo initialize_kapow($procURL)
	}
	
	public function label() {
		$form = Loader::helper('form');
		print $form->label('captcha', t('Verify yourself.'));
	}
	
	public function showInput() {}

	public function check() {
		$pkg = Package::getByHandle('kapow');
		Loader::library('3rdparty/headwinds2lib.php', 'kapow');
		echo verify()
	}

}
