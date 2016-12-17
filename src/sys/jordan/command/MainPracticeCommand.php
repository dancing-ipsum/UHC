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
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\utils\TextFormat;
use sys\jordan\basefiles\BaseCommand;
use sys\jordan\Central;
use sys\jordan\ParadoxPlayer;

class MainPracticeCommand extends BaseCommand{

	/**
	 * MainPracticeCommand constructor.
	 * @param Central $plugin
	 */
	public function __construct(Central $plugin){
		parent::__construct($plugin, "practice", "Join the practice arena.", "/practice", ["arena"]);
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return mixed|void
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if ($sender instanceof ParadoxPlayer) {
			if ($this->getPlugin()->getEventManager()->getEvent() <= 0) {
				if (!$sender->inArena()) {
					$this->getPlugin()->getArenaManager()->addToArena($sender);
					return true;
				} else {
					$this->getPlugin()->getArenaManager()->removeFromArena($sender);
				}
			} else {
				$sender->sendMessage(TextFormat::RED . "You can't teleport into the arena while a UHC is going on!");
				return false;
			}
		} else {
			$sender->sendMessage(TextFormat::RED . "Please use this command in-game!");
			return false;
		}
	}
}
