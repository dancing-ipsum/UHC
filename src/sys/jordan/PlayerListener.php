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


use pocketmine\block\Block;
use pocketmine\entity\Effect;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\LeavesDecayEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use sys\jordan\event\EventChangeEvent;
use sys\jordan\managers\EventManager;
use sys\jordan\task\DeathTask;

class PlayerListener implements Listener {

	public $plugin;

	public function __construct(Central $plugin){
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function onDamage(EntityDamageEvent $event){
		$entity = $event->getEntity();
		if($event instanceof EntityDamageByEntityEvent){
			$damager = $event->getDamager();
			if(($entity instanceof ParadoxPlayer) && ($damager instanceof ParadoxPlayer)){
				if(!$this->plugin->getEventManager()->canHit()){
					if((!$entity->inArena()) && (!$damager->inArena())){
						$event->setCancelled();
					}
				}
			}
		}
		if($event->getCause() == EntityDamageEvent::CAUSE_FALL){
			if(($entity instanceof Player)){
				if($this->plugin->getEventManager()->getEvent() <= 1){
					$event->setCancelled();
				}
			}
		}
		if($entity->getLevel() == $this->plugin->getLevelManager()->getSpawnLevel()){
			$event->setCancelled();
		}
	}

	public function onDeath(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		if($player instanceof ParadoxPlayer) {
			if($player->getLevel() == $this->plugin->getLevelManager()->getArena() && $this->plugin->getEventManager()->getEvent() <= 0){
				$event->setDeathMessage(null);
				$event->setDrops([Item::get(Item::GOLDEN_APPLE, 2, 1)->setCustomName(TextFormat::GOLD."Golden Head")]);
				return true;
			}
			if($this->plugin->getEventManager()->canHit() && $player->getLevel() == $this->plugin->getLevelManager()->getLevel()){
				$ev = $player->getLastDamageCause();
				if($ev instanceof EntityDamageByEntityEvent) {
					$damager = $ev->getDamager();
					if($damager instanceof Player) {
						$this->plugin->getEssentialManager()->addKill($damager);
						$event->setDeathMessage(Central::PREFIX.TextFormat::GOLD.$player->getDisplayName().TextFormat::GRAY." has been eliminated by ".TextFormat::GOLD.$damager->getDisplayName().TextFormat::GRAY."!");
					}
				} else {
					$event->setDeathMessage(Central::PREFIX.TextFormat::GOLD.$player->getDisplayName().TextFormat::GRAY." has been eliminated!");
				}
			}
			if($this->plugin->getEventManager()->getEvent() <= 0 && !$player->inArena()){
				return true;
			} else {
				if(!$this->plugin->getScenarioManager()->getScenarioByName("Timebomb")->isActive()){
					$this->plugin->getEssentialManager()->spawnHeadPole($player);
				}
				$this->plugin->getEssentialManager()->checkKill($player);
				if (!$player->hasPermission("paradox.permissions.spectate")) {
					new DeathTask($this->plugin, $player);
					$player->sendMessage(TextFormat::GRAY."You have 30 seconds to bid your farewells and wish everyone a good game!");

				}
			}
		}
		return true;
	}

	public function onRespawn(PlayerRespawnEvent $event){
		$player = $event->getPlayer();
		if($player instanceof ParadoxPlayer) {
			if ($player->inArena()) {
				$x = mt_rand(-500, 500);
				$z = mt_rand(-500, 500);
				$event->setRespawnPosition(new Position($x, 75, $z, $this->plugin->getLevelManager()->getArena()));
				$player->getInventory()->addItem(Item::get(Item::IRON_SWORD), Item::get(Item::BOW), Item::get(Item::GOLDEN_APPLE), Item::get(Item::ARROW, 0, 64));
				$player->getInventory()->setArmorContents([Item::get(Item::IRON_HELMET), Item::get(Item::IRON_CHESTPLATE), Item::get(Item::IRON_LEGGINGS), Item::get(Item::IRON_BOOTS)]);
			}
		}
	}
	public function onCreation(PlayerCreationEvent $event){
		$event->setPlayerClass(ParadoxPlayer::class);
	}

	public function eventChecker(Player $player){
		if($player->getLevel() == $this->plugin->getLevelManager()->getSpawnLevel() && (!$player->hasPermission("paradox.permissions.bypass"))) return true;
		if($this->plugin->getEventManager()->getEvent() < 2 && !($player->hasPermission("paradox.permissions.bypass"))) return true;
		return false;
	}



}