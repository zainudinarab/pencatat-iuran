<?php

namespace App\Policies;

use App\Models\SetoranPetugas;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SetoranPetugasPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('setoran_petugas-view');
    }

    public function view(User $user, SetoranPetugas $setoranPetugas): bool
    {
        return $user->can('setoran_petugas-view');
    }

    public function create(User $user): bool
    {
        return $user->can('setoran_petugas-create');
    }

    public function update(User $user, SetoranPetugas $setoranPetugas): Response
    {
        if (!$user->can('setoran_petugas-edit')) {
            return Response::deny('Anda tidak memiliki izin untuk mengedit setoran.');
        }

        if ($setoranPetugas->status === 'confirmed') {
            return Response::deny('Setoran sudah dikonfirmasi bendahara, tidak dapat diubah.');
        }

        return Response::allow();
    }


    public function delete(User $user, SetoranPetugas $setoranPetugas): Response
    {
        if (!$user->can('setoran_petugas-delete')) {
            return Response::deny('Anda tidak memiliki izin untuk menghapus setoran.');
        }

        if ($setoranPetugas->status === 'confirmed') {
            return Response::deny('Setoran sudah dikonfirmasi bendahara, tidak dapat dihapus.');
        }

        return Response::allow();
    }

    public function restore(User $user, SetoranPetugas $setoranPetugas): bool
    {
        return false;
    }

    public function forceDelete(User $user, SetoranPetugas $setoranPetugas): bool
    {
        return false;
    }

    public function confirm(User $user, SetoranPetugas $setoranPetugas): bool
    {
        return
            $user->hasRole('bendahara_rt') &&    // pastikan role bendaharaRt
            $user->can('setoran_petugas-confirm') && // pastikan punya permission konfirmasi
            $setoranPetugas->status === 'pending';   // dan status setoran masih pending
    }

    public function reject(User $user, SetoranPetugas $setoranPetugas): bool
    {
        return
            $user->hasRole('bendahara_rt') &&
            $user->can('setoran_petugas-reject') &&
            $setoranPetugas->status === 'pending';
    }
}
