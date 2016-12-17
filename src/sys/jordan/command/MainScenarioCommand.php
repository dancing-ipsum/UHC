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

class MainScenarioCommand extends BaseCommand{

	/**
	 * MainScenarioCommand constructor.
	 * @param Central $plugin
	 */
	public function __construct(Central $plugin){
		parent::__construct($plugin, "scenario", "Set and remove scenarios", "/scenario [set|rem] [scenario]", []);
		$this->setPermission("paradox.commands.scenario");
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return mixed|void
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(isset($args[0])) {
			if (isset($args[1])) {
				switch ($args[0]) {
					case "set":
						foreach ($this->getPlugin()->getScenarioManager()->getScenarios() as $scenario) {
							if ($scenario->stringMatches($args[1])) {
								if (!$scenario->isActive()) {
									$sender->sendMessage(TextFormat::GREEN . $scenario->getName() . " has been enabled!");
									$scenario->setActive(true);
									return true;
								} else {
									$sender->sendMessage(TextFormat::RED . $scenario->getName() . " is already enabled!");
									return false;
								}
							}
						}
						break;
					case "rem":
						foreach ($this->getPlugin()->getScenarioManager()->getScenarios() as $scenario) {
							if ($scenario->stringMatches($args[1])) {
								if ($scenario->isActive()) {
									$sender->sendMessage(TextFormat::GREEN . $scenario->getName() . " has been disabled!");
									$scenario->setActive(false);
									return true;
								} else {
									$sender->sendMessage(TextFormat::RED . $scenario->getName() . " is already disabled!");
									return false;
								}
							}
						}
						break;
				}
			} else {
				switch ($args[0]){
					case "get":
					foreach($this->getPlugin()->getScenarioManager()->getScenarios() as $scenario){
						$active = $scenario->isActive() ? TextFormat::GREEN."[ACTIVE]" : TextFormat::RED."[NOT ACTIVE]";
						$sender->sendMessage(TextFormat::BLUE . "(" . $scenario->getName() . ")" . TextFormat::GRAY . " - " . $scenario->getDescription(). "[".implode(",", $scenario->getAliases())."]".$active);
					}
				}
			}
		} else {
			$sender->sendMessage(TextFormat::DARK_GRAY.'Usage: '.TextFormat::RED. $this->getUsage());
		}
	}
}
