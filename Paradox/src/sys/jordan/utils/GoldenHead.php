<?php
namespace sys\jordan\utils;
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

use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\item\Food;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;

class GoldenHead extends Food{
	public function __construct($meta = 2, $count = 1){
		parent::__construct(self::GOLDEN_APPLE, $meta, $count, $this->getNameByMeta($meta));
	}

	public function canBeConsumedBy(Entity $entity): bool{
		return $entity instanceof Human and $this->canBeConsumed();
	}

	public function getFoodRestore() : int{
		return 4;
	}

	public function getSaturationRestore() : float{
		return 9.6;
	}

	public function getAdditionalEffects() : array{
		return $this->meta == 2 ? [
			Effect::getEffect(Effect::REGENERATION)->setDuration(180)->setAmplifier(1),
			Effect::getEffect(Effect::ABSORPTION)->setDuration(2400)->setAmplifier(0)
		] : [
			Effect::getEffect(Effect::REGENERATION)->setDuration(100)->setAmplifier(1),
			Effect::getEffect(Effect::ABSORPTION)->setDuration(2400)->setAmplifier(0)
		];
	}

	public function getNameByMeta(int $meta){
		switch($meta){
			case 0:
				$this->name = "Golden Apple";
				return "Golden Apple";
			default:
				$this->name = TextFormat::GOLD."Golden Head";
				return TextFormat::GOLD."Golden Head";
		}
	}
}