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

class MainReportCommand extends BaseCommand {
	private $plugin;
	public $config;
	public $player;

	const REPORT_PREFIX = TextFormat::BOLD.TextFormat::DARK_GRAY.'['.TextFormat::RESET.TextFormat::BLUE.'Report'.TextFormat::BOLD.TextFormat::DARK_GRAY.']'.TextFormat::RESET;

	/**
	 * MainReportCommand constructor.
	 * @param Central $plugin
	 */
	public function __construct(Central $plugin) {
		$this->plugin = $plugin;
		parent::__construct($plugin, "report", "Report players if they are doing something wrong.", "/report [name] [reason]", []);
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return bool|mixed
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args)
	{
		if($sender instanceof Player){
			if(count($args) < 2 ){
				$sender->sendMessage(TextFormat::DARK_GRAY.'Usage: '.TextFormat::RED. $this->getUsage());
				return false;
			}
			$name = array_shift($args);
			$sname = $sender->getName();
			$player = $this->plugin->getServer()->getPlayer($name);
			$pname = $player->getName();
			if(!$player){
				$sender->sendMessage(self::REPORT_PREFIX.TextFormat::GRAY.' That player is not online!');
				return false;
			}
			if($sname === $pname){
				$sender->sendMessage(self::REPORT_PREFIX.TextFormat::GRAY." You cannot report yourself!");
				return false;
			} else {
				$sender->sendMessage(self::REPORT_PREFIX.TextFormat::GRAY.' You have successfully reported '. TextFormat::GOLD.$pname.TextFormat::RESET.TextFormat::GRAY.' for '.implode(" ", $args));
				foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
					if($player->hasPermission("paradox.permissions.report")){
						$player->sendMessage(self::REPORT_PREFIX.TextFormat::RESET.TextFormat::GOLD.$sname.TextFormat::RESET.TextFormat::GRAY.' has reported '.TextFormat::GOLD.$pname.TextFormat::RESET.TextFormat::GRAY.' for '.implode(" ", $args));
					}
				}
			}
		} else {
			$sender->sendMessage(TextFormat::RED."Run this command in-game!");
		}
		return true;
	}

}
