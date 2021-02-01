<?php

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;

// conditional declaration is *not* a side effect
if (! function_exists('bar')) {
    function bar()
    {
        // function body
    }
}

if (! function_exists('parse_doc_comment')) {
    function parse_doc_comment($doc_comment) : array
    {
        if (!$doc_comment) {
            return [];
        }
        $lexer = new Lexer();
        $tokens = $lexer->tokenize($doc_comment);
        $parser = new PhpDocParser(new TypeParser, new ConstExprParser);
        $output = $parser->parse(new TokenIterator($tokens));
        return array_map(fn ($child) => str_replace("\n", ' ', $child), $output->children);
    }
}

if (! function_exists('prepare_name')) {
    function prepare_name(ReflectionClass $class) : string
    {
        if ($class->isInterface()) {
            return ";;Интерфейс " . $class->getShortName();
        }

        if ($class->isTrait()) {
            return ";;Трейт " . $class->getShortName();
        }

        if ($class->isAbstract()) {
            return ";;Абстрактный класс " . $class->getShortName();
        }

        if ($class->isIterable()) {
            return ";;Итератор " . $class->getShortName();
        }

        if ($class->isSubclassOf(Exception::class)) {
            return ";;Исключение " . $class->getShortName();
        }

        if ($class->isSubclassOf(Error::class)) {
            return ";;Ошибка " . $class->getShortName();
        }

        return ";;Класс " . $class->getShortName();
    }
}

if (! function_exists('prepare_comment')) {
    function prepare_comment(ReflectionClass $class) : string
    {

        $traits = $class->getTraits();
        foreach ($traits as $trait) {
            $result_traits[] = $trait->getName();
        }

        $methods = $class->getMethods();
        foreach ($methods as $method) {

            $modifiers = Reflection::getModifierNames($method->getModifiers());
            if (

                !in_array('private', $modifiers)
                && $method->getDeclaringClass()->getName() == $class->getName()

            ) {

                //Модификаторы метода
                unset($modifiers[array_search('public', $modifiers)]);
                $result_method = (empty($modifiers) ? '' : implode(' ', $modifiers) . ' ') . $method->name . '(';

                //Параметры метода
                $params = $method->getParameters();
                $result_params = [];

                foreach ($params as $param) {

                    try {
                        $type = $param -> getType();
                        $result_param = ($type ? $type->getName() . ' ' : '') . $param->name;
                        if ($param->isOptional()) $result_param .= ' = ' . $param->getDefaultValue();
                        $result_params[] = $result_param;
                    } catch (Throwable $e) {
                        $result_params[] = $e->getMessage();
                    }

                }

                $result_method .= implode(', ', $result_params) . ')';

                //Возвращаемый тип
                $result_type = $method->getReturnType();
                if ($result_type) {
                    $result_method .= ' : ' . $result_type->getName();
                }

                //Описание метода
                $result_phpdoc = parse_doc_comment($method->getDocComment());
                if ($result_phpdoc) $result_method .= "''" . implode("''", $result_phpdoc);
                $result_methods[] = $result_method;

            }

        }

        return ';;'
            . (isset($result_traits) ? implode("''", $result_traits) . "=" : '')
            . (isset($result_methods) ? implode("''-''", $result_methods) : '');

    }
}

if (! function_exists('prepare_definition')) {
    function prepare_definition(ReflectionClass $class) : string
    {
        $result_phpdoc = parse_doc_comment($class->getDocComment());
        return $result_phpdoc ? ";;Определение:" . implode("''", $result_phpdoc) : '';
    }
}

if (! function_exists('prepare_class')) {
    function prepare_class(ReflectionClass $class) : string
    {
        $parentClass = $class->getParentClass();
        return $parentClass ? ";;Класс:".$parentClass->name : '';
    }
}

if (! function_exists('prepare_interface')) {
    function prepare_interface(ReflectionClass $class) : string
    {
        $interfaces = $class->getInterfaces();
        if ($interfaces) {
            $repeating = [];

            foreach ($interfaces as $interface) {
                if (($parentClass = $class->getParentClass()) && $parentClass->implementsInterface($interface)) {
                    $repeating[] = $interface;
                } else foreach ($interfaces as $checking) {
                    if ($checking !== $interface && $checking->implementsInterface($interface)) {
                        $repeating[] = $interface;
                    }
                }
            }

            $not_repeating = array_diff($interfaces, $repeating);
            $result_interfaces = array_map(fn(ReflectionClass $interface) => $interface->name, $not_repeating);
        }
        return isset($result_interfaces) && !empty($result_interfaces) ? ";;Интерфейс:".implode("''", $result_interfaces) : '';

    }
}

if (! function_exists('print_library')) {
    function print_library(string $directory, string $namespace) : string
    {
        $result = "";
        $directory = '../vendor/' . $directory;

        $dir = new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS);
        $dir  = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST);

        $dirs  = [];
        $files = [];

        while ($dir->valid()) {
            if ($dir->isDir()) {
                $dirs[] = $dir->getSubPathName();
            }
            else {
                $files[] = $dir->getSubPathName();
            }
            $dir->next();
        }

        $result .= ' \\;;Объект рассмотрения;;' . $namespace . "''" . $directory . "\n";

        foreach ($dirs as $str) {
            $result .= ' \\!! ' . str_replace('\\', '\\!! ', $str) . "\\\n";
        }

        $files = array_unique($files, SORT_REGULAR);
        foreach ($files as $str) {

            $parts = explode('.', $str);

            try {
                $class = new ReflectionClass($namespace . $class = '\\' . $parts[0]);
                $replace = prepare_name($class)
                    . prepare_comment($class)
                    . prepare_definition($class)
                    . prepare_class($class)
                    . prepare_interface($class);
                $result .= ' \\!! ' . str_replace(['\\', '.php'], ['\\!! ', $replace], $str). "\n";

            } catch (ReflectionException | Error $e) {
                $result .= ' \\!! ' . str_replace('\\', '\\!! ', $str). "\n";
            }

        }

        return $result;

    }
}
