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

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\utils\TextFormat;
use sys\jordan\basefiles\BaseCommand;
use sys\jordan\Central;
use sys\jordan\ParadoxPlayer;

class MainWhitelistCommand extends BaseCommand{

	private $count = [];

	public function __construct(Central $plugin){
		parent::__construct($plugin, "whitelist", "%pocketmine.command.whitelist.description", "%commands.whitelist.usage", []);
		$this->setAliases(["wl"]);
		$this->setPermission("paradox.command.whitelist.full;paradox.command.whitelist.hero;paradox.command.whitelist.hero_plus;paradox.command.whitelist.paranormal");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(count($args) === 0 or count($args) > 2){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));
			return true;
		}
		if(count($args) > 1){
			switch(strtolower($args[0])){
				case "add":
					if(!$sender->hasPermission("paradox.command.whitelist.full")){
						if($sender instanceof ParadoxPlayer) {
							if ($this->getCount($sender) >= $this->getMaxCount($sender)) {
								$sender->sendMessage(TextFormat::RED . "You have reached the max amount of whitelisted people!");
								return false;
							}
							$sender->sendMessage(TextFormat::GREEN.$args[1]." has successfully been whitelisted! You can whitelist ".$this->getCountLeft($sender)." more people!");
							$this->count[$sender->getName()] += 1;
							$sender->getServer()->getOfflinePlayer($args[1])->setWhitelisted(true);
						}
					} else {
						Command::broadcastCommandMessage($sender, new TranslationContainer("commands.whitelist.add.success", [$args[1]]));
						$sender->getServer()->getOfflinePlayer($args[1])->setWhitelisted(true);
					}
					break;
				case "remove":
					if($sender->hasPermission("paradox.command.whitelist.full")){
						Command::broadcastCommandMessage($sender, new TranslationContainer("commands.whitelist.remove.success", [$args[1]]));
						$sender->getServer()->getOfflinePlayer($args[1])->setWhitelisted(false);
					} else {
						$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.permission"));
					}
			}
		} else {
			if($sender->hasPermission("paradox.command.whitelist.full")) {
				switch (strtolower($args[0])) {
					case "reload":
						$sender->getServer()->reloadWhitelist();
						Command::broadcastCommandMessage($sender, new TranslationContainer("commands.whitelist.reloaded"));
						return true;
					case "on":
						$sender->getServer()->setConfigBool("white-list", true);
						Command::broadcastCommandMessage($sender, new TranslationContainer("commands.whitelist.enabled"));
						return true;
					case "off":
						$sender->getServer()->setConfigBool("white-list", false);
						Command::broadcastCommandMessage($sender, new TranslationContainer("commands.whitelist.disabled"));
						return true;
					case "list":
						$result = "";
						$count = 0;
						foreach ($sender->getServer()->getWhitelisted()->getAll(true) as $player) {
							$result .= $player . ", ";
							++$count;
						}
						$sender->sendMessage(new TranslationContainer("commands.whitelist.list", [$count, $count]));
						$sender->sendMessage(substr($result, 0, -2));
						return true;
				}
			} else {
				$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.permission"));
			}
		}
	}

	private function getCount(ParadoxPlayer $player){
		if(!isset($this->count[$player->getName()])){
			$this->count[$player->getName()] = 0;
		}
		return $this->count[$player->getName()];
	}

	private function getMaxCount(ParadoxPlayer $player){
		if($player->hasPermission("paradox.command.whitelist.hero")){
			return 5;
		} else if($player->hasPermission("paradox.command.whitelist.hero_plus")){
			return 8;
		} else if($player->hasPermission("paradox.command.whitelist.paranormal")){
			return 10;
		} else {
			return 0;
		}
	}

	private function getCountLeft(ParadoxPlayer $player){
		return $this->getMaxCount($player) - $this->getCount($player);
	}
}
