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
use pocketmine\tile\Chest;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;
use sys\jordan\basefiles\Scenario;
use sys\jordan\Central;
use sys\jordan\ParadoxPlayer;
use sys\jordan\task\BombTask;

class Timebomb extends Scenario{

	public function __construct(Central $plugin){
		parent::__construct($plugin, "Timebomb", "On death, a double chest will spawn at the player's position!", ["tb"]);
	}

	public function onDeath(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		if($player instanceof ParadoxPlayer && $player->inUHC() and $player->getLevel() == $this->getCentral()->getLevelManager()->getLevel()){
			$chest1 = Block::get(Block::CHEST, 0, $player->getPosition());
			$chest1->place(Item::get(Item::CHEST), $chest1, $chest1, 0, 0, 0, 0);
			$chest1->setDamage(0);
			$player->getPosition()->getLevel()->setBlock($chest1, $chest1, false, false);
			$chest2 = Block::get(Block::CHEST, 0, $player->getPosition()->add(0, 0, 1));
			$chest2->place(Item::get(Item::CHEST), $chest2, $chest2, 0, 0, 0, 0);
			$player->getPosition()->getLevel()->setBlock($chest2, $chest2, false, false);
			$tile = $player->getLevel()->getTile($player->getPosition());
			if($tile instanceof Chest){
				$tile2 = $player->getLevel()->getTile($player->getPosition()->add(0, 0, 1));
				if($tile2 instanceof Chest){
					$event->setDrops([]);
					$tile->pairWith($tile2);
					$tile2->pairWith($tile);
					$tile->getDoubleInventory()->setContents($player->getInventory()->getContents());
					$tile2->getDoubleInventory()->addItem($player->getInventory()->getHelmet(),$player->getInventory()->getChestplate(),
						$player->getInventory()->getLeggings(),$player->getInventory()->getBoots(), Item::get(Item::GOLDEN_APPLE, 2)->setCustomName(TextFormat::GOLD."Golden Head"));
				}
			}
			new BombTask($this->getCentral(), $player, $player->getPosition());
		}
	}

}