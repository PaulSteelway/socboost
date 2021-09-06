<?php

namespace App\Admin\Policies;

use App\Admin\Sections\Users;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsersSectionModelPolicy
{

  use HandlesAuthorization;

  public function before(User $user, $ability, Users $section, User $item = null)
  {
    //   return true;
  }


  public function display(User $user, Users $section, User $item) {
    return true;
  }

  public function create(User $user, Users $section, User $item) {
    return true;
  }

  public function edit(User $user, Users $section, User $item) {
    return true;
  }

  public function delete(User $user, Users $section, User $item) {
    return false;
  }

  public function restore(User $user, Users $section, User $item) {
    return false;
  }

  // public function destroy(User $user, Users $section, User $item) {
  //   if ($user->isAdmin()) {
  //     return true;
  //   }
  //   return false;
  // }

}
