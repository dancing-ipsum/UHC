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
use pocketmine\entity\Effect;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Item;
use sys\jordan\basefiles\Scenario;
use sys\jordan\Central;
use sys\jordan\event\EventChangeEvent;
use sys\jordan\managers\EventManager;

class InfiniteEnchanter extends Scenario{

	public function __construct(Central $plugin){
		parent::__construct($plugin, "Infinite Enchanter", "Players receive 10,000 XP levels as well as the necessities for enchanting!", ["ie"]);
	}

	public function onChange(EventChangeEvent $event){
		if($event->getAfterEvent() === EventManager::GRACE){
			foreach($this->getCentral()->getPlayers() as $player){
				$player->setXpLevel(10000);
				$player->getInventory()->addItem(Item::get(Item::ENCHANTMENT_TABLE, 0, 64), Item::get(Item::LAPIS_BLOCK, 0, 64));
			}
		}
	}

}