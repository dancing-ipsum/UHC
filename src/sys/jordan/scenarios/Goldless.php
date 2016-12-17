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

class Goldless extends Scenario{

	public function __construct(Central $plugin){
		parent::__construct($plugin, "Goldless", "Gold ore drops nothing when mined.", ["gl"]);
	}

	public function onBreak(BlockBreakEvent $event){
		switch($event->getBlock()->getId()){
			case Block::GOLD_ORE:
				$event->setDrops([]);
		}
	}

}