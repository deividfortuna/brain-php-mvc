<?php
namespace Brain;
/**
 * @author  Deivid Fortuna <deividfortuna@gmail.com>
 * @package Brain
 */
interface CacheInterface
{
	public function load();
	public function creatCacheFile();
}