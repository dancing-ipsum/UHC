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
use pocketmine\entity\Sheep;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use sys\jordan\basefiles\Scenario;
use sys\jordan\Central;

class Cutclean extends Scenario{

	public $plugin;

	public function __construct(Central $plugin){
		parent::__construct($plugin, "Cutclean", "Smelts ores into ingots", ["cc"], true);
		$this->plugin = $plugin;
	}

	public function onBreak(BlockBreakEvent $event){
		switch($event->getBlock()->getId()){
			case Block::IRON_ORE:
				if(!$event->isCancelled()) {
					$event->getBlock()->getLevel()->spawnXPOrb(new Vector3($event->getBlock()->getX(), $event->getBlock()->getY(), $event->getBlock()->getZ()), mt_rand(2, 6));
				}
				$event->setDrops([Item::get(Item::IRON_INGOT)]);
				break;
			case Block::GOLD_ORE:
				if(!$event->isCancelled()) {
					$event->getBlock()->getLevel()->spawnXPOrb(new Vector3($event->getBlock()->getX(), $event->getBlock()->getY(), $event->getBlock()->getZ()), mt_rand(4, 10));
				}
				$event->setDrops([Item::get(Item::GOLD_INGOT)]);
				break;
			case Block::LEAVES:
				if(mt_rand(0, 30) == 15) $event->setDrops([Item::get(Item::APPLE)]);
		}
	}

	public function onEntityDeath(EntityDeathEvent $event){
		$entity = $event->getEntity();
		$drops = ["cow" => [Item::get(Item::COOKED_BEEF, 0, mt_rand(2, 4)), Item::get(Item::LEATHER, 0, mt_rand(0, 3))],
			"pig" => [Item::get(Item::COOKED_PORKCHOP, 0, mt_rand(2, 4))],
			"rabbit" => [Item::get(Item::COOKED_RABBIT, 0, mt_rand(2, 4))],
			"chicken" => [Item::get(Item::COOKED_CHICKEN, 0, mt_rand(2, 4))]];
		if($entity instanceof Sheep) {
			$color = $entity->getColor();
			$drops["sheep"] = [Item::get(Item::COOKED_MUTTON, 0, mt_rand(2, 4)), Item::get(Item::WOOL, $color, mt_rand(0, 3))];
		}
		foreach($drops as $key=>$value){
			if(strtolower($entity->getName()) === $key){
				$event->setDrops($value);
			}
		}
	}

}