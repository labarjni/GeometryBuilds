<?php

namespace Labarjni\GeometryBuild\command;

use Labarjni\GeometryBuild\builder\BuilderManager;
use Labarjni\GeometryBuild\Main;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class BuildCommand extends Command
{
    public function __construct(private readonly Main $plugin)
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
        if (!$sender instanceof Player) return false;

        if (empty($args)) {
            $sender->sendMessage("§cUsage: /build [geometry_name]");
            return false;
        }

        if (!file_exists($this->getPlugin()->getDataFolder() . $args[0] . ".json")) {
            $sender->sendMessage("§cThe selected geometry was not found, load it into /plugin_data/GeometryBuilds/");
            return false;
        }

        $buildManager = new BuilderManager($this->getPlugin());

        if ($buildManager->build($args[0], $sender->getPosition())) {
            $sender->sendMessage("§aThe building was loaded successfully");
        } else {
            $sender->sendMessage("§cAn error occurred while loading the building");
        }

        return true;
    }
}