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
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
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

class MainListener implements Listener{

	public $plugin;

	public function __construct(Central $plugin){
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function onBreak(BlockBreakEvent $event){
		$player = $event->getPlayer();
		if($this->eventChecker($player)) $event->setCancelled();
	}

	public function onPlace(BlockPlaceEvent $event){
		$player = $event->getPlayer();
		if($this->eventChecker($player)) $event->setCancelled();
	}

	public function onBucketPlace(PlayerBucketEmptyEvent $event){
		$player = $event->getPlayer();
		if($this->eventChecker($player)) $event->setCancelled();
	}

	public function onBucketFill(PlayerBucketFillEvent $event){
		$player = $event->getPlayer();
		if($this->eventChecker($player)) $event->setCancelled();
	}


	public function onRegen(EntityRegainHealthEvent $event){
		$entity = $event->getEntity();
		if($entity instanceof Player){
			switch($event->getRegainReason()){
				case EntityRegainHealthEvent::CAUSE_SATURATION:
					$event->setCancelled();
					break;
			}
		}
	}

	public function onPreLogin(PlayerPreLoginEvent $event){
		if(!$event->getPlayer()->isWhitelisted()) {
			$this->plugin->getEventManager()->uhcActive($event);
		}
	}

	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		if($player->getGamemode() === Player::SURVIVAL){
			$this->plugin->getUHCManager()->addPlayer($player);
		}
		if($player instanceof ParadoxPlayer) {
			$player->loadStats();
			if($this->plugin->getTeamManager()->isTeamsEnabled()){
				if($player->inTeam()){
					$player->setDisplayName($this->plugin->getTeamManager()->getTeam($player)->getTeamString().$player->getDisplayName());
				}
			}
			if($this->plugin->getEventManager()->getEvent() <= 0){
				$player->teleport($this->plugin->getLevelManager()->getSpawnLevel()->getSafeSpawn());
			}
			$this->plugin->getEssentialManager()->teleportToPosition($player);
			$this->plugin->getBossBar()->setMob($player);
		}
		$event->setJoinMessage(TextFormat::DARK_GRAY.TextFormat::BOLD."[".TextFormat::RESET.TextFormat::GREEN."»".TextFormat::DARK_GRAY.TextFormat::BOLD."]".TextFormat::RESET.TextFormat::GREEN.$event->getPlayer()->getDisplayName());
	}

	public function onQuit(PlayerQuitEvent $event){
		$player = $event->getPlayer();
		$this->plugin->getEssentialManager()->savePosition($player);
		$this->plugin->getUHCManager()->removePlayer($player);
		$event->setQuitMessage(TextFormat::DARK_GRAY.TextFormat::BOLD."[".TextFormat::RESET.TextFormat::RED."«".TextFormat::DARK_GRAY.TextFormat::BOLD."]".TextFormat::RESET.TextFormat::RED.$event->getPlayer()->getDisplayName());
	}

	public function onKick(PlayerKickEvent $event){
		$player = $event->getPlayer();
		$this->plugin->getEssentialManager()->savePosition($player);
		$this->plugin->getUHCManager()->removePlayer($player);
	}

	public function onGameModeChange(PlayerGameModeChangeEvent $event){
		if($event->getNewGamemode() === Player::SURVIVAL){
			$this->plugin->getUHCManager()->addPlayer($event->getPlayer());
		} else {
			$this->plugin->getUHCManager()->removePlayer($event->getPlayer());
		}
	}

	public function onSpawn(EntitySpawnEvent $event){
		if($event->isHuman() or $event->isItem() or $event->isProjectile()){
			return;
		}
		if($event->getPosition()->getLevel() == $this->plugin->getLevelManager()->getArena()){
			$event->getEntity()->close();
		}
	}

	public function onDecay(LeavesDecayEvent $event){
		if($event->getBlock()->getId() == Block::LEAVES && (mt_rand(0, 50)) == 15 && $this->plugin->getEventManager()->getEvent() > 0 && $event->getBlock()->getLevel() !== $this->plugin->getLevelManager()->getArena()) {
			$event->getBlock()->getLevel()->setBlock(new Position($event->getBlock()->getX(), $event->getBlock()->getY(), $event->getBlock()->getZ(), $event->getBlock()->getLevel()), Block::get(Block::AIR));
			$event->getBlock()->getLevel()->dropItem(new Vector3($event->getBlock()->x, $event->getBlock()->y, $event->getBlock()->z), Item::get(Item::APPLE));
		}
	}

	public function onChange(EventChangeEvent $event){
		switch($event->getAfterEvent()){
			case EventManager::COUNTDOWN:
				$this->plugin->getEssentialManager()->setGlobalMute();
				$this->plugin->getEssentialManager()->broadcastMessage(Central::PREFIX.TextFormat::GREEN." Global mute has been enabled!");
				break;
			case EventManager::GRACE:
				$this->plugin->getEssentialManager()->giveAllItems(Item::get(Item::STEAK, 0, 16));
				$this->plugin->getEssentialManager()->broadcastMessage(Central::PREFIX.TextFormat::GREEN." Everyone has been given 16 steak!");
				break;
			case EventManager::PVP:
				$this->plugin->getEssentialManager()->setGlobalMute(false);
				$this->plugin->getEssentialManager()->broadcastMessage(Central::PREFIX.TextFormat::GREEN." Global mute has been disabled!");
				$this->plugin->getEssentialManager()->broadcastMessage(Central::PREFIX.TextFormat::GRAY."PvP has now been enabled!");
				break;
			case EventManager::FIRST_TP:
				$this->plugin->getEssentialManager()->broadcastMessage(Central::PREFIX.TextFormat::GRAY."The border will start shrinking in 5 minutes!");
				break;
			case EventManager::SECOND_TP:
				$this->plugin->getEssentialManager()->broadcastMessage(Central::PREFIX.TextFormat::GRAY."The border has shrank to 500x500. The next border shrink will happen in 10 minutes!");
				break;
			case EventManager::END:
				$this->plugin->getEssentialManager()->broadcastMessage(Central::PREFIX.TextFormat::GRAY."The border has shrank to 100x100. Good luck!");
		}
	}

	public function onChat(PlayerChatEvent $event){
		if($this->plugin->getEssentialManager()->getGlobalMute() && !($event->getPlayer()->hasPermission("paradox.permissions.bypass"))){
			$event->getPlayer()->sendMessage(Central::PREFIX.TextFormat::RED." You can't talk during the global mute!");
			$event->setCancelled();
		}
	}

	public function eventChecker(ParadoxPlayer $player){
		if($player->getLevel() == $this->plugin->getLevelManager()->getSpawnLevel() && (!$player->hasPermission("paradox.permissions.bypass"))) return true;
		if($this->plugin->getEventManager()->getEvent() < 2 && !($player->hasPermission("paradox.permissions.bypass"))) return true;
		return false;
	}



}