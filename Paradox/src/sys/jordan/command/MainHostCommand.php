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
use pocketmine\utils\TextFormat;
use sys\jordan\basefiles\BaseCommand;
use sys\jordan\Central;

class MainHostCommand extends BaseCommand{

	/**
	 * MainHostCommand constructor.
	 * @param Central $plugin
	 */
	public function __construct(Central $plugin){
		parent::__construct($plugin, "host", "Main command for hosts.", "/host [start|stop|level|help]", []);
		$this->setPermission("paradox.commands.host");
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return mixed|void
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(isset($args[0])){
			switch($args[0]){
				case "?":
				case "help":
					$this->sendHostHelp($sender);
					break;
				case "teams":
					if(isset($args[1])){
						$args[1] = strtolower($args[1]);
						if($args[1] === "true"){
							if($this->getPlugin()->getTeamManager()->isTeamsEnabled()){
								$sender->sendMessage(TextFormat::RED."Teams are already enabled!");
								return true;
							}
							$this->getPlugin()->getTeamManager()->setTeamsEnabled(true);
							$sender->sendMessage(TextFormat::GREEN."Teams have been enabled!");
						} else {
							if(!$this->getPlugin()->getTeamManager()->isTeamsEnabled()){
								$sender->sendMessage(TextFormat::RED."Teams are already disabled!");
								return true;
							}
							$this->getPlugin()->getTeamManager()->disbandAllTeams();
							$this->getPlugin()->getTeamManager()->setTeamsEnabled(false);
							$sender->sendMessage(TextFormat::GREEN."Teams have been disabled!");
						}
					}
					break;
				case "start":
					$this->getPlugin()->getEventManager()->start();
					break;
				case "stop":
					$this->getPlugin()->getEventManager()->stop();
					break;
				case "level":
					if(isset($args[1])){
						$setLevel = $this->getPlugin()->getLevelManager()->setLevel($args[1]);
						if($setLevel){
							$sender->sendMessage(TextFormat::GREEN."The level has successfully been set as ".$args[1]."!");
						} else {
							$sender->sendMessage(TextFormat::RED."The level could not be set!");
						}
						return true;
					}
					break;
				default:
					$this->sendHostHelp($sender);

			}
			return true;
		} else {
			$sender->sendMessage(TextFormat::DARK_GRAY.'Usage: '.TextFormat::RED. $this->getUsage());
			return true;
		}
	}

	//TODO: Make this better/more efficient
	public function sendHostHelp(CommandSender $sender){
		$sender->sendMessage(TextFormat::GRAY."--Paradox Hosting Help--");
		$sender->sendMessage(TextFormat::GOLD."start: ".TextFormat::GRAY."Start the UHC using this. Usage: /host start");
		$sender->sendMessage(TextFormat::GOLD."stop: ".TextFormat::GRAY."Stop the UHC using this. Usage: /host stop");
		$sender->sendMessage(TextFormat::GOLD."teams: ".TextFormat::GRAY."Set teams enabled or disabled. Default is disabled. Usage: /host teams [true|false]");
		$sender->sendMessage(TextFormat::GOLD."level: ".TextFormat::GRAY."Set the UHC level. Default level is 'UHC'. Usage: /host level [name]");
		$sender->sendMessage(TextFormat::GRAY."------------------------");

	}
}
