<?php

class MurmurServer
{
	private $iceObj;

	public static function fromIceObject($iceObject)
	{
		if ($iceObject==null) {
			throw new Exception('Required iceObject parameter was null');
		}

		return new self($iceObject);
	}

	public function __construct($iceObj)
	{
		$this->iceObj = $iceObj;
	}

	public function getUsers()
	{
		return $this->iceObj->getUsers();
	}

	public function getChannels()
	{
		return $this->iceObj->getChannels();
	}

	public function getTree()
	{
		return MurmurTree::fromIceObject($this->iceObj->getTree(), $this);
	}

}

class MurmurUser
{
	private $name;
	
	public function __construct($name)
	{
		$this->name = $name;

	}

	public static function fromIceObject(Murmur_User $iceUser)
	{
		return new self(
			$iceUser->name
		);

		/*
		$iceUser->session,
		$iceUser->userid,
		$iceUser->mute,
		$iceUser->deaf,
		$iceUser->suppress,
		$iceUser->selfMute,
		$iceUser->selfDeaf,
		$iceUser->channel,
		$iceUser->name,
		$iceUser->onlinesecs,
		$iceUser->bytespersec,
		$iceUser->version,
		$iceUser->release,
		$iceUser->os,
		$iceUser->osversion,
		$iceUser->identity,
		$iceUser->context,
		$iceUser->comment,
		MurmurNetAddress::fromIceObject($iceUser->address),
		$iceUser->tcponly,
		$iceUser->idlesecs,
		isset($iceUser->prioritySpeaker)?$iceUser->prioritySpeaker:null
		*/
	}

	public function getName() 
	{
		return $this->name;
	}

}

class MurmurTree
{
	private $channel;
	private $children;
	private $users;

	public function __construct($channel, $children, $users)
	{
		$this->channel = $channel;
		$this->children = $children;
		$this->users = $users;
	}

	public static function fromIceObject($iceObject, &$server)
	{
		$channel = MurmurChannel::fromIceObject($iceObject->c, $server);

		$children = array();
		foreach ($iceObject->children as $child) {
			$children[] = self::fromIceObject($child, $server);
		}

		$users = array();
		foreach ($iceObject->users as $user) {
			$users[] = MurmurUser::fromIceObject($user);
		}

		return new self($channel, $children, $users);
	}

	public function getRootChannel()
	{
		return $this->channel;
	}

	public function getSubChannels()
	{
		return $this->children;
	}

	public function getUsers()
	{
		return $this->users;
	}
}

class MurmurChannel
{
	public static function fromIceObject($iceObject, &$server)
	{
		return new self(
			$iceObject->id,
			$iceObject->name,
			$iceObject->parent
		);
	}

	public function __construct($id, $name, $parentId = null)
	{
		$this->id = $id;
		$this->name = $name;
		$this->parentId = $parentId;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getParentChannelId()
	{
		return $this->parentId;
	}
}

