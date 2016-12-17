<?php
namespace sys\jordan\managers;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\PigZombie;
use pocketmine\entity\Zombie;
use pocketmine\inventory\BigShapedRecipe;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Skull;
use pocketmine\tile\Tile;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\utils\UUID;
use sys\jordan\Central;
use sys\jordan\ParadoxPlayer;
use sys\jordan\task\LoggerTask;
use sys\jordan\utils\GoldenHead;

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

class EssentialManager {

	public $relog = [];
	public $kills = [];

	public $loggerTask = [];

	/** @var ParadoxPlayer[] */
	public $players = [];

	/** @var bool */
	public $globalMute = false;

	/** @var Central */
	private $plugin;

	/** @var Config */
	private $config;

	public function __construct(Central $plugin){
		$this->plugin = $plugin;
		$this->initEssentials();
	}

	public function getPlugin(){
		return $this->plugin;
	}

	private function initEssentials(){
		if(!is_dir($this->getPlugin()->getDataFolder())) {
			@mkdir($this->plugin->getDataFolder());
			@mkdir($this->plugin->getDataFolder() . "players/");
			$this->config = new Config($this->getPlugin()->getDataFolder().DIRECTORY_SEPARATOR."config.json", Config::JSON, array(
				"#UHC CORE CONFIGURATION BY IRISH",
				"NAME"=>"UHC",
				"TWITTER"=>"@UHC",
				"HUB_LEVEL"=>"Hub",
				"DEFAULT_UHC_LEVEL"=>"UHC",
				"ARENA_LEVEL"=>"Arena"
			));
		} else {
			$this->config = new Config($this->getPlugin()->getDataFolder().DIRECTORY_SEPARATOR."config.json");
		}
		Central::$BRAND_PREFIX = str_replace("UHC", $this->config->get("NAME"), Central::$BRAND_PREFIX);
		Central::$TWITTER = $this->config->get("TWITTER");
		Item::$list[Item::GOLDEN_APPLE] = GoldenHead::class;
	}

	public function getConfig(){
		return $this->config;
	}

	/**
	 * @param ParadoxPlayer|Player $player
	 */
	public function savePosition(ParadoxPlayer $player){
		if($this->plugin->getEventManager()->getEvent() >= 1 && $this->getPlugin()->getUHCManager()->inUHC($player)) {
			if (!isset($this->relog[$player->getName()])) {
				$this->relog[$player->getName()] = clone $player->getPosition();
			}
		}
	}

	/**
	 * @param ParadoxPlayer $player
	 */
	public function teleportToPosition(ParadoxPlayer $player){
		if (isset($this->relog[$player->getName()])) {
			$player->teleport($this->relog[$player->getName()]);
			unset($this->relog[$player->getName()]);
		}
	}

	/**
	 * @param ParadoxPlayer $player
	 */
	public function addKill(ParadoxPlayer $player){
		$this->kills[$player->getName()] = $this->kills[$player->getName()] + 1;
		$player->addStat("Kills");
	}

	/**
	 * @param ParadoxPlayer $player
	 * @return int
	 */
	public function getKills(ParadoxPlayer $player){
		if(!isset($this->kills[$player->getName()])){
			$this->kills[$player->getName()] = 0;
		}
		return $this->kills[$player->getName()];
	}

	public function checkKill(ParadoxPlayer $player){
		if(isset($this->loggerTask[$player->getName()])){
			if(isset($this->plugin->getEssentialManager()->players[$player->getName()])){
				unset($this->plugin->getEssentialManager()->players[$player->getName()]);
			}
			if ($this->plugin->getServer()->isWhitelisted($player->getName())) {
				$this->plugin->getServer()->getWhitelisted()->remove(strtolower($player->getName()));
				$this->plugin->getServer()->getWhitelisted()->save();
			}
			$task = $this->loggerTask[$player->getName()];
			$this->getPlugin()->getServer()->getScheduler()->cancelTask($task[0]);
			unset($this->loggerTask[$player->getName()]);
		}
	}


	public function finalHeal(){
		foreach($this->plugin->getPlayers() as $player){
			$player->setHealth($player->getMaxHealth());
			$player->setFood($player->getMaxFood());
		}
		$this->broadcastMessage(TextFormat::GREEN." Everyone has been healed and fed!");
	}

	/**
	 * @param Item[] ...$items
	 */
	public function giveAllItems(...$items){
		foreach ($this->plugin->getPlayers() as $player) {
			foreach ($items as $item) {
				$player->getInventory()->addItem($item);
			}
		}
	}

	/**
	 * @param string $message
	 */
	public function broadcastMessage(string $message){
		foreach($this->getPlayers() as $player){
			$player->sendMessage($message);
		}
	}

	/**
	 * @param string $message
	 */
	public function broadcastPopup(string $message){
		foreach($this->getPlayers() as $player){
			$player->sendPopup($message);
		}
	}

	/**
	 * @return bool
	 */
	public function getGlobalMute(){
		return $this->globalMute;
	}

	/**
	 * @param bool $value
	 */
	public function setGlobalMute($value = true){
		$this->globalMute = $value;
	}

	public function spawnHeadPole(ParadoxPlayer $player){
		$level = $player->getLevel();
		$pos = $player->getPosition();
		$level->setBlock($pos->add(0, 1), Block::get(Block::SKULL_BLOCK), true);
		$level->setBlock($pos, Block::get(Block::NETHER_BRICK_FENCE));
		$chunk = $level->getChunk($pos->x >> 4, $pos->x >> 4);
		$nbt = new CompoundTag("", [
			new StringTag("id", Tile::SKULL),
			new ByteTag("SkullType", 3),
			new IntTag("x", $pos->x),
			new IntTag("y", $pos->y + 1),
			new IntTag("z", $pos->z),
			new ByteTag("Rot", 0)
		]);
		$tile = Tile::createTile("Skull", $chunk, $nbt);
		$level->addTile($tile);
	}

	/**
	 * @return int
	 */
	public function getPlayerCount(){
		return count($this->plugin->getUHCManager()->getPlayers());
	}



	/**
	 * @return ParadoxPlayer[]
	 */
	public function getPlayers(){
		return $this->plugin->getUHCManager()->getPlayers();
	}

}