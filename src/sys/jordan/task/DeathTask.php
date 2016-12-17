<?php

namespace sys\jordan\task;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;
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

class DeathTask extends PluginTask{

	public $plugin;
	public $player;

	public $time = 31;

	public function __construct(Central $owner, ParadoxPlayer $player){
		parent::__construct($owner);
		$this->getOwner()->getServer()->getScheduler()->scheduleDelayedRepeatingTask($this, 0, 20);
		$this->plugin = $owner;
		$this->player = $player;
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
		switch($this->time){
			case 30:
				$this->player->sendMessage(Central::$BRAND_PREFIX.TextFormat::GRAY."You will be kicked in 30 seconds!", true);
				break;
			case 15:
				$this->player->sendMessage(Central::$BRAND_PREFIX.TextFormat::GRAY."You will be kicked in 15 seconds!", true);
				break;
			case 5:
				$this->player->sendMessage(Central::$BRAND_PREFIX.TextFormat::GRAY."You will be kicked in 5 seconds!", true);
				break;
			case 4:
				$this->player->sendMessage(Central::$BRAND_PREFIX.TextFormat::GRAY."You will be kicked in 4 seconds!", true);
				break;
			case 3:
				$this->player->sendMessage(Central::$BRAND_PREFIX.TextFormat::GRAY."You will be kicked in 3 seconds!", true);
				break;
			case 2:
				$this->player->sendMessage(Central::$BRAND_PREFIX.TextFormat::GRAY."You will be kicked in 2 seconds!", true);
				break;
			case 1:
				$this->player->sendMessage(Central::$BRAND_PREFIX.TextFormat::GRAY."You will be kicked in 1 seconds!", true);
				break;
			case 0:
				$this->player->kick(Central::$BRAND_PREFIX."\n".TextFormat::GRAY."Thanks for playing on".Central::$TWITTER." !", false);
				$this->cancel();
		}
	}

	public function cancel(){
		$this->plugin->getServer()->getScheduler()->cancelTask($this->getTaskId());
	}
}