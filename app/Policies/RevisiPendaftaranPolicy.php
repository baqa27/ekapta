<?php

namespace App\Policies;

use App\Models\RevisiPendaftaran;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RevisiPendaftaranPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RevisiPendaftaran  $revisiPendaftaran
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, RevisiPendaftaran $revisiPendaftaran)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RevisiPendaftaran  $revisiPendaftaran
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, RevisiPendaftaran $revisiPendaftaran)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RevisiPendaftaran  $revisiPendaftaran
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, RevisiPendaftaran $revisiPendaftaran)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RevisiPendaftaran  $revisiPendaftaran
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, RevisiPendaftaran $revisiPendaftaran)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RevisiPendaftaran  $revisiPendaftaran
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, RevisiPendaftaran $revisiPendaftaran)
    {
        //
    }
}
