<?php

/**
 * CsvTranslator.php
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

namespace CoffeePhp\Csv;

use CoffeePhp\Csv\Contract\CsvTranslatorInterface;
use CoffeePhp\Csv\Exception\CsvSerializeException;
use CoffeePhp\Csv\Exception\CsvUnserializeException;
use Throwable;

use function is_array;
use function is_string;
use function str_getcsv;
use function str_putcsv;

/**
 * Class CsvTranslator
 * @package coffeephp\csv
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2020-10-09
 */
final class CsvTranslator implements CsvTranslatorInterface
{

    /**
     * @inheritDoc
     */
    public function serializeArray(array $array): string
    {
        try {
            $csv = str_putcsv($array);
            if (!is_string($csv)) {
                throw new CsvSerializeException("Array provided is not in a valid CSV format.");
            }
            return $csv;
        } catch (CsvUnserializeException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new CsvSerializeException(
                "Failed to serialize array to CSV format: {$e->getMessage()}",
                (int)$e->getCode(),
                $e
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function unserializeArray(string $string): array
    {
        try {
            /** @var array|mixed $array */
            $array = str_getcsv($string);
            if (!is_array($array)) {
                throw new CsvUnserializeException("String provided is not a valid CSV: $string");
            }
            return $array;
        } catch (CsvUnserializeException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new CsvUnserializeException(
                "Failed to unserialize array to CSV format: {$e->getMessage()}",
                (int)$e->getCode(),
                $e
            );
        }
    }
}
