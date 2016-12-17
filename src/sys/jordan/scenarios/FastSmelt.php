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
use pocketmine\event\inventory\FurnaceBurnEvent;
use pocketmine\item\Item;
use sys\jordan\basefiles\Scenario;
use sys\jordan\Central;

class FastSmelt extends Scenario{

	public function __construct(Central $plugin){
		parent::__construct($plugin, "Fast-Smelt", "Furnaces now smelt much faster!", ["fs"]);
	}

	public function onBurn(FurnaceBurnEvent $event){
		if($event->getFuel()->getId() == Item::COAL) {
			$event->setBurnTime(40);
		}
		else {
			$event->setBurnTime(30);
		}
		$event->getFurnace()->namedtag["CookTime"] = 300;

	}

}