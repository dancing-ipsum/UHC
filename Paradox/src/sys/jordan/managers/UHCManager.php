<?php
namespace sys\jordan\managers;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\Player;
use sys\jordan\Central;
use sys\jordan\ParadoxPlayer;
use sys\jordan\utils\Utils;

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
class UHCManager{

	private $totalPlayers = [];
	private $players = [];
	private $plugin;

	/**
	 * @var Level
	 */
	private $level;

	public function __construct(Central $plugin){
		$this->plugin = $plugin;
		$this->level = $this->plugin->getLevelManager()->getLevel();
	}

	/**
	 * @return Central
	 */
	public function getPlugin() {
		return $this->plugin;
	}

	public function getUHCLevel(){
		return $this->level;
	}

	/**
	 * @return ParadoxPlayer[]
	 */
	public function getPlayers(){
		return $this->players;
	}

	/**
	 * @param ParadoxPlayer|Player $player
	 */
	public function addPlayer(ParadoxPlayer $player){
		if($this->getPlugin()->getEventManager()->getEvent() > 0){
			if(isset($this->totalPlayers[$player->getName()])){
				$this->players[$player->getName()] = $player;
			}
		} else {
			if(!isset($this->totalPlayers[$player->getName()])){
				$this->totalPlayers[$player->getName()] = $player;
			}

		}
	}

	/**
	 * @param ParadoxPlayer|Player $player
	 */
	public function removePlayer(ParadoxPlayer $player){
		if($this->plugin->getEventManager()->getEvent() > 0){
			if($player->inUHC()){
				unset($this->players[$player->getName()]);
			}
		} else {
			if(isset($this->totalPlayers[$player->getName()])){
				unset($this->totalPlayers[$player->getName()]);
			}
			if(isset($this->players[$player->getName()])){
				unset($this->players[$player->getName()]);
			}
		}
		if ($player->inArena()) {
			$this->getPlugin()->getArenaManager()->removeFromArena($player);
		}
	}

	/**
	 * @param ParadoxPlayer $player
	 * @return bool
	 */
	public function inUHC(ParadoxPlayer $player){
		return isset($this->players[$player->getName()]);
	}
}