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
use sys\jordan\Central;
use sys\jordan\utils\Utils;

class MainColorCommand extends BaseCommand{

	/**
	 * MainColorCommand constructor.
	 * @param Central $plugin
	 */
	public function __construct(Central $plugin){
		parent::__construct($plugin, "color", "Change your color name", "/color [color]", ["colour"]);
		$this->setPermission("paradox.permission.color");
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return mixed|void
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if($sender instanceof Player){
			if(isset($args[0])){
				if($args[0] === "help"){
					$sender->sendMessage(TextFormat::GRAY."All colors: [".Utils::getAllColors()."]");
					return true;
				}
				$name = TextFormat::clean($sender->getDisplayName());
				$color = Utils::getColorByName($args[0]);
				$sender->setDisplayName($color.$name);
				$sender->sendMessage(TextFormat::GREEN."You have successfully set your name color to ".$color.Utils::getNameByColor($color));
			} else {
				$sender->sendMessage(TextFormat::RED."Usage: ".$this->getUsage());
			}
		}
		return true;
	}
}
