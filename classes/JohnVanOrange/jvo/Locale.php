<?php
namespace JohnVanOrange\jvo;

class Locale {
	
	public static function get() {
		$locale = \Browser\Language::getLanguageLocale('_');

		putenv('LC_ALL=' . $locale);
		setlocale(LC_ALL, $locale);
		bindtextdomain('messages', ROOT_DIR.'/locale');
		bind_textdomain_codeset('messages', 'UTF-8');
		return $locale;
	}
}