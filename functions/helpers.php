<?php

if (!function_exists('dd')) {

    /**
     * Dump and die: Dumps data and exits the script.
     *
     * @param mixed ...$data The data to dump.
     */
    function dd(...$data)
    {
        dump(...$data);
        exit(1);
    }
}

if (!function_exists('dump')) {

    /**
     * Dump data to the output.
     *
     * @param mixed ...$data The data to dump.
     */
    function dump(...$data)
    {
        $varDumper = new \PhpDevCommunity\Debug\VarDumper();
        $varDumper->dump(...$data);
    }
}

if (!function_exists('console_log')) {

    /**
     * Log data to the javascript console.
     *
     * @param mixed ...$data The data to log.
     */
    function console_log(...$data)
    {
        $varDumper = new \PhpDevCommunity\Debug\VarDumper(new \PhpDevCommunity\Debug\Output\ConsoleLogOutput());
        $varDumper->dump(...$data);
    }

}
