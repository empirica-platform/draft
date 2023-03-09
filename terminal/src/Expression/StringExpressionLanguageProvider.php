<?php

namespace EmpiricaPlatform\Terminal\Expression;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class StringExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions()
    {
        return [
            new ExpressionFunction('getProjectDir',
                function () { return 'getProjectDir()'; },
                function () {
                        $r = new \ReflectionObject($this);
                        if (!is_file($dir = $r->getFileName())) {
                            throw new \LogicException(sprintf('Cannot auto-detect project dir for kernel of class "%s".', $r->name));
                        }
                        $dir = $rootDir = \dirname($dir);
                        while (!is_file($dir.'/composer.json')) {
                            if ($dir === \dirname($dir)) {
                                return $this->projectDir = $rootDir;
                            }
                            $dir = \dirname($dir);
                        }

                    return $dir;
                }
            ),
            new ExpressionFunction('lowercase', function ($str) {
                return sprintf('(is_string(%1$s) ? strtolower(%1$s) : %1$s)', $str);
            }, function ($arguments, $str) {
                if (!is_string($str)) {
                    return $str;
                }

                return strtolower($str);
            }),
            new ExpressionFunction('isEmpty',
                function($str) {return "isEmpty($str)";},
                function($arguments, $str) {return empty($str);}
            ),
            new ExpressionFunction('isNull',
                function($str) {return "isNull($str)";},
                function($arguments, $str) {return is_null($str);}
            ),
            ExpressionFunction::fromPhp('count'),
            ExpressionFunction::fromPhp('implode'),
            ExpressionFunction::fromPhp('explode'),
            ExpressionFunction::fromPhp('array_key_exists'),
        ];
    }
}
