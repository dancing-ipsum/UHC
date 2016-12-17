<?php

namespace sys\jordan;

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

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use sys\jordan\basefiles\BossBar;
use sys\jordan\language\LanguageManager;
use sys\jordan\managers\ArenaManager;
use sys\jordan\managers\CommandManager;
use sys\jordan\managers\EssentialManager;
use sys\jordan\managers\EventManager;
use sys\jordan\managers\LevelManager;
use sys\jordan\managers\ScenarioManager;
use sys\jordan\managers\TeamManager;
use sys\jordan\managers\UHCManager;
use sys\jordan\task\BarTask;
use sys\jordan\task\BroadcastTask;
use sys\jordan\utils\GoldenHead;


class Central extends PluginBase{

	const PREFIX = TextFormat::RESET.TextFormat::DARK_GRAY.TextFormat::BOLD."[".TextFormat::RESET.TextFormat::BLUE."Walterion".TextFormat::DARK_GRAY.TextFormat::BOLD."]".TextFormat::RESET;
	public static $BRAND_PREFIX = TextFormat::RESET.TextFormat::DARK_GRAY.TextFormat::BOLD."[".TextFormat::RESET.TextFormat::BLUE."Walterion".TextFormat::DARK_GRAY.TextFormat::BOLD."]".TextFormat::RESET;
	public static $TWITTER = TextFormat::BLUE."@WalterionPE";

	public $isBeta = true;

	/** @var ArenaManager */
	private $arenaManager;

	/** @var EventManager */
	private $eventManager;

	/** @var EssentialManager */
	private $essentialManager;

	/** @var ScenarioManager */
	private $scenarioManager;

	/** @var LanguageManager */
	private $languageManager;

	/** @var LevelManager */
	private $levelManager;

	/** @var TeamManager */
	private $teamManager;

	/** @var Timer */
	private $timer;

	/** @var UHCManager */
	private $uhcManager;

	public function onEnable(){
		$this->setTimer();
		$this->setManagers();
		$this->loadListeners();
		$this->getServer()->getLogger()->info(TextFormat::GREEN."The core has been enabled!");
	}

	public function onDisable(){
		$this->getServer()->getLogger()->info(TextFormat::RED."The core has been disabled!");
	}

	/**
	 * @return ArenaManager
	 */
	public function getArenaManager(){
		return $this->arenaManager;
	}

	/**
	 * @return EssentialManager
	 */
	public function getEssentialManager(){
		return $this->essentialManager;
	}

	/**
	 * @return EventManager
	 */
	public function getEventManager(){
		return $this->eventManager;
	}

	/**
	 * @return LanguageManager
	 */
	public function getLanguageManager(){
		return $this->languageManager;
	}

	/**
	 * @return LevelManager
	 */
	public function getLevelManager(){
		return $this->levelManager;
	}

	/**
	 * @return ScenarioManager
	 */
	public function getScenarioManager(){
		return $this->scenarioManager;
	}

	/**
	 * @return TeamManager
	 */
	public function getTeamManager(){
		return $this->teamManager;
	}

	/**
	 * @return Timer
	 */
	public function getTimer(){
		return $this->timer;
	}

	public function getUHCManager(){
		return $this->uhcManager;
	}

	public function setTimer(){
		$this->timer = new Timer($this);
	}

	/**
	 * @return ParadoxPlayer[]
	 */
	public function getPlayers(){
		return $this->getUHCManager()->getPlayers();
	}

	public function setEssentials(){
		new BarTask($this);
		new BroadcastTask($this);
	}

	public function getBossBar(){
		return new BossBar($this);
	}

	private function setManagers(){
		$this->arenaManager = new ArenaManager($this);
		new CommandManager($this);
		$this->setEssentials();
		$this->essentialManager = new EssentialManager($this);
		$this->eventManager = new EventManager($this);
		$this->languageManager = new LanguageManager($this);
		$this->levelManager = new LevelManager($this);
		$this->scenarioManager = new ScenarioManager($this);
		$this->teamManager = new TeamManager($this);
		$this->uhcManager = new UHCManager($this);
	}

	public function loadListeners(){
		new ScenarioListener($this);
		new MainListener($this);
		new PlayerListener($this);
	}


}
