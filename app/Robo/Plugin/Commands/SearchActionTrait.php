<?php
declare(strict_types=1);

namespace Willow\Robo\Plugin\Commands;

use Throwable;
use Twig\Environment;

trait SearchActionTrait
{
    /**
     * @var Environment
     */
    protected $twig;

    /**
     * Forge the SearchAction code given the entity (table/view) name.
     *
     * @param string $entity
     * @return string|null
     */
    protected function forgeSearchAction(string $entity): ?string
    {
        // Format the SearchAction class name
        $className = ucfirst($entity);

        // Render the SearchAction code.
        try {
            $searchActionCode = $this->twig->render('SearchAction.php.twig', [
                    'class_name' => $className
                ]
            );
        } catch (Throwable $e) {
            return $e->getMessage();
        }

        $controllerPath = __DIR__ . '/../../../Controllers/' . $className;

        if (is_dir($controllerPath) === false) {
            if (mkdir($controllerPath) === false) {
                return 'Unable to create directory: ' . $controllerPath;
            }
        }

        // Save the searchAction code file into the Controllers/ directory.
        if (file_put_contents($controllerPath . '/' . $className . 'SearchAction.php', $searchActionCode) === false) {
            return 'Unable to create: ' . $controllerPath . '/' . $className . 'SearchAction.php';
        }

        return null;
    }
}