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
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\level\ChunkLoadEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\item\WoodenPickaxe;
use sys\jordan\basefiles\Scenario;
use sys\jordan\Central;
use sys\jordan\event\EventChangeEvent;
use sys\jordan\managers\EventManager;
use sys\jordan\ParadoxPlayer;

class SpeedMode extends Scenario{



	public function __construct(Central $plugin){
		parent::__construct($plugin, "Speed Mode", "Speed and haste are applied as well as many crafting recipes are changed to speed up the game!", ["sm"]);
	}

	public function onChange(EventChangeEvent $event){
		if($event->getAfterEvent() == EventManager::GRACE){
			foreach($this->getCentral()->getPlayers() as $player){
				$player->addEffect(Effect::getEffect(Effect::SPEED)->setDuration(1000000)->setVisible(false));
				$player->addEffect(Effect::getEffect(Effect::HASTE)->setDuration(1000000)->setVisible(false));
			}
		}
	}

	public function onCraft(CraftItemEvent $event){
		$item = $event->getRecipe()->getResult();
		$player = $event->getPlayer();
		if(($player instanceof ParadoxPlayer) and ($player->inUHC()) and ($item instanceof Tool)) {
			$new_item = $this->getNextTier($item);
			if($new_item !== 0) {
				$new_item->addEnchantment(Enchantment::getEnchantment(Enchantment::TYPE_MINING_EFFICIENCY)->setLevel(3));
				$item->setCount(0);
			}
		}
	}

	/**
	 * @param Tool $item
	 * @return Item|void
	 */
	public function getNextTier(Tool $item){
		$i = 0;
			switch($item->getId()){
				case Tool::WOODEN_PICKAXE:
					$i = Item::STONE_PICKAXE;
					break;
				case Tool::GOLD_PICKAXE:
					$i  = Item::STONE_PICKAXE;
					break;
				case Tool::STONE_PICKAXE:
					$i = Item::IRON_PICKAXE;
					break;
				case Tool::IRON_PICKAXE:
					$i = Item::DIAMOND_PICKAXE;
					break;
				case Tool::WOODEN_AXE:
					$i = Item::STONE_AXE;
					break;
				case Tool::GOLD_AXE:
					$i  = Item::STONE_AXE;
					break;
				case Tool::STONE_AXE:
					$i = Item::IRON_AXE;
					break;
				case Tool::IRON_AXE:
					$i = Item::DIAMOND_AXE;
					break;
				case Tool::WOODEN_SHOVEL:
					$i = Item::STONE_SHOVEL;
					break;
				case Tool::GOLD_SHOVEL:
					$i  = Item::STONE_SHOVEL;
					break;
				case Tool::STONE_SHOVEL:
					$i = Item::IRON_SHOVEL;
					break;
				case Tool::IRON_SHOVEL:
					$i = Item::DIAMOND_SHOVEL;
					break;
			}
		return Item::get($i);
	}

}