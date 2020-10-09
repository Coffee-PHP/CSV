<?php

/**
 * CsvComponentRegistrarTest.php
 *
 * Copyright 2020 Danny Damsky
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package coffeephp\csv
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2020-10-09
 */

declare(strict_types=1);

namespace CoffeePhp\Csv\Test\Integration;

use CoffeePhp\Csv\Contract\CsvTranslatorInterface;
use CoffeePhp\Csv\CsvTranslator;
use CoffeePhp\Csv\Integration\CsvComponentRegistrar;
use CoffeePhp\Di\Container;
use CoffeePhp\QualityTools\TestCase;

use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/**
 * Class CsvComponentRegistrarTest
 * @package coffeephp\csv
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2020-10-09
 */
final class CsvComponentRegistrarTest extends TestCase
{
    /**
     * @see CsvComponentRegistrar::register()
     */
    public function testRegister(): void
    {
        $di = new Container();
        $registrar = new CsvComponentRegistrar();
        $registrar->register($di);

        assertTrue($di->has(CsvTranslatorInterface::class));
        assertTrue($di->has(CsvTranslator::class));

        assertInstanceOf(CsvTranslator::class, $di->get(CsvTranslator::class));
        assertSame($di->get(CsvTranslator::class), $di->get(CsvTranslatorInterface::class));
    }
}
