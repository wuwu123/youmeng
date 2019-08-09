<?php


namespace Rongun;

class Tool
{
    /**
     * 判断是否是时间
     * @param string $data
     * @return bool
     */
    public static function isTime(string $data)
    {
        $patten = "/^\d{4}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01])(\s+(0?[0-9]|1[0-9]|2[0-3])\:(0?[0-9]|[1-5][0-9])\:(0?[0-9]|[1-5][0-9]))?$/";
        if (preg_match($patten, $data)) {
            return true;
        }
        return false;
    }

    /**
     * 重试
     * @param $times
     * @param callable $callback
     * @param int $sleep
     * @return mixed
     * @throws \Throwable
     */
    public static function retry($times, callable $callback, $sleep = 0)
    {
        beginning:
        try {
            return $callback();
        } catch (\Throwable $e) {
            if (--$times < 0) {
                throw $e;
            }
            if ($sleep) {
                usleep($sleep * 1000);
            }
            goto beginning;
        }
    }
}
