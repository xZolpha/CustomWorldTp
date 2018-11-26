<?php
/**
 * Created by PhpStorm.
 * User: Zolpha#0001
 * Date: 25-Nov-18
 */

namespace CustomWorldTp;

use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as R;
use pocketmine\math\Vector3;

class Main extends PluginBase implements Listener{

	const WORLDNOTFOUND = R::RED . "Sorry, I couldn't find that world in the Worlds\ directory!";

	public function onEnable(){
		$this->getLogger()->info(R::GREEN . "CustomWorldTp Enabled.");
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		if($cmd->getName() == "wtp"){
			if($sender instanceof Player){
				if(!empty($args[0])){
					if(file_exists($this->getServer()->getDataPath() . "worlds/" . $args[0])){
						$notp = $args[0];
						if(!$this->getServer()->isLevelLoaded($notp)){
							$this->getServer()->loadLevel($notp);
						}
						$snotp = $this->getServer()->getLevelByName($notp);
						$this->TPOtherWorld($sender, $snotp);
					}else{
						$sender->sendMessage(self::WORLDNOTFOUND);
					}
				}
			}
		}
		if($cmd->getName() == "worldlist"){
			if($sender instanceof Player){
				$sender->sendMessage(R::GREEN . "Available Worlds:");
				foreach($this->getServer()->getLevels() as $worlds){
					if($this->getServer()->isLevelLoaded($worlds->getName())){
						$sender->sendMessage(R::GRAY . "- " . R::AQUA . $worlds->getName() . " " . R::GRAY . "(" . R::WHITE . count($worlds->getPlayers()) . R::GRAY . ")" . R::RESET);
					}else{
						$sender->sendMessage(R::GRAY . "- " . R::RED . $worlds->getName() . " " . R::GRAY . "(" . R::WHITE . count($worlds->getPlayers()) . R::GRAY . ")" . R::RESET);
					}
				}
			}
		}
		return true;
	}

	public function TPOtherWorld(Player $player, Level $world){
		$safespawn = $world->getSpawnLocation();
		$player->teleport(new Position($safespawn, $world));
		$player->sendMessage(R::DARK_PURPLE . "Hey, look at that. You've been teleported to " . $world->getName());
	}

	public function onDisable(){
		$this->getLogger()->info(R::RED . "CustomWorldTp Disabled");
	}
}
