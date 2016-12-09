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
use sys\jordan\ParadoxPlayer;
use sys\jordan\utils\Utils;

class MainTeamCommand extends BaseCommand{

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

		} else {
			$sender->sendMessage(TextFormat::RED."Run this command in-game!");
		}
		return true;
	}

}
