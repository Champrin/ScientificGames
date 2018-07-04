<?php


namespace ScientificGames\Commands;

use onebone\economyapi\EconomyAPI;
use ScientificGames\Main;
use pocketmine\level\generator\Generator;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;

class Commands implements CommandExecutor{
	
	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
    public function help($s)
    {
        $s->sendMessage(C::GRAY .      "============== -=§l§dScientificGames§r§7=- ================");
        $s->sendMessage(C::WHITE.      "/rlset help                         §8查看帮助");
        $s->sendMessage(C::WHITE.      "/rlset reload                       §8重载配置文件");
        $s->sendMessage(C::WHITE.      "/rlset addworld [地图名] [种子]      §3生成§8真实生存世界");
        $s->sendMessage(C::WHITE.      "/rlset setworld [世界名称]          §3添加§8真实生存世界");
        $s->sendMessage(C::WHITE.      "/rlset delworld [世界名称]          §3移除§8真实生存世界");
        $s->sendMessage(C::WHITE.      "/kzz [玩家名称] [数值]              §8设置玩家§3水份值");
        $s->sendMessage(C::WHITE.      "/pxz [玩家名称] [数值]              §8设置玩家§3排泄值");
        $s->sendMessage(C::WHITE.      "/tw  [玩家名称] [数值]              §8设置玩家§3体温值");
        $s->sendMessage(C::WHITE.      "/nl  [玩家名称] [数值]              §8设置玩家§3能量值");
        $s->sendMessage(C::WHITE.      "/ss  [玩家名称] [数值]              §8设置玩家§3年龄");
        $s->sendMessage(C::WHITE.      "/sg  [玩家名称] [数值]              §8设置玩家§3身高");
        $s->sendMessage(C::WHITE.      "/ph  [玩家名称] [数值]              §8设置玩家§3pH值");
    }
    public function ill($s)
    {
        $s->sendMessage(C::GRAY .      "==============-=§l§dScientificGames§r§7=-====================");
        $s->sendMessage(C::DARK_AQUA . "> 特效药   §b适合症状:§a眩晕  §d治疗方法:§e食用 §6物品:§f苹果 ");
        $s->sendMessage(C::GOLD .      "> 骨头     §b适合症状:§a骨折  §d治疗方法:§e点地 §6物品:§f骨头");
        $s->sendMessage(C::WHITE .     "> 南瓜派   §b适合症状:§a虚弱  §d治疗方法:§e食用 §6物品:§f南瓜派");
        $s->sendMessage(C::YELLOW .    "> 士力架   §b适合症状:§a饥饿  §d治疗方法:§e食用 §6物品:§f面包");
        $s->sendMessage(C::GREEN .     "> 曲奇     §b适合症状:§a疲劳  §d治疗方法:§e食用 §6物品:§f曲奇");
        $s->sendMessage(C::AQUA .      "> 维生素A  §b适合症状:§a失明  §d治疗方法:§e食用 §6物品:§f胡萝卜");
        $s->sendMessage(C::RED .       "> 云南白药 §b适合症状:§a中毒  §d治疗方法:§e点地 §6物品:§f纸");
    }
    public function onCommand(CommandSender $s, Command $command, $label, array $args)
    {
        $Title = "§7-=§l§dScientificGames§r§7=-";
        switch($command->getName())
        {
            case "ph":
                if(!isset($args[0]))
                {
                    $s->sendMessage($Title."  §a你未输入玩家名");
                    return true;
                }
                if(!isset($args[1]))
                {
                    $s->sendMessage($Title."  §a你未输入要设置的pH值");
                    return true;
                }
                if(!is_numeric($args[1]))
                {
                    $s->sendMessage($Title."  §a输入的pH值不为数字,请重新输入");
                    return true;
                }
                if($args[1] < 1)
                {
                    $s->sendMessage($Title."  §a输入的pH值不为正数,请重新输入");
                    return true;
                }
                else
                {
                    $this->plugin->setAge($args[0],$args[1]);
                    $s->sendMessage($Title."  §a已设置玩家§c$args[0]§a的pH值为§c$args[1]");
                    return true;
                }
            case "ss":
                if(!isset($args[0]))
                {
                    $s->sendMessage($Title."  §a你未输入玩家名");
                    return true;
                }
                if(!isset($args[1]))
                {
                    $s->sendMessage($Title."  §a你未输入要设置的年龄");
                    return true;
                }
                if(!is_numeric($args[1]))
                {
                    $s->sendMessage($Title."  §a输入的年龄不为数字,请重新输入");
                    return true;
                }
                if($args[1] < 1)
                {
                    $s->sendMessage($Title."  §a输入的年龄不为正数,请重新输入");
                    return true;
                }
                else
                {
                    $this->plugin->setAge($args[0],$args[1]);
                    if($args[1] <= 21)
                    {
                        $this->plugin->setHight($args[0],$args[1]*0.05+0.4);
                    }
                    elseif($args[1] > 21)
                    {
                        $this->plugin->setHight($args[0],1.45);
                    }
                    $this->plugin->PlayerHight($args[0]);
                    $s->sendMessage($Title."  §a已设置玩家§c$args[0]§a的年龄为§c$args[1]");
                    $s->sendMessage($Title."  §a且身高也随之变化了");
                    return true;
                }
            case "sg":
                if(!isset($args[0]))
                {
                    $s->sendMessage($Title."  §a你未输入玩家名");
                    return true;
                }
                if(!isset($args[1]))
                {
                    $s->sendMessage($Title."  §a你未输入要设置的身高");
                    return true;
                }
                if(!is_numeric($args[1]))
                {
                    $s->sendMessage($Title."  §a输入的身高不为数字,请重新输入");
                    return true;
                }
                if($args[1] < 1)
                {
                    $s->sendMessage($Title."  §a输入的身高不为正数,请重新输入");
                    return true;
                }
                else
                {
                    $this->plugin->setHight($args[0],$args[1]);
                    $this->plugin->PlayerHight($args[0]);
                    $s->sendMessage($Title."  §a已设置玩家§c$args[0]§a的身高为§c$args[1]");
                    return true;
                }
            case "kkz":
                if(!isset($args[0]))
                {
                    $s->sendMessage($Title."  §a你未输入玩家名");
                    return true;
                }
                if(!isset($args[1]))
                {
                    $s->sendMessage($Title."  §a你未输入要设置的水含量值");
                    return true;
                }
                if(!is_numeric($args[1]))
                {
                    $s->sendMessage($Title."  §a输入的水含量值不为数字,请重新输入");
                    return true;
                }
                else
                {
                    $this->plugin->setWater($args[0],$args[1]);
                    $s->sendMessage($Title."  §a已设置玩家§c$args[0]§a的水含量为§c$args[1]");
                    return true;
                }
            case "nl":
                if(!isset($args[0]))
                {
                    $s->sendMessage($Title."  §a你未输入玩家名");
                    return true;
                }
                if(!isset($args[1]))
                {
                    $s->sendMessage($Title."  §a你未输入要设置的能量值");
                    return true;
                }
                if (!is_numeric($args[1]))
                {
                    $s->sendMessage($Title."  §a输入的能量值不为数字,请重新输入");
                    return true;
                }
                else
                {
                    $this->plugin->setEnergy($args[0],$args[1]);
                    $s->sendMessage($Title."  §a已设置玩家§c$args[0]§a的能量值为§c$args[1]");
                    return true;
                }
            case "pxz":
                if(!isset($args[0]))
                {
                    $s->sendMessage($Title."  §a你未输入玩家名");
                    return true;
                }
                if(!isset($args[1]))
                {
                    $s->sendMessage($Title."  §a你未输入要设置的排泄值");
                    return true;
                }
                if (!is_numeric($args[1]))
                {
                    $s->sendMessage($Title."  §a输入的排泄值不为数字,请重新输入");
                    return true;
                }
                else
                {
                    $this->plugin->setExcretion($args[0],$args[1]);
                    $s->sendMessage($Title."  §a已设置玩家§c$args[0]§a的排泄值为§c$args[1]");
                    return true;
                }
            case "tw":
                if(!isset($args[0]))
                {
                    $s->sendMessage($Title."  §a你未输入玩家名");
                    return true;
                }
                if(!isset($args[1]))
                {
                    $s->sendMessage($Title."  §a你未输入要设置的体温值");
                    return true;
                }
                if (!is_numeric($args[1]))
                {
                    $s->sendMessage($Title."  §a输入的体温值不为数字,请重新输入");
                    return true;
                }
                else
                {
                    $this->plugin->setTemperature($args[0],$args[1]);
                    $s->sendMessage($Title."  §a已设置玩家§c$args[0]§a的体温值为§c$args[1]");
                    return true;
                }
            case "ill":
                $this->ill($s);
                return true;
            case "rlset":
                if(count($args) === 0)
                {
                    $this->help($s);
                    return true;
                }
                if($args[0]!="help"  AND $args[0]!="reload" AND $args[0]!="addworld" AND $args[0]!="delworld"
                    AND $args[0]!="nl" AND $args[0]!="tw" AND $args[0]!="pxz" AND $args[0]!="kzz"
                    AND $args[0]!="setworld")
                {
                    $s->sendMessage($Title." §c指令输入错误！");
                    $this->help($s);
                    return true;
                }
                if($args[0] == "help")
                {
                    $this->help($s);
                    return true;
                }

                if(isset($args[0]))
                {
                    if($args[0] == "reload")
                    {
                        $this->plugin->tip->reload();
                        $this->plugin->world->reload();
                        $this->plugin->config->reload();
                        $this->plugin->block->reload();
                        $this->plugin->chest->reload();
                        $s->sendMessage($Title."  §f配置重载完成");
                        return true;
                    }
                    if($args[0]=="addworld")
                    {
                        if(isset($args[1]))
                        {
                            $levels=$this->plugin->world->get("worlds");
                            $level=$args[1];
                            if($this->plugin->getServer()->isLevelGenerated($level))
                            {
                                $s->sendMessage($Title."  对不起，此地图已存在，请换个名字生成！");
                                return true;
                            }
                            else
                            {
                                if(isset($args[2]))
                                {
                                    $seed=$args[2];
                                    $opts=[];
                                    $gen=Generator::getGenerator("default");
                                    $s->sendMessage($Title."  §9正在生成真实生存世界地图§a{$level}§9中，过程可能会卡顿");
                                    $this->plugin->getServer()->generateLevel($level,$seed,$gen,$opts);
                                    $this->plugin->getServer()->loadLevel($level);
                                    $s->sendMessage($Title."  §b成功真实生存世界地图！");
                                    $levels[]=$level;
                                    $this->plugin->world->set("worlds",$levels);
                                    $this->plugin->world->save();
                                    $s->sendMessage($Title."  §e真实生存在世界§a{$level}§e已开启");
                                    return true;
                                }
                                else
                                {
                                    $s->sendMessage($Title."  §c未输入要生成地图的种子");
                                    $s->sendMessage(C::GREEN.       "PS: 不知道种子随意输即可,推荐几个种子:§e43046,86480,46807,1999323");
                                    $s->sendMessage($Title."  §a用法: /rlset addworld [地图名] [种子]");
                                    return true;
                                }
                            }
                        }
                        else
                        {
                            $s->sendMessage($Title."  §c未输入要生成的地图名");
                            $s->sendMessage($Title."  §a用法: /rlset addworld [地图名] [种子]");
                            return true;
                        }
                    }
                    if($args[0]=="setworld")
                    {
                        if(isset($args[1]))
                        {
                            $levels=$this->plugin->world->get("worlds");
                            $level=$args[1];
                            if(!$this->plugin->getServer()->isLevelGenerated($level))
                            {
                                $s->sendMessage($Title."  §a地图§6{$level}§a不存在！");
                                return true;
                            }
                            else
                            {
                                $levels[]=$level;
                                $this->plugin->world->set("worlds",$levels);
                                $this->plugin->world->save();
                                $s->sendMessage($Title."  §6真实生存开启在世界§a$level");
                                return true;
                            }
                        }
                        else
                        {
                            $s->sendMessage($Title."  §c未输入要添加的地图名");
                            $s->sendMessage($Title."  §a用法: /rlset setworld [地图名]");
                            return true;
                        }
                    }
                    if($args[0]=="delworld")
                    {
                        if(isset($args[1]))
                        {
                            $levels=$this->plugin->world->get("worlds");
                            $level=$args[1];
                            if(in_array($level,$levels))
                            {
                                $inv = array_search($level, $levels);
                                $inv = array_splice($levels, $inv, 1);
                                $this->plugin->world->set("worlds",$levels);
                                $this->plugin->world->save();
                                $s->sendMessage($Title."  §6真实生存关闭在世界§a$level");
                                return true;
                            }
                            else
                            {
                                $s->sendMessage($Title."  §6配置文件不存在真实生存世界§a{$level}§6,请检查后输入");
                                return true;
                            }
                        }
                        else
                        {
                            $s->sendMessage($Title."  §c未输入要删除的地图名");
                            $s->sendMessage($Title."  §a用法: /rlset delworld [地图名]");
                            return true;
                        }
                    }
                }
        }
    }
}