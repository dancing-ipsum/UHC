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

namespace sys\jordan\managers;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\player\PlayerDeathEvent;
use sys\jordan\basefiles\Scenario;
use sys\jordan\Central;
use sys\jordan\event\EventChangeEvent;
use sys\jordan\scenarios\Barebones;
use sys\jordan\scenarios\CatEyes;
use sys\jordan\scenarios\Cutclean;
use sys\jordan\scenarios\Diamondless;
use sys\jordan\scenarios\FastSmelt;
use sys\jordan\scenarios\Fireless;
use sys\jordan\scenarios\Goldless;
use sys\jordan\scenarios\NoFall;
use sys\jordan\scenarios\SpeedMode;
use sys\jordan\scenarios\Timebomb;

class ScenarioManager{

	private $plugin;
	/** @var Scenario[] */
	public $scenarios = [];

	/**
	 * ScenarioManager constructor.
	 * @param Central $plugin
	 */
	public function __construct(Central $plugin){
		$this->plugin = $plugin;
		$this->setScenarios();
	}

	public function addScenario(Scenario $scenario){
		$this->scenarios[$scenario->getName()] = $scenario;
	}


	/**
	 * @return Scenario[]
	 */
	public function getScenarios(){
		return $this->scenarios;
	}

	public function scenariosEmpty(){
		foreach($this->getScenarios() as $scenario){
			if($scenario->isActive()){
				return false;
			}
		}
		return true;
	}

	public function scenariosFull(){
		foreach($this->getScenarios() as $scenario){
			if(!$scenario->isActive()){
				return false;
			}
		}
		return true;
	}

	public function getScenarioByName(string $name){
		$name = strtolower($name);
		foreach($this->scenarios as $scenario){
			if(strtolower($scenario->getName()) === $name){
				return $scenario;
			}
		}
		return null;
	}

	private function setScenarios(){
		$this->addScenario(new Cutclean($this->plugin));
		$this->addScenario(new Diamondless($this->plugin));
		$this->addScenario(new Goldless($this->plugin));
		$this->addScenario(new Barebones($this->plugin));
		$this->addScenario(new NoFall($this->plugin));
		$this->addScenario(new Fireless($this->plugin));
		$this->addScenario(new SpeedMode($this->plugin));
		$this->addScenario(new FastSmelt($this->plugin));
		$this->addScenario(new Timebomb($this->plugin));
		$this->addScenario(new CatEyes($this->plugin));
	}

	public function handleDeath(PlayerDeathEvent $event){
		if($this->plugin->getEventManager()->getEvent() > 0) {
			foreach ($this->getScenarios() as $scenario) {
				try {
					if ($scenario->isActive()) {
						$scenario->onDeath($event);
					}
				} catch (\Exception $exception) {

				}
			}
		}
	}

	public function handleBreak(BlockBreakEvent $event){
		if($this->plugin->getEventManager()->getEvent() > 0) {
			foreach ($this->getScenarios() as $scenario) {
				try {
					if ($scenario->isActive()) {
						$scenario->onBreak($event);
					}
				} catch (\Exception $exception) {

				}
			}
		}
	}

	public function handleChange(EventChangeEvent $event){
		if($this->plugin->getEventManager()->getEvent() > 0) {
			foreach ($this->getScenarios() as $scenario) {
				try {
					if ($scenario->isActive()) {
						$scenario->onChange($event);
					}
				} catch (\Exception $exception) {

				}
			}
		}
	}

	public function handleDamage(EntityDamageEvent $event){
		if($this->plugin->getEventManager()->getEvent() > 0) {
			foreach ($this->getScenarios() as $scenario) {
				try {
					if ($scenario->isActive()) {
						$scenario->onDamage($event);
					}
				} catch (\Exception $exception) {

				}
			}
		}
	}

	public function handleEntityDeath(EntityDeathEvent $event){
		if($this->plugin->getEventManager()->getEvent() > 0) {
			foreach ($this->getScenarios() as $scenario) {
				try {
					if ($scenario->isActive()) {
						$scenario->onEntityDeath($event);
					}
				} catch (\Exception $exception) {

				}
			}
		}
	}

	public function handleCraft(CraftItemEvent $event){
		if($this->plugin->getEventManager()->getEvent() > 0) {
			foreach ($this->getScenarios() as $scenario) {
				try {
					if ($scenario->isActive()) {
						$scenario->onCraft($event);
					}
				} catch (\Exception $exception) {

				}
			}
		}
	}
}