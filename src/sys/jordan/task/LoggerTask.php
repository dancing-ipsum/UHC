<?php

namespace sys\jordan\task;
use pocketmine\entity\Human;
use pocketmine\utils\TextFormat;
use sys\jordan\ParadoxPlayer;
use sys\jordan\utils\BossEventPacket;
use pocketmine\scheduler\PluginTask;
use sys\jordan\Central;

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

class LoggerTask extends PluginTask{

	public $plugin;
	private $player;
	public $time = 60;

	public function __construct(Central $owner, ParadoxPlayer $player){
		parent::__construct($owner);
		$this->plugin = $owner;
		$this->player = $player;
		$this->getOwner()->getServer()->getScheduler()->scheduleDelayedRepeatingTask($this, 0, 100);
	}

	/**
	 * Actions to execute when run
	 *
	 * @param $currentTick
	 *
	 * @return void
	 */
	public function onRun($currentTick){
		$this->time--;
		if($this->time == 0){
			$logger = $this->plugin->getEssentialManager()->loggerTask[$this->player->getName()];
			if ($this->plugin->getServer()->isWhitelisted($this->player->getName())) {
				$this->plugin->getServer()->getWhitelisted()->remove(strtolower($this->player->getName()));
				$this->plugin->getServer()->getWhitelisted()->save();
			}
			if(isset($this->plugin->getEssentialManager()->players[$this->player->getName()])){
				unset($this->plugin->getEssentialManager()->players[$this->player->getName()]);
			}
			$entity = $this->player->getLevel()->getEntity($logger[1]);
			if($entity instanceof Human){
				$entity->kill();
			}
			$this->plugin->getServer()->broadcastMessage(Central::PREFIX.TextFormat::GOLD." ".$this->player->getName()." has died due to combat logger!");
			$this->cancel();
		}
	}

	public function cancel(){
		$task = $this->getTaskId();
		$this->plugin->getServer()->getScheduler()->cancelTask($task);
	}
}