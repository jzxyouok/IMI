<?php
namespace Imi\Util;

/**
 * 对象及数组帮助类
 * 智能识别数组和对象，支持对a.b.c这样的name属性进行操作
 */
abstract class ObjectArrayHelper
{
	/**
	 * 获取值
	 *
	 * @param array|object $object
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public static function get(&$object, $name, $default = null)
	{
		$names = explode('.', $name);
		$result = &$object;
		foreach ($names as $nameItem)
		{
			$type = gettype($result);
			if('array' === $type)
			{
				// 数组
				if (isset($result[$nameItem]))
				{
					$result = &$result[$nameItem];
				}
				else
				{
					return $default;
				}
			}
			else if('object' === $type)
			{
				// 对象
				if (isset($result->$nameItem))
				{
					$result = &$result->$nameItem;
				}
				else
				{
					return $default;
				}
			}
			else
			{
				return $default;
			}
		}
		if (isset($names[0]))
		{
			return $result;
		}
		else
		{
			return $default;
		}
	}

	/**
	 * 设置值
	 *
	 * @param array|object $object
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public static function set(&$object, $name, $value)
	{
		$names = explode('.', $name);
		$lastName = array_pop($names);
		$data = &$object;
		foreach ($names as $nameItem)
		{
			if(is_array($data))
			{
				if (!isset($data[$nameItem]))
				{
					$data[$nameItem] = [];
				}
				$data = &$data[$nameItem];
			}
			else if(is_object($data))
			{
				if (!isset($data->$nameItem))
				{
					$data->$nameItem = new stdClass;
				}
				$data = &$data->$nameItem;
			}
		}
		if(is_array($data))
		{
			$data[$lastName] = $value;
		}
		else if(is_object($data))
		{
			$data->$lastName = $value;
		}
	}

	/**
	 * 移除值
	 *
	 * @param array|object $object
	 * @param string $name
	 * @return void
	 */
	public static function remove(&$object, $name)
	{
		$names = explode('.', $name);
		$lastName = array_pop($names);
		$data = &$object;
		foreach ($names as $nameItem)
		{
			if(is_array($data))
			{
				if (!isset($data[$nameItem]))
				{
					$data[$nameItem] = [];
				}
				$data = &$data[$nameItem];
			}
			else if(is_object($data))
			{
				if (!isset($data->$nameItem))
				{
					$data->$nameItem = new stdClass;
				}
				$data = &$data->$nameItem;
			}
		}
		if(is_array($data))
		{
			unset($data[$lastName]);
		}
		else if(is_object($data))
		{
			unset($data->$lastName);
		}
	}

	/**
	 * 值是否存在
	 *
	 * @param array|object $object
	 * @param string $name
	 * @return boolean
	 */
	public static function exists(&$object, $name)
	{
		return null !== static::get($object, $name);
	}

}