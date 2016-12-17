<?php
namespace sys\jordan\managers;
use pocketmine\level\Level;
use pocketmine\utils\MainLogger;
use sys\jordan\Central;
use sys\jordan\ParadoxPlayer;

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

class LevelManager {

	private $plugin;

	/** @var Level */
	public $level;

	/** @var Level */
	public $arenaLevel;

	/** @var Level */
	public $spawnLevel;

	/** @var ParadoxPlayer[] */
	public $arenaPlayers = [];

	public function __construct(Central $plugin){
		$this->plugin = $plugin;
		$this->loadLevels();
	}

	public function getLevel(){
		return $this->level;
	}

	public function getArena(){
		return $this->arenaLevel;
	}

	public function getSpawnLevel(){
		return $this->spawnLevel;
	}

	public function loadLevels(){
		$this->plugin->getServer()->loadLevel("hubuhc");
		$this->spawnLevel = $this->plugin->getServer()->getLevelByName("hubuhc");
		$this->plugin->getServer()->loadLevel("UHC");
		$this->level = $this->plugin->getServer()->getLevelByName("UHC");
		$this->plugin->getServer()->loadLevel("Arena");
		$this->arenaLevel = $this->plugin->getServer()->getLevelByName("Arena");
	}

	public function loadChunkInLevel(Level $level){
		for($i = 1; $i< 400; $i +=16){
			for($j = 1; $j< 400; $j+= 16){
				if(!$level->isChunkLoaded($i, $j)){
					$level->loadChunk($i << 4, $j << 4);
				}
			}
		}
	}

	public function setLevel(string $name){
		if($this->plugin->getServer()->isLevelLoaded($name)){
			$this->level = $this->plugin->getServer()->getLevelByName($name);
			return true;
		}
		try{
			if(is_dir($this->plugin->getServer()->getDataPath()."worlds".DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR)) {
				$this->plugin->getServer()->loadLevel($name);
				$this->level = $this->plugin->getServer()->getLevelByName($name);
				return true;
			} else {
				return false;
			}
		}catch(\Throwable $e){
			if($this->plugin->getLogger() instanceof MainLogger){
				$this->plugin->getLogger()->logException($e);
			}
		}
	}

}