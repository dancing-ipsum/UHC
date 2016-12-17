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


use pocketmine\entity\Effect;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use sys\jordan\Central;
use sys\jordan\ParadoxPlayer;
use sys\jordan\utils\Utils;

class Team{

	/** @var int */
	private $id;

	/**@var Central*/
	private $central;

	/** @var Player[] */
	private $members = [];

	/** @var Player */
	private $leader;

	/** @var string */
	private $teamString;

	/**
	 * Team constructor.
	 * @param int $id
	 * @param Central $central
	 * @param ParadoxPlayer[] $members
	 */
	public function __construct(int $id, Central $central, array $members){
		$this->id = $id;
		$this->central = $central;
		$this->members = $members;
		$this->leader = $members[0];
		$color = Utils::randColor();
		foreach($this->members as $member) {
			$member->setDisplayName(($this->teamString = $color . "[Team " . $this->getId() . "] ") . TextFormat::GRAY . $member->getDisplayName());
		}
	}

	/**
	 * @return Central
	 */
	public function getCentral(){
		return $this->central;
	}

	/**
	 * @return ParadoxPlayer[]
	 */
	public function getMembers(){
		return $this->members;
	}

	public function getId(){
		return $this->id;
	}

	public function addMember(ParadoxPlayer $player){
		if(!isset($this->members[$player->getName()])){
			$this->members[$player->getName()] = $player;
			$player->setDisplayName($this->getTeamString().$player->getDisplayName());
		}
	}

	public function removeMember(ParadoxPlayer $player){
		if(isset($this->members[$player->getName()])){
			unset($this->members[$player->getName()]);
		}
	}

	public function scatterTogether($range){
		$level = $this->getCentral()->getLevelManager()->getLevel();
		$x = mt_rand(-$range, $range);
		$z = mt_rand(-$range, $range);
		if(!$level->isChunkLoaded($x, $z)){
			$level->loadChunk($x >> 4, $z >> 4);
		}
		$pos = new Position($x, $level->getHighestBlockAt($x, $z) + 2, $z, $level);
		foreach($this->members as $player) {
			$player->teleport($pos);
			$player->addEffect(Effect::getEffect(Effect::SLOWNESS)->setDuration(20 * 85)->setAmplifier(25));
			$player->addEffect(Effect::getEffect(Effect::JUMP)->setDuration(20 * 85)->setAmplifier(-10));
		}
	}

	public function sendTeamMessage(string $message){
		foreach($this->getMembers() as $member) $member->sendMessage($message);
	}

	public function getTeamKills(){
		$kills = 0;
		foreach($this->getMembers() as $member){
			$kills += $member->getKills();
		}
		return $kills;
	}

	public function getTeamString(){
		return $this->teamString;
	}

	public function getMemberCount(){
		return count($this->getMembers());
	}
}