<?php
namespace ScientificGames\Event;


use pocketmine\event\Listener;
use ScientificGames\Main;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\Item;
use pocketmine\entity\Effect;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\level\sound\GhastShootSound;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;

class ItemEvent implements Listener
{
    private $plugin;
    private $Title = "§7-=§l§dScientificGames§r§7=-";
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }


    public function onPlayerInteract(PlayerInteractEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $player = $event->getPlayer();
            $inventory = $player->getInventory();
            $level = $player->getLevel();

            $item = $event->getItem()->getId();
            $damage = $event->getItem()->getDamage();
            $name = $player->getName();

            switch($item)
            {
                case 325:
                    if($damage == 8)//牛奶
                    {
                        $inventory->removeItem(new Item(325, 8,1));
                        $inventory->addItem(new Item(325,0,1));
                        $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+98);
                        $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+2);
                        $this->plugin->setWater($name,$this->plugin->getWater($name)+15);
                        $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    }
                    if($damage == 1)//水桶
                    {
                        if($this->plugin->getWater($name) >= $this->plugin->Thirst)
                        {
                            $player->sendMessage("你现在不渴！不需要补充水份！");
                            $event->setCancelled(true);
                        }
                        else
                        {
                            $inventory->removeItem(new Item(325, 10,1));
                            $inventory->addItem(new Item(325, 0,1));
                            $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+10);
                            $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+2);
                            $this->plugin->setWater($name,$this->plugin->getWater($name)+25);
                        }
                    }
                    break;
                case 437://肾上腺素 Item:龙息
                    $level->addSound(new GhastShootSound($player));
                    $player->sendTitle("§a你打了肾上激素","§6加强速度,挖掘速度,力量",2,2,40);
                    $player->addEffect(Effect::getEffect(Effect::SPEED)->setVisible(true)->setAmplifier(3)->setDuration(20*60*2));
                    $player->addEffect(Effect::getEffect(Effect::HASTE)->setVisible(true)->setAmplifier(3)->setDuration(20*60*2));
                    $player->addEffect(Effect::getEffect(Effect::STRENGTH)->setVisible(true)->setAmplifier(3)->setDuration(20*60*2));
                    $player->getInventory()->removeItem(new Item(437, 0, 1));
                    break;
                case 352://骨头
                    if($player->hasEffect(Effect::SLOWNESS))
                    {
                        $level->addSound(new AnvilFallSound($player));
                        $player->removeEffect(Effect::SLOWNESS);
                        $inventory->removeItem(new Item(352, 0, 1));
                        $player->sendMessage($this->Title."§6 你已换骨");
                    }
                    break;
                case 339://纸
                    if($player->hasEffect(Effect::POISON))
                    {
                        $level->addSound(new AnvilFallSound($player));
                        $player->removeEffect(Effect::POISON);
                        $inventory->removeItem(new Item(339, 0, 1));
                        $player->sendMessage($this->Title."§e 你已排毒");
                    }
                    break;
            }
        }
    }


    public function onHeld(PlayerItemHeldEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $player = $event->getPlayer();
            $item = $event->getItem();
            $itemid = $item->getId();
            $name = $player->getName();

            switch($itemid)
            {
                case 260://苹果
                    $player->sendMessage("§a>§3  特效药 §b适合症状:眩晕 §d治疗方法：食用 §6物品：苹果");
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 352://骨头
                    $player->sendMessage("§a>§6  骨头 §b适合症状:骨折 §d治疗方法：点地 §6物品：骨头");
                    break;
                case 400: //南瓜派
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $player->sendMessage("§a>§f  南瓜派 §b适合症状:虚弱 §d治疗方法：点地 §6物品：南瓜派");
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 297: //面包
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $player->sendMessage("§a>§e  士力架 §b适合症状:饥饿 §d治疗方法：食用 §6物品：面包");
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 357://曲奇
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $player->sendMessage("§a>§9  曲奇 §b适合症状:疲劳 §d治疗方法：食用 §6物品：曲奇");
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 391://胡萝卜
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $player->sendMessage("§a>§b  维生素A §b适合症状:失明 §d治疗方法：食用 §6物品：胡萝卜");
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 339://纸
                    $player->sendMessage("§a>§c  云南白药§b适合症状:中毒 §d治疗方法：点地  §6物品：纸");
                    break;
                case 437://龙息
                    $player->sendMessage("§a>§6  肾上腺素 §b加强速度,挖掘速度,力量");
                    break;
                case 466://金苹果
                case 322:
                    $player->sendMessage("§a你想磕掉呀吗2333");
                    break;
                case 396://金萝卜
                    $player->sendMessage("§a你想磕掉呀吗2333");
                    break;
                case 382://金西瓜
                    $player->sendMessage("§a你想磕掉呀吗2333");
                    break;
                case 349://生鱼
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $player->sendMessage("§a食用生肉可能会中毒哦~~");
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 350://熟鱼
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 367://腐肉
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 282://蘑菇汤
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 319://生猪
                    $player->sendMessage("§a食用生肉可能会中毒哦~~");
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 320://熟猪
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 365://生鸡
                    $player->sendMessage("§a食用生肉可能会中毒哦~~");
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 366://熟鸡
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 423://生羊
                    $player->sendMessage("§a食用生肉可能会中毒哦~~");
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 424://熟羊
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 363://生牛
                    $player->sendMessage("§a食用生肉可能会中毒哦~~");
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 364://熟牛
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 360://西瓜
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 392://生土豆
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 393://熟土豆
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 457://菜根
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 459://菜根汤
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 411://生兔
                    $player->sendMessage("§a食用生肉可能会中毒哦~~");
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 412://熟兔
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 413://兔汤
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 432://共鸣果
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
                case 433://爆裂共鸣果
                    $this->plugin->PlayerStateCheckExcretion($name,$player);
                    $this->plugin->PlayerStateCheckThirst($name,$player);
                    $this->plugin->PlayerStateCheckpH($name,$player);
                    break;
            }
        }
    }
    public function onPlayerEat(PlayerItemConsumeEvent $event)
    {
        if(in_array($event->getPlayer()->getLevel()->getFolderName(),$this->plugin->world->get("worlds")))
        {
            $player = $event->getPlayer();
            $item = $event->getItem();
            $itemid = $item->getId();
            $damage = $item->getDamage();

            $name= $player->getName();
            $ezz = $this->plugin->getWater($name);

            switch($itemid)
            {
                case 373:
                    if($damage == 0)
                    {
                        if($ezz >= $this->plugin->Thirst)
                        {
                            $player->sendMessage("你现在不渴！不需要补充水份！");
                            $event->setCancelled(true);
                        }
                        else
                        {
                            $this->plugin->setWater($name,$this->plugin->getWater($name)+15);
                            $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+0.5);
                            $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+3);
                        }
                    }
                    break;
                case 350://熟鱼
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+2.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+148);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-17);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    break;
                case 367://腐肉
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+2.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+118);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-11);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.1);
                    break;
                case 282://蘑菇汤
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+0.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+78);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)+9);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)+0.06);
                    break;
                case 320://熟猪
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+3.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+239);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-20);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    break;
                case 366://熟鸡
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+3.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+228);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-15);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    break;
                case 424://熟羊
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+3.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+239);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-19);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    break;
                case 364://熟牛
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+3.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+245);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-21);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    break;
                case 360://西瓜
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+0.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+35);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)+15);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)+0.06);
                    break;
                case 393://熟土豆
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+0.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+105);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-6);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)+0.06);
                    break;
                case 457://菜根
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+0.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+35);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)+13);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)+0.06);
                    break;
                case 459://菜根汤
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+0.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+78);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)+9);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)+0.06);
                    break;
                case 412://熟兔
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+2.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+245);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-18);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    break;
                case 413://兔汤
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+1);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+128);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)+7);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    break;
                case 432://共鸣果
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+0.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+30);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)+12);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)+0.06);
                    break;
                case 433://爆裂共鸣果
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+0.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+30);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)+12);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)+0.06);
                    break;
                case 260: //苹果---特效药
                    if($player->hasEffect(Effect::NAUSEA))
                    {
                        $player->removeEffect(Effect::NAUSEA);
                        $player->sendMessage($this->Title."  §a已缓解头晕！");
                    }
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+0.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+35);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)+13);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)+0.06);
                    break;
                case 297://面包---士力架
                    if($player->hasEffect(Effect::HUNGER))
                    {
                        $player->removeEffect(Effect::HUNGER);
                        $player->sendMessage($this->Title."  §e横扫饥饿，做回自己！");
                    }
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+1);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+160);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-7);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    break;
                case 357://曲奇
                    if($player->hasEffect(Effect::FATIGUE))
                    {
                        $player->removeEffect(Effect::FATIGUE);
                        $player->sendMessage($this->Title."  §2已缓解疲劳！");
                    }
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+1);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+183);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-7);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.05);
                    break;
                case 400://南瓜派
                    if($player->hasEffect(Effect::WEAKNESS))
                    {
                        $player->removeEffect(Effect::WEAKNESS);
                        $player->sendMessage($this->Title."  §6强身健体");
                    }
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+1.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+133);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-7);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    break;
                case 391://胡萝卜---维生素A
                    if($player->hasEffect(Effect::BLINDNESS))
                    {
                        $player->removeEffect(Effect::BLINDNESS);
                        $player->sendMessage($this->Title."  §3成功治疗失明！");
                    }
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+0.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+35);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)+13);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)+0.06);
                    break;
                case 392://马铃薯--中毒
                    $player->addEffect(Effect::getEffect(Effect::POISON)->setVisible(true)->setAmplifier(0)->setDuration(20*120));
                    $player->sendTitle("§e你中毒了","§a因为你生吃了马铃薯",2,2,40);
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+0.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+32);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)+13);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)+0.06);
                    break;
                case 466://金苹果
                case 322:
                    $player->setHealth($player->getHealth() - 13);
                    $player->sendTitle("§e你的牙被磕掉,造成大量流血","§a因为你食用了金苹果",2,2,40);
                    break;
                case 396://金萝卜
                    $player->setHealth($player->getHealth() - 13);
                    $player->sendTitle("§e你的牙被磕掉,造成大量流血","§a因为你食用了金萝卜",2,2,40);
                    break;
                case 382://金西瓜
                    $player->setHealth($player->getHealth() - 13);
                    $player->sendTitle("§e你的牙被磕掉,造成大量流血","§a因为你食用了金西瓜",2,2,40);
                    break;
                case 319://生猪肉
                    $num = mt_rand(0,100);
                    if($num <= 30)
                    {
                        $player->addEffect(Effect::getEffect(Effect::POISON)->setVisible(true)->setAmplifier(0)->setDuration(20*120));
                        $player->addEffect(Effect::getEffect(Effect::WEAKNESS)->setVisible(true)->setAmplifier(0)->setDuration(40));
                        $player->addEffect(Effect::getEffect(Effect::NAUSEA)->setVisible(false)->setAmplifier(0)->setDuration(20*120));
                        $player->sendTitle("§b你得了禽流感","§c因为你生吃了猪肉",2,2,40);
                        unset($num);
                    }
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+3.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+213);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-18);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    break;
                case 365://生鸡肉
                    $num = mt_rand(0,100);
                    if($num <= 30)
                    {
                        $player->addEffect(Effect::getEffect(Effect::POISON)->setVisible(true)->setAmplifier(0)->setDuration(20*120));
                        $player->addEffect(Effect::getEffect(Effect::WEAKNESS)->setVisible(true)->setAmplifier(0)->setDuration(40));
                        $player->addEffect(Effect::getEffect(Effect::NAUSEA)->setVisible(false)->setAmplifier(0)->setDuration(20*120));
                        $player->sendTitle("§b你得了鸡瘟","§c竟然敢生吃鸡？？",2,2,40);
                        unset($num);
                    }
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+3.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+188);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-17);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    break;
                case 423://生羊肉
                    $num = mt_rand(0,100);
                    if($num <= 30)
                    {
                        $player->addEffect(Effect::getEffect(Effect::POISON)->setVisible(true)->setAmplifier(0)->setDuration(20*120));
                        $player->addEffect(Effect::getEffect(Effect::WEAKNESS)->setVisible(true)->setAmplifier(0)->setDuration(40));
                        $player->addEffect(Effect::getEffect(Effect::NAUSEA)->setVisible(false)->setAmplifier(0)->setDuration(20*120));
                        $player->sendTitle("§c你生吃羊肉吃到了寄生虫","§b你得病了",2,2,40);
                        unset($num);
                    }
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+3.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+199);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-20);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    break;
                case 363://生牛肉
                    $num = mt_rand(0,100);
                    if($num <= 30)
                    {
                        $player->addEffect(Effect::getEffect(Effect::POISON)->setVisible(true)->setAmplifier(0)->setDuration(20*120));
                        $player->addEffect(Effect::getEffect(Effect::WEAKNESS)->setVisible(true)->setAmplifier(0)->setDuration(40));
                        $player->addEffect(Effect::getEffect(Effect::NAUSEA)->setVisible(false)->setAmplifier(0)->setDuration(20*120));
                        $player->sendTitle("§c你生吃的牛肉中有大肠杆菌","§a所以你生病了~",2,2,40);
                        unset($num);
                    }
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+3.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+230);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-23);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    break;
                case 411://生兔肉
                    $num = mt_rand(0,100);
                    if($num <= 30)
                    {
                        $player->addEffect(Effect::getEffect(Effect::POISON)->setVisible(true)->setAmplifier(0)->setDuration(20*120));
                        $player->addEffect(Effect::getEffect(Effect::WEAKNESS)->setVisible(true)->setAmplifier(0)->setDuration(40));
                        $player->addEffect(Effect::getEffect(Effect::NAUSEA)->setVisible(false)->setAmplifier(0)->setDuration(20*120));
                        $player->sendTitle("§c你吃到了带有病菌的生兔肉","§a所以你生病了~",2,2,40);
                        unset($num);
                    }
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+3.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+223);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-18);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    break;
                case 349://生鱼肉
                    $num = mt_rand(0,100);
                    if($num <= 30)
                    {
                        $player->addEffect(Effect::getEffect(Effect::POISON)->setVisible(true)->setAmplifier(0)->setDuration(20*120));
                        $player->addEffect(Effect::getEffect(Effect::WEAKNESS)->setVisible(true)->setAmplifier(0)->setDuration(40));
                        $player->addEffect(Effect::getEffect(Effect::NAUSEA)->setVisible(false)->setAmplifier(0)->setDuration(20*120));
                        $player->sendTitle("§c你吃到了生坏鱼肉","§a所以你生病了~",2,2,40);
                        unset($num);
                    }
                    $this->plugin->setExcretion($name,$this->plugin->getExcretion($name)+2.5);
                    $this->plugin->setEnergy($name,$this->plugin->getEnergy($name)+120);
                    $this->plugin->setWater($name,$this->plugin->getWater($name)-13);
                    $this->plugin->setpH($name,$this->plugin->getpH($name)-0.08);
                    break;
            }
        }
    }
}