<?php
namespace ScientificGames\Event;


use pocketmine\event\Listener;
use ScientificGames\Main;
use pocketmine\event\player\PlayerToggleSneakEvent;

class ExcretionEvent implements Listener
{

    private $plugin;
    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onToggleSneak(PlayerToggleSneakEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $player = $event->getPlayer();
            $name = $player->getName();
            $world = $player->getLevel();
            $block = $world->getBlock($player->floor()->subtract(0, 1));
            $ez = $this->plugin->getExcretion($name);
            $nl = $this->plugin->getEnergy($name);
            $id = $this->plugin->config->get("排泄方块ID");
            if($block->getId() == $id)
            {
                if($nl >= 20)
                {
                    if($ez >= 14)//判断排泄值是否大于或等于18
                    {
                        $this->plugin->setExcretion($name,0);//排泄值归零
                        $this->plugin->setEnergy($name,$nl-20);
                        $player->sendMessage("§b你已排泄");
                    }
                }
            }
        }
    }

}