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

namespace sys\jordan\utils;

use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\TextFormat;
use sys\jordan\ParadoxPlayer;

class Utils {

	/**
	 * Get a position instance from a string
	 *
	 * @param string $string
	 *
	 * @return Position|Vector3
	 */
	public static function parsePosition(string $string) {
		$data = explode(",", str_replace(" ", "", $string));
		$level = Server::getInstance()->getLevelByName($data[3]);
		if($level instanceof Level) {
			return new Position($data[0], $data[1], $data[2], $level);
		}

		return self::parseVector($string);
	}

	/**
	 * Get a vector instance from a string
	 *
	 * @param string $string
	 *
	 * @return Vector3
	 */
	public static function parseVector(string $string) {
		$data = explode(",", str_replace(" ", "", $string));

		return new Vector3($data[0], $data[1], $data[2]);
	}

	/**
	 * Removes all coloring and color codes from a string
	 *
	 * @param $string
	 *
	 * @return mixed
	 */
	public static function cleanString($string) {
		$string = self::translateColors($string);
		$string = TF::clean($string);

		return $string;
	}

	/**
	 * Apply minecraft color codes to a string from our custom ones
	 *
	 * @param string $string
	 * @param string $symbol
	 *
	 * @return mixed
	 */
	public static function translateColors($string, $symbol = "&") {
		$string = str_replace($symbol . "0", TF::BLACK, $string);
		$string = str_replace($symbol . "1", TF::DARK_BLUE, $string);
		$string = str_replace($symbol . "2", TF::DARK_GREEN, $string);
		$string = str_replace($symbol . "3", TF::DARK_AQUA, $string);
		$string = str_replace($symbol . "4", TF::DARK_RED, $string);
		$string = str_replace($symbol . "5", TF::DARK_PURPLE, $string);
		$string = str_replace($symbol . "6", TF::GOLD, $string);
		$string = str_replace($symbol . "7", TF::GRAY, $string);
		$string = str_replace($symbol . "8", TF::DARK_GRAY, $string);
		$string = str_replace($symbol . "9", TF::BLUE, $string);
		$string = str_replace($symbol . "a", TF::GREEN, $string);
		$string = str_replace($symbol . "b", TF::AQUA, $string);
		$string = str_replace($symbol . "c", TF::RED, $string);
		$string = str_replace($symbol . "d", TF::LIGHT_PURPLE, $string);
		$string = str_replace($symbol . "e", TF::YELLOW, $string);
		$string = str_replace($symbol . "f", TF::WHITE, $string);

		$string = str_replace($symbol . "k", TF::OBFUSCATED, $string);
		$string = str_replace($symbol . "l", TF::BOLD, $string);
		$string = str_replace($symbol . "m", TF::STRIKETHROUGH, $string);
		$string = str_replace($symbol . "n", TF::UNDERLINE, $string);
		$string = str_replace($symbol . "o", TF::ITALIC, $string);
		$string = str_replace($symbol . "r", TF::RESET, $string);

		return $string;
	}

	public static function randColor(){
		$colors = [TextFormat::RED, TextFormat::BLUE, TextFormat::GOLD, TextFormat::GREEN, TextFormat::AQUA, TextFormat::DARK_AQUA,
		TextFormat::LIGHT_PURPLE, TextFormat::UNDERLINE, TextFormat::DARK_PURPLE, TextFormat::DARK_GREEN];
		$rand = array_rand($colors, 1);
		return $colors[$rand];
	}

	public static function getAllColors(){
		$textFormat = new \ReflectionClass(TextFormat::class);
		return implode(", ", array_keys($textFormat->getConstants()));
	}

	public static function getColorByName(string $name){
		$textFormat = new \ReflectionClass(TextFormat::class);
		if($textFormat->hasConstant(strtoupper($name))){
			return $textFormat->getConstant(strtoupper($name));
		}
		return TextFormat::GRAY;
	}

	public static function getNameByColor($v){
		$textFormat = new \ReflectionClass(TextFormat::class);
		$constants = $textFormat->getConstants();
		$constName = "GRAY";
		foreach ($constants as $name=>$value ){
			if ($value == $v){
				$constName = $name;
				break;
			}
		}
		return $constName;
	}

	/**
	 * Replaces all in a string spaces with -
	 *
	 * @param $string
	 *
	 * @return mixed
	 */
	public static function stripSpaces($string) {
		return str_replace(" ", "_", $string);
	}

	/**
	 * Strip all white space in a string
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function stripWhiteSpace(string $string) {
		$string = preg_replace('/\s+/', "", $string);
		$string = preg_replace('/=+/', '=', $string);

		return $string;
	}

	/**
	 * @param int $range
	 * @param Level $level
	 * @return Position
	 */

	public static function randomizeCoordinates(int $range, Level $level){
		$x = mt_rand(-$range, $range);
		$z = mt_rand(-$range, $range);
		$y = 126;
		$pos = new Position($x, $y, $z, $level);
		if(!$level->isChunkLoaded($pos->x >> 4, $pos->y >> 4)){
			$level->loadChunk($pos->x >> 4, $pos->y >> 4);
		}
		do {
			$pos->y -= 1;
			$block = $pos->level->getBlock($pos);
		} while($pos->y > 0 and (!$block->isSolid() and $block->getId() !== Block::LAVA and $block->getId() !== Block::STILL_LAVA) and ($pos->level->getBlock($pos->add(0, 1))->isSolid() and $pos->level->getBlock($pos->add(0, 2))->isSolid()));
		if($pos->y > 0) {
			return $pos;
		}
		return self::randomizeCoordinates($range, $level);
	}

	/**
	 * Center a line of text based around the length of another line
	 *
	 * @param $toCentre
	 * @param $checkAgainst
	 *
	 * @return string
	 */
	public static function centerText($toCentre, $checkAgainst) {
		if(strlen($toCentre) >= strlen($checkAgainst)) {
			return $toCentre;
		}

		$times = floor((strlen($checkAgainst) - strlen($toCentre)) / 2);

		return str_repeat(" ", ($times > 0 ? $times : 0)) . $toCentre;
	}

	public static function clamp($var, $min, $max, Player $player, $message = null){
		if($var >= $max) {
			$player->sendMessage($message);
			return ($var = $max);
		} else if($var <= $min) {
			$player->sendMessage($message);
			return ($var = $min);
		} else {
			return $var;
		}
	}

	/**
	 * Return the stack trace
	 *
	 * @param int $start
	 * @param null $trace
	 *
	 * @return array
	 */
	public static function getTrace($start = 1, $trace = null) {
		if($trace === null) {
			if(function_exists("xdebug_get_function_stack")) {
				$trace = array_reverse(xdebug_get_function_stack());
			} else {
				$e = new \Exception();
				$trace = $e->getTrace();
			}
		}
		$messages = [];
		$j = 0;
		for($i = (int)$start; isset($trace[$i]); ++$i, ++$j) {
			$params = "";
			if(isset($trace[$i]["args"]) or isset($trace[$i]["params"])) {
				if(isset($trace[$i]["args"])) {
					$args = $trace[$i]["args"];
				} else {
					$args = $trace[$i]["params"];
				}
				foreach($args as $name => $value) {
					$params .= (is_object($value) ? get_class($value) . " " . (method_exists($value, "__toString") ? $value->__toString() : "object") : gettype($value) . " " . @strval($value)) . ", ";
				}
			}
			$messages[] = "#$j " . (isset($trace[$i]["file"]) ? ($trace[$i]["file"]) : "") . "(" . (isset($trace[$i]["line"]) ? $trace[$i]["line"] : "") . "): " . (isset($trace[$i]["class"]) ? $trace[$i]["class"] . (($trace[$i]["type"] === "dynamic" or $trace[$i]["type"] === "->") ? "->" : "::") : "") . $trace[$i]["function"] . "(" . substr($params, 0, -2) . ")";
		}

		return $messages;
	}

	/**
	 * Uses SHA-512 [http://en.wikipedia.org/wiki/SHA-2] and Whirlpool
	 * [http://en.wikipedia.org/wiki/Whirlpool_(cryptography)]
	 *
	 * Both of them have an output of 512 bits. Even if one of them is broken in the future, you have to break both
	 * of them at the same time due to being hashed separately and then XORed to mix their results equally.
	 *
	 * @param string $salt
	 * @param string $password
	 *
	 * @return string[128] hex 512-bit hash
	 */
	public static function hash($salt, $password) {
		$salt = strtolower($salt); // temp fix for password in chat check :p
		return bin2hex(hash("sha512", $password . $salt, true) ^ hash("whirlpool", $salt . $password, true));
	}

}