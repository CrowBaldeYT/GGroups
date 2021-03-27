<?php

namespace MDev\GGroups;

use MDev\GGroups\commands\GroupCommand;
use MDev\GGroups\events\EventListener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\Server;
use pocketmine\utils\Internet;

class GGroups extends PluginBase implements Listener
{

    ###############[=Important things=]###############
    private $prefix;
    private $mainCommand;
    private $defaultGroup;
    private $newVersion;

    ###############[=Important things=]###############


    public function onLoad()
    {

        $this->getLogger()->info($this->getPrefix() . "§7Load GGroups Plugin...");

        $this->saveResource("config.yml");
        $this->saveResource("lang.yml");

        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        $this->prefix = $config->get("prefix");
        $this->mainCommand = $config->get("command");
    }

    public function onEnable()
    {
        $this->getLogger()->info(" $$$$$$\   $$$$$$\                                                    ");
        $this->getLogger()->info("$$  __$$\ $$  __$$\                                                   ");
        $this->getLogger()->info("$$ /  \__|$$ /  \__| $$$$$$\   $$$$$$\  $$\   $$\  $$$$$$\   $$$$$$$\ ");
        $this->getLogger()->info("$$ |$$$$\ $$ |$$$$\ $$  __$$\ $$  __$$\ $$ |  $$ |$$  __$$\ $$  _____|");
        $this->getLogger()->info("$$ |\_$$ |$$ |\_$$ |$$ |  \__|$$ /  $$ |$$ |  $$ |$$ /  $$ |/$$$$$$\  ");
        $this->getLogger()->info("$$ |  $$ |$$ |  $$ |$$ |      $$ |  $$ |$$ |  $$ |$$ |  $$ | \____$$\ ");
        $this->getLogger()->info("/$$$$$$  |/$$$$$$  |$$ |      /$$$$$$  |/$$$$$$  |$$$$$$$  |$$$$$$$  |");
        $this->getLogger()->info(" \______/  \______/ \__|       \______/  \______/ $$  ____/ \_______/ ");
        $this->getLogger()->info("                                                  $$ |                ");
        $this->getLogger()->info("                                                  $$ |                ");
        $this->getLogger()->info("                                                  \__|                ");
        $this->getLogger()->info($this->prefix . "§7Enabled GGroups Plugin!");

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        if ($this->getProvider() == "yamlv2") {
            $groups = new Config("/home/GGroups/" . "groups.yml", Config::YAML);
            $playerdata = new Config("/home/GGroups/" . "players.yml", Config::YAML);

            if (!file_exists("/home/GGroups")) {
                mkdir("/home/GGroups");
                $groups = new Config("/home/GGroups/" . "groups.yml", Config::YAML);
                $this->getLogger()->info($this->getPrefix() . "§7Creating GGroups Folder...");
                $this->defaultGroup = $groups->get("DefaultGroup");
                $groups->setNested("Groups." . "default" . ".format", "§7Player §8: §7{name} §7{msg}");
                $groups->setNested("Groups." . "default" . ".nametag", "§7Player §8: §7{name}");
                $groups->setNested("Groups." . "default" . ".permissions", ["placeholder"]);
                $groups->set("DefaultGroup", "default");
                $groups->save();
            } else {
                $this->getLogger()->info($this->getPrefix() . "§7GGroups Folder already exist! Getting Data...");
            }

        } elseif ($this->getProvider() == "yamlv1") {
            $groups = new Config($this->getDataFolder() . "groups.yml", Config::YAML);
            $playerdata = new Config($this->getDataFolder() . "players.yml", Config::YAML);

            $this->defaultGroup = $groups->get("DefaultGroup");
            $groups->setNested("Groups." . "Player" . ".format", "§7Player §8: §7{name} §7{msg}");
            $groups->setNested("Groups." . "Player" . ".nametag", "§7Player §8: §7{name}");
            $groups->setNested("Groups." . "Player" . ".permissions", ["placeholder"]);
            $groups->set("DefaultGroup", "Player");
            $groups->save();
        }

        #Commands

        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->getServer()->getCommandMap()->register("group", new GroupCommand($this, $config->get("command"), $config->get("description")));
        return true;
    }

        #$$$$$$\  $$$$$$$\ $$$$$$\
        #$$  __$$\ $$  __$$\\_$$  _|
        #$$ /  $$ |$$ |  $$ | $$ |
        #$$$$$$$$ |$$$$$$$  | $$ |
        #$$  __$$ |$$  ____/  $$ |
        #$$ |  $$ |$$ |       $$ |
        #$$ |  $$ |$$ |     $$$$$$\
        #\__|  \__|\__|     \______|

    public function getProvider() {

        $config = new Config($this->getDataFolder()."config.yml", Config::YAML);

        return $config->get("provider");
    }

    public function getPrefix() {
        return $this->prefix;
    }

    public function getMainCommand() {
        return $this->mainCommand;
    }

    public function getDefaultGroup() {

        if($this->getProvider() == "yamlv2"){
            $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
            $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);

            return $groups->get("DefaultGroup");

        } elseif ($this->getProvider() == "yamlv1") {
            $groups = new Config($this->getDataFolder()."groups.yml", Config::YAML);
            $playerdata = new Config($this->getDataFolder()."players.yml", Config::YAML);

            return $groups->get("DefaultGroup");
        }
    }

    public function getGroup(Player $player) {

        if($this->getProvider() == "yamlv2"){
            $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
            $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);

            return $playerdata->getNested($player->getName().".group");

        } elseif ($this->getProvider() == "yamlv1") {
            $groups = new Config($this->getDataFolder()."groups.yml", Config::YAML);
            $playerdata = new Config($this->getDataFolder()."players.yml", Config::YAML);

            return $playerdata->getNested($player->getName().".group");
        }
    }

    public function setGroup(Player $player, string $group) {

        if($this->getProvider() == "yamlv2"){
            $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
            $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);

            $playerdata->setNested($player->getName().".group", $group);
            $playerdata->save();

        } elseif ($this->getProvider() == "yamlv1") {
            $groups = new Config($this->getDataFolder()."groups.yml", Config::YAML);
            $playerdata = new Config($this->getDataFolder()."players.yml", Config::YAML);

            $playerdata->setNested($player->getName().".group", $group);
            $playerdata->save();
        }

    }

    public function addGroup(string $group) {

        if($this->getProvider() == "yamlv2"){
            $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
            $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);

            $groups->setNested("Groups.".$group.".format", "§8$group §7: §8{name} §f{msg}");
            $groups->setNested("Groups.".$group.".nametag", "§8$group §7: §8{name}");
            $groups->save();

        } elseif ($this->getProvider() == "yamlv1") {
            $groups = new Config($this->getDataFolder()."groups.yml", Config::YAML);
            $playerdata = new Config($this->getDataFolder()."players.yml", Config::YAML);

            $groups->setNested("Groups.".$group.".format", "§8$group §7: §8{name} §f{msg}");
            $groups->setNested("Groups.".$group.".nametag", "§8$group §7: §8{name}");
            $groups->save();
        }

    }

    public function setGroupFormat(string $group, string $format) {

        if($this->getProvider() == "yamlv2"){
            $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
            $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);

            $groups->setNested("Groups.".$group.".format", $format);
            $groups->save();

        } elseif ($this->getProvider() == "yamlv1") {
            $groups = new Config($this->getDataFolder()."groups.yml", Config::YAML);
            $playerdata = new Config($this->getDataFolder()."players.yml", Config::YAML);

            $groups->setNested("Groups.".$group.".format", $format);
            $groups->save();
        }

    }

    public function setGroupNametag(string $group, string $nametag) {

        if($this->getProvider() == "yamlv2"){
            $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
            $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);

            $groups->setNested("Groups.".$group.".format", $nametag);
            $groups->save();

        } elseif ($this->getProvider() == "yamlv1") {
            $groups = new Config($this->getDataFolder()."groups.yml", Config::YAML);
            $playerdata = new Config($this->getDataFolder()."players.yml", Config::YAML);

            $groups->setNested("Groups.".$group.".format", $nametag);
            $groups->save();
        }

    }

    public function getGroupFormat(string $group) {

        if($this->getProvider() == "yamlv2"){
            $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
            $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);

            return $groups->getNested("Groups.".$group.".format");

        } elseif ($this->getProvider() == "yamlv1") {
            $groups = new Config($this->getDataFolder()."groups.yml", Config::YAML);
            $playerdata = new Config($this->getDataFolder()."players.yml", Config::YAML);

            return $groups->getNested("Groups.".$group.".format");
        }

    }

    public function getGroupNametag(string $group) {

        if($this->getProvider() == "yamlv2"){
            $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
            $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);

            return $groups->getNested("Groups.".$group.".nametag");

        } elseif ($this->getProvider() == "yamlv1") {
            $groups = new Config($this->getDataFolder()."groups.yml", Config::YAML);
            $playerdata = new Config($this->getDataFolder()."players.yml", Config::YAML);

            return $groups->getNested("Groups.".$group.".nametag");
        }

    }

    public function getPermissions($group) {
        if($this->getProvider() == "yamlv2"){
            $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
            $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);

            return $groups->getNested("Groups.". $group . ".permissions");

        } elseif ($this->getProvider() == "yamlv1") {
            $groups = new Config($this->getDataFolder()."groups.yml", Config::YAML);
            $playerdata = new Config($this->getDataFolder()."players.yml", Config::YAML);

            return $groups->getNested("Groups.". $group . ".permissions");
        }
    }

}