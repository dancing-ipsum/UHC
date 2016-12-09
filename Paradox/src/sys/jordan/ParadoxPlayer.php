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

namespace sys\jordan;

use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\network\SourceInterface;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\PluginException;
use pocketmine\utils\TextFormat;
use sys\jordan\utils\Utils;

class ParadoxPlayer extends Player {

	const GLOBAL = "global";
	const TEAM = "team";

	/** @var Central */
	private $plugin = null;

	/** @var string */
	private $lang = "en";

	/** @var bool */
	private $chatMode = "global";

	/** @var Config */
	private $statFile;

	/**
	 * Make sure the core plugin is enabled before an instance is constructed
	 *
	 * @param SourceInterface $interface
	 * @param null $clientID
	 * @param string $ip
	 * @param int $port
	 */
	public function __construct(SourceInterface $interface, $clientID, $ip, $port) {
		parent::__construct($interface, $clientID, $ip, $port);
		if(($plugin = $this->getServer()->getPluginManager()->getPlugin("ParadoxHosted")) instanceof Central and $plugin->isEnabled()) {
			$this->plugin = $plugin;
		} else {
			$this->kick(TextFormat::RED."Error!");
			throw new PluginException("The Paradox Core isn't loaded!");
		}
	}

	/**
	 * @return Central
	 */
	public function getPlugin() {
		return $this->plugin;
	}

	/**
	 * @return int
	 */
	public function getKills(){
		return $this->getPlugin()->getEssentialManager()->getKills($this);
	}

	/**
	 * @return string
	 */
	public function getLanguageAbbreviation() {
		return $this->lang;
	}

	/**
	 * @param string $lang
	 */
	public function setLanguageAbbreviation($lang = "en") {
		$this->lang = $lang;
	}

	public function getStatFile(){
		return $this->statFile;
	}

	/**
	 * @param $key
	 * @param array $args
	 * @param bool $isImportant
	 */
	public function sendTranslatedMessage($key, array $args = [], $isImportant = false) {
		$this->sendMessage($this->getPlugin()->getLanguageManager()->translateForPlayer($this, $key, $args), $isImportant);
	}

	/**
	 * @param \pocketmine\event\TextContainer|string $message
	 * @param bool $isImportant
	 *
	 * @return bool
	 */
	public function sendMessage($message, $isImportant = false) {
		parent::sendMessage($message);
		return true;
	}

	/**
	 * @return bool
	 */
	public function inTeam(){
		return $this->getPlugin()->getTeamManager()->getTeam($this) !== null;
	}

	public function getTeam(){
		return $this->getPlugin()->getTeamManager()->getTeam($this);
	}

	/**
	 * @return bool
	 */
	public function inArena(){
		return $this->getPlugin()->getArenaManager()->inArena($this);
	}

	/**
	 * @return bool
	 */
	public function inUHC(){
		return $this->getPlugin()->getUHCManager()->inUHC($this);
	}

	/**
	 * @param int $range
	 * @param Level $level
	 */
	public function scatter(int $range, Level $level){
		$position = Utils::randomizeCoordinates($range, $level);
		$this->teleport($position);
	}

	public function loadStats(){
		if(!file_exists($this->getPlugin()->getDataFolder()."players/".strtolower($this->getName()).".stats")) {
			$this->statFile = new Config($this->getPlugin()->getDataFolder()."players/".strtolower($this->getName()).".stats", Config::DETECT, array(
				"Kills"=> 0,
				"Deaths"=> 0,
				"Wins"=> 0,
				"Heads"=> 0,
				"Diamonds"=> 0,
				"Gold"=> 0,
				"Iron"=> 0,
				"Lapis"=> 0,
				"Redstone"=> 0
			));
			$this->statFile->save();
		}
		$this->statFile = new Config($this->getPlugin()->getDataFolder()."players/".strtolower($this->getName()).".stats");
	}

	/**
	 * @param string $stat
	 * @return bool|mixed
	 */
	public function getStat(string $stat){
		return $this->statFile->get($stat);
	}

	/**
	 * @param string $stat
	 */
	public function addStat(string $stat){
		$this->getStatFile()->set($stat, $this->getStat($stat) + 1);
		$this->getStatFile()->save();
	}
}