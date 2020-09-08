<?php

namespace ZnBundle\Messenger\Domain\Libs;

use IntlChar;
use Phpml\Classification\Classifier;

class WordClassificator
{

    private $wordLength;

    /**
     * @var Classifier
     */
    private $classifier;

    public function getClassifier() : ?Classifier
    {
        return $this->classifier;
    }

    public function setClassifier(Classifier $classifier): void
    {
        $this->classifier = $classifier;
    }

    public function getWordLength(): ?int
    {
        return $this->wordLength;
    }

    public function setWordLength(int $wordLength): void
    {
        $this->wordLength = $wordLength;
    }

    public function train($wordCollection) {
        $arr = $this->generateTrain($wordCollection, 2);
        list($samples, $labels) = $this->prepareSamplesForTraining($arr);
        $this->classifier->train($samples, $labels);
    }

    public function predict($samples) {
        $samplesArr = $this->splitWord($samples);
        $predict = $this->classifier->predict($samplesArr);
        return $predict;
    }

        /**
     * Заполнить массив до нужной длины
     */
    public static function fillChars(array $a, int $wantLength): array
    {
        $dd = $wantLength - count($a) + 1;
        if ($dd > 0) {
            for ($i = 0; $i < $dd; $i++) {
                $a[] = 0;
            }
        }
        return $a;
    }

    public function prepareSamplesForTraining(array $arr): array
    {
        $samples = [];
        $labels = [];
        foreach ($arr as $keyWord => $words) {
            foreach ($words as $word) {
                $labels[] = $keyWord;
                $code = $this->splitWord($word, $this->wordLength);
                $samples[] = $code;
            }
        }
        return [$samples, $labels];
    }

    public function splitWord(string $value): array
    {
        $value = mb_strtolower($value);
        $code = self::splitWordToArrayOfChar($value);
        $code = self::fillChars($code, $this->wordLength);
        return $code;
    }

    public static function splitWordToArrayOfChar($value): array
    {
        $chars = preg_split('//u', $value, NULL, PREG_SPLIT_NO_EMPTY);
        $code = [];
        foreach ($chars as $char) {
            $code[] = IntLChar::ord($char);
        }
        return $code;
    }

    public static function generateLabels(string $word, int $labelCount = 1): array
    {
        $labels = [];
        for ($i = 0; $i < $labelCount; $i++) {
            $labels[] = $word;
        }
        return $labels;
    }

    public static function generateTrain($words, int $labelCount = 1): array
    {
        $train = [];
        foreach ($words as $word) {
            $train[$word] = self::generateLabels($word, $labelCount);
        }
        return $train;
    }

}