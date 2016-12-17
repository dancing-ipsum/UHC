<?php
namespace sys\jordan\scenarios;
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

use pocketmine\entity\Effect;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\Player;
use sys\jordan\basefiles\Scenario;
use sys\jordan\Central;

class Fireless extends Scenario{

	public function __construct(Central $plugin){
		parent::__construct($plugin, "Fireless", "Neglect taking damage from fire", ["fl"]);
	}

	public function onDamage(EntityDamageEvent $event){
		$entity = $event->getEntity();
		if($entity instanceof Player){
			if ($event->getCause() === EntityDamageEvent::CAUSE_FIRE or $event->getCause() === EntityDamageEvent::CAUSE_FIRE_TICK or $event->getCause() === EntityDamageEvent::CAUSE_LAVA){
				$event->setCancelled();
				$entity->extinguish();
			}
		}
	}

}