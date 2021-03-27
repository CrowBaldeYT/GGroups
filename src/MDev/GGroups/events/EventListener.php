<?php

namespace MDev\GGroups\events;

use MDev\GGroups\GGroups;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;

class EventListener implements Listener {

    ###############[=Important things=]###############
    public $plugin;
    ###############[=Important things=]###############

    public function __construct(GGroups $plugin) {

        $this->plugin = $plugin;

    }

    public function onPlayerJoin(PlayerJoinEvent $event) {

        if($this->plugin->getProvider() == "yamlv2"){
            $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
            $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);
            $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

            $player = $event->getPlayer();
            $name = $player->getName();

            if(!$playerdata->exists($name)){

                $this->plugin->getLogger()->info($this->plugin->getPrefix()."§7Player §6" . $name . "'s §7Data does not exist. Creating new Data...");

                $playerdata->setNested($name.".group", $this->plugin->getDefaultGroup());
                $playerdata->save();
            }

            $playergroup = $playerdata->getNested($name.".group");

            $nametag = str_replace("{name}", $name, $groups->getNested("Groups.".$playergroup.".nametag"));

            $permissionlist = (array)$groups->getNested("Groups.".$playergroup.".permissions", []);

            foreach($permissionlist as $name => $data) {
                var_dump($name);
                $player->addAttachment($this->plugin)->setPermission($data, true);
            }

            $player->setNameTag($nametag);

            $player->setDisplayName($nametag);

        } elseif ($this->plugin->getProvider() == "yamlv1") {
            $groups = new Config($this->plugin->getDataFolder()."groups.yml", Config::YAML);
            $playerdata = new Config($this->plugin->getDataFolder()."players.yml", Config::YAML);
            $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

            $player = $event->getPlayer();
            $name = $player->getName();

            if(!$playerdata->exists($name)){

                $this->plugin->getLogger()->info($this->plugin->getPrefix()."§7Player §6" . $name . "'s §7Data does not exist. Creating new Data...");

                $playerdata->setNested($name.".group", $this->plugin->getDefaultGroup());
                $playerdata->save();
            }

            $playergroup = $playerdata->getNested($name.".group");

            $nametag = str_replace("{name}", $name, $groups->getNested("Groups.".$playergroup.".nametag"));

            $player->setNameTag($nametag);

            $player->setDisplayName($nametag);

        }

    }

    public function onPlayerChat(PlayerChatEvent $event) {

        if($this->plugin->getProvider() == "yamlv2"){
            $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
            $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);
            $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

            $player = $event->getPlayer();
            $name = $player->getName();

            $playergroup = $playerdata->getNested($name.".group");

            $getformat = $groups->getNested("Groups.".$playergroup.".format");

            $stepone = str_replace("{name}", $name, $getformat);

            $steptwo = str_replace("{msg}", $event->getMessage(), $stepone);

            $format = $steptwo;

            $event->setFormat($format);

        } elseif ($this->plugin->getProvider() == "yamlv1") {
            $groups = new Config($this->plugin->getDataFolder()."groups.yml", Config::YAML);
            $playerdata = new Config($this->plugin->getDataFolder()."players.yml", Config::YAML);
            $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

            $player = $event->getPlayer();
            $name = $player->getName();

            $playergroup = $playerdata->getNested($name.".group");

            $getformat = $groups->getNested("Groups.".$playergroup.".format");

            $stepone = str_replace("{name}", $name, $getformat);

            $steptwo = str_replace("{msg}", $event->getMessage(), $stepone);

            $format = $steptwo;

            $event->setFormat($format);

        }
    }

}