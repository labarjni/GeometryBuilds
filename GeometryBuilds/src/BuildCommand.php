<?php

namespace Labarjni\GeometryBuild;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class BuildCommand extends Command
{
    public function __construct(private Main $plugin)
    {
        parent::__construct("build", "Build from geometry");
        $this->setPermission("BuildCommand.use");
    }

    private function getPlugin(): Main
    {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $this->testPermission($sender);

        if (empty($args)) {
            $sender->sendMessage("§cUsage: /build [geometry_name]");
            return false;
        }

        $dataFolder = $this->getPlugin()->getDataFolder();
        if (!file_exists($dataFolder . $args[0] . ".json")) {
            $sender->sendMessage("§cThe selected geometry was not found, load it into /plugin_data/GeometryBuilds/");
            return false;
        }

        $geometry = file_get_contents($dataFolder . $args[0] . ".json");
        $geometry = json_decode($geometry, true);

        return true;
    }
}