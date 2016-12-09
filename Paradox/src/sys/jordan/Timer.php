<?php
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

namespace sys\jordan;

use pocketmine\entity\Effect;
use pocketmine\level\Position;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;
use sys\jordan\basefiles\Team;
use sys\jordan\managers\EventManager;
use sys\jordan\utils\Utils;

class Timer extends PluginTask {

	public $plugin;

	public $countdown = 90;
	public $grace = 60 * 20;
	public $pvp = 60 * 30;
	public $tp1 = 60 * 5;
	//BORDER SHRINK//
	public $tp2 = 60 * 10;
	public $end = 60 * 10;
	public $endCounter = 10;
	public $border = 1000;

	public $expectedPositions = [];

	public function __construct(Central $owner){
		parent::__construct($owner);
		$this->plugin = $owner;
		$this->getOwner()->getServer()->getScheduler()->scheduleRepeatingTask($this, 20);
	}

	/**
	 * Actions to execute when run
	 *
	 * @param $currentTick
	 *
	 * @return void
	 */
	public function onRun($currentTick){
		$this->handleEvents();
		$this->sendHealth();
		$this->testBorder();
		$this->testPlayers();
	}

	public function sendCoords(){
		foreach($this->getPlugin()->getUHCManager()->getPlayers() as $player) {
			$essentials = TextFormat::GRAY."Kills: ".TextFormat::GOLD.$player->getKills().TextFormat::GRAY." X: ".TextFormat::GOLD.$player->getFloorX().TextFormat::GRAY." Y: ".TextFormat::GOLD.$player->getFloorY().TextFormat::GRAY." Z: ".TextFormat::GOLD.$player->getFloorZ().TextFormat::GRAY." Players Left: ".TextFormat::GOLD.count($this->plugin->getPlayers());
			$player->sendPopup(Utils::centerText(Central::BRAND_PREFIX, $essentials) . "\n" . $essentials);
		}
	}

	public function sendHealth(){
		foreach($this->getPlugin()->getUHCManager()->getPlayers() as $player) {
			$healthText = TextFormat::RED.$player->getHealth().TextFormat::GRAY." Health".TextFormat::GRAY;
			$player->setNameTag($player->getDisplayName()."\n".(Utils::centerText($healthText, $player->getDisplayName())));
		}
	}

	public function getTime(){
		$time = "";
		switch($this->getPlugin()->getEventManager()->getEvent()){
			case EventManager::COUNTDOWN:
				$time = $this->countdown;
				break;
			case EventManager::GRACE:
				$time = $this->grace;
				break;
			case EventManager::PVP:
				$time = $this->pvp;
				break;
			case EventManager::FIRST_TP:
				$time = $this->tp1;
				break;
			case EventManager::SECOND_TP:
				$time = $this->tp2;
				break;
			case EventManager::END:
				$time = $this->end;
				break;
			default:
				return "";
		}
		return TextFormat::GOLD.gmdate("i:s", $time);
	}

	public function getEventMessage(){
		switch($this->getPlugin()->getEventManager()->getEvent()){
			case EventManager::COUNTDOWN:
				return TextFormat::GRAY."\n\nCountdown will end in: ";
			case EventManager::GRACE:
				return TextFormat::GRAY."\n\nGrace will end in: ";
			case EventManager::PVP:
				return TextFormat::GRAY."\n\nEverything is normal for: ";
			case EventManager::FIRST_TP:
				return TextFormat::GRAY."\n\nThe first teleport will commence in: ";
			case EventManager::SECOND_TP:
				return TextFormat::GRAY."\n\nThe second teleport will commence in: ";
			case EventManager::END:
				return TextFormat::GRAY."\n\nThe UHC will end in: ";
			default:
				return TextFormat::GRAY."\n\nPlayers Queued: ".TextFormat::GOLD.count($this->getPlugin()->getUHCManager()->getPlayers());
		}
	}

	public function handleEvents(){
		switch($this->getPlugin()->getEventManager()->getEvent()){
			case EventManager::COUNTDOWN:
				$this->handleCountdown();
				$this->sendCoords();
				break;
			case EventManager::GRACE:
				$this->handleGrace();
				$this->sendCoords();
				break;
			case EventManager::PVP:
				$this->handlePvP();
				$this->sendCoords();
				break;
			case EventManager::FIRST_TP:
				$this->handleFirstTP();
				$this->sendCoords();
				break;
			case EventManager::SECOND_TP:
				$this->handleSecondTP();
				$this->sendCoords();
				break;
			case EventManager::END:
				$this->handleEnd();
				$this->sendCoords();
				break;
			default:
				$this->resetTimes();

		}
	}

	public function handleCountdown(){
		$this->countdown--;
		switch($this->countdown){
			case 85:
				$this->handleSolos();
				break;
			case 80:
				$this->broadcastMessage(Central::PREFIX.TextFormat::GRAY."Teleporting players...");
				$this->scatter(1000);
				break;
			case 60:
				$this->broadcastMessage(Central::PREFIX.TextFormat::GRAY." Welcome to Paradox!");
				break;
			case 55:
				$this->broadcastMessage(Central::PREFIX.TextFormat::GRAY." We have a few rules before the UHC starts!");
				break;
			case 50:
				$this->broadcastMessage(Central::PREFIX.TextFormat::GRAY." 1. You are allowed to stalk people, but not excessively.");
				break;
			case 45:
				$this->broadcastMessage(Central::PREFIX.TextFormat::GRAY." 2. If you are caught hacking in any way, you will be".TextFormat::RED." banned".TextFormat::GRAY."!");
				break;
			case 40:
				$this->broadcastMessage(Central::PREFIX.TextFormat::GRAY." 3. If you do see a hacker feel free to use the ".TextFormat::GOLD."/report".TextFormat::GRAY." command!");
				break;
			case 35:
				$this->broadcastMessage(Central::PREFIX.TextFormat::GRAY." 4. Staircasing is allowed in this UHC, but strip-mining is not!");
				break;
			case 30:
				$this->broadcastMessage(Central::PREFIX.TextFormat::GRAY." 5. Any person that violates these rules will receive a ban depending on previous bans.");
				break;
			case 25:
				$this->broadcastMessage(Central::PREFIX.TextFormat::GRAY." 6. To find out what scenarios are in this UHC, do ".TextFormat::GOLD."/scenarios".TextFormat::GRAY."!");
				break;
			case 20:
				$this->broadcastMessage(Central::PREFIX.TextFormat::GRAY." 7. To find out all of your stats, do ".TextFormat::GOLD."/stats".TextFormat::GRAY."!");
				break;
			case 15:
				$this->broadcastMessage(Central::PREFIX.TextFormat::GRAY." Follow".TextFormat::BLUE." @ParadoxTwitUHC ".TextFormat::GRAY."for new features.");
				break;
			case 10:
				$this->broadcastMessage(Central::PREFIX.TextFormat::GRAY." Final heal happens only once, so please do not ask for more heals!");
				break;
			case 5:
				$this->broadcastMessage(Central::PREFIX.TextFormat::GRAY." Good luck, and remember to have fun!");
				break;
			case 0:
				$this->getPlugin()->getEventManager()->setEvent(EventManager::GRACE);
				$this->countdown = 90;
		}
	}

	public function handleGrace(){
		$this->grace--;
		switch($this->grace){
			case (60 * 20) - (60 * 5):
				$this->plugin->getEssentialManager()->broadcastMessage(Central::PREFIX.TextFormat::GRAY."Final heal will occur in 5 minutes!");
				break;
			case (60 * 20) / 2:
				$this->plugin->getEssentialManager()->finalHeal();
				break;
			case 0:
				$this->getPlugin()->getEventManager()->setEvent(EventManager::PVP);
				$this->grace = 60 * 20;
		}
	}

	public function handlePvP(){
		$this->pvp--;
		if($this->pvp == 0){
			$this->getPlugin()->getEventManager()->setEvent(EventManager::FIRST_TP);
			$this->pvp = 60 * 30;
		}
	}

	public function handleFirstTP(){
		$this->tp1--;
		if($this->tp1 == 0){
			$this->getPlugin()->getEventManager()->setEvent(EventManager::SECOND_TP);
			$this->tp1 = 60 * 5;
			$this->setBorder(500);
			$this->warpInBorder();
		}
	}

	public function handleSecondTP(){
		$this->tp2--;
		for($i = 0; $i<=100; $i+=16){
			for($j = 0; $j<= 100; $j+=16){
				if(!$this->plugin->getLevelManager()->getLevel()->isChunkLoaded($i >> 4, $j >> 4)) {
					$this->plugin->getLevelManager()->getLevel()->loadChunk($i >> 4, $j >> 4);
				}
			}
		}
		if($this->tp2 == 0){
			$this->getPlugin()->getEventManager()->setEvent(EventManager::END);
			$this->tp1 = 60 * 10;
			$this->setBorder(100);
			$this->warpInBorder();
		}
	}

	public function handleEnd(){
		$this->end--;
		if($this->end == 0){
			$this->plugin->getEventManager()->stop();
			$this->tp1 = 60 * 10;
		}
	}

	public function broadcastMessage(string $message){
		foreach($this->getPlugin()->getUHCManager()->getPlayers() as $player){
			$player->sendMessage($message);
		}
	}

	public function broadcastPopup(string $message){
		foreach($this->getPlugin()->getUHCManager()->getPlayers() as $player){
			$player->sendPopup($message);
		}
	}

	public function broadcastTip(string $message){
		foreach($this->getPlugin()->getUHCManager()->getPlayers() as $player){
			$player->sendTip($message);
		}
	}

	public function scatter(int $range){
		$level = $this->getPlugin()->getLevelManager()->getLevel();
		if($this->getPlugin()->getTeamManager()->isTeamsEnabled()){
			foreach ($this->getPlugin()->getTeamManager()->getTeams() as $team) {
				$team->scatterTogether($range);
			}
		} else {
			foreach ($this->getPlugin()->getUHCManager()->getPlayers() as $player) {
				$player->scatter($range, $level);
			}
		}
	}

	public function handleSolos(){
		if($this->getPlugin()->getTeamManager()->isTeamsEnabled()) {
			$solos = $this->getPlugin()->getTeamManager()->getSolos();
			for($i = 0; $i <= floor(count($solos)/2); $i++) {
				$rand = array_rand($solos, 2);
				$team = new Team(count($this->getPlugin()->getTeamManager()->getTeams()) + 1, $this->getPlugin(),array_values($rand));
				$this->getPlugin()->getTeamManager()->addTeam($team);
			}
		}
	}

	public function testBorder(){
		foreach($this->getPlugin()->getUHCManager()->getPlayers() as $player) {
			if(($this->getPlugin()->getEventManager()->getEvent() > 0) && ($player->getLevel() == $this->getPlugin()->getLevelManager()->getLevel())) {
			}
		}
	}

	public function setBorder($value){
		$this->border = $value;
	}

	public function warpInBorder(){
		$level = $this->getPlugin()->getLevelManager()->getLevel();
		foreach($this->plugin->getPlayers() as $player){
			if(($player->getFloorX() >= $this->border or $player->getFloorX() <= -$this->border or
				$player->getFloorZ() >= $this->border or $player->getFloorZ() <= -$this->border) && $player->getGamemode() == 0){
				$x = mt_rand(-$this->border, $this->border);
				$z = mt_rand(-$this->border, $this->border);
				if(!$level->isChunkLoaded($x, $z)){
					$level->loadChunk($x << 4, $z << 4);
				}
				$player->addEffect(Effect::getEffect(Effect::BLINDNESS)->setDuration(20 * 5));
				$player->teleport(new Position($x, $level->getHighestBlockAt($x, $z) + 1, $z, $level));
			}
		}
		return false;
	}

	public function resetTimes(){
		$this->countdown = 90;
		$this->grace = 60 * 20;
		$this->pvp = 60 * 30;
		$this->tp1 = 60 * 5;
		$this->tp2 = 60 * 10;
		$this->end = 60 * 10;
	}

	public function testPlayers(){
		if($this->plugin->getEventManager()->getEvent() > 0){
			if($this->getPlugin()->getTeamManager()->isTeamsEnabled()){
				if($this->getPlugin()->getTeamManager()->getTeamsCount() === 1) {
					$this->endCounter--;
					foreach($this->getPlugin()->getTeamManager()->getTeams() as $team)
					$this->broadcastMessage(Central::PREFIX . TextFormat::GRAY . "Congratulations to " . $team->getTeamString() . TextFormat::GRAY . " for winning today's UHC!");
				}
			} else {
				if(($this->getPlugin()->getEssentialManager()->getPlayerCount() === 1) && (!$this->getPlugin()->isBeta)) {
					$this->endCounter--;
					foreach ($this->plugin->getPlayers() as $player) {
						if ($this->endCounter === 8) {
							$player->sendMessage(TextFormat::GRAY . "--Game Overview--");
							if($this->getPlugin()->getTeamManager()->isTeamsEnabled()) $player->sendMessage(TextFormat::GRAY."Team Kills: ".$player->getTeam()->getTeamKills());
							$player->sendMessage(TextFormat::GRAY . "Kills: " . TextFormat::GOLD . $player->getKills());
							$player->sendMessage(TextFormat::GRAY . "-----------------");
						}
					}
				}
			}
			if($this->endCounter == 0){
				$this->plugin->getEventManager()->stop();
				$this->endCounter = 10;
			}
		}
	}

	public function getPlugin(){
		return $this->plugin;
	}
}