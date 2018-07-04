<?php
namespace ScientificGames\Event;

use ScientificGames\Main;
use pocketmine\item\Item;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;

class ThirstEvent implements Listener
{

    private $plugin;
    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onChat(PlayerChatEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $name = $event->getPlayer()->getName();
            $this->plugin->PlayerWater($name);
        }
    }
    public function onPlayerInteract(PlayerInteractEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $name = $event->getPlayer()->getName();
            $this->plugin->PlayerWater($name);
        }
    }
    public function OnBreak(BlockBreakEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $name = $event->getPlayer()->getName();
            $this->plugin->PlayerWater($name);
        }
    }
    public function onMove(PlayerMoveEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $player = $event->getPlayer();
            $name = $player->getName();
            $this->plugin->PlayerWater($name);
            $block = $player->getLevel()->getBlock($player->floor()->subtract(0, 1))->getId();
            if($block == 88 OR $block == 12 OR $block == 24 OR $block == 128 OR
                $block == 181 OR $block == 179 OR $block == 182 OR $block == 180 )
            {
                $this->plugin->setThirstCount($name,$this->plugin->getThirstCount($name)+8);
            }
        }
    }
    public function onDropItem(PlayerDropItemEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $name = $event->getPlayer()->getName();
            $this->plugin->PlayerWater($name);
        }
    }
    public function onBlockPlace(BlockPlaceEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $name = $event->getPlayer()->getName();
            $this->plugin->PlayerWater($name);
        }
    }
    public function onToggleSneak(PlayerToggleSneakEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $name = $event->getPlayer()->getName();
            $this->plugin->PlayerWater($name);
        }
    }
    public function onHeld(PlayerItemHeldEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $name = $event->getPlayer()->getName();
            $this->plugin->PlayerWater($name);
        }
    }
    public function onPlayerEat(PlayerItemConsumeEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $name = $event->getPlayer()->getName();
            $this->plugin->PlayerWater($name);
        }
    }
}
