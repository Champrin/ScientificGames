<?php


namespace ScientificGames;


use pocketmine\entity\Entity;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\config;
use ScientificGames\Event\BionicsEvent;
use ScientificGames\Event\BlockEvent;
use ScientificGames\Event\EnergyEvent;
use ScientificGames\Event\ExcretionEvent;
use ScientificGames\Event\GluEvent;
use ScientificGames\Event\ItemEvent;
use ScientificGames\Event\Pedometer;
use ScientificGames\Event\Listeners;
use ScientificGames\Event\ThirstEvent;
use pocketmine\utils\TextFormat as C;
use ScientificGames\Commands\Commands;


class Main extends PluginBase
{

    public $world,$tip,$config,$block,$chest;
    public $high_80,$high_100,$high_120;
    public $Thirst=101,$ThirstCount=0,$ThirstCountP=150;
    public $Excretion=-0.5;
    public $Energy=1069,$EnergyCount=0,$EnergyCountP=450;
    public $Temperature=36;
    public $Glu=5;
    public $pH=7.5;
    public $Hight=0.35;
    public $Age=0;
    public $tired=0;
    public $Heart_rate=60;
    public $Step=0;



    public function onEnable()
    {

        $this->getLogger()->info(C::RED.C::BOLD."真实生存插件§1§l---§e§lScientificGames科学游戏 §f§l已加载");
        $this->getLogger()->info(C::AQUA.C::BOLD."Spiderman§l§d开发,§l§2获取更多信息请加入交流群哟(๑•̀ω•́๑)");

        $this->getServer()->getPluginManager()->registerEvents(new Listeners($this),$this);
        $this->getServer()->getPluginManager()->registerEvents(new BlockEvent($this),$this);
        $this->getServer()->getPluginManager()->registerEvents(new BionicsEvent($this),$this);
        $this->getServer()->getPluginManager()->registerEvents(new ExcretionEvent($this),$this);
        $this->getServer()->getPluginManager()->registerEvents(new EnergyEvent($this),$this);
        $this->getServer()->getPluginManager()->registerEvents(new GluEvent($this),$this);
        $this->getServer()->getPluginManager()->registerEvents(new Pedometer($this),$this);
        $this->getServer()->getPluginManager()->registerEvents(new ItemEvent($this),$this);
        $this->getServer()->getPluginManager()->registerEvents(new ThirstEvent($this),$this);

        $this->getCommand("ill")->setExecutor(new Commands($this),$this);
        $this->getCommand("rlset")->setExecutor(new Commands($this),$this);
        $this->getCommand("kkz")->setExecutor(new Commands($this),$this);
        $this->getCommand("nl")->setExecutor(new Commands($this),$this);
        $this->getCommand("pxz")->setExecutor(new Commands($this),$this);
        $this->getCommand("tw")->setExecutor(new Commands($this),$this);
        $this->getCommand("ph")->setExecutor(new Commands($this),$this);
        $this->getCommand("ss")->setExecutor(new Commands($this),$this);
        $this->getCommand("sg")->setExecutor(new Commands($this),$this);

        @mkdir($this->getDataFolder(),0777,true);
        $this->config = new Config($this->getDataFolder()."Config.yml", Config::YAML, array(
            "不能使用指令"=>true,
            "排泄方块ID"=>46,
            "底部显示格式"=>"tip",
            "Tip"=>"-=§l§dScientificGames§r§7=-                                                
            \n§b   水含量:   §f{水含量}                                                
            \n§6   排泄值:   §c{排泄值}                                                
            \n§a   能量:     §e{能量值}                                                
            \n§2   体温:     §9{体温值}                                                
            \n§3   血糖:     §5{血糖}                                                
            \n§a   pH值:     §5{pH值}                                                
            \n§a   身高:     §5{身高}                                                
            \n§a   年龄:     §5{年龄}                                                
            \n§a   步数:     §5{步数}                                                
            \n§a   心率:     §5{心率}                                                
            \n§a   疲劳度:   §5{疲劳度}                                                \n\n\n\n\n\n\n\n\n\n\n\n"

        ));
        $this->world = new Config($this->getDataFolder()."Worlds.yml", Config::YAML, array("worlds"=>array()));
        $this->tip = new Config($this->getDataFolder() . "PlayerIn.yml", Config::YAML, array());
        $this->block = new Config($this->getDataFolder() . "BlockIn.yml", Config::YAML, array());
        $this->chest = new Config($this->getDataFolder() . "ChestIn.yml", Config::YAML, array());
    }

    public function getWater($name)
    {
        return $this->tip->get($name)["Thirst"];
    }
    public function getThirstCount($name)
    {
        return $this->tip->get($name)["ThirstCount"];
    }
    public function getExcretion($name)
    {
        return $this->tip->get($name)["Excretion"];
    }
    public function getEnergy($name)
    {
        return $this->tip->get($name)["Energy"];
    }
    public function getEnergyCount($name)
    {
        return $this->tip->get($name)["EnergyCount"];
    }
    public function getTemperature($name)
    {
        return $this->tip->get($name)["Temperature"];
    }
    public function getGlu($name)
    {
        return $this->tip->get($name)["Glu"];
    }
    public function getpH($name)
    {
        return $this->tip->get($name)["pH"];
    }
    public function getHight($name)
    {
        return $this->tip->get($name)["Hight"];
    }
    public function getAge($name)
    {
        return $this->tip->get($name)["Age"];
    }
    public function getStep($name)
    {
        return $this->tip->get($name)["Step_number"];
    }
    public function getPos($name)
    {
        return $this->tip->get($name)["Position"];
    }
    public function getFatigue($name)
    {
        return $this->tip->get($name)["Fatigue"];
    }
    public function getHeart_rate($name)
    {
        return $this->tip->get($name)["Heart_rate"];
    }
    public function setWater($name,$water)
    {
        $this->tip->set($name,[
            "Thirst"=>$water,
            "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
            "Excretion"=>$this->tip->get($name)["Excretion"],
            "Energy"=>$this->tip->get($name)["Energy"],
            "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
            "Temperature"=>$this->tip->get($name)["Temperature"],
            "Glu"=>$this->tip->get($name)["Glu"],
            "pH"=>$this->tip->get($name)["pH"],
            "Hight"=>$this->tip->get($name)["Hight"],
            "Age"=>$this->tip->get($name)["Age"],
            "Fatigue"=>$this->tip->get($name)["Fatigue"],
            "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
            "Step_number"=>$this->tip->get($name)["Step_number"],
            "Position"=>$this->tip->get($name)["Position"]
        ]);
        $this->tip->save();
    }
    public function setThirstCount($name,$count)
    {
        $this->tip->set($name,[
            "Thirst"=>$this->tip->get($name)["Thirst"],
            "ThirstCount"=>$count,
            "Excretion"=>$this->tip->get($name)["Excretion"],
            "Energy"=>$this->tip->get($name)["Energy"],
            "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
            "Temperature"=>$this->tip->get($name)["Temperature"],
            "Glu"=>$this->tip->get($name)["Glu"],
            "pH"=>$this->tip->get($name)["pH"],
            "Hight"=>$this->tip->get($name)["Hight"],
            "Age"=>$this->tip->get($name)["Age"],
            "Fatigue"=>$this->tip->get($name)["Fatigue"],
            "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
            "Step_number"=>$this->tip->get($name)["Step_number"],
            "Position"=>$this->tip->get($name)["Position"]
        ]);
        $this->tip->save();
    }
    public function setExcretion($name,$excretion)
    {
        $this->tip->set($name,[
            "Thirst"=>$this->tip->get($name)["Thirst"],
            "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
            "Excretion"=>$excretion,
            "Energy"=>$this->tip->get($name)["Energy"],
            "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
            "Temperature"=>$this->tip->get($name)["Temperature"],
            "Glu"=>$this->tip->get($name)["Glu"],
            "pH"=>$this->tip->get($name)["pH"],
            "Hight"=>$this->tip->get($name)["Hight"],
            "Age"=>$this->tip->get($name)["Age"],
            "Fatigue"=>$this->tip->get($name)["Fatigue"],
            "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
            "Step_number"=>$this->tip->get($name)["Step_number"],
            "Position"=>$this->tip->get($name)["Position"]
        ]);
        $this->tip->save();
    }
    public function setEnergy($name,$energy)
    {
        $this->tip->set($name,[
            "Thirst"=>$this->tip->get($name)["Thirst"],
            "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
            "Excretion"=>$this->tip->get($name)["Excretion"],
            "Energy"=>$energy,
            "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
            "Temperature"=>$this->tip->get($name)["Temperature"],
            "Glu"=>$this->tip->get($name)["Glu"],
            "pH"=>$this->tip->get($name)["pH"],
            "Hight"=>$this->tip->get($name)["Hight"],
            "Age"=>$this->tip->get($name)["Age"],
            "Fatigue"=>$this->tip->get($name)["Fatigue"],
            "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
            "Step_number"=>$this->tip->get($name)["Step_number"],
            "Position"=>$this->tip->get($name)["Position"]
        ]);
        $this->tip->save();
    }
    public function setEnergyCount($name,$count)
    {
        $this->tip->set($name,[
            "Thirst"=>$this->tip->get($name)["Thirst"],
            "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
            "Excretion"=>$this->tip->get($name)["Excretion"],
            "Energy"=>$this->tip->get($name)["Energy"],
            "EnergyCount"=>$count,
            "Temperature"=>$this->tip->get($name)["Temperature"],
            "Glu"=>$this->tip->get($name)["Glu"],
            "pH"=>$this->tip->get($name)["pH"],
            "Hight"=>$this->tip->get($name)["Hight"],
            "Age"=>$this->tip->get($name)["Age"],
            "Fatigue"=>$this->tip->get($name)["Fatigue"],
            "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
            "Step_number"=>$this->tip->get($name)["Step_number"],
            "Position"=>$this->tip->get($name)["Position"]
        ]);
        $this->tip->save();
    }
    public function setTemperature($name,$temperature)
    {
        $this->tip->set($name,[
            "Thirst"=>$this->tip->get($name)["Thirst"],
            "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
            "Excretion"=>$this->tip->get($name)["Excretion"],
            "Energy"=>$this->tip->get($name)["Energy"],
            "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
            "Temperature"=>$temperature,
            "Glu"=>$this->tip->get($name)["Glu"],
            "pH"=>$this->tip->get($name)["pH"],
            "Hight"=>$this->tip->get($name)["Hight"],
            "Age"=>$this->tip->get($name)["Age"],
            "Fatigue"=>$this->tip->get($name)["Fatigue"],
            "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
            "Step_number"=>$this->tip->get($name)["Step_number"],
            "Position"=>$this->tip->get($name)["Position"]
        ]);
        $this->tip->save();
    }
    public function setGlu($name,$glu)
    {
        $this->tip->set($name,[
            "Thirst"=>$this->tip->get($name)["Thirst"],
            "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
            "Excretion"=>$this->tip->get($name)["Excretion"],
            "Energy"=>$this->tip->get($name)["Energy"],
            "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
            "Temperature"=>$this->tip->get($name)["Temperature"],
            "Glu"=>$glu,
            "pH"=>$this->tip->get($name)["pH"],
            "Hight"=>$this->tip->get($name)["Hight"],
            "Age"=>$this->tip->get($name)["Age"],
            "Fatigue"=>$this->tip->get($name)["Fatigue"],
            "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
            "Step_number"=>$this->tip->get($name)["Step_number"],
            "Position"=>$this->tip->get($name)["Position"]
        ]);
        $this->tip->save();
    }
    public function setpH($name,$ph)
    {
        $this->tip->set($name,[
            "Thirst"=>$this->tip->get($name)["Thirst"],
            "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
            "Excretion"=>$this->tip->get($name)["Excretion"],
            "Energy"=>$this->tip->get($name)["Energy"],
            "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
            "Temperature"=>$this->tip->get($name)["Temperature"],
            "Glu"=>$this->tip->get($name)["Glu"],
            "pH"=>$ph,
            "Hight"=>$this->tip->get($name)["Hight"],
            "Age"=>$this->tip->get($name)["Age"],
            "Fatigue"=>$this->tip->get($name)["Fatigue"],
            "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
            "Step_number"=>$this->tip->get($name)["Step_number"],
            "Position"=>$this->tip->get($name)["Position"]
        ]);
        $this->tip->save();
    }
    public function setHight($name,$hight)
    {
        $this->tip->set($name,[
            "Thirst"=>$this->tip->get($name)["Thirst"],
            "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
            "Excretion"=>$this->tip->get($name)["Excretion"],
            "Energy"=>$this->tip->get($name)["Energy"],
            "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
            "Temperature"=>$this->tip->get($name)["Temperature"],
            "Glu"=>$this->tip->get($name)["Glu"],
            "pH"=>$this->tip->get($name)["pH"],
            "Hight"=>$hight,
            "Age"=>$this->tip->get($name)["Age"],
            "Fatigue"=>$this->tip->get($name)["Fatigue"],
            "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
            "Step_number"=>$this->tip->get($name)["Step_number"],
            "Position"=>$this->tip->get($name)["Position"]
        ]);
        $this->tip->save();
    }
    public function setAge($name,$age)
    {
        $this->tip->set($name,[
            "Thirst"=>$this->tip->get($name)["Thirst"],
            "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
            "Excretion"=>$this->tip->get($name)["Excretion"],
            "Energy"=>$this->tip->get($name)["Energy"],
            "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
            "Temperature"=>$this->tip->get($name)["Temperature"],
            "Glu"=>$this->tip->get($name)["Glu"],
            "pH"=>$this->tip->get($name)["pH"],
            "Hight"=>$this->tip->get($name)["Hight"],
            "Age"=>$age,
            "Fatigue"=>$this->tip->get($name)["Fatigue"],
            "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
            "Step_number"=>$this->tip->get($name)["Step_number"],
            "Position"=>$this->tip->get($name)["Position"]
        ]);
        $this->tip->save();
    }
    public function setTired($name,$tired)
    {
        $this->tip->set($name,[
            "Thirst"=>$this->tip->get($name)["Thirst"],
            "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
            "Excretion"=>$this->tip->get($name)["Excretion"],
            "Energy"=>$this->tip->get($name)["Energy"],
            "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
            "Temperature"=>$this->tip->get($name)["Temperature"],
            "Glu"=>$this->tip->get($name)["Glu"],
            "pH"=>$this->tip->get($name)["pH"],
            "Hight"=>$this->tip->get($name)["Hight"],
            "Age"=>$this->tip->get($name)["Age"],
            "Fatigue"=>$tired,
            "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
            "Step_number"=>$this->tip->get($name)["Step_number"],
            "Position"=>$this->tip->get($name)["Position"]
        ]);
        $this->tip->save();
    }
    public function setHeart_rate($name,$tick)
    {
        $this->tip->set($name,[
            "Thirst"=>$this->tip->get($name)["Thirst"],
            "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
            "Excretion"=>$this->tip->get($name)["Excretion"],
            "Energy"=>$this->tip->get($name)["Energy"],
            "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
            "Temperature"=>$this->tip->get($name)["Temperature"],
            "Glu"=>$this->tip->get($name)["Glu"],
            "pH"=>$this->tip->get($name)["pH"],
            "Hight"=>$this->tip->get($name)["Hight"],
            "Age"=>$this->tip->get($name)["Age"],
            "Fatigue"=>$this->tip->get($name)["Fatigue"],
            "Heart_rate"=>$tick,
            "Step_number"=>$this->tip->get($name)["Step_number"],
            "Position"=>$this->tip->get($name)["Position"]
        ]);
        $this->tip->save();
    }
    public function setStep($name,$num)
    {
        $this->tip->set($name,[
            "Thirst"=>$this->tip->get($name)["Thirst"],
            "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
            "Excretion"=>$this->tip->get($name)["Excretion"],
            "Energy"=>$this->tip->get($name)["Energy"],
            "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
            "Temperature"=>$this->tip->get($name)["Temperature"],
            "Glu"=>$this->tip->get($name)["Glu"],
            "pH"=>$this->tip->get($name)["pH"],
            "Hight"=>$this->tip->get($name)["Hight"],
            "Age"=>$this->tip->get($name)["Age"],
            "Fatigue"=>$this->tip->get($name)["Fatigue"],
            "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
            "Step_number"=>$num,
            "Position"=>$this->tip->get($name)["Position"]
        ]);
        $this->tip->save();
    }
    public function setPos($name,$pos)
    {
        $this->tip->set($name,[
            "Thirst"=>$this->tip->get($name)["Thirst"],
            "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
            "Excretion"=>$this->tip->get($name)["Excretion"],
            "Energy"=>$this->tip->get($name)["Energy"],
            "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
            "Temperature"=>$this->tip->get($name)["Temperature"],
            "Glu"=>$this->tip->get($name)["Glu"],
            "pH"=>$this->tip->get($name)["pH"],
            "Hight"=>$this->tip->get($name)["Hight"],
            "Age"=>$this->tip->get($name)["Age"],
            "Fatigue"=>$this->tip->get($name)["Fatigue"],
            "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
            "Step_number"=>$this->tip->get($name)["Step_number"],
            "Position"=>$pos
        ]);
        $this->tip->save();
    }
    public function PlayerEnergy($name)
    {
        $a = $this->tip->get($name)["EnergyCount"];
        $this->setEnergyCount($name,$this->getEnergyCount($name)+3);
        if($a >= $this->EnergyCountP)
        {
            $this->setEnergy($name,$this->getEnergy($name)-1);
            $this->setEnergyCount($name,0);
        }
    }
    public function PlayerWater($name)
    {
        $a = $this->tip->get($name)["ThirstCount"];
        $this->setThirstCount($name,$this->getThirstCount($name)+1);
        if($a >= $this->ThirstCountP)
        {
            $this->setWater($name,$this->getWater($name)-1);
            $this->setThirstCount($name,0);
        }
    }
    public function PlayerTemperature($name)
    {
        $this->setTemperature($name,$this->getTemperature($name)-1);
    }
    public function PlayerHight($name)
    {
        $ta=$this->getServer()->getPlayerExact($name);
        $num=$this->getHight($name);
        if($ta !== null)
        {
            $ta->setDataProperty(Entity::DATA_SCALE, Entity::DATA_TYPE_FLOAT,$num);
        }
    }

    public function PlayerConfigCheck($name)
    {
        if(!$this->tip->exists($name))
        {
            $this->tip->set($name,[
                "Thirst"=>$this->Thirst,
                "ThirstCount"=>$this->ThirstCount,
                "Excretion"=>$this->Excretion,
                "Energy"=>$this->Energy,
                "EnergyCount"=>$this->EnergyCount,
                "Temperature"=>$this->Temperature,
                "Glu"=>$this->Glu,
                "pH"=>$this->pH,
                "Hight"=>$this->Hight,
                "Age"=>$this->Age,
                "Fatigue"=>$this->tired,
                "Heart_rate"=>$this->Heart_rate,
                "Step_number"=>$this->Step,
                "Position"=>"0:0:0"
            ]);
            $this->tip->save();
        }
        else
        {
            if($this->tip->get($name)["Thirst"]===null)
            {
                $this->tip->set($name,[
                    "Thirst"=>$this->Thirst,
                    "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
                    "Excretion"=>$this->tip->get($name)["Excretion"],
                    "Energy"=>$this->tip->get($name)["Energy"],
                    "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
                    "Temperature"=>$this->tip->get($name)["Temperature"],
                    "Glu"=>$this->tip->get($name)["Glu"],
                    "pH"=>$this->tip->get($name)["pH"],
                    "Hight"=>$this->tip->get($name)["Hight"],
                    "Age"=>$this->tip->get($name)["Age"],
                    "Fatigue"=>$this->tip->get($name)["Fatigue"],
                    "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
                    "Step_number"=>$this->tip->get($name)["Step_number"],
                    "Position"=>$this->tip->get($name)["Position"]
                ]);
                $this->tip->save();
            }
            if($this->tip->get($name)["ThirstCount"]===null)
            {
                $this->tip->set($name,[
                    "Thirst"=>$this->tip->get($name)["Thirst"],
                    "ThirstCount"=>$this->ThirstCount,
                    "Excretion"=>$this->tip->get($name)["Excretion"],
                    "Energy"=>$this->tip->get($name)["Energy"],
                    "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
                    "Temperature"=>$this->tip->get($name)["Temperature"],
                    "Glu"=>$this->tip->get($name)["Glu"],
                    "pH"=>$this->tip->get($name)["pH"],
                    "Hight"=>$this->tip->get($name)["Hight"],
                    "Age"=>$this->tip->get($name)["Age"],
                    "Fatigue"=>$this->tip->get($name)["Fatigue"],
                    "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
                    "Step_number"=>$this->tip->get($name)["Step_number"],
                    "Position"=>$this->tip->get($name)["Position"]
                ]);
                $this->tip->save();
            }
            if($this->tip->get($name)["Excretion"]===null)
            {
                $this->tip->set($name,[
                    "Thirst"=>$this->tip->get($name)["Thirst"],
                    "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
                    "Excretion"=>$this->Excretion,
                    "Energy"=>$this->tip->get($name)["Energy"],
                    "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
                    "Temperature"=>$this->tip->get($name)["Temperature"],
                    "Glu"=>$this->tip->get($name)["Glu"],
                    "pH"=>$this->tip->get($name)["pH"],
                    "Hight"=>$this->tip->get($name)["Hight"],
                    "Age"=>$this->tip->get($name)["Age"],
                    "Fatigue"=>$this->tip->get($name)["Fatigue"],
                    "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
                    "Step_number"=>$this->tip->get($name)["Step_number"],
                    "Position"=>$this->tip->get($name)["Position"]
                ]);
                $this->tip->save();
            }
            if($this->tip->get($name)["Energy"]===null)
            {
                $this->tip->set($name,[
                    "Thirst"=>$this->tip->get($name)["Thirst"],
                    "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
                    "Excretion"=>$this->tip->get($name)["Excretion"],
                    "Energy"=>$this->Energy,
                    "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
                    "Temperature"=>$this->tip->get($name)["Temperature"],
                    "Glu"=>$this->tip->get($name)["Glu"],
                    "pH"=>$this->tip->get($name)["pH"],
                    "Hight"=>$this->tip->get($name)["Hight"],
                    "Age"=>$this->tip->get($name)["Age"],
                    "Fatigue"=>$this->tip->get($name)["Fatigue"],
                    "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
                    "Step_number"=>$this->tip->get($name)["Step_number"],
                    "Position"=>$this->tip->get($name)["Position"]
                ]);
                $this->tip->save();
            }
            if($this->tip->get($name)["EnergyCount"]===null)
            {
                $this->tip->set($name,[
                    "Thirst"=>$this->tip->get($name)["Thirst"],
                    "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
                    "Excretion"=>$this->tip->get($name)["Excretion"],
                    "Energy"=>$this->tip->get($name)["Energy"],
                    "EnergyCount"=>$this->EnergyCount,
                    "Temperature"=>$this->tip->get($name)["Temperature"],
                    "Glu"=>$this->tip->get($name)["Glu"],
                    "pH"=>$this->tip->get($name)["pH"],
                    "Hight"=>$this->tip->get($name)["Hight"],
                    "Age"=>$this->tip->get($name)["Age"],
                    "Fatigue"=>$this->tip->get($name)["Fatigue"],
                    "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
                    "Step_number"=>$this->tip->get($name)["Step_number"],
                    "Position"=>$this->tip->get($name)["Position"]
                ]);
                $this->tip->save();
            }
            if($this->tip->get($name)["Temperature"]===null)
            {
                $this->tip->set($name,[
                    "Thirst"=>$this->tip->get($name)["Thirst"],
                    "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
                    "Excretion"=>$this->tip->get($name)["Excretion"],
                    "Energy"=>$this->tip->get($name)["Energy"],
                    "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
                    "Temperature"=>$this->Temperature,
                    "Glu"=>$this->tip->get($name)["Glu"],
                    "pH"=>$this->tip->get($name)["pH"],
                    "Hight"=>$this->tip->get($name)["Hight"],
                    "Age"=>$this->tip->get($name)["Age"],
                    "Fatigue"=>$this->tip->get($name)["Fatigue"],
                    "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
                    "Step_number"=>$this->tip->get($name)["Step_number"],
                    "Position"=>$this->tip->get($name)["Position"]
                ]);
                $this->tip->save();
            }
            if($this->tip->get($name)["Glu"]===null)
            {
                $this->tip->set($name,[
                    "Thirst"=>$this->tip->get($name)["Thirst"],
                    "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
                    "Excretion"=>$this->tip->get($name)["Excretion"],
                    "Energy"=>$this->tip->get($name)["Energy"],
                    "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
                    "Temperature"=>$this->tip->get($name)["Temperature"],
                    "Glu"=>$this->Glu,
                    "pH"=>$this->tip->get($name)["pH"],
                    "Hight"=>$this->tip->get($name)["Hight"],
                    "Age"=>$this->tip->get($name)["Age"],
                    "Fatigue"=>$this->tip->get($name)["Fatigue"],
                    "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
                    "Step_number"=>$this->tip->get($name)["Step_number"],
                    "Position"=>$this->tip->get($name)["Position"]
                ]);
                $this->tip->save();
            }
            if($this->tip->get($name)["pH"]===null)
            {
                $this->tip->set($name,[
                    "Thirst"=>$this->tip->get($name)["Thirst"],
                    "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
                    "Excretion"=>$this->tip->get($name)["Excretion"],
                    "Energy"=>$this->tip->get($name)["Energy"],
                    "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
                    "Temperature"=>$this->tip->get($name)["Temperature"],
                    "Glu"=>$this->tip->get($name)["Glu"],
                    "pH"=>$this->pH,
                    "Hight"=>$this->tip->get($name)["Hight"],
                    "Age"=>$this->tip->get($name)["Age"],
                    "Fatigue"=>$this->tip->get($name)["Fatigue"],
                    "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
                    "Step_number"=>$this->tip->get($name)["Step_number"],
                    "Position"=>$this->tip->get($name)["Position"]
                ]);
                $this->tip->save();
            }
            if($this->tip->get($name)["Hight"]===null)
            {
                $this->tip->set($name,[
                    "Thirst"=>$this->tip->get($name)["Thirst"],
                    "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
                    "Excretion"=>$this->tip->get($name)["Excretion"],
                    "Energy"=>$this->tip->get($name)["Energy"],
                    "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
                    "Temperature"=>$this->tip->get($name)["Temperature"],
                    "Glu"=>$this->tip->get($name)["Glu"],
                    "pH"=>$this->tip->get($name)["pH"],
                    "Hight"=>$this->Hight,
                    "Age"=>$this->tip->get($name)["Age"],
                    "Fatigue"=>$this->tip->get($name)["Fatigue"],
                    "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
                    "Step_number"=>$this->tip->get($name)["Step_number"],
                    "Position"=>$this->tip->get($name)["Position"]
                ]);
                $this->tip->save();
            }
            if($this->tip->get($name)["Age"]===null)
            {
                $this->tip->set($name,[
                    "Thirst"=>$this->tip->get($name)["Thirst"],
                    "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
                    "Excretion"=>$this->tip->get($name)["Excretion"],
                    "Energy"=>$this->tip->get($name)["Energy"],
                    "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
                    "Temperature"=>$this->tip->get($name)["Temperature"],
                    "Glu"=>$this->tip->get($name)["Glu"],
                    "pH"=>$this->tip->get($name)["pH"],
                    "Hight"=>$this->tip->get($name)["Hight"],
                    "Age"=>$this->Age,
                    "Fatigue"=>$this->tip->get($name)["Fatigue"],
                    "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
                    "Step_number"=>$this->tip->get($name)["Step_number"],
                    "Position"=>$this->tip->get($name)["Position"]
                ]);
                $this->tip->save();
            }
            if($this->tip->get($name)["Fatigue"]===null)
            {
                $this->tip->set($name,[
                    "Thirst"=>$this->tip->get($name)["Thirst"],
                    "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
                    "Excretion"=>$this->tip->get($name)["Excretion"],
                    "Energy"=>$this->tip->get($name)["Energy"],
                    "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
                    "Temperature"=>$this->tip->get($name)["Temperature"],
                    "Glu"=>$this->tip->get($name)["Glu"],
                    "pH"=>$this->tip->get($name)["pH"],
                    "Hight"=>$this->tip->get($name)["Hight"],
                    "Age"=>$this->tip->get($name)["Age"],
                    "Fatigue"=>$this->tired,
                    "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
                    "Step_number"=>$this->tip->get($name)["Step_number"],
                    "Position"=>$this->tip->get($name)["Position"]
                ]);
                $this->tip->save();
            }
            if($this->tip->get($name)["Heart_rate"]===null)
            {
                $this->tip->set($name,[
                    "Thirst"=>$this->tip->get($name)["Thirst"],
                    "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
                    "Excretion"=>$this->tip->get($name)["Excretion"],
                    "Energy"=>$this->tip->get($name)["Energy"],
                    "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
                    "Temperature"=>$this->tip->get($name)["Temperature"],
                    "Glu"=>$this->tip->get($name)["Glu"],
                    "pH"=>$this->tip->get($name)["pH"],
                    "Hight"=>$this->tip->get($name)["Hight"],
                    "Age"=>$this->tip->get($name)["Age"],
                    "Fatigue"=>$this->tip->get($name)["Fatigue"],
                    "Heart_rate"=>$this->Heart_rate,
                    "Step_number"=>$this->tip->get($name)["Step_number"],
                    "Position"=>$this->tip->get($name)["Position"]
                ]);
                $this->tip->save();
            }
            if($this->tip->get($name)["Step_number"]===null)
            {
                $this->tip->set($name,[
                    "Thirst"=>$this->tip->get($name)["Thirst"],
                    "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
                    "Excretion"=>$this->tip->get($name)["Excretion"],
                    "Energy"=>$this->tip->get($name)["Energy"],
                    "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
                    "Temperature"=>$this->tip->get($name)["Temperature"],
                    "Glu"=>$this->tip->get($name)["Glu"],
                    "pH"=>$this->tip->get($name)["pH"],
                    "Hight"=>$this->tip->get($name)["Hight"],
                    "Age"=>$this->tip->get($name)["Age"],
                    "Fatigue"=>$this->tip->get($name)["Fatigue"],
                    "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
                    "Step_number"=>$this->Step,
                    "Position"=>$this->tip->get($name)["Position"]
                ]);
                $this->tip->save();
            }
            if($this->tip->get($name)["Position"]===null)
            {
                $this->tip->set($name,[
                    "Thirst"=>$this->tip->get($name)["Thirst"],
                    "ThirstCount"=>$this->tip->get($name)["ThirstCount"],
                    "Excretion"=>$this->tip->get($name)["Excretion"],
                    "Energy"=>$this->tip->get($name)["Energy"],
                    "EnergyCount"=>$this->tip->get($name)["EnergyCount"],
                    "Temperature"=>$this->tip->get($name)["Temperature"],
                    "Glu"=>$this->tip->get($name)["Glu"],
                    "pH"=>$this->tip->get($name)["pH"],
                    "Hight"=>$this->tip->get($name)["Hight"],
                    "Age"=>$this->tip->get($name)["Age"],
                    "Fatigue"=>$this->tip->get($name)["Fatigue"],
                    "Heart_rate"=>$this->tip->get($name)["Heart_rate"],
                    "Step_number"=>$this->tip->get($name)["Step_number"],
                    "Position"=>"0:0:0"
                ]);
                $this->tip->save();
            }
        }
    }
    public function PlayerStateCheckThirst($name,$player)
    {
        if($this->getWater($name) <= 15)
        {
            $player->sendMessage(C::RED."你现在非常渴,不能再吃这些干燥的东西！！");
        }
    }
    public function PlayerStateCheckExcretion($name,$player)
    {
        if($this->getExcretion($name) >= 17)
        {
            $player->sendMessage(C::RED."你现在膀胱要爆了,不能再吃东西！！你需要先排泄！！");
        }
    }
    public function PlayerStateCheckpH($name,$player)
    {
        if($this->getpH($name) <= 7.05)
        {
            $player->sendMessage(C::RED."你的ph值已经快小于7了,不能再吃酸性食物！！");
        }
    }
}
