<?php

namespace om\Commands;

use FolderPluginLoader\Main;
use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\event\Listener;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use om\Test;

class BM extends Command implements  Listener{

    /** @var Main **/
    private $main;

    public function __construct(Test $main){
        $this->main = $main;

        parent::__construct('§bblackmarket', '§bOpens Blackmarket menu', '/blackmarket', ['bm']);
    }

    public $cd = [];

    public function onEnable(){
        @mkdir($this->main->getDataFolder() . "Cooldowns/");

    }

    public function execute(CommandSender $sender, String $label, Array $args) {

        if($sender instanceof Player){
            $menu = InvMenu::create(InvMenu::TYPE_HOPPER);
            $menu->setName("Black Market");
            $inv = $menu->getInventory();
            $config = $this->main->getConfig();
            $inv->setItem(0, Item::get($config->get("Item1_Id"),$config->get("Item1_Meta"),$config->get("Item1_Amt")));
            $inv->setItem(1, Item::get(0, 0, 1,));
            $inv->setItem(2, Item::get($config->get("Item2_Id"),$config->get("Item2_Meta"),$config->get("Item3_Amt")));
            $inv->setItem(3, Item::get(0, 0, 1,));
            $inv->setItem(4, Item::get($config->get("Item3_Id"),$config->get("Item3_Meta"),$config->get("Item3_Amt")));
            $menu->setListener(function (InvMenuTransaction $transaction) : InvMenuTransactionResult{
                $player = $transaction->getPlayer();
                $action = $transaction->getAction();
                $itemClicked = $transaction->getItemClicked();
                if($itemClicked->getId() == 268){
                    $itemid = $itemClicked->getId();
                    $check = $this->main->cooldownCheck($player, $itemid);
                    $money = EconomyAPI::getInstance()->myMoney($player);
                    $cost = $this->main->getConfig()->get("Cost1");
                    if($check === null && $money >= $cost){
                        $player->getInventory()->addItem($itemClicked);
                        EconomyAPI::getInstance()->reduceMoney($player , $cost);
                        $player->sendMessage("§aYou've Purchased from §bBlackMarket");
                        $this->main->addCooldown($player, $itemid);
                        $player->removeWindow($action->getInventory());
                    }
                    if($money < $cost) {
                        $player->sendMessage("You do not have enough money");
                        $player->removeWindow($action->getInventory());
                    }
                    if(!$check == null) {
                        $hours = floor($check/3600);
                        $minutes = floor(($check/60) % 60);
                        $seconds = $check % 60;
                        $player->sendMessage("§cYou are on cooldown for $hours hours $minutes minutes $seconds seconds");
                        $player->removeWindow($action->getInventory());
                    }
                }
                if($itemClicked->getId() == 267){
                    $itemid = $itemClicked->getId();
                    $check = $this->main->cooldownCheck($player, $itemid);
                    $money = EconomyAPI::getInstance()->myMoney($player);
                    $cost = $this->main->getConfig()->get("Cost2");
                    if($check === null && $money >= $cost){
                        $player->getInventory()->addItem($itemClicked);
                        EconomyAPI::getInstance()->reduceMoney($player, $cost);
                        $player->sendMessage("§aYou've Purchased from §bBlackMarket");
                        $this->main->addCooldown($player, $itemid);
                        $player->removeWindow($action->getInventory());
                    }
                    if($money < $cost) {
                        $player->sendMessage("You do not have enough money");
                        $player->removeWindow($action->getInventory());
                    }
                    if(!$check == null) {
                        $hours = floor($check/3600);
                        $minutes = floor(($check/60) % 60);
                        $seconds = $check % 60;
                        $player->sendMessage("§cYou are on cooldown for $hours hours $minutes minutes $seconds seconds");
                        $player->removeWindow($action->getInventory());
                    }
                }
                if($itemClicked->getId() == 276){
                    $itemid = $itemClicked->getId();
                    $check = $this->main->cooldownCheck($player, $itemid);
                    $money = EconomyAPI::getInstance()->myMoney($player);
                    $cost = $this->main->getConfig()->get("Cost3");
                    if($check === null && $money >= $cost){
                        $player->getInventory()->addItem($itemClicked);
                        EconomyAPI::getInstance()->reduceMoney($player, $cost);
                        $player->sendMessage("§aYou've Purchased from §bBlackMarket");
                        $this->main->addCooldown($player, $itemid);
                        $player->removeWindow($action->getInventory());
                    }
                    if($money < $cost) {
                        $player->sendMessage("You do not have enough money");
                        $player->removeWindow($action->getInventory());
                    }
                    if(!$check == null) {
                        $hours = floor($check/3600);
                        $minutes = floor(($check/60) % 60);
                        $seconds = $check % 60;
                        $player->sendMessage("§cYou are on cooldown for $hours hours $minutes minutes $seconds seconds");
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