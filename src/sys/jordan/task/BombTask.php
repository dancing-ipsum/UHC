<?php

namespace sys\jordan\task;
use pocketmine\level\Explosion;
use pocketmine\level\Position;
use pocketmine\utils\TextFormat;
use sys\jordan\ParadoxPlayer;
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

class BombTask extends PluginTask{

	public $plugin;
	private $pos;
	private $player;
	private $time = 31;

	public function __construct(Central $owner, ParadoxPlayer $player, Position $position){
		parent::__construct($owner);
		$this->plugin = $owner;
		$this->player = $player;
		$this->pos = $position;
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
		$this->time--;
		if($this->time == 0){
			$this->getOwner()->getServer()->broadcastMessage(TextFormat::BLUE."[Timebomb] ".TextFormat::GOLD.$this->player->getDisplayName().TextFormat::GOLD."'s corpse has exploded!");
			$ex = new Explosion($this->pos, 4);
			$ex->explodeB();
			$this->cancel();
		}
	}

	public function cancel(){
		$this->plugin->getServer()->getScheduler()->cancelTask($this->getTaskId());
	}
}