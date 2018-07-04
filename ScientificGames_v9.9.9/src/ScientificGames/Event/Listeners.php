<?php
namespace ScientificGames\Event;


use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerMoveEvent;
use ScientificGames\Main;
use pocketmine\Player;
use ScientificGames\Task\CheckTask;
use ScientificGames\Task\Task;
use ScientificGames\Task\HightTask;

class Listeners implements Listener
{

    private $plugin;
    private $Title = "§7-=§l§dScientificGames§r§7=-";
    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onUseCmd(PlayerCommandPreprocessEvent $event)
    {
        if($this->plugin->config->get("不能使用指令") == true)
        {
            if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
            {
                $cmd = $event->getMessage();
                $player = $event->getPlayer();
                if(count(explode("/",$cmd)) == 2 AND explode(" ",$cmd)[0] !== "/ill" AND explode(" ",$cmd)[0] !== "/rlset")
                {
                    $event->setCancelled(true);
                    $player->sendMessage($this->Title."§9你在真实生存世界里不能输入其他指令,退出请输入§a- cancel");
                }
            }
        }
    }
    public function onChat(PlayerChatEvent $event)
    {
        if($this->plugin->config->get("不能使用指令") == true)
        {
            if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
            {
                $cmd = $event->getMessage();
                $player = $event->getPlayer();
                $name = $player->getName();
                if($cmd == "- cancel")
                {
                    $this->plugin->tip->remove($name);
                    $this->plugin->tip->save();
                    $event->setCancelled(true);
                    $player->sendMessage($this->Title."§d你已退出真实生存世界,你的相关信息已删除");
                    $level = $this->plugin->getServer()->getDefaultLevel()->getSpawnLocation();
                    $player->teleport($level);
                    $player->sendTitle("§l§a已将你传送回主城","§l----§eScientificGames科学游戏",2,2,60);
                }
            }
        }
    }
    public function onDeath(PlayerDeathEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $player = $event->getPlayer();
            $name = $player->getName();
            $level = $event->getPlayer()->getLevel()->getFolderName();
            $player->sendTitle("§a你在真实生存世界§l§c[{$level}]§a中死亡了","§l----§eScientificGames科学游戏",2,2,60);
            $level = $this->plugin->getServer()->getDefaultLevel()->getSpawnLocation();
            $player->teleport($level);
            $player->sendTitle("§l§a已将你传送回主城","§l----§eScientificGames科学游戏",2,2,60);
            $this->plugin->tip->remove($name);
            $this->plugin->tip->save();
        }
    }
    public function oTeleport(EntityTeleportEvent $event)
    {
        $player = $event->getEntity();
        if($player instanceof Player)
        {
            $name = $player->getName();
            $level = $event->getTo()->getLevel()->getFolderName();
            if(in_array($level,$this->plugin->world->get("worlds")))
            {
                $this->plugin->PlayerConfigCheck($name);
                $this->plugin->getServer()->getScheduler()->scheduleRepeatingTask(new CheckTask($this->plugin,$player), 100);
                $this->plugin->getServer()->getScheduler()->scheduleRepeatingTask(new HightTask($this->plugin,$player), 20*60*60);
                $this->plugin->getServer()->getScheduler()->scheduleRepeatingTask(new Task($this->plugin,$player), 20*60*6);
                $player->sendTitle("§a你来到了§l§c[{$level}]§a真实世界","§l----§eScientificGames科学游戏",2,2,60);
            }
        }
    }
    public function ontip(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();
        if(in_array($player->getLevel()->getFolderName(), $this->plugin->world->get("worlds")))
        {
            if ($player->getGamemode() != 0)
            {
                $player->setGamemode(0);
                $player->sendMessage("§7-=§l§dScientificGames§r§7=-  检测到你不是生存模式,已将你切换为生存模式");
            }
            $send = $this->plugin->config->get("Tip");
            $name = $player->getName();
            $shl = $this->plugin->getWater($name);
            $pxz = $this->plugin->getExcretion($name);
            $nl = $this->plugin->getEnergy($name);
            $tw = $this->plugin->getTemperature($name);
            $xt = $this->plugin->getGlu($name);
            $ph = $this->plugin->getpH($name);
            $sg = $this->plugin->getHight($name);
            $ss = $this->plugin->getAge($name);
            $bs = $this->plugin->getStep($name);
            $xl = $this->plugin->getHeart_rate($name);
            $pld = $this->plugin->getFatigue($name);
            $a = str_replace("{水含量}", $shl, $send);
            $b = str_replace("{排泄值}", $pxz, $a);
            $c = str_replace("{能量值}", $nl, $b);
            $d = str_replace("{体温值}", $tw, $c);
            $e = str_replace("{血糖}", $xt, $d);
            $f = str_replace("{pH值}", $ph, $e);
            $g = str_replace("{身高}", $sg, $f);
            $h = str_replace("{年龄}", $ss, $g);
            $i = str_replace("{步数}", $bs, $h);
            $j = str_replace("{心率}", $xl, $i);
            $k = str_replace("{疲劳度}", $pld, $j);
            $abc = $this->plugin->config->get("底部显示格式");
            if ($abc == "tip") {
                $player->sendTip($k);
            } elseif ($abc == "pop") {
                $player->sendPopup($k);
            } elseif ($abc == "no") {
                return false;
            }
            return true;
        }
    }

}