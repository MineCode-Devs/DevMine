<?php



namespace devmine\server\permissions;


interface PermissionRemovedExecutor{

	/**
	 * @param PermissionAttachment $attachment
	 *
	 * @return void
	 */
	public function attachmentRemoved(PermissionAttachment $attachment);
}