<?php
/**
 * i-MSCP - internet Multi Server Control Panel
 * Copyright (C) 2010 - 2012 by i-MSCP Team
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @category	iMSCP
 * @package
 * @subpackage
 * @copyright	2010 - 2012 by Laurent Declercq
 * @author		Laurent Declercq <l.declercq@nuxwin.com>
 * @version		0.0.1
 * @link		http://www.i-mscp.net i-MSCP Home Site
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GPL v2
 */

/**
 * Responses class.
 *
 * @category
 * @package		iMSCP
 * @subpackage
 * @author		Laurent Declercq <l.declercq@nuxwin.com>
 * @version		0.0.1
 */
class iMSCP_Events_Listeners_ResponseCollection extends SplStack
{
	/**
	 * @var bool
	 */
	protected $isStopped = false;

	/**
	 * Did the last response provided trigger a short circuit of the stack?
	 *
	 * @return bool
	 */
	public function isStopped()
	{
		return $this->isStopped;
	}

	/**
	 * Mark the collection as stopped (or its opposite)
	 *
	 * @param  bool $flag
	 * @return ResponseCollection
	 */
	public function setStopped($flag)
	{
		$this->isStopped = (bool)$flag;
		return $this;
	}

	/**
	 * Convenient access to the first listener method return value.
	 *
	 * @return mixed The first handler return value
	 */
	public function first()
	{
		return parent::bottom();
	}

	/**
	 * Convenient access to the last listener method return value.
	 *
	 * If the collection is empty, returns null. Otherwise, returns value
	 * returned by last handler.
	 *
	 * @return mixed The last handler return value
	 */
	public function last()
	{
		if (count($this) === 0) {
			return null;
		}

		return parent::top();
	}

	/**
	 * Check if any of the responses match the given value.
	 *
	 * @param  mixed $value The value to look for among responses
	 * @return bool
	 */
	public function contains($value)
	{
		foreach ($this as $response) {
			if ($response === $value) {
				return true;
			}
		}

		return false;
	}
}
