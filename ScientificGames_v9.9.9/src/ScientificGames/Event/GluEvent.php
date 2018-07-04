<?php
namespace ScientificGames\Event;


use pocketmine\event\Listener;
use ScientificGames\Main;
use pocketmine\entity\Effect;
use pocketmine\event\player\PlayerBedLeaveEvent;

class GluEvent implements Listener
{

    private $plugin;
    private $Title = "§7-=§l§dScientificGames§r§7=-";
    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onLeaveBed(PlayerBedLeaveEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $player = $event->getPlayer();
            $name = $player->getName();
            $a = $this->plugin->getEnergy($name);
            $this->plugin->setEnergy($name,$a-120);
            $xt = $this->plugin->getGlu($name);
            if($a-120 <= 0)
            {
                $player->sendMessage($this->Title."  §e你由于能量过低,已在睡梦中死去,一睡不醒！");
                $player->kill();
            }
            if($a-120 <= 60)
            {
                $player->sendTitle("§a你刚起床时能量过低","§e会有短时间眩晕和虚弱效果",2,2,40);
                $player->addEffect(Effect::getEffect(Effect::NAUSEA)->setVisible(true)->setAmplifier(0)->setDuration(20*20));
                $player->addEffect(Effect::getEffect(Effect::WEAKNESS)->setVisible(true)->setAmplifier(0)->setDuration(20*20));
            }
            if($xt < 3)
            {
                $player->sendTitle("§a你刚起床时因为血糖过低","§e会有短暂眩晕和虚弱效果",2,2,40);
                $player->addEffect(Effect::getEffect(Effect::NAUSEA)->setVisible(true)->setAmplifier(0)->setDuration(20*20));
                $player->addEffect(Effect::getEffect(Effect::WEAKNESS)->setVisible(true)->setAmplifier(0)->setDuration(40));
            }
        }
    }

}