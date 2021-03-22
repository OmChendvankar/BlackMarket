<?php

namespace Om;

use pocketmine\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\item\Item;

use pocketmine\event\Listener;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvmenuHandler;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;

use pocketmine\inventory\transaction\action\SlotChangeAction;

use pocketmine\utils\Config; # NEW
use onebone\economyapi\EconomyAPI;

class BlackMarket extends PluginBase implements Listener {

	public $cd = []; # NEW

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

    public function removeCooldown($player){
        unlink($this->getDataFolder() . 'Cooldowns/' . strtolower($player->getName()));
    }

	public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args) : bool{

		switch($cmd->getName()){
			case "blackmarket":
			if($sender instanceof Player){
				$menu = InvMenu::create(InvMenu::TYPE_HOPPER);
				$menu->setName("Black Market");
				$inv = $menu->getInventory();
				$menu->readonly();
				$inv->setItem(0, Item::get(268, 0, 1,));
				$inv->setItem(1, Item::get(0, 0, 1,));
				$inv->setItem(2, Item::get(267, 0, 1,));
				$inv->setItem(3, Item::get(0, 0, 1,));
				$inv->setItem(4, Item::get(276, 0, 1,));
				$menu->setListener(function (InvMenuTransaction $transaction) : InvMenuTransactionResult{
					$player = $transaction->getPlayer();
					$action = $transaction->getAction();
					$itemClicked = $transaction->getItemClicked();
					if($itemClicked->getId() == 268){
						$itemid = $itemClicked->getId();
						$check = $this->cooldownCheck($player, $itemid);
                        $money = EconomyAPI::getInstance()->myMoney($player);
                        $cost = 10000;
						if($check === null && $money >= $cost){
							$player->getInventory()->addItem($itemClicked);
                            EconomyAPI::getInstance()->reduceMoney($player , $cost); #Change the amount to change the price of the item
							$player->sendMessage("§aYou've Purchased from §bBlackMarket");
							$player->removeWindow($action->getInventory());
						} else {
							$time = (int)$check;
							$hours = floor($check/3600);
							$minutes = floor(($check/60) % 60);
							$seconds = $check % 60;
							$player->sendMessage("§cYou are on cooldown for $hours hours $minutes minutes $seconds seconds");
							$player->sendMessage("You do not have enough money");
							$player->removeWindow($action->getInventory());
						}
					}
					if($itemClicked->getId() == 267){
						$itemid = $itemClicked->getId();
						$check = $this->cooldownCheck($player, $itemid);
                        $money = EconomyAPI::getInstance()->myMoney($player);
                        $cost = 15000;
						if($check === null && $money >= $cost){
							$player->getInventory()->addItem($itemClicked);
                            EconomyAPI::getInstance()->reduceMoney($player, $cost); #Change the amount to change the price of the item
							$player->sendMessage("§aYou've Purchased from §bBlackMarket");
							$player->removeWindow($action->getInventory());
						} else {
							$time = (int)$check;
							$hours = floor($check/3600);
							$minutes = floor(($check/60) % 60);
							$seconds = $check % 60;
							$player->sendMessage("§cYou are on cooldown for $hours hours $minutes minutes $seconds seconds");
                            $player->sendMessage("You do not have enough money");
							$player->removeWindow($action->getInventory());
						}
					}
					if($itemClicked->getId() == 276){
						$itemid = $itemClicked->getId();
						$check = $this->cooldownCheck($player, $itemid);
                        $money = EconomyAPI::getInstance()->myMoney($player);
                        $cost = 20000;
						if($check === null && $money >= $cost){
							$player->getInventory()->addItem($itemClicked);
                            EconomyAPI::getInstance()->reduceMoney($player, $cost); #Change the amount to change the price of the item
							$player->sendMessage("§aYou've Purchased from §bBlackMarket");
							$player->removeWindow($action->getInventory());
						} else {
							$time = (int)$check;
							$hours = floor($check/3600);
							$minutes = floor(($check/60) % 60);
							$seconds = $check % 60;
							$player->sendMessage("§cYou are on cooldown for $hours hours $minutes minutes $seconds seconds");
                            $player->sendMessage("You do not have enough money");
							$player->removeWindow($action->getInventory());
						}
					}
					return $transaction->discard();
				});
				$menu->send($sender);
			} break;
            case "removecooldown":
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
                $this-> removeCooldown($target);
                $sender->sendMessage($target->getName() . "'s cooldown has been removed");
		}
		return true;
	}
};
