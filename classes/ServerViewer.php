<?php

require_once 'classes/MurmurClasses.php';
require_once 'classes/ServerInterface.php';

class ServerViewer
{
	private static function buildChannelTree($tree) {
		$ret = array();
		$ret['users'] = $tree->getUserList();

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

	public static function buildUserList($tree, array &$users = array()) {
		$userlist = $tree->getUserList();

		array_walk($userlist, array('ServerViewer', 'arrayPushAssoc'), array('channel', $tree->getRootChannel()->getName()));

		$users = array_merge($users, $userlist);

		foreach ($tree->getSubChannels() as $leaf) {
			self::buildUserList($leaf, $users);
		}

		return $users;
	}

	public static function arrayPushAssoc(&$array, $key, $dat) 
	{
		$array[$dat[0]] = $dat[1];
		return $array;
	}

	public static function getUserList($serverId)
	{
		$server = ServerInterface::getInstance()->getServer($serverId);
		$server = MurmurServer::fromIceObject($server);

		return self::buildUserlist($server->getTree());
	}

	public static function getServer($serverId)
	{
		$ret = array();

		$server = ServerInterface::getInstance()->getServer($serverId);
		$server = MurmurServer::fromIceObject($server);

		$ret['tree'] = self::buildChannelTree($server->getTree());
		
		return $ret;
	}
}
