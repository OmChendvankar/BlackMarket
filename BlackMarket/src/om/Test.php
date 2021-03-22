<?php


namespace om;

use om\Commands\RemoveCooldown;
use pocketmine\plugin\PluginBase;
use pocketmine\player;
use om\Commands\BM;

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

}



