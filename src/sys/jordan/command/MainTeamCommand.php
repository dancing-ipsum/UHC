<?php
namespace sys\jordan\command;
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

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use sys\jordan\basefiles\BaseCommand;
use sys\jordan\basefiles\Team;
use sys\jordan\Central;
use sys\jordan\managers\TeamManager;
use sys\jordan\ParadoxPlayer;
use sys\jordan\utils\Utils;

class MainTeamCommand extends BaseCommand {

	public $requester = [];
	public $requested = [];

	/**
	 * MainTeamCommand constructor.
	 * @param Central $plugin
	 */
	public function __construct(Central $plugin){
		parent::__construct($plugin, "team", "Team with other players for UHCs![Only for Team UHCs!]", "/team [player|accept|deny]", []);
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return mixed|void
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if($sender instanceof ParadoxPlayer) {
			if($this->getPlugin()->getTeamManager()->isTeamsEnabled()) {
				if($this->getPlugin()->getEventManager()->getEvent() <= 0) {
					if (isset($args[0])) {
						switch (strtolower($args[0])) {
							case "accept":
								if ($this->hasIncomingRequest($sender)) {
									$requester = $this->getRequest($sender);
									$requester->sendMessage(TextFormat::GREEN . $sender->getDisplayName() . " has accepted your team request!");
									if ($requester->getTeam() !== null) {
										$requester->getTeam()->addMember($sender);
									} else {
										$this->getPlugin()->getTeamManager()->createTeam([$requester, $sender]);
									}
									$this->removeRequest($requester, $sender);
									return $sender->sendMessage(TextFormat::GREEN . "You have successfully accepted the request!");
								}
								return $sender->sendMessage(TextFormat::RED."You have no incoming requests at this time!");
								break;
							case "deny":
								if ($this->hasIncomingRequest($sender)) {
									$requester = $this->getRequest($sender);
									if($requester !== null) {
										$this->removeRequest($requester, $sender);
										return $sender->sendMessage(TextFormat::GREEN."You have successfully denied the request!");
									}
									return $sender->sendMessage(TextFormat::RED."There has been an error performing this command!");
								}
								return $sender->sendMessage(TextFormat::RED . "You have no incoming requests at this time!");
								break;
							case "invite":
								if($sender->inTeam()) {
									if ($sender->getTeam()->getMemberCount() < TeamManager::$MAX_TEAM_COUNT) {
										$player = $this->getPlugin()->getServer()->getPlayer($args[1]);
										if ($player !== null and $player instanceof ParadoxPlayer) {
											if (!$player->inTeam()) {
												$this->addRequest($sender, $player);
												$player->sendMessage(TextFormat::GREEN . "You have received a team request from " . $sender->getDisplayName());
												return $sender->sendMessage(TextFormat::GREEN . "You have successfully sent a team request to " . $player->getDisplayName());
											}
											return $sender->sendMessage(TextFormat::RED . "This player is already on a team!");

										}
										return $sender->sendMessage(TextFormat::RED . "That player is not online!");
									}
									return $sender->sendMessage(TextFormat::RED."You have exceeded the maximum count of players on your team!");
								}
								return $sender->sendMessage(TextFormat::RED."You are not on a team!");
								break;
							case "disband":
								if ($sender->inTeam()) {
									return $this->getPlugin()->getTeamManager()->removeTeam($sender->getTeam());
								}
								return $sender->sendMessage(TextFormat::RED."You are not on a team!");
								break;
							default:
								if($sender->getTeam() !== null){
									$sender->sendMessage(TextFormat::RED."You are already on a team!");
								}
								$player = $this->getPlugin()->getServer()->getPlayer($args[0]);
									//if($sender === $player) return TextFormat::RED."You can't team with yourself!";
									if ($player !== null) {
										if($player instanceof ParadoxPlayer and $player->getTeam() === null) {
											return $this->addRequest($sender, $player);
										} else {
											return $sender->sendMessage(TextFormat::RED . "This player is already on a team!");
										}
									}
								return $sender->sendMessage(TextFormat::RED."That player is not online!");
						}
					}
					return $this->sendUsage($sender);
				}
			} else {
				return $sender->sendMessage(TextFormat::RED . "Teams are not enabled!");
			}
		}
		return $sender->sendMessage(TextFormat::RED."Run this command in-game!");
	}

	/**
	 * @param ParadoxPlayer $requester
	 * @param ParadoxPlayer $requested
	 */
	public function addRequest(ParadoxPlayer $requester, ParadoxPlayer $requested){
		if(!isset($this->requester[$requester->getName()])) {
			$this->requester[$requester->getName()] = $requested;
			$requester->sendMessage(TextFormat::GREEN."You have successfully sent a team request to ".$requested->getDisplayName()."!");
		}
		if(!isset($this->requested[$requested->getName()])) {
			$this->requested[$requested->getName()] = $requester;
			$requested->sendMessage(TextFormat::GREEN."You have received a team request from ".$requester->getDisplayName()."!");
		}
	}

	/**
	 * @param ParadoxPlayer $requester
	 * @param ParadoxPlayer $requested
	 */
	public function removeRequest(ParadoxPlayer $requester, ParadoxPlayer $requested){
		if(isset($this->requester[$requester->getName()])) unset($this->requester[$requester->getName()]);
		if(isset($this->requested[$requested->getName()])) unset($this->requested[$requested->getName()]);
	}

	/**
	 * @param ParadoxPlayer $player
	 * @return bool
	 */
	public function hasRequest(ParadoxPlayer $player){
		if(isset($this->requester[$player->getName()])) return true;
		return false;
	}

	/**
	 * @param ParadoxPlayer $player
	 * @return bool
	 */
	public function hasIncomingRequest(ParadoxPlayer $player){
		if(isset($this->requested[$player->getName()])) return true;
		return false;
	}

	/**
	 * @param ParadoxPlayer $player
	 * @return ParadoxPlayer|null
	 */
	public function getRequest(ParadoxPlayer $player){
		if($this->hasIncomingRequest($player)) {
			$rq = $this->requested[$player->getName()];
			if(is_string($rq)){
				 $player = $this->getPlugin()->getServer()->getPlayer($rq);
				if($player instanceof ParadoxPlayer) return $player;
			}
			return $rq;
		}
		return null;
	}

}
