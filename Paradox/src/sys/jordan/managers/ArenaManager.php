<?php
namespace sys\jordan\managers;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\level\Level;
use sys\jordan\Central;
use sys\jordan\ParadoxPlayer;
use sys\jordan\utils\Utils;

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
class ArenaManager{

	private $arenaPlayers = [];
	private $plugin;

	/**
	 * @var Level
	 */
	private $arena;

	public function __construct(Central $plugin){
		$this->plugin = $plugin;
	}

	/**
	 * @return Central
	 */
	public function getPlugin() {
		return $this->plugin;
	}

	public function getArena(){
		return $this->plugin->getLevelManager()->getArena();
	}

	/**
	 * @param ParadoxPlayer $player
	 * @return bool
	 */
	public function inArena(ParadoxPlayer $player){
		return isset($this->arenaPlayers[$player->getName()]);
	}

	/**
	 * @return int
	 */
	public function getArenaPlayerCount(){
		return count($this->arenaPlayers);
	}

	/**
	 * @return ParadoxPlayer[]
	 */
	public function getArenaPlayers(){
		return $this->arenaPlayers;
	}

	public function addArenaItems(ParadoxPlayer $player){
		$armor = [Item::get(Item::IRON_HELMET)->addEnchantment(Enchantment::getEnchantment(Enchantment::TYPE_ARMOR_PROTECTION)->setLevel(2)),
			Item::get(Item::IRON_CHESTPLATE)->addEnchantment(Enchantment::getEnchantment(Enchantment::TYPE_ARMOR_PROTECTION)->setLevel(2)),
			Item::get(Item::IRON_LEGGINGS)->addEnchantment(Enchantment::getEnchantment(Enchantment::TYPE_ARMOR_PROTECTION)->setLevel(2)),
			Item::get(Item::IRON_BOOTS)->addEnchantment(Enchantment::getEnchantment(Enchantment::TYPE_ARMOR_PROTECTION)->setLevel(2))];
		$player->getInventory()->addItem(Item::get(Item::IRON_SWORD)->addEnchantment(Enchantment::getEnchantment(Enchantment::TYPE_WEAPON_SHARPNESS)->setLevel(2)),
			Item::get(Item::BOW)->addEnchantment(Enchantment::getEnchantment(Enchantment::TYPE_BOW_INFINITY)->setLevel(1)),
			Item::get(Item::GOLDEN_APPLE), Item::get(Item::ARROW, 0, 1));
		$player->getInventory()->setArmorContents($armor);
	}

	public function addToArena(ParadoxPlayer $player){
		if(($this->getPlugin()->getEventManager()->getEvent() <= 0) or ($this->getPlugin()->getEventManager()->getEvent() > 0 && !$this->getPlugin()->getUHCManager()->inUHC($player))) {
			if (!isset($this->arenaPlayers[$player->getName()])) {
				$this->arenaPlayers[$player->getName()] = $player;
				$player->teleport(Utils::randomizeCoordinates(500, $this->getArena()));
				$this->addArenaItems($player);
			}
		}
	}

	public function removeFromArena(ParadoxPlayer $player){
		if(isset($this->arenaPlayers[$player->getName()])) {
			unset($this->arenaPlayers[$player->getName()]);
		}
		$player->teleport($this->getPlugin()->getLevelManager()->getSpawnLevel()->getSafeSpawn());
		$player->getInventory()->clearAll();
		$player->setHealth($player->getMaxHealth());
		$player->setFood($player->getMaxFood());
		$player->removeAllEffects();
	}
}