<?php

namespace sys\jordan\task;
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

class BarTask extends PluginTask{

	public $plugin;

	public function __construct(Central $owner){
		parent::__construct($owner);
		$this->plugin = $owner;
		$this->getOwner()->getServer()->getScheduler()->scheduleDelayedRepeatingTask($this, 0, 20);
	}

	/**
	 * Actions to execute when run
	 *
	 * @param $currentTick
	 *
	 * @return void
	 */
	public function onRun($currentTick){
		foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
			$this->plugin->getBossBar()->moveMob($player);
		}
	}
}