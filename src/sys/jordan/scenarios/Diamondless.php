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

namespace sys\jordan\scenarios;


use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Item;
use sys\jordan\basefiles\Scenario;
use sys\jordan\Central;

class Diamondless extends Scenario{

	public function __construct(Central $plugin){
		parent::__construct($plugin, "Diamondless", "Diamond ore drops nothing when mined.", ["dl"]);
	}

	public function onBreak(BlockBreakEvent $event){
		switch($event->getBlock()->getId()){
			case Block::DIAMOND_ORE:
				$event->setDrops([]);
		}
	}

}