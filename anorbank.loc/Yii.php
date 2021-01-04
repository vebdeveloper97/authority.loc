<?php

use yii\BaseYii;
use tune\ebp\Ebp;
use yii\queue\Queue;
use tune\nibbd\Nibbd;
use tune\soliq\Soliq;
use yii\db\Connection;
use common\components\Asoki;
use common\components\Misoki;
use yii\rbac\ManagerInterface;
use tune\playmobile\Playmobile;
use tune\billmaster\Billmaster;

/**
 * Yii bootstrap file.
 * Used for enhanced IDE code autocompletion.
 */
class Yii extends BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication the application instance
     */
    public static $app;
}

/**
 * Class BaseApplication
 * Used for properties that are identical for both WebApplication and ConsoleApplication
 *
 * @property ManagerInterface $authManager The auth manager for this application. Null is returned if auth manager is
 *     not configured. This property is read-only. Extended component.
 * @property Connection       $db
 * @property Nibbd            $nibbd
 * @property Playmobile       $playmobile
 * @property Billmaster       $billmaster
 * @property Asoki            $asoki
 * @property Misoki           $misoki
 * @property Queue            $queue
 * @property Soliq            $soliq
 * @property Ebp              $ebp
 */
abstract class BaseApplication extends yii\base\Application
{
}

/**
 * Class WebApplication
 * Include only Web application related components here
 */
class WebApplication extends yii\web\Application
{
}

/**
 * Class ConsoleApplication
 * Include only Console application related components here
 */
class ConsoleApplication extends yii\console\Application
{
}
