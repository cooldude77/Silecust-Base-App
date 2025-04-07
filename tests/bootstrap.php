<?php

use App\Kernel;
use Doctrine\Deprecations\Deprecation;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (class_exists(Deprecation::class)) {
    Deprecation::enableWithTriggerError();
}
if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

bootstrap();
function bootstrap(): void
{
    $kernel = new Kernel('test', true);
    $kernel->boot();

    $application = new Application($kernel);
    $application->setCatchExceptions(false);
    $application->setAutoExit(false);

    $application->run(new ArrayInput(['command' => 'doctrine:database:drop', '--if-exists' => '1', '--force' => '1',]));

    $application->run(new ArrayInput(['command' => 'doctrine:database:create', '--no-interaction' => true]));

    $application->run(new ArrayInput(['command' => 'doctrine:migrations:migrate', '--no-interaction' => true]));

    //    $application->run(new ArrayInput(['command' => 'doctrine:fixtures:load', '--no-interaction'
    // => true,'--append'=>true]));

    $kernel->shutdown();
}