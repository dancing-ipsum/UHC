<?php
namespace sys\jordan\basefiles;

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

use pocketmine\item\Item;
use pocketmine\network\protocol\AddPlayerPacket;
use sys\jordan\utils\BossEventPacket;
use pocketmine\network\protocol\MovePlayerPacket;
use pocketmine\network\protocol\SetEntityDataPacket;
use pocketmine\Player;
use pocketmine\network\protocol\RemoveEntityPacket;
use pocketmine\Server;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\entity\Entity;
use pocketmine\utils\UUID;
use sys\jordan\Central;

class BossBar {

	private $plugin;

	public function __construct(Central $plugin){
		$this->plugin = $plugin;
	}

	public function addBar(array $players = null){
		if(empty($players)){
			$players = $this->plugin->getPlayers();
		}
		$eid = Entity::$entityCount++;
		$aepkt = new AddEntityPacket();
		$aepkt->metadata = [Entity::DATA_LEAD_HOLDER => [Entity::DATA_TYPE_LONG, -1], Entity::DATA_FLAG_SILENT => [Entity::DATA_TYPE_BYTE, 1], Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0],
			Entity::DATA_BOUNDING_BOX_WIDTH => [Entity::DATA_TYPE_FLOAT, 0], Entity::DATA_BOUNDING_BOX_HEIGHT => [Entity::DATA_TYPE_FLOAT, 0]];
		$aepkt->eid = Entity::$entityCount++;
		$aepkt->type = 52;
		$aepkt->yaw = 0;
		$aepkt->pitch = 0;
		foreach($players as $player){
			$ppkt = clone $aepkt;
			$ppkt->x = $player->x;
			$ppkt->y = $player->y;
			$ppkt->z = $player->z;
			$player->dataPacket($ppkt);
		}
		$pk = new BossEventPacket();
		$pk->state = 0;
		$pk->eid = Entity::$entityCount++;
		Server::getInstance()->broadcastPacket($players, $pk);
		return $eid;
	}


	public function setTitle(int $eid, string $string){
		$players = $this->plugin->getServer()->getOnlinePlayers();
		$pk = new SetEntityDataPacket();
		$pk->metadata = [Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $string]];
		$pk->eid = $eid;
		Server::getInstance()->broadcastPacket($players, $pk);
		$pkt = new BossEventPacket();
		$pkt->eid = $eid;
		$pkt->state = 500;
		Server::getInstance()->broadcastPacket($players, $pkt);

	}

	public function moveMob(Player $player){
		$pk = new MovePlayerPacket();
		$pk->eid = 11000;
		$pk->x = $player->getX();
		$pk->y = $player->getY() - 5;
		$pk->z = $player->getZ();
		$player->dataPacket($pk);
		$this->setTitle(11000, $this->plugin->getTimer()->getEventMessage().$this->plugin->getTimer()->getTime());
	}

	public function removeMob(Player $player){
		$pk = new RemoveEntityPacket();
		$pk->eid = 11000;
		$player->dataPacket($pk);
	}

	public function setMob(Player $player){
		$pk = new AddPlayerPacket();
		$pk->uuid = UUID::fromRandom();
		$pk->x = $player->getX();
		$pk->y = $player->getY() - 5;
		$pk->z = $player->getZ();
		$pk->eid = 11000;
		$pk->speedX = 0;
		$pk->speedY = 0;
		$pk->speedZ = 0;
		$pk->yaw = 0;
		$pk->pitch = 0;
		$pk->item = Item::get(Item::AIR);
		$flags = 1 << Entity::DATA_FLAG_INVISIBLE;
		$flags |= 0 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
		$flags |= 0 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
		$flags |= 1 << Entity::DATA_FLAG_IMMOBILE;
		$pk->metadata = [Entity::DATA_FLAGS =>[Entity::DATA_TYPE_LONG, $flags],
			Entity::DATA_NAMETAG =>[Entity::DATA_TYPE_STRING, $this->plugin->getTimer()->getEventMessage().$this->plugin->getTimer()->getTime()],
			Entity::DATA_LEAD_HOLDER_EID => [Entity::DATA_TYPE_LONG, -1]];
		$player->dataPacket($pk);
		$pk = new BossEventPacket();
		$pk->eid = 11000;
		$player->dataPacket($pk);
	}

}