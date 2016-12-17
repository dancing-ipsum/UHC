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

namespace sys\jordan\basefiles;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\inventory\FurnaceBurnEvent;
use pocketmine\event\player\PlayerDeathEvent;
use sys\jordan\Central;
use sys\jordan\event\EventChangeEvent;

class Scenario implements ScenarioInterface{

	/** @var Central */
	private $central;

	/**@var bool $active*/
	private $active;

	/** @var string $name */
	private $name;

	/** @var string $aliases */
	private $description;

	/** @var string[] $aliases */
	private $aliases;

	/** @var bool $default */
	private $default;

	/**
	 * Scenario constructor.
	 * @param Central $central
	 * @param string $name
	 * @param string $description
	 * @param string[] $aliases
	 * @param bool $default
	 */
	public function __construct(Central $central, string $name, string $description, array $aliases, bool $default = false){
		$this->central = $central;
		$this->name = $name;
		$this->description = $description;
		$this->aliases = $aliases;
		$this->default = $default;
		if($default) {
			$this->active = true;
		} else {
			$this->active = false;
		}
	}

	/**
	 * @return Central
	 */
	public function getCentral(){
		return $this->central;
	}

	/**
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDescription(){
		return $this->description;
	}

	/**
	 * @return string[]
	 */
	public function getAliases(){
		return $this->aliases;
	}

	/**
	 * @param string[] $aliases
	 */
	public function setAliases(array $aliases){
		$this->aliases = $aliases;
	}

	/**
	 * @return bool
	 */
	public function isDefault(){
		return $this->default;
	}

	/**
	 * @return bool
	 */
	public function isActive(){
		return $this->active;
	}

	public function stringMatches(string $string){
		$string = strtolower($string);
		foreach($this->getAliases() as $alias){
			if(($string == strtolower($this->getName())) or ($string == strtolower($alias))){
				return true;
			}
		}
		return false;
	}

	/**
	 * @param bool $value
	 */
	public function setActive(bool $value){
		$this->active = $value;
	}

	public function onDeath(PlayerDeathEvent $event){
		// Execute death events
	}

	public function onBreak(BlockBreakEvent $event){
		// Execute break events
	}

	public function onChange(EventChangeEvent $event){
		// Execute change events
	}

	public function onDamage(EntityDamageEvent $event){
		// Execute damage events
	}

	public function onEntityDeath(EntityDeathEvent $event){
		// Execute entity death events
	}

	public function onCraft(CraftItemEvent $event){
		// Execute crafting events
	}

	public function onBurn(FurnaceBurnEvent $event){
		// Execute furnace events
	}
}