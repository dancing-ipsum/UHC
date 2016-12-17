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
use sys\jordan\ParadoxPlayer;

class MainStatsCommand extends BaseCommand{

	/**
	 * MainStatsCommand constructor.
	 * @param Central $plugin
	 */
	public function __construct(Central $plugin){
		parent::__construct($plugin, "stats", "View your stats.", "/stats", []);
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return mixed|void
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if($sender instanceof ParadoxPlayer){
			$sender->sendMessage(TextFormat::GRAY."--Your Stats--");
			$sender->sendMessage(TextFormat::GRAY."Kills: ".TextFormat::GOLD.$sender->getStat("Kills"));
			$sender->sendMessage(TextFormat::GRAY."Deaths: ".TextFormat::GOLD.$sender->getStat("Deaths"));
			$sender->sendMessage(TextFormat::GRAY."KDR: ".TextFormat::GOLD.$sender->getStat("Kills") / $sender->getStat("Deaths"));
			$sender->sendMessage(TextFormat::GRAY."Wins: ".TextFormat::GOLD.$sender->getStat("Wins"));
			$sender->sendMessage(TextFormat::GRAY."Golden Heads Eaten: ".TextFormat::GOLD.$sender->getStat("Heads"));
			$sender->sendMessage(TextFormat::GRAY."Diamonds Mined: ".TextFormat::GOLD.$sender->getStat("Diamonds"));
			$sender->sendMessage(TextFormat::GRAY."Gold Mined: ".TextFormat::GOLD.$sender->getStat("Gold"));
			$sender->sendMessage(TextFormat::GRAY."Iron Mined: ".TextFormat::GOLD.$sender->getStat("Iron"));
			$sender->sendMessage(TextFormat::GRAY."Lapis Mined: ".TextFormat::GOLD.$sender->getStat("Lapis"));
			$sender->sendMessage(TextFormat::GRAY."Redstone Mined: ".TextFormat::GOLD.$sender->getStat("Redstone"));

		}
		return true;
	}
}
