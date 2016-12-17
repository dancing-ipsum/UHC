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

namespace sys\jordan\event;


use pocketmine\event\Cancellable;
use pocketmine\event\Event;

class EventChangeEvent extends Event implements Cancellable {

	public $beforeEvent;
	public $afterEvent;
	public static $handlerList = null;

	public function __construct($beforeEvent, $afterEvent){
		$this->beforeEvent = $beforeEvent;
		$this->afterEvent = $afterEvent;
	}

	/**
	 * @return int
	 */
	public function getBeforeEvent(){
		return $this->beforeEvent;
	}

	/**
	 * @return int
	 */
	public function getAfterEvent(){
		return $this->afterEvent;
	}

}