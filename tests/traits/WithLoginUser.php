<?php

namespace Tests\traits;

use App\Models\User;


/**
 * Trait WithLoginUser
 * @author yourname
 */
trait WithLoginUser
{
    protected $user;

    public function setUpLoginUser()
    {
        $this->user = User::factory()->create();
    }
}
