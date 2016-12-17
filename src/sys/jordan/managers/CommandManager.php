<?php
namespace sys\jordan\managers;

use pocketmine\command\defaults\WhitelistCommand;
use sys\jordan\Central;
use sys\jordan\command\MainColorCommand;
use sys\jordan\command\MainGlobalMuteCommand;
use sys\jordan\command\MainHostCommand;
use sys\jordan\command\MainPracticeCommand;
use sys\jordan\command\MainReportCommand;
use sys\jordan\command\MainScenarioCommand;
use sys\jordan\command\MainScenariosCommand;
use sys\jordan\command\MainSpectateCommand;
use sys\jordan\command\MainStatsCommand;
use sys\jordan\command\MainTeamCommand;
use sys\jordan\command\MainWhitelistCommand;

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

class CommandManager {

	private $plugin;

	public function __construct(Central $plugin){
		$this->plugin = $plugin;
		$this->init();
	}

	private function init(){
		$commandMap = $this->plugin->getServer()->getCommandMap();
		$whitelist = $commandMap->getCommand("whitelist");
		$whitelist->setLabel("whitelist_disabled");
		$whitelist->unregister($commandMap);
		$this->plugin->getServer()->getCommandMap()->registerAll("paradox",[
			new MainColorCommand($this->plugin),
			new MainReportCommand($this->plugin),
			new MainSpectateCommand($this->plugin),
			new MainScenarioCommand($this->plugin),
			new MainScenariosCommand($this->plugin),
			new MainStatsCommand($this->plugin),
			new MainHostCommand($this->plugin),
			new MainPracticeCommand($this->plugin),
			new MainTeamCommand($this->plugin),
			new MainGlobalMuteCommand($this->plugin),
			new MainWhitelistCommand($this->plugin)
		]);
	}

}