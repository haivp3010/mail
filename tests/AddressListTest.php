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

namespace OCA\Mail\Tests;

use Horde_Mail_Rfc822_Address;
use Horde_Mail_Rfc822_Group;
use Horde_Mail_Rfc822_List;
use OCA\Mail\Address;
use OCA\Mail\AddressList;

class AddressListTest extends TestCase {

	public function testSerialize() {
		$list = new AddressList([
			new Address('User 1', 'user1@domain.tld'),
			new Address('User 2', 'user2@domain.tld'),
		]);
		$expected = [
			[
				'label' => 'User 1',
				'email' => 'user1@domain.tld',
			],
			[
				'label' => 'User 2',
				'email' => 'user2@domain.tld',
			],
		];

		$json = $list->jsonSerialize();

		$this->assertCount(2, $json);
		$this->assertEquals($expected, $json);
	}

	public function testFromHordeList() {
		$hordeList = new Horde_Mail_Rfc822_List([
			new Horde_Mail_Rfc822_Address(),
			new Horde_Mail_Rfc822_Group(),
		]);

		$list = AddressList::fromHorde($hordeList);

		$this->assertCount(1, $list);
	}

}
