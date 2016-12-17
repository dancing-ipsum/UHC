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

class MainGlobalMuteCommand extends BaseCommand{

	/**
	 * MainGlovalMuteCommand constructor.
	 * @param Central $plugin
	 */
	public function __construct(Central $plugin){
		parent::__construct($plugin, "globalmute", "Turn global mute on and off.", "/globalmute [on|off]", ["gm"]);
		$this->setPermission("paradox.commands.mute");
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return mixed|void
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(isset($args[0])){
			switch(strtolower($args[0])){
				case "on":
					if($this->getPlugin()->getEssentialManager()->getGlobalMute()){
						$sender->sendMessage(TextFormat::RED."Global mute is already enabled!");
						return false;
					}
					$this->getPlugin()->getServer()->broadcastMessage(TextFormat::GREEN."Global mute has been enabled by an admin!");
					$this->getPlugin()->getEssentialManager()->setGlobalMute(true);
					break;
				default:
					if(!$this->getPlugin()->getEssentialManager()->getGlobalMute()){
						$sender->sendMessage(TextFormat::RED."Global mute is already disabled!");
						return false;
					}
					$this->getPlugin()->getServer()->broadcastMessage(TextFormat::GREEN."Global mute has been disabled by an admin!");
					$this->getPlugin()->getEssentialManager()->setGlobalMute(false);
			}
		}
		return true;
	}
}
