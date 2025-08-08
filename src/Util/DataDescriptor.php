<?php

namespace PhpDevCommunity\Debug\Util;

class DataDescriptor
{
    public static function describe($value, int $depth = 0, int $maxDepth = 5): array
    {
        if ($depth >= $maxDepth) {
            return [
                'type' => gettype($value),
                'depth' => $depth,
                'truncated' => true,
                'value' => '…'
            ];
        }

        $type = gettype($value);
        $result = [
            'type' => $type,
            'depth' => $depth,
            'truncated' => false
        ];

        switch (true) {
            case is_null($value):
                $result['value'] = 'null';
                break;

            case is_bool($value):
                $result['value'] = $value ? 'true' : 'false';
                break;

            case is_float($value):
                $result['type'] = 'float';
                $result['value'] = $value;
                break;

            case is_int($value):
                $result['type'] = 'int';
                $result['value'] = $value;
                break;

            case is_string($value):
                $result['length'] = function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
                $result['value'] = sprintf('"%s"', $value);
                break;

            case is_array($value):
                $result['count'] = count($value);
                $result['value'] = sprintf('array(%d)', $result['count']);       // ← affichable, homogène
                $result['items'] = [];
                foreach ($value as $k => $v) {
                    $result['items'][$k] = self::describe($v, $depth + 1, $maxDepth); // ← depth++
                }
                break;

            case is_object($value):
                $class = get_class($value);
                $result['class'] = $class;
                $result['object_id'] = spl_object_id($value);
                $result['value'] = sprintf('%s#%d', $class, $result['object_id']);
                $result['properties'] = [];
                foreach (ObjectPropertyExtractor::extract($value) as $prop => $val) {
                    $result['properties'][$prop] = self::describe($val, $depth + 1, $maxDepth); // ← depth++
                }
                break;

            case is_resource($value):
                $result['value'] = sprintf('resource(%s)', get_resource_type($value));
                break;
            default:
                $result['value'] = (string)$value;
        }


        return $result;
    }
}