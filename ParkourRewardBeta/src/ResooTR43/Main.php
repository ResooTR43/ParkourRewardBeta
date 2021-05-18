<?php

namespace ResooTR43;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as C;
use pocketmine\event\Listener;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\Item;
use onebone\economyapi\EconomyAPI;
use pocketmine\tile\Sign;
use pocketmine\math\Vector3;

class Main extends PluginBase implements Listener{

    public function onEnable(){
        $this->getLogger()->info("OdulTabelası aktif edildi!");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function kurma(SignChangeEvent $event){
        $g = $event->getPlayer();

        if($event->getLine(0) == "[ParkourReward]"){
            if($g->isOp()){
                if($event->getLine(1) == null and $event->getLine(2) == null and $event->getLine(3) == null){
                    $event->setLine(0, "§a[§cParkur ödülü§a]");
                    $event->setLine(1, "§bTebrikler");
                    $event->setLine(2, "§bÖdülün");
                    $event->setLine(3, "§610K Para");
                }
            }else{
                $g->sendMessage("§c» Bunun İçin Yetkin Yok");
            }
        }
        if(!$g->isOp()){
            if($event->getLine(0) == "§a[§cParkur ödülü§a]" and $event->getLine(1) == "§bTebrikler" and $event->getLine(2) == "§bÖdülün" and $event->getLine(3) == "§610K Para"){
                $event->setLine(0, "§cBiz Bunuda §cDüşündük");
                $event->setLine(1, "§cHile Yapamazsın §cDostum");
            }
        }
    }

    public function tiklama(PlayerInteractEvent $event){
        $g = $event->getPlayer();
        $tile = $event->getBlock()->getLevel()->getTile(new Vector3($event->getBlock()->getFloorX(), $event->getBlock()->getFloorY(), $event->getBlock()->getFloorZ()));
        
        if($tile instanceof Sign){
            if($tile->getLine(0) == "§a[§cParkur ödülü§a]"){
                if($tile->getLine(3) == "§610K Para"){
                    $this->getServer()->loadLevel("Lobi");
                    EconomyAPI::getInstance()->addMoney($g, 10000);
                    $world = $this->getServer()->getLevelByName("Lobi");
				    $spawn = $world->getSafeSpawn();
				    $g->teleport($spawn, 0, 0);
				    $g->teleport(new Vector3($spawn->getX(), $spawn->getY(), $spawn->getZ()));
                    $g->sendMessage("» §aBaşarıyla Parkur Ödülünü Aldın");
                }
            }
        }
    }
}