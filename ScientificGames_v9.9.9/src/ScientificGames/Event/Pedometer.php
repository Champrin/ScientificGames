<?php

namespace ScientificGames\Event;

use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Player;
use ScientificGames\Main;
use pocketmine\event\player\PlayerJoinEvent;

class Pedometer implements Listener
{
    private $plugin;
    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    public function onjoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $x = floor($player->getX());
        $y = floor($player->getY());
        $z = floor($player->getZ());
        $pos = "{$x}:{$y}:{$z}";
        if(!$this->plugin->tip->exists($name))
        {
            $this->plugin->tip->set($name,[
                "Step_number"=>$this->plugin->Step,
                "Position"=>"0:0:0"
            ]);
            $this->plugin->tip->save();
        }
        else
        {
            $this->plugin->setPos($name,$pos);
            unset($x,$y,$z,$pos);
        }
    }
    public function onmove(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $x = floor($player->getX());
        $y = floor($player->getY());
        $z = floor($player->getZ());
        $pos = $this->plugin->getPos($name);
        $step =  $this->plugin->getStep($name);
        $num = explode(":",$pos);
        if($x != $num[0])
        {
            $a = abs($x-$num[0]);
            $step = $step+$a;
            $this->plugin->setStep($name,$step);
        }
        elseif($y != $num[1])
        {
            $a = abs($y-$num[1]);
            $step = $step+$a;
            $this->plugin->setStep($name,$step);
        }
        elseif($z != $num[2])
        {
            $a = abs($z-$num[2]);
            $step = $step+$a;
            $this->plugin->setStep($name,$step);
        }
        $this->plugin->setPos($name,"{$x}:{$y}:{$z}");

        unset($x,$y,$z,$pos,$step,$num);
    }
    public function oTeleport(EntityTeleportEvent $event)
    {
        $player = $event->getEntity();
        if($player instanceof Player)
        {
            $name = $player->getName();
            $x = floor($player->getX());
            $y = floor($player->getY());
            $z = floor($player->getZ());
            $pos = "{$x}:{$y}:{$z}";
            $this->plugin->setPos($name,$pos);
            unset($x,$y,$z,$pos);
        }
    }
}