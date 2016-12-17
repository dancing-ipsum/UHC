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

class MainSpectateCommand extends BaseCommand{

	/**
	 * MainSpectateCommand constructor.
	 * @param Central $plugin
	 */
	public function __construct(Central $plugin){
		parent::__construct($plugin, "spectate", "Spectate other players while they are in the UHC.", "/spectate [player]", ["spec"]);
		$this->setPermission("paradox.commands.spectate");
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return mixed|void
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if($sender instanceof Player) {
			if($sender->getGamemode() === Player::SPECTATOR) {
				if (isset($args[0])) {
					$player = $this->getPlugin()->getServer()->getPlayer(strtolower($args[0]));
					if ($player !== null) {
						foreach ($this->getPlugin()->getPlayers() as $p) {
							if ($player === $p) {
								if ($player === $sender) {
									$sender->sendMessage(TextFormat::RED . "You can't spectate yourself!");
								} else {
									$sender->teleport($player->getPosition());
									$sender->sendMessage(TextFormat::GREEN . "Now spectating:  " . $player->getDisplayName());
								}
							} else {
								$sender->sendMessage(TextFormat::RED . "That player is not in the UHC!");
							}
						}
					} else {
						$sender->sendMessage(TextFormat::RED . "That player is offline!");
					}
				} else {
					$sender->sendMessage(TextFormat::DARK_GRAY . 'Usage: ' . TextFormat::RED . $this->getUsage());
				}
			} else {
				$sender->sendMessage(TextFormat::RED."You must be in spectator mode to use this command!");
			}
		} else {
			$sender->sendMessage(TextFormat::RED."Run this command in-game!");
		}
		return true;
	}
}
