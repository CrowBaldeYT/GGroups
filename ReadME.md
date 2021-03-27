# GGroups

<h2>FAQ</h2>
<h3>What is GGroups</h3>
GGroups is an Group System specially designed for V Server (KVM, VPS etc...). But you can use it on Game Servers too.
<h3>Does it have an API?</h3>
Of course it have! It's an easy API where you can do everything like with the Commands.


<h2>SubCommands</h2>

/group add [Group] - Add an Group.
/group remove [Group] - Remove an Group.
/group list - List all Groups.
/group format [Format] - Set Chat Format of Group.
/group nametag [Nametag] - Set Nametag Format of Group.
/group set [Player] [Group] - Set Players Group.
/group addperm [Group] [Permission] - Add Permission to Group.
/group removeperm - Remove Permission form Group.
/group default - List all Groups.

<h2>API</h2>
<h3>Get Provider</h3>
<pre>
$api = $this->getServer()->getPluginManager()->getPlugin("GGroups");

$api->getProvider();
</pre>

It returns yamlv1 or yamlv2.
