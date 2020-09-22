<?php

namespace App\Policies;


class PostPolicy
{
	/**
	 * Create a new policy instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	public function update(User $user, Post $post)
	{
		return $user->id == $post->user_id;
	}
}
