<?php

/**
 * CsvTranslatorTest.php
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

namespace CoffeePhp\Csv\Test\Unit;

use CoffeePhp\Csv\CsvTranslator;
use CoffeePhp\QualityTools\TestCase;
use SplFileObject;

use function explode;
use function PHPUnit\Framework\assertSame;

/**
 * Class CsvTranslatorTest
 * @package coffeephp\csv
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2020-10-09
 * @see CsvTranslator
 */
final class CsvTranslatorTest extends TestCase
{
    private CsvTranslator $translator;
    private array $fileData;
    private SplFileObject $file;

    /**
     * @before
     */
    public function setupDependencies(): void
    {
        $this->translator = new CsvTranslator();
        $this->fileData = [
            ['id', 'first_name', 'last_name', 'email', 'gender', 'ip_address'],
            ['1', 'Annabell', 'Bentinck', 'abentinck0@mapy.cz', 'Female', '58.61.141.234'],
            ['2', 'Cilka', 'Merrill', 'cmerrill1@illinois.edu', 'Female', '55.244.164.10'],
            ['3', 'Calla', 'Stollwerck', 'cstollwerck2@cocolog-nifty.com', 'Female', '34.144.153.189'],
            ['4', 'Pauly', 'Pickthorne', 'ppickthorne3@macromedia.com', 'Male', '59.180.3.122'],
            ['5', 'Matthias', 'Halton', 'mhalton4@businesswire.com', 'Male', '102.82.162.189'],
            ['6', 'Lyn', 'Halston', 'lhalston5@bing.com', 'Female', '209.190.61.163'],
            ['7', 'Gawain', 'Houlston', 'ghoulston6@harvard.edu', 'Male', '227.100.124.221'],
            ['8', 'Kariotta', 'Stotherfield', 'kstotherfield7@tripod.com', 'Female', '130.87.80.85'],
            ['9', 'Benny', 'Panniers', 'bpanniers8@moonfruit.com', 'Female', '99.243.74.208'],
            ['10', 'Allister', 'Bernardi', 'abernardi9@instagram.com', 'Male', '45.210.112.131'],
        ];
        $this->file = new SplFileObject(
            dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Fake' . DIRECTORY_SEPARATOR . 'dummy.csv'
        );
        $this->file->openFile('rbw');
    }

    /**
     * @see CsvTranslator::serializeArray()
     */
    public function testSerializeArray(): void
    {
        $array = [$this->getFaker()->paragraph, $this->getFaker()->paragraph, $this->getFaker()->paragraph];
        assertSame(
            "\"{$array[0]}\",\"{$array[1]}\",\"{$array[2]}\"\n",
            $this->translator->serializeArray($array)
        );
    }

    /**
     * @see CsvTranslator::unserializeArray()
     */
    public function testUnserializeArray(): void
    {
        $array = [$this->getFaker()->paragraph, $this->getFaker()->paragraph, $this->getFaker()->paragraph];
        $csv = "\"{$array[0]}\",\"{$array[1]}\",\"{$array[2]}\"\n";
        assertSame(
            $array,
            $this->translator->unserializeArray($csv)
        );
    }

    /**
     * @see CsvTranslator::serializeArray()
     * @see CsvTranslator::unserializeArray()
     */
    public function testSerializeAndUnserializeArray(): void
    {
        $array = [$this->getFaker()->paragraph, $this->getFaker()->paragraph, $this->getFaker()->paragraph];
        assertSame(
            $array,
            $this->translator->unserializeArray($this->translator->serializeArray($array))
        );
    }


    /**
     * @see CsvTranslator::unserializeArray()
     */
    public function testCsvFileRead(): void
    {
        $array = [];
        while ($line = $this->file->fgets()) {
            if (!empty($line)) {
                $array[] = $this->translator->unserializeArray($line);
            }
        }
        assertSame(
            $this->fileData,
            $array
        );
    }

    /**
     * @see CsvTranslator::unserializeArray()
     */
    public function testCsvFileReadLine(): void
    {
        $array = [];
        while ($line = $this->file->fgets()) {
            $array[] = $this->translator->unserializeArray($line);
        }
        assertSame(
            $this->fileData,
            $array
        );
    }

    /**
     * @see CsvTranslator::serializeArray()
     */
    public function testCsvFileCopy(): void
    {
        $fakeFileContents = '';
        foreach ($this->fileData as $csvRow) {
            $fakeFileContents .= $this->translator->serializeArray($csvRow);
        }
        assertSame(
            $fakeFileContents,
            $this->file->fread(4096)
        );
    }
}
