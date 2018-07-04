<?php


namespace ScientificGames\Event;


use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use ScientificGames\Main;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerBedLeaveEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\utils\TextFormat as C;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;

class EnergyEvent implements Listener
{

    private $plugin;
    private $Title = "§7-=§l§dScientificGames§r§7=-";
    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onChat(PlayerChatEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $name = $event->getPlayer()->getName();
            $this->plugin->PlayerEnergy($name);
        }
    }
    public function onLeaveBed(PlayerBedLeaveEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $name = $event->getPlayer()->getName();
            $a = $this->plugin->getEnergy($name);
            $this->plugin->setEnergy($name,$a-120);
        }
    }
    public function onPlayerInteract(PlayerInteractEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $player = $event->getPlayer();
            $name = $player->getName();
            $this->plugin->PlayerEnergy($name);
        }
    }
    public function OnBreak(BlockBreakEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $player = $event->getPlayer();
            $name = $player->getName();
            $this->plugin->PlayerEnergy($name);
            $nl = $this->plugin->getEnergy($name);
            if($nl <= 20)
            {
                $event->setCancelled(true);
                $player->sendPopup(C::RED."你的能量太低,导致无法打破方块,你需要补充能量");
            }
        }
    }
    public function onMove(PlayerMoveEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $player = $event->getPlayer();
            $name = $player->getName();
            $this->plugin->PlayerEnergy($name);
            $block = $player->getLevel()->getBlock($player->floor()->subtract(0, 1))->getId();
            if($block == 88 OR $block == 12 OR $block == 24 OR $block == 128 OR
                $block == 181 OR $block == 179 OR $block == 182 OR $block == 180 )
            {
                $this->plugin->setEnergyCount($name,$this->plugin->getEnergyCount($name)+12);
            }
        }
    }
    public function onDropItem(PlayerDropItemEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $player = $event->getPlayer();
            $name = $event->getPlayer()->getName();
            $this->plugin->PlayerEnergy($name);
            $nl = $this->plugin->getEnergy($name);
            if($nl <= 20)
            {
                $event->setCancelled(true);
                $player->sendPopup(C::RED."你的能量太低,导致无法丢弃物品,你需要补充能量");
            }
        }
    }
    public function onBlockPlace(BlockPlaceEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $player = $event->getPlayer();
            $name = $event->getPlayer()->getName();
            $this->plugin->PlayerEnergy($name);
            $nl = $this->plugin->getEnergy($name);
            if($nl <= 20)
            {
                $event->setCancelled(true);
                $player->sendPopup(C::RED."你的能量太低,导致无法无法放置方块,你需要补充能量");
            }
        }
    }
    public function onToggleSneak(PlayerToggleSneakEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $player = $event->getPlayer();
            $name = $player->getName();
            $nl = $this->plugin->getEnergy($name);
            $this->plugin->PlayerEnergy($name);
            if($nl <= 20)
            {
                $event->setCancelled(true);
                $player->sendPopup(C::RED."你的能量太低,导致无法排泄,你需要补充能量");
            }
        }
    }
    public function onHeld(PlayerItemHeldEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $name = $event->getPlayer()->getName();
            $this->plugin->PlayerEnergy($name);
        }
    }
    public function onPlayerEat(PlayerItemConsumeEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $name = $event->getPlayer()->getName();
            $this->plugin->PlayerEnergy($name);
        }
    }

}