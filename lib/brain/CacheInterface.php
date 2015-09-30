<?php
namespace Brain;
/**
 * @author  Deivid Fortuna <deividfortuna@gmail.com>
 * @package Brain
 */
interface CacheInterface
{
	function load();
	function creatCacheFile();
}