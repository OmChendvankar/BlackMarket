<?php


namespace Om\Commands;

use Om\Test;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;

class RemoveCooldown extends Command {

    /** @var Main **/
    private $main;
    
    public function __construct(Test $main){
        $this->main = $main;
        
        parent::_construct('commandname', 'command description');
        $this->setPermission('blackmarket.remove.cooldown');
    }

    public function removeCooldown($player){
        if(file_exists($this->main->getDataFolder() . "Cooldowns/" . strtolower($player->getName()))){
            unlink($this->main->getDataFolder() . 'Cooldowns/' . strtolower($player->getName()));
        }
    }

    public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args) {
        if(!$sender instanceof Player){
            $sender->sendMessage("Run the command in game");
            return false;
        }

        if(!$sender->hasPermission('blackmarket.remove.cooldown')){
            $sender->sendMessage("You cant run this command!");
            return false;
        }

        if(!isset($args[0])){
            $sender->sendMessage("§busage : /removecooldown ign");
            return false;
        }

        if($this->getServer()->getPlayer($args[0]) === null or !$this->getServer()->getPlayer($args[0])->isOnline()){
            $sender->sendMessage("Player is not online");
            return false;
        }

        $target = $this->getServer()->getPlayer($args[0]);
        $this->removeCooldown($target);
        $sender->sendMessage($target->getName() . "'s cooldown has been removed");
    }


}