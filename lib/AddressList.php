<?php

/**
 * @copyright 2017 Christoph Wurst <christoph@winzerhof-wurst.at>
 *
 * @author 2017 Christoph Wurst <christoph@winzerhof-wurst.at>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Mail;

use Countable;
use Horde_Mail_Rfc822_Address;
use Horde_Mail_Rfc822_List;
use Horde_Mail_Rfc822_Object;
use JsonSerializable;

class AddressList implements Countable, JsonSerializable {

	/** @var Address[] */
	private $addresses;

	/**
	 * @param Address[] $addresses
	 */
	public function __construct(array $addresses = []) {
		$this->addresses = $addresses;
	}

	/**
	 * Construct a new list from an horde list
	 *
	 * @param Horde_Mail_Rfc822_List $hordeList
	 * @return AddressList
	 */
	public static function fromHorde(Horde_Mail_Rfc822_List $hordeList) {
		$addresses = array_map(function(Horde_Mail_Rfc822_Address $addr) {
			return new Address($addr->personal, $addr->bare_address);
		}, array_filter(iterator_to_array($hordeList), function(Horde_Mail_Rfc822_Object $obj) {
				// TODO: how to handle non-addresses? This doesn't seem right …
				return $obj instanceof Horde_Mail_Rfc822_Address;
			}));
		return new AddressList($addresses);
	}

	/**
	 * Get first element
	 *
	 * Returns null if the list is empty
	 *
	 * @return Address|null
	 */
	public function first() {
		if (empty($this->addresses)) {
			return null;
		}

		return $this->addresses[0];
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return array_map(function(Address $address) {
			return $address->jsonSerialize();
		}, $this->addresses);
	}

	/**
	 * @return int
	 */
	public function count() {
		return count($this->addresses);
	}

}