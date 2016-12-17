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
use pocketmine\event\inventory\FurnaceSmeltEvent;
use pocketmine\event\player\PlayerDeathEvent;
use sys\jordan\event\EventChangeEvent;

interface ScenarioInterface{

	public function onDamage(EntityDamageEvent $event);

	public function onDeath(PlayerDeathEvent $event);

	public function onBreak(BlockBreakEvent $event);

	public function onChange(EventChangeEvent $event);

	public function onEntityDeath(EntityDeathEvent $event);

	public function onCraft(CraftItemEvent $event);

	public function onBurn(FurnaceBurnEvent $event);

}