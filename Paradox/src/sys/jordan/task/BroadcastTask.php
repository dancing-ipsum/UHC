<?php

namespace sys\jordan\task;
use pocketmine\utils\TextFormat;
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

class BroadcastTask extends PluginTask{

	public $plugin;

	public function __construct(Central $owner){
		parent::__construct($owner);
		$this->plugin = $owner;
		$this->getOwner()->getServer()->getScheduler()->scheduleRepeatingTask($this, 20 * 60 * 15);
	}

	/**
	 * Actions to execute when run
	 *
	 * @param $currentTick
	 *
	 * @return void
	 */
	public function onRun($currentTick){
		$messages = ["Use /scenarios to see the scenarios that are active!", "Check out @ParadoxTwitUHC for upcoming UHCs!", "Use /stats to check out your stats!", "Report any bugs to admins so we can fix them!", "Want to buy a rank? Visit store.paradoxuhc.net to purchase one!"];
		$rand = array_rand($messages, 1);
		$returnedMessage = $messages[$rand];
		$this->getOwner()->getServer()->broadcastMessage(TextFormat::BLUE."[TIP] ".TextFormat::GRAY.$returnedMessage);
	}
}