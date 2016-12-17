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

class Barebones extends Scenario{

	public function __construct(Central $plugin){
		parent::__construct($plugin, "Barebones", "Gold and diamond now drop iron.", ["bb"]);
	}

	public function onBreak(BlockBreakEvent $event){
		switch($event->getBlock()->getId()){
			case Block::DIAMOND_ORE:
			case Block::GOLD_ORE:
				if($this->getCentral()->getScenarioManager()->getScenarioByName("Cutclean")->isActive()) {
					$event->setDrops([Item::get(Item::IRON_INGOT)]);
				} else {
					$event->setDrops([Item::get(Item::IRON_ORE)]);
				}
				break;
		}
	}

}