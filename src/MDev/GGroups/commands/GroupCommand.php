<?php

namespace MDev\GGroups\commands;

use MDev\GGroups\GGroups;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\biome\UnknownBiome;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class GroupCommand extends Command{

    ###############[=Important things=]###############
    public $plugin;
    ###############[=Important things=]###############

    public function __construct(GGroups $plugin, string $name, string $description = "", string $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);

        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        $this->setPermission("ggroups");

        if(!$sender instanceof Player) {

            $sender->sendMessage($this->plugin->getPrefix()."§cUse this Command InGame!");
            return true;
        }

        if(!$sender->hasPermission("ggroups")){
            $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);
            $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
            return true;
        }

        if(!isset($args[0])) {
            $command = $this->plugin->getMainCommand();
            $sender->sendMessage($this->plugin->getPrefix()."§cUsage: /$command <SubCommand>");
            return true;
        }

        if($args[0] == "list"){

            if($this->plugin->getProvider() == "yamlv2"){
                $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
                $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                if(!$sender->hasPermission("ggroups.cmd.list")){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                    return true;
                }

                $list = [];
                $grouplist = $groups->get("Groups");
                foreach($grouplist as $name => $data) $list[] = $name;
                $message = str_replace("{count}", (count($grouplist)), $lang->get("list-group-list"));
                $format = $lang->get("list-group-format");
                $sender->sendMessage($this->plugin->getPrefix().$message . "\n$format" . implode("\n$format ", $list));

            } elseif ($this->plugin->getProvider() == "yamlv1") {
                $groups = new Config($this->plugin->getDataFolder()."groups.yml", Config::YAML);
                $playerdata = new Config($this->plugin->getDataFolder()."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                if(!$sender->hasPermission("ggroups.cmd.list")){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                    return true;
                }

                $list = [];
                $grouplist = $groups->get("Groups");
                foreach($grouplist as $name => $data) $list[] = $name;
                $message = str_replace("{count}", (count($grouplist)), $lang->get("list-group-list"));
                $sender->sendMessage($this->plugin->getPrefix().$message . "\n§8- §7" . implode("\n§8-§7 ", $list));

            }

        }

        if($args[0] == "add"){

            if($this->plugin->getProvider() == "yamlv2"){
                $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
                $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                if(!$sender->hasPermission("ggroups.cmd.list")){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                    return true;
                }

                if(!isset($args[1])) {
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("add-usage")));
                    return true;
                }

                $groupName = $args[1];

                if(!$sender->hasPermission("ggroups.cmd.add")){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                    return true;
                }

                if($groups->getNested("Groups.".$groupName) !== null){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("add-group-exist"));
                    return true;
                }

                $groups->setNested("Groups.".$groupName.".format", "§8$groupName §7: §8{name} §f{msg}");
                $groups->setNested("Groups.".$groupName.".nametag", "§8$groupName §7: §8{name}");
                $groups->setNested("Groups.".$groupName.".permissions", ["placeholder"]);
                $groups->save();

                $sender->sendMessage($this->plugin->getPrefix().str_replace("{group}", $groupName, $lang->get("add-group-added")));

            } elseif ($this->plugin->getProvider() == "yamlv1") {
                $groups = new Config($this->plugin->getDataFolder()."groups.yml", Config::YAML);
                $playerdata = new Config($this->plugin->getDataFolder()."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                if(!$sender->hasPermission("ggroups.cmd.list")){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                    return true;
                }

                if(!isset($args[1])) {
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("add-usage")));
                    return true;
                }

                $groupName = $args[1];

                if(!$sender->hasPermission("ggroups.cmd.add")){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                    return true;
                }

                if($groups->getNested("Groups.".$groupName) !== null){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("add-group-exist"));
                    return true;
                }

                $groups->setNested("Groups.".$groupName.".format", "§8$groupName §7: §8{name} §f{msg}");
                $groups->setNested("Groups.".$groupName.".nametag", "§8$groupName §7: §8{name}");
                $groups->save();

                $sender->sendMessage($this->plugin->getPrefix().str_replace("{group}", $groupName, $lang->get("add-group-added")));

            }

        }

        if($args[0] == "format"){

                if($this->plugin->getProvider() == "yamlv2"){
                    $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
                    $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);
                    $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                    if(!$sender->hasPermission("ggroups.cmd.format")){
                        $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                        return true;
                    }

                    if(!isset($args[1])){
                        $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("format-usage")));
                        return true;
                    }
                    if(!isset($args[2])){
                        $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);
                        $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("format-usage")));
                        return true;
                    }

                    if($groups->getNested("Groups.".$args[1]) == null){
                        $sender->sendMessage($this->plugin->getPrefix().$lang->get("format-group-dont-exist"));
                        return true;
                    }

                    $format = implode(" ", array_slice($args, 2));

                    $groups->setNested("Groups.".$args[1].".format", $format);
                    $groups->save();

                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{group}", $args[1], $lang->get("format-set-format")));

                } elseif ($this->plugin->getProvider() == "yamlv1") {
                    $groups = new Config($this->plugin->getDataFolder()."groups.yml", Config::YAML);
                    $playerdata = new Config($this->plugin->getDataFolder()."players.yml", Config::YAML);
                    $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                    if(!$sender->hasPermission("ggroups.cmd.format")){
                        $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                        return true;
                    }

                    if(!isset($args[1])){
                        $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("format-usage")));
                        return true;
                    }
                    if(!isset($args[2])){
                        $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);
                        $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("format-usage")));
                        return true;
                    }

                    if($groups->getNested("Groups.".$args[1]) == null){
                        $sender->sendMessage($this->plugin->getPrefix().$lang->get("format-group-dont-exist"));
                        return true;
                    }

                    $format = implode(" ", array_slice($args, 2));

                    $groups->setNested("Groups.".$args[1].".format", $format);
                    $groups->save();

                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{group}", $args[1], $lang->get("format-set-format")));

                }

            }

        if($args[0] == "nametag"){
            if($this->plugin->getProvider() == "yamlv2"){
                $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
                $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                if(!$sender->hasPermission("ggroups.cmd.nametag")){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                    return true;
                }

                if(!isset($args[1])){
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("nametag-usage")));
                    return true;
                }
                if(!isset($args[2])){
                    $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("nametag-usage")));
                    return true;
                }

                if($groups->getNested("Groups.".$args[1]) == null){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("nametag-group-dont-exist"));
                    return true;
                }

                $nametag = implode(" ", array_slice($args, 2));

                $groups->setNested("Groups.".$args[1].".nametag", $nametag);
                $groups->save();

                $sender->sendMessage($this->plugin->getPrefix().str_replace("{group}", $args[1], $lang->get("nametag-set-nametag")));

            } elseif ($this->plugin->getProvider() == "yamlv1") {
                $groups = new Config($this->plugin->getDataFolder()."groups.yml", Config::YAML);
                $playerdata = new Config($this->plugin->getDataFolder()."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                if(!$sender->hasPermission("ggroups.cmd.nametag")){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                    return true;
                }

                if(!isset($args[1])){
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("nametag-usage")));
                    return true;
                }
                if(!isset($args[2])){
                    $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("nametag-usage")));
                    return true;
                }

                if($groups->getNested("Groups.".$args[1]) == null){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("nametag-group-dont-exist"));
                    return true;
                }

                $nametag = implode(" ", array_slice($args, 2));

                $groups->setNested("Groups.".$args[1].".nametag", $nametag);
                $groups->save();

                $sender->sendMessage($this->plugin->getPrefix().str_replace("{group}", $args[1], $lang->get("nametag-set-nametag")));

            }

        }

        if($args[0] == "set"){
            if($this->plugin->getProvider() == "yamlv2"){
                $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
                $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                if(!isset($args[1])){
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("set-usage")));
                    return true;
                }
                if(!isset($args[2])){
                    $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("set-usage")));
                    return true;
                }

                $player = $this->plugin->getServer()->getPlayer($args[1]);

                if(!$player instanceof Player) {
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("set-player-offline"));
                    return true;
                }

                $name = $player->getName();

                $group = $args[2];

                if($groups->getNested("Groups.".$group) == null){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("set-group-dont-exist"));
                    return true;
                }

                $playerdata->setNested($name.".group", $group);
                $playerdata->save();
                $player->close("", str_replace("{group}", $group, $lang->get("set-kick-message")));
                $stepone = str_replace("{group}", $group, $lang->get("set-group-set"));
                $steptwo = str_replace("{player}", $name, $stepone);
                $msg = $steptwo;
                $sender->sendMessage($this->plugin->getPrefix().$msg);
                $this->plugin->getLogger()->info($this->plugin->getPrefix(). "§6" . $sender->getName() ." §7updated the Group of " . $name . " §7to§6 " . $group);

            } elseif ($this->plugin->getProvider() == "yamlv1") {
                $groups = new Config($this->plugin->getDataFolder()."groups.yml", Config::YAML);
                $playerdata = new Config($this->plugin->getDataFolder()."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                if(!isset($args[1])){
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("set-usage")));
                    return true;
                }
                if(!isset($args[2])){
                    $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("set-usage")));
                    return true;
                }

                $player = $this->plugin->getServer()->getPlayer($args[1]);

                if(!$player instanceof Player) {
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("set-player-offline"));
                    return true;
                }

                $name = $player->getName();

                $group = $args[2];

                if($groups->getNested("Groups.".$group) == null){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("set-group-dont-exist"));
                    return true;
                }

                $playerdata->setNested($name.".group", $group);
                $playerdata->save();
                $player->close("", str_replace("{group}", $group, $lang->get("set-kick-message")));
                $stepone = str_replace("{group}", $group, $lang->get("set-group-set"));
                $steptwo = str_replace("{player}", $name, $stepone);
                $msg = $steptwo;
                $sender->sendMessage($this->plugin->getPrefix().$msg);
                $this->plugin->getLogger()->info($this->plugin->getPrefix(). "§6" . $sender->getName() ." §7updated the Group of " . $name . " §7to§6 " . $group);

            }

        }

        if($args[0] == "remove") {

            if($this->plugin->getProvider() == "yamlv2"){
                $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
                $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                if(!isset($args[1])) {
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("remove-usage")));
                    return true;
                }

                $groupName = $args[1];

                if(!$sender->hasPermission("ggroups.cmd.remove")){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                    return true;
                }

                if($groups->getNested("Groups.".$groupName) == null){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("remove-group-dont-exist"));
                    return true;
                }

                $groups->removeNested("Groups.".$groupName);
                $groups->save();

                $sender->sendMessage($this->plugin->getPrefix().str_replace("{group}", $groupName, $lang->get("remove-group-removed")));

            } elseif ($this->plugin->getProvider() == "yamlv1") {
                $groups = new Config($this->plugin->getDataFolder()."groups.yml", Config::YAML);
                $playerdata = new Config($this->plugin->getDataFolder()."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                if(!isset($args[1])) {
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("remove-usage")));
                    return true;
                }

                $groupName = $args[1];

                if(!$sender->hasPermission("ggroups.cmd.remove")){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                    return true;
                }

                if($groups->getNested("Groups.".$groupName) == null){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("remove-group-dont-exist"));
                    return true;
                }

                $groups->removeNested("Groups.".$groupName);
                $groups->save();

                $sender->sendMessage($this->plugin->getPrefix().str_replace("{group}", $groupName, $lang->get("remove-group-removed")));

            }

        }

        if($args[0] == "addperm") {

            if($this->plugin->getProvider() == "yamlv2"){
                $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
                $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                if(!isset($args[1])) {
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("addperm-usage")));
                    return true;
                }

                if(!isset($args[2])) {
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("addperm-usage")));
                    return true;
                }

                $groupName = $args[1];

                if(!$sender->hasPermission("ggroups.cmd.addperm")){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                    return true;
                }

                if($groups->getNested("Groups.".$groupName) == null){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("addperm-group-dont-exist"));
                    return true;
                }

                //Code

                $perms = $groups->getNested("Groups.{$groupName}.permissions",[]);
                $permission = $args[2];
                $perms[] = $permission;
                $groups->setNested("Groups.{$groupName}.permissions", $perms);
                $groups->save();

                $stepone = str_replace("{permission}", $args[2], $lang->get("addperm-added-perm"));
                $steptwo = str_replace("{group}", $groupName, $stepone);

                $msg = $steptwo;

                $sender->sendMessage($this->plugin->getPrefix().$msg);

            } elseif ($this->plugin->getProvider() == "yamlv1") {
                $groups = new Config($this->plugin->getDataFolder() . "groups.yml", Config::YAML);
                $playerdata = new Config($this->plugin->getDataFolder() . "players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder() . "lang.yml", Config::YAML);

                if(!isset($args[1])) {
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("addperm-usage")));
                    return true;
                }

                if(!isset($args[2])) {
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("addperm-usage")));
                    return true;
                }

                $groupName = $args[1];

                if(!$sender->hasPermission("ggroups.cmd.addperm")){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                    return true;
                }

                if($groups->getNested("Groups.".$groupName) == null){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("addperm-group-dont-exist"));
                    return true;
                }

                //Code

                $perms = $groups->getNested("Groups.{$groupName}.permissions",[]);
                $permission = $args[2];
                $perms[] = $permission;
                $groups->setNested("Groups.{$groupName}.permissions", $perms);
                $groups->save();

                $stepone = str_replace("{permission}", $args[2], $lang->get("addperm-added-perm"));
                $steptwo = str_replace("{group}", $groupName, $stepone);

                $msg = $steptwo;

                $sender->sendMessage($this->plugin->getPrefix().$msg);

            }

        }

        if($args[0] == "removeperm"){

            if($this->plugin->getProvider() == "yamlv2"){
                $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
                $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                if(!isset($args[1])) {
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("removeperm-usage")));
                    return true;
                }

                if(!isset($args[2])) {
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("removeperm-usage")));
                    return true;
                }

                $groupName = $args[1];

                if(!$sender->hasPermission("ggroups.cmd.removeperm")){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                    return true;
                }

                if($groups->getNested("Groups.".$groupName) == null){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("removeperm-group-dont-exist"));
                    return true;
                }

                //Code

                $perms = $groups->getNested("Groups.{$groupName}.permissions",[]);

                $permission = $args[2];
                if(!in_array($permission, $perms)){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("removeperm-perm-dont-exist"));
                    return true;
                }
                unset($perms[array_search($permission, $perms)]);
                $groups->setNested("Groups.{$groupName}.permissions", $perms);
                $groups->save();

                $stepone = str_replace("{permission}", $args[2], $lang->get("removeperm-removed-perm"));
                $steptwo = str_replace("{group}", $groupName, $stepone);

                $msg = $steptwo;

                $sender->sendMessage($this->plugin->getPrefix().$msg);

            } elseif ($this->plugin->getProvider() == "yamlv1") {
                $groups = new Config($this->plugin->getDataFolder() . "groups.yml", Config::YAML);
                $playerdata = new Config($this->plugin->getDataFolder() . "players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder() . "lang.yml", Config::YAML);

                if(!isset($args[1])) {
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("removeperm-usage")));
                    return true;
                }

                if(!isset($args[2])) {
                    $sender->sendMessage($this->plugin->getPrefix().str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("removeperm-usage")));
                    return true;
                }

                $groupName = $args[1];

                if(!$sender->hasPermission("ggroups.cmd.addperm")){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                    return true;
                }

                if($groups->getNested("Groups.".$groupName) == null){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("removeperm-group-dont-exist"));
                    return true;
                }

                //Code

                $perms = $groups->getNested("Groups.{$groupName}.permissions",[]);

                $permission = $args[2];
                if(!in_array($permission, $perms)){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("removeperm-perm-dont-exist"));
                    return true;
                }
                unset($perms[array_search($permission, $perms)]);
                $groups->setNested("Groups.{$groupName}.permissions", $perms);
                $groups->save();

                $stepone = str_replace("{permission}", $args[2], $lang->get("removeperm-removed-perm"));
                $steptwo = str_replace("{group}", $groupName, $stepone);

                $msg = $steptwo;

                $sender->sendMessage($this->plugin->getPrefix().$msg);

            }

        }

        if($args[0] == "default") {

            if($this->plugin->getProvider() == "yamlv2"){
                $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
                $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                if(!$sender->hasPermission("ggroups.cmd.default")){
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("no-permissions"));
                    return true;
                }

                if(!isset($args[1])) {
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("defgroup-usage"));
                    return true;
                }

                if($groups->getNested("Groups.".$args[1]) == null) {
                    $sender->sendMessage($this->plugin->getPrefix().$lang->get("defgroup-group-dont-exist"));
                    return true;
                }

                $groups->set("DefaultGroup", $args[1]);
                $groups->save();

                $sender->sendMessage($this->plugin->getPrefix().str_replace("{group}", $args[1], $lang->get("defgroup-set")));



            } elseif ($this->plugin->getProvider() == "yamlv1") {
                $groups = new Config($this->plugin->getDataFolder()."groups.yml", Config::YAML);
                $playerdata = new Config($this->plugin->getDataFolder()."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                //Code

            }

        }

        if($args[0] == "help") {

            if($this->plugin->getProvider() == "yamlv2"){
                $groups = new Config("/home/GGroups/"."groups.yml", Config::YAML);
                $playerdata = new Config("/home/GGroups/"."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                $sender->sendMessage($this->plugin->getPrefix().$lang->get("help-message"));
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." help §8- §7Get all Commands.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." add [Group] - Add an Group.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." remove [Group] - Remove an Group.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." list - List all Groups.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." format [Format] - Set Chat Format of Group.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." nametag [Nametag] - Set Nametag Format of Group.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." set [Player] [Group] - Set Players Group.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." addperm [Group] [Permission] - Add Permission to Group.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." removeperm - Remove Permission form Group.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." default [Group] - Set default Group.");

            } elseif ($this->plugin->getProvider() == "yamlv1") {
                $groups = new Config($this->plugin->getDataFolder()."groups.yml", Config::YAML);
                $playerdata = new Config($this->plugin->getDataFolder()."players.yml", Config::YAML);
                $lang = new Config($this->plugin->getDataFolder()."lang.yml", Config::YAML);

                $sender->sendMessage($this->plugin->getPrefix().$lang->get("help-message"));
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." help §8- §7Get all Commands.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." add [Group] - Add an Group.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." remove [Group] - Remove an Group.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." list - List all Groups.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." format [Format] - Set Chat Format of Group.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." nametag [Nametag] - Set Nametag Format of Group.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." set [Player] [Group] - Set Players Group.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." addperm [Group] [Permission] - Add Permission to Group.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." removeperm - Remove Permission form Group.");
                $sender->sendMessage($this->plugin->getPrefix()."/".str_replace("{command}", $this->plugin->getMainCommand(), $lang->get("help-format"))." default [Group] - Set default Group.");

            }

        }
        return true;
    }

}
