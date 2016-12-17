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

class MainScenariosCommand extends BaseCommand{

	/**
	 * MainScenariosCommand constructor.
	 * @param Central $plugin
	 */
	public function __construct(Central $plugin){
		parent::__construct($plugin, "scenarios", "View the scenarios for the UHC", "/scenarios", []);
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return mixed|void
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		$sender->sendMessage(TextFormat::GRAY."Scenarios:");
		if($this->getPlugin()->getScenarioManager()->scenariosEmpty()){
			$sender->sendMessage(TextFormat::BLUE."(NONE)".TextFormat::GRAY." - No scenarios are enabled at this time!");
			return true;
		} else if($this->getPlugin()->getScenarioManager()->scenariosFull()){
			$sender->sendMessage(TextFormat::BLUE."(ALL)".TextFormat::GRAY." - Every scenario is active right now!");
			return true;
		} else {
			foreach ($this->getPlugin()->getScenarioManager()->getScenarios() as $scenario) {
				if ($scenario->isActive()) {
					$sender->sendMessage(TextFormat::BLUE . "(" . $scenario->getName() . ")" . TextFormat::GRAY . " - " . $scenario->getDescription());
				}
			}
		}
		return true;
	}
}
