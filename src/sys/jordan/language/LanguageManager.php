<?php
/**
 * _____        _____            _____   ______   __
 *|  __ \ /\   |  __ \     /\   |  __ \ / __ \ \ / /
 *| |__) /  \  | |__) |   /  \  | |  | | |  | \ V /
 *|  ___/ /\ \ |  _  /   / /\ \ | |  | | |  | |> <
 *| |  / ____ \| | \ \  / ____ \| |__| | |__| / . \
 *|_| /_/    \_\_|  \_\/_/    \_\_____/ \____/_/ \_\
 *
 * @author JordanSystems
 */

namespace sys\jordan\language;


use pocketmine\utils\Config;
use sys\jordan\Central;
use sys\jordan\ParadoxPlayer;
use sys\jordan\utils\Utils;

class LanguageManager {

	const MESSAGE_DIRECTORY = "messages" . DIRECTORY_SEPARATOR;

	private static $messageFiles = ["en" => "english.json", "es" => "spanish.json"];

	/** @var array */
	public $messages = [];

	/** @var Central */
	private $plugin = null;

	/** @var string */
	private $path = "";

	public function __construct(Central $plugin) {
		$this->plugin = $plugin;
		$this->path = $plugin->getDataFolder();
	}

	/**
	 * @param ParadoxPlayer $player
	 * @param $key
	 * @param array $args
	 *
	 * @return mixed|null
	 */
	public function translateForPlayer(ParadoxPlayer $player, $key, $args = []) {
		return $this->translate($key, $player->getLanguageAbbreviation(), $args);
	}

	/**
	 * @param $key
	 * @param string $lang
	 * @param array $args
	 *
	 * @return mixed|null
	 */
	public function translate($key, $lang = "en", $args = []) {
		if(!$this->isLanguage($lang)) $lang = "en";
		try {
			$message = $this->messages[$lang][$key];
			if(is_array($message)) $message = $message[array_rand($message)];

			return self::argumentsToString($message, $args);
		} catch(\Throwable $e) {
			$this->plugin->getLogger()->debug("Couldn't find message key '{$key}' for language '{$lang}'" . PHP_EOL . "Error: {$e->getMessage()}");
		}

		return "";
	}

	/**
	 * @param $string
	 *
	 * @return bool
	 */
	public function isLanguage($string) {
		return isset($this->messages[$string]);
	}

	/**
	 * @param string $string
	 * @param array $args
	 *
	 * @return string
	 */
	public static function argumentsToString($string, $args = []) {
		foreach($args as $key => $data) {
			$string = str_replace("{args" . (string)((int)$key + 1) . "}", $data, $string);
		}

		return $string;
	}

	/**
	 * Load all the messages and apply colors so less work is done while the server is actually running
	 */
	protected function loadMessages() {
		$path = $this->path . self::MESSAGE_DIRECTORY;
		if(!is_dir($path)) @mkdir($path);
		foreach(self::$messageFiles as $langKey => $fileName) {
			$this->plugin->saveResource(self::MESSAGE_DIRECTORY . $fileName);
			$file = $path . $fileName;
			$this->registerLanguage($langKey, (new Config($file, Config::JSON))->getAll());
		}
	}

	/**
	 * Load a language into the existing ones
	 *
	 * @param $lang
	 * @param $data
	 */
	public function registerLanguage($lang, $data) {
		foreach($data as $key => $message) {
			try {
				$this->registerMessage($lang, $key, Utils::translateColors($message));
			} catch(\Throwable $e) {
				$this->plugin->getLogger()->debug("Error while parsing message '{$key}' from '{$lang}'!" . PHP_EOL . "Error: {$e->getMessage()}");
			}
		}
	}

	/**
	 * Register a message into an existing language
	 *
	 * @param string $lang
	 * @param string $key
	 * @param string|array $message
	 */
	public function registerMessage($lang, $key, $message) {
		$this->messages[$lang][$key] = Utils::translateColors($message);
	}

}