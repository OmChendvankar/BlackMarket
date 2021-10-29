<?php


namespace om\BlackMarket;

use muqsit\invmenu\InvMenuHandler;
use om\BlackMarket\Commands\RemoveCooldown;
use pocketmine\plugin\PluginBase;
use om\BlackMarket\Commands\BM;
use pocketmine\utils\Config;

class Test extends PluginBase {

    public function onEnable()
    {
        $this->saveDefaultConfig();
        @mkdir($this->getDataFolder() . "Cooldowns/");
        $this->registerCommands();
        if(!InvmenuHandler::isRegistered()){
            InvmenuHandler::register($this);
        }
    }

    public function registerCommands(){
        $cmdMap = $this->getServer()->getCommandMap();
        $cmdMap->register('BlackMarket', new BM($this));
        $cmdMap->register('RemoveCooldown', new RemoveCooldown($this));
    }

    public $cd = [];

    public function cooldownCheck($player, $itemid){
        if(file_exists($this->getDataFolder() . "Cooldowns/" . strtolower($player->getName()))){
            $file = new Config($this->getDataFolder() . "Cooldowns/" . strtolower($player->getName()), Config::YAML);
            $time = $file->get($itemid);
            if($time >= time()){
                $cdtime = $time - time();
                return $cdtime;
            }
        } else {
            new Config($this->getDataFolder() . "Cooldowns/" . strtolower($player->getName()), Config::YAML, array (
                '268' => 0, '267' => 0, '276' => 0
            ));
            return null;
        }
    }

    public function addCooldown($player, $itemid){
        $file = new Config($this->getDataFolder() . "Cooldowns/" . strtolower($player->getName()), Config::YAML);
        $file->set($itemid, time() + 604800);
        $file->save();
    }
}



