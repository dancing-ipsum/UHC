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

namespace sys\jordan;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use sys\jordan\event\EventChangeEvent;

class ScenarioListener implements Listener{

	public $plugin;

	public function __construct(Central $plugin){
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function onDeath(PlayerDeathEvent $event){
		$this->plugin->getScenarioManager()->handleDeath($event);
	}

	public function onBreak(BlockBreakEvent $event){
		$this->plugin->getScenarioManager()->handleBreak($event);
	}

	public function onChange(EventChangeEvent $event){
		$this->plugin->getScenarioManager()->handleChange($event);
	}

	public function onDamage(EntityDamageEvent $event){
		$this->plugin->getScenarioManager()->handleDamage($event);
	}

	public function onEntityDeath(EntityDeathEvent $event){
		$this->plugin->getScenarioManager()->handleEntityDeath($event);
	}

	public function onCraft(CraftItemEvent $event){
		$this->plugin->getScenarioManager()->handleCraft($event);
	}

}