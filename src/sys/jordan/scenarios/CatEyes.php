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

class CatEyes extends Scenario{

	public function __construct(Central $plugin){
		parent::__construct($plugin, "Cat-Eyes", "Players receive night-vision for the whole game!", ["ce"]);
	}

	public function onChange(EventChangeEvent $event){
		if($event->getAfterEvent() === EventManager::GRACE){
			foreach($this->getCentral()->getPlayers() as $player){
				$player->addEffect(Effect::getEffect(Effect::NIGHT_VISION)->setDuration(1000000)->setVisible(false));
			}
		}
	}

}