<?php
namespace sys\jordan\managers;

use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\TextFormat;
use sys\jordan\Central;
use sys\jordan\event\EventChangeEvent;
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

class EventManager {

	const NULL = -1;
	/*EVENT CONSTANTS*/
	const PRE = 0;
	const COUNTDOWN = 1;
	const GRACE = 2;
	const PVP = 3;
	const FIRST_TP = 4;
	const SECOND_TP = 5;
	const END = 6;

	public $event = self::PRE;

	public $kills = [];

	public function __construct(Central $plugin){
		$this->plugin = $plugin;
	}

	public function getEvent(){
		return $this->event;
	}

	public function isEvent(int $event){
		return $this->event === $event;
	}

	public function setEvent(int $event){
		$this->plugin->getServer()->getPluginManager()->callEvent($ev = new EventChangeEvent($this->event, $event));
		if($ev->isCancelled()){
			return;
		}
		$this->event = $event;
	}

	public function canHit(){
		return $this->event > 2;
	}

	public function stop(){
		$this->setEvent(self::PRE);
		$this->plugin->getTimer()->border = 1000;
		foreach($this->plugin->getPlayers() as $player){
			$level = $this->plugin->getLevelManager()->getSpawnLevel();
			$level->loadChunk($level->getSafeSpawn()->x >> 4, $level->getSafeSpawn()->z >> 4);
			if($player->inArena()){
				$this->plugin->getArenaManager()->removeFromArena($player);
			}
			$player->teleport($level->getSafeSpawn());
			$player->getInventory()->clearAll();
			$player->removeAllEffects();
			$player->extinguish();
			$player->setHealth($player->getMaxHealth());
			$player->setFood($player->getMaxFood());
			$player->teleport($this->plugin->getLevelManager()->getSpawnLevel()->getSpawnLocation());
			$player->setXpLevel(0);
			$player->setXpProgress(0);
		}
	}

	public function start(){
		$this->setEvent(self::COUNTDOWN);
		foreach($this->plugin->getPlayers() as $player){
				if(isset($this->plugin->getLevelManager()->arenaPlayers[$player->getName()])){
					unset($this->plugin->getLevelManager()->arenaPlayers[$player->getName()]);
				}
				$player->getInventory()->clearAll();
				$player->removeAllEffects();
				$player->extinguish();
				$player->setHealth($player->getMaxHealth());
				$player->setFood($player->getMaxFood());
				$player->setXpLevel(0);
				$player->setXpProgress(0);
				$level = $this->plugin->getLevelManager()->getSpawnLevel();
				$level->loadChunk($level->getSafeSpawn()->x >> 4, $level->getSafeSpawn()->z >> 4);
				$player->teleport($level->getSafeSpawn());
		}
	}

	public function uhcActive(PlayerPreLoginEvent $event){
		if($this->getEvent() > 0) {
			$lastText = "Follow ".Central::$TWITTER.TextFormat::GRAY." for upcoming UHCs!";
			$middleText = Utils::centerText(TextFormat::GRAY."There is a UHC going on right now!", $lastText);
			$topText = Utils::centerText(Central::$BRAND_PREFIX, $middleText);
			$event->getPlayer()->close("Not in the UHC!", $topText."\n".$middleText."\n".$lastText);
		}
	}

}