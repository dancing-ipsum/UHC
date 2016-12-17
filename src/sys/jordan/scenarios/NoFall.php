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


use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\Player;
use sys\jordan\basefiles\Scenario;
use sys\jordan\Central;

class NoFall extends Scenario{

	public function __construct(Central $plugin){
		parent::__construct($plugin, "NoFall", "Neglect taking fall damage", ["nf"]);
	}

	public function onDamage(EntityDamageEvent $event){
		if($event->getEntity() instanceof Player){
			if ($event->getCause() === EntityDamageEvent::CAUSE_FALL){
				$event->setCancelled();
			}
		}
	}

}