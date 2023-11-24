<?php

namespace Labarjni\GeometryBuild\builder;

use Labarjni\GeometryBuild\Main;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\math\Vector3;
use pocketmine\world\Position;

class BuilderManager
{
    public function __construct(private readonly Main $plugin){}

    private function getPlugin(): Main
    {
        return $this->plugin;
    }

    public function build(string $name, Position $position): bool
    {
        $dataFolder = $this->getPlugin()->getDataFolder();
        $geometry = file_get_contents($dataFolder . $name . ".json");
        $geometry = json_decode($geometry, true);
        $bones = 0;
        foreach ($geometry["minecraft:geometry"][0]["bones"] as $bone) {
            $block = LegacyStringToItemParser::getInstance()->parse($bone['name']);
            foreach ($bone['cubes'] as $cube) {
                $position->getWorld()->setBlock(new Vector3($position->getX() + ($cube['origin'][0] / 2), $position->getY() + ($cube['origin'][1] / 2), $position->getZ() + ($cube['origin'][2] / 2)), $block->getBlock());
            }
            $bones++;
        }

        if ($bones == count($geometry["minecraft:geometry"][0]["bones"])) {
            return true;
        }

        return false;
    }
}