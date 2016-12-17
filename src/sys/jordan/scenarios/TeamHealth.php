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
use sys\jordan\basefiles\Scenario;
use sys\jordan\Central;
use sys\jordan\ParadoxPlayer;

class TeamHealth extends Scenario{

	public function __construct(Central $plugin){
		parent::__construct($plugin, "Team Health", "Every player on a team shares health.", ["th"]);
	}

	public function onDamage(EntityDamageEvent $event){
		if($this->getCentral()->getTeamManager()->isTeamsEnabled()){
			$entity = $event->getEntity();
			if($entity instanceof ParadoxPlayer){
				$team = $entity->getTeam()->getMembers();
				foreach($team as $player){
					if($player === $entity){
						continue;
					}
					$player->attack($event->getFinalDamage(), $event);
				}
			}
		}
	}

}