<?php

namespace om\Commands;

use FolderPluginLoader\Main;
use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
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

    /** @var Main **/
    private $main;

    public function __construct(Test $main){
        $this->main = $main;

        parent::__construct('BlackMarket', '§bOPens Blackmarket menu');
    }

    public $cd = [];

    public function onEnable(){
        @mkdir($this->main->getDataFolder() . "Cooldowns/");

        if(!InvmenuHandler::isRegistered()){
            InvmenuHandler::register($this->main);
        }
    }

    public function execute(CommandSender $sender, String $label, Array $args) {

        if($sender instanceof Player){
            $menu = InvMenu::create(InvMenu::TYPE_HOPPER);
            $menu->setName("Black Market");
            $inv = $menu->getInventory();
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
                    $check = $this->main->cooldownCheck($player, $itemid);
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
                    $check = $this->main->cooldownCheck($player, $itemid);
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
                    $check = $this->main->cooldownCheck($player, $itemid);
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
        }
        return true;
    }
}