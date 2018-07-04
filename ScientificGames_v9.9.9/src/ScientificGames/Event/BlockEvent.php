<?php
namespace ScientificGames\Event;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use ScientificGames\Main;
use pocketmine\item\Item;
use pocketmine\block\DeadBush;
use pocketmine\block\TallGrass;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\block\Cactus;
use pocketmine\block\Leaves;
use pocketmine\block\Leaves2;
use pocketmine\block\Melon;
use pocketmine\block\Ice;
use pocketmine\block\PackedIce;
use pocketmine\block\Glass;
use pocketmine\block\GlassPane;
use pocketmine\block\Wood;
use pocketmine\block\BrownMushroomBlock;
use pocketmine\block\RedMushroomBlock;
use pocketmine\block\Vine;

class BlockEvent implements Listener
{
    private $plugin;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onPlayerInteract(PlayerInteractEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $player = $event->getPlayer();
            $name = $player->getName();

            $xyz = "{$event->getBlock()->getX()}:{$event->getBlock()->getY()}:{$event->getBlock()->getZ()}:{$event->getBlock()->getLevel()->getFolderName()}:{$name}";
            $bb = $this->plugin->block->get("Chest");
            if($event->getBlock()->getId() == 54)
            {
                if(in_array($xyz,$bb))
                {
                    $a = explode(":",$xyz);
                    if($player->getLevel()->getFolderName() == $a[3])
                    {
                        if($a[4] != $name)
                        {
                            $event->setCancelled(true);
                            $player->sendMessage("§7-=§l§dScientificGames§r§7=-  §a你不能使用别人的箱子");
                        }
                    }
                }
            }
        }
    }
    public function onBlockPlace(BlockPlaceEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $name = $event->getPlayer()->getName();
            $xyz = "{$event->getBlock()->getX()}:{$event->getBlock()->getY()}:{$event->getBlock()->getZ()}";
            $world = $event->getBlock()->getLevel()->getFolderName();
            if(!$this->plugin->block->exists($xyz) AND $event->getBlock()->getId() != 54)
            {
                $this->plugin->block->set($xyz,[
                    "name"=>$name,
                    "world"=>$world
                ]);
                $this->plugin->block->save();
            }
            if($event->getBlock()->getId() == 54)
            {
                if(!$this->plugin->chest->exists($xyz))
                {
                    $this->plugin->chest->set($xyz,[
                        "name"=>$name,
                        "world"=>$world
                    ]);
                    $this->plugin->chest->save();
                }
            }
        }
    }
    public function OnBreak(BlockBreakEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $player = $event->getPlayer();
            $item = $player->getInventory()->getItemInHand()->getId();
            $block = $event->getBlock();
            $health = $player->getHealth();
            $name = $player->getName();
            /**
             * todo:挖别人箱子会花费很多能量
             */
            $xyz = "{$event->getBlock()->getX()}:{$event->getBlock()->getY()}:{$event->getBlock()->getZ()}";
            if($event->getBlock()->getId() == 54)
            {
                if($this->plugin->chest->exists($xyz))
                {
                    $world = $this->plugin->chest->get($xyz)["world"];
                    if($player->getLevel()->getFolderName() == $world)
                    {
                        $host=$this->plugin->chest->get($xyz)["name"];
                        if($host == $name)
                        {
                            $this->plugin->chest->remove($xyz);
                            $this->plugin->chest->save();
                        }
                        else
                        {
                            $event->setCancelled(true);
                            $player->sendMessage("§7-=§l§dScientificGames§r§7=-  §a你不能破坏别人的箱子");
                        }
                    }
                }
            }
            if($this->plugin->block->exists($xyz))
            {
                $world = $this->plugin->block->get($xyz)["world"];
                if($player->getLevel()->getFolderName() == $world)
                {
                    $host=$this->plugin->block->get($xyz)["name"];
                    if($host == $name)
                    {
                        $this->plugin->block->remove($xyz);
                        $this->plugin->block->save();
                    }
                    else
                    {
                        $event->setCancelled(true);
                        $player->sendMessage("§7-=§l§dScientificGames§r§7=-  §a你不能破坏别人放置的方块");
                    }
                }
            }
            if($block instanceof Wood)
            {
                if($item == "0")
                {
                    $player->setHealth($health-1);
                    $player->sendTitle(" ","§7手撸木头,会伤害手哦~~",2,2,40);
                }
            }
            if($block instanceof TallGrass)
            {
                $event->setDrops(array(Item::get(295,0,1)));
            }
            if($block instanceof DeadBush)
            {
                $event->setDrops(array(Item::get(280,0,1)));
            }
            if($block instanceof Vine)
            {
                if($item == "0")
                {
                    $event->setDrops(array(Item::get(106,0,1)));
                }
            }
            if(($block instanceof Leaves) OR ($block instanceof Leaves2))
            {
                if($item == "0")
                {
                    $blockid = $block->getId();
                    $blockide = $block->getDamage();
                    $event->setDrops(array(Item::get($blockid,$blockide,1)));
                }
            }
            if($block instanceof Cactus)
            {
                if($item == "0")
                {
                    $player->setHealth($health-1);
                    $player->sendTitle(" ","§7手撸仙人掌,会伤害手哦~~",2,2,40);
                    $num = mt_rand(0,10);
                    $event->setDrops(array(Item::get(373,0,$num)));
                    $player->sendMessage(" ","§a你在仙人掌里找到了§e{$num}§a瓶水~~",2,2,40);
                    unset($num);
                }
                else
                {
                    $num = mt_rand(0,10);
                    $event->setDrops(array(Item::get(373,0,$num)));
                    $player->sendTitle(" ","§a你在仙人掌里找到了§e{$num}§a瓶水~~",2,2,40);
                    unset($num);
                }
            }
            if($block instanceof Melon)
            {
                if($item == "267" || $item == "272" || $item == "283" || $item == "276" || $item == "268" || $item == "359" )
                {
                    $event->setDrops(array(Item::get(103,0,1)));
                }
            }
            if($block instanceof Ice)
            {
                if($item == "267" || $item == "272" || $item == "283" || $item == "276" || $item == "268" )
                {
                    $event->setDrops(array(Item::get(79,0,1)));
                }
            }
            if($block instanceof PackedIce)
            {
                if($item == "267" || $item == "272" || $item == "283" || $item == "276" || $item == "268" )
                {
                    $event->setDrops(array(Item::get(174,0,1)));
                }
            }
            if($block instanceof Glass)
            {
                if($item == "267" || $item == "272" || $item == "283" || $item == "276" || $item == "268" || $item == "359" )
                {
                    $event->setDrops(array(Item::get(20,0,1)));
                }
            }
            if($block instanceof GlassPane)
            {
                if($item == "267" || $item == "272" || $item == "283" || $item == "276" || $item == "268" || $item == "359" )
                {
                    $event->setDrops(array(Item::get(102,0,1)));
                }
            }
            if($block instanceof BrownMushroomBlock)
            {
                $event->setDrops(array(Item::get(39,0,1)));
            }
            if($block instanceof RedMushroomBlock)
            {
                $event->setDrops(array(Item::get(40,0,1)));
            }
        }
    }

}