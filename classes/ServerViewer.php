<?php

require_once 'classes/MurmurClasses.php';
require_once 'classes/ServerInterface.php';

class ServerViewer
{
	private static function buildChannelTree($tree) {
		$ret = array();

		$users = $tree->getUsers();

		if (!empty($users)) {
			$ret['users'] = array();

			foreach ($users as $userObj) {
				$user = array(
					'username' => $userObj->getName(),
				);

				$ret['users'][] = $user;
			}
		}

		$subchannels = $tree->getSubChannels();

		if (!empty($subchannels)) {
			$ret['channels'] = array();

			foreach ($tree->getSubChannels() as $leaf) {
				$channel = array(
					'name' => $leaf->getRootChannel()->getName(),
					'subtree' => self::buildChannelTree($leaf)
				);

				$ret['channels'][] = $channel;
			}

		}

		return $ret;
	}

	public static function getServer($serverId)
	{
		$ret = array();

		$server = ServerInterface::getInstance()->getServer($serverId);

		if ($server == null) {
			throw new Exception('Server not found.');
		}

		$server = MurmurServer::fromIceObject($server);

		$ret['tree'] = self::buildChannelTree($server->getTree());
		
		return $ret;
	}
}
