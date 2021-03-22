<?php

namespace Om\Commands;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\event\Listener;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvmenuHandler;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use om\Test;

class BM extends Command implements  Listener{

    public $cd = [];

    public function onEnable(){
        @mkdir($this->getDataFolder() . "Cooldowns/");

        if(!InvmenuHandler::isRegistered()){
            InvmenuHandler::register($this);
        }
    }

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