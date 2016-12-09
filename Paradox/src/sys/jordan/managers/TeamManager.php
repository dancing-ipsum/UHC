<?php
namespace sys\jordan\managers;

use pocketmine\Player;
use pocketmine\utils\TextFormat;
use sys\jordan\basefiles\Team;
use sys\jordan\Central;
use sys\jordan\ParadoxPlayer;

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

class TeamManager {

	private $teamsEnabled = false;
	private static $MAX_TEAM_COUNT = 2;

	/** @var Team[] */
	public $teams = [];

	public function __construct(Central $plugin){
		$this->plugin = $plugin;
	}

	public function addTeam(Team $team){
		if(!isset($this->teams[$team->getId()])){
			$this->teams[$team->getId()] = $team;
		}
	}

	public function removeTeam(Team $team){
		foreach($team->getMembers() as $member){
			$member->setDisplayName(TextFormat::GRAY.$member->getName());
			$member->sendMessage($team->getTeamString().TextFormat::RED." was disbanded!");
		}
		if(isset($this->teams[$team->getId()])){
			unset($this->teams[$team->getId()]);
		}
	}

	/**
	 * @return Team[]
	 */
	public function getTeams(){
		return $this->teams;
	}

	/**
	 * @param int $id
	 * @return Team|null
	 */
	public function getTeamById(int $id){
		return $this->teams[$id];
	}

	/**
	 * @param ParadoxPlayer $player
	 * @return null|Team
	 */
	public function getTeam(ParadoxPlayer $player){
		foreach($this->teams as $team){
			foreach($team->getMembers() as $member){
				if($player === $member){
					return $team;
				}
			}
		}
		return null;
	}

	/**
	 * @param Player|ParadoxPlayer $player
	 * @return null|Player
	 */
	public function getTeammate(Player $player){
		foreach($this->getTeam($player)->getMembers() as $member){
			if($player === $member){
				continue;
			}
			return $player;
		}
		return null;
	}

	/**
	 * @return bool
	 */
	public function isTeamsEnabled(){
		return $this->teamsEnabled;
	}

	public function setTeamsEnabled($value = false){
		$this->teamsEnabled = $value;
	}

	public function disbandAllTeams(){
		foreach($this->getTeams() as $team){
			$this->removeTeam($team);
		}
	}

	/**
	 * @return ParadoxPlayer[]
	 */
	public function getSolos(){
		$solos = [];
		foreach($this->plugin->getPlayers() as $player){
			if(!$player->inTeam()){
				$solos[$player->getName()] = $player;
			}
		}
		return $solos;
	}

	public function getTeamsCount(){
		return count($this->getTeams());
	}

	public function getMaxTeam(){
		return self::$MAX_TEAM_COUNT;
	}

}