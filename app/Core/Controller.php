<?php
declare(strict_types=1);

namespace Mapi\Core;

use Phalcon\Mvc\Controller as PController;

use Mapi\Interfaces\ISharedConst;

use function join;
use function strpos;
use function strval;
use function explode;
use function in_array;
use function array_map;
use function array_values;
use function array_filter;
use function htmlentities;

class Controller extends PController implements ISharedConst
{

    /**
     * Index : Get List of Routes
     *
     * @return array
     */
    public function index() : array
    {
        $matched = $this->router->getMatchedRoute();
        $matched = $matched->getPattern() ?? "/";
        
        return [
            'routes'=> array_values(array_filter(self::getRoutes($this), function ($item) use ($matched) {
                return strpos(strval($item), $matched) !== false;
            }))
        ];
    }

    /**
     * Get List of Routes
     *
     * @param Micro $app
     * @return array|null
     */
    public static function getRoutes(Controller $controller) :? array
    {
        $routes = array_map(function ($item) {
            $route = $item->getPattern();
            $routeParts = explode('/', $route) ?? [];
            foreach ($routeParts as $i => $v) {
                if (strpos(strval($v), ':') !== false && strpos(strval($v), '{') !== false) {
                    $partArray = explode('{', explode(':', $v)[0])[1];
                    $routeParts[$i] = ":$partArray";
                }
            }

            return  join('/', $routeParts) ?? $route;
        }, $controller ->router->getRoutes());
        sort($routes);

        return $routes;
    }

    /**
     * Filter Data & return allowed fields data
     *
     * @param array $dataArray
     * @param array $excludes
     * @param array $includes
     * @return array|null
     */
    public static function filterData(array $dataArray, array $excludes = [], array $includes = []) :? array
    {
        if (count($includes)) {
            return array_filter($dataArray, function ($key) use ($includes) {
                return in_array($key, $includes);
            }, ARRAY_FILTER_USE_KEY);
        }
        
        return array_filter($dataArray, function ($key) use ($excludes) {
            return !in_array($key, $excludes);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Sanitize Data
     *
     * @param array $data
     * @return array
     */
    public function sanitizeData(array $data) : array
    {
        foreach ($data as $key=>$value) {
            $data[$key] = (!is_array($value)) ? htmlentities($value) : $this->sanitizeData($value);
        }
        return $data;
    }
}
