<?php

interface TimeToWordConvertingInterface
{
    /**
     * Конвертирует цифровое представление времени в словесное
     *
     * @param int $hours
     * @param int $minutes
     * @return string
     */
    public function convert(int $hours, int $minutes);
}

class TimeToWordConverter implements TimeToWordConvertingInterface
{
    /**
     * Вариации часа
     *
     * @var array
     */
    const hours = [
        1 => ['один', 'первого', 'первого'],
        2 => ['два', 'двух', 'второго'],
        3 => ['три', 'трех', 'третьего'],
        4 => ['четыре', 'четырех', 'четвертого'],
        5 => ['пять', 'пяти', 'пятого'],
        6 => ['шесть', 'шести', 'шестого'],
        7 => ['семь', 'семи', 'седьмого'],
        8 => ['восемь', 'восьми', 'восьмого'],
        9 => ['девять', 'девяти', 'девятого'],
        10 => ['десять', 'десяти', 'десятого'],
        11 => ['одиннадцать', 'одиннадцати', 'одиннадцатого'],
        12 => ['двенадцать', 'двенадцати', 'двенадцатого'],
    ];

    /**
     * Вариации минут
     *
     * @var array
     */
    const minutes = [
        0 => '',
        1 => 'одна',
        2 => 'две',
        3 => 'три',
        4 => 'четыре',
        5 => 'пять',
        6 => 'шесть',
        7 => 'семь',
        8 => 'восемь',
        9 => 'девять',
        10 => 'десять',
        11 => 'одиннадцать',
        12 => 'двенадцать',
        13 => 'тринадцать',
        14 => 'четырнадцать',
        15 => 'пятнадцать',
        16 => 'шестнадцать',
        17 => 'семнадцать',
        18 => 'восемнадцать',
        19 => 'девятнадцать',
        20 => 'двадцать',
        30 => 'тридцать',
        40 => 'сорок',
        50 => 'пятьдесят'
    ];

    /**
     * Возвращает слово "час", форматированное под значение часа
     *
     * @param int $hours
     * @return string
     */
    protected static function getHoursWord(int $hours)
    {
        switch ($hours) {
            case 1:
                return 'час';

            case 2:
            case 3:
            case 4:
                return 'часа';

            default:
                return 'часов';
        }
    }

    /**
     * Возвращает массив с вариациями конвертирования часа
     *
     * @param int $hours
     * @param bool $next
     * @return string[]
     */
    protected static function getHours(int $hours, bool $next = false)
    {
        if ($next) {
            $hours++;
            if ($hours > 12) $hours = 1;
        }

        return self::hours[$hours];
    }

    /**
     * Возвращает слово "минута", форматированное под значение минут
     *
     * @param int $minutes
     * @return string
     */
    protected static function getMinutesWord(int $minutes)
    {
        if ($minutes >= 11 && $minutes <= 14) return 'минут';
        switch ($minutes % 10) {
            case 1:
                return 'минута';

            case 2:
            case 3:
            case 4:
                return 'минуты';

            default:
                return 'минут';
        }
    }

    /**
     * Возвращает конвертированное значение минут
     *
     * @param int $minutes
     * @return string
     */
    protected static function getMinutes(int $minutes)
    {
        if ($minutes < 21 || !$minutes < 21 && $minutes % 10 == 0)
            return self::minutes[$minutes];
        else
            return self::minutes[intdiv($minutes, 10) * 10] . ' ' . self::minutes[$minutes % 10];
    }

    /**
     * Конвертирует цифровое представление времени в словесное
     *
     * @param int $hours
     * @param int $minutes
     * @return string
     */
    public function convert(int $hours, int $minutes)
    {
        switch (true) {
            case $minutes == 0:
                $converted = self::getHours($hours)[0] . ' ' . self::getHoursWord($hours);
                break;

            case $minutes == 15:
                $converted = 'четверть ' . self::getHours($hours, true)[2];
                break;

            case $minutes == 30:
                $converted = 'половина ' . self::getHours($hours, true)[2];
                break;

            case $minutes < 30:
                $converted = self::getMinutes($minutes) . ' ' . self::getMinutesWord($minutes) . ' после ' . self::getHours($hours)[1];
                break;

            case $minutes > 30:
                $minutes = 60 - $minutes;
                $converted = self::getMinutes($minutes) . ' ' . self::getMinutesWord($minutes) . ' до ' . self::getHours($hours, true)[1];
                break;

            default:
                $converted = self::getHours($hours)[0] . ' ' . self::getHoursWord($hours) . ' ' . self::getMinutes($minutes) . ' ' . self::getMinutesWord($minutes);
                break;
        }

        return mb_strtoupper(mb_substr($converted, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($converted, 1, strlen($converted) - 1, 'UTF-8');
    }
}