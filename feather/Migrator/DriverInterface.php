<?php namespace Feather\Migrator;

interface DriverInterface {

	/**
	 * Migrate discussions from the old system to Feather.
	 * 
	 * @return void
	 */
	public function migrateDiscussions();

	/**
	 * Migrate discussion replies from the old system to Feather.
	 * 
	 * @return void
	 */
	public function migrateReplies();

	/**
	 * Migrate users from the old system to Feather.
	 * 
	 * @return void
	 */
	public function migrateUsers();

	/**
	 * Migrate a given user from the old system to Feather.
	 * 
	 * @return void
	 */
	public function migrateGivenUser();

}