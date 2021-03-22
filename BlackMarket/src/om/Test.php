<?php


namespace om;

use om\Commands\RemoveCooldown;
use pocketmine\plugin\PluginBase;
use pocketmine\player;
use om\Commands\BM;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Test extends PluginBase {

    public function onEnable()
    {
        @mkdir($this->getDataFolder() . "Cooldowns/");
        $this->registerCommands();
    }

    public function registerCommands(){
        $cmdMap = $this->getServer()->getCommandMap();
        $cmdMap->register('BM', new BM($this));
        $cmdMap->register('RemoveCooldown', new RemoveCooldown($this));
    }

    public $cd = [];

    public function cooldownCheck($player, $itemid){
        if(file_exists($this->getDataFolder() . "Cooldowns/" . strtolower($player->getName()))){
            $file = new Config($this->getDataFolder() . "Cooldowns/" . strtolower($player->getName()), Config::YAML);
            $time = $file->get($itemid);
            if($time <= time()){
                $this->addCooldown($player, $itemid);
                return null;
            } else {
                $cdtime = $time - time();
                return $cdtime;
            }
        } else {
            new Config($this->getDataFolder() . "Cooldowns/" . strtolower($player->getName()), Config::YAML, array (
                '268' => 0, '267' => 0, '276' => 0
            ));
            $this->addCooldown($player, $itemid);
            return null;
        }
    }

    public function addCooldown($player, $itemid){
        $file = new Config($this->getDataFolder() . "Cooldowns/" . strtolower($player->getName()), Config::YAML);
        $file->set($itemid, time() + 604800);
        $file->save();
    }
}



