<?php

namespace App\Policies;

use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PembayaranPolicy
{
    /**
     * Lihat semua pembayaran.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('pembayarans-view');
    }

    /**
     * Lihat detail pembayaran.
     */
    public function view(User $user, Pembayaran $pembayaran): bool
    {
        return $user->can('pembayarans-view');
    }

    /**
     * Tambah pembayaran.
     */
    public function create(User $user): bool
    {
        return $user->can('pembayarans-create');
    }

    /**
     * Update pembayaran.
     */
    public function update(User $user, Pembayaran $pembayaran): Response
    {
        if (!$user->can('pembayarans-edit')) {
            return Response::deny('Anda tidak memiliki izin untuk mengedit pembayaran.');
        }
        // Hanya pemilik (petugas yang menginput) yang boleh mengedit
        if ($user->id !== $pembayaran->collector_id) {
            return Response::deny('Anda tidak dapat mengedit pembayaran milik petugas lain.');
        }

        if ($pembayaran->setoran_id !== null) {
            return Response::deny('Pembayaran sudah disetorkan dan dikonfirmasi, tidak dapat diubah.');
        }

        return Response::allow();
    }

    /**
     * Hapus pembayaran.
     */
    public function delete(User $user, Pembayaran $pembayaran): Response
    {
        if (!$user->can('pembayarans-delete')) {
            return Response::deny('Anda tidak memiliki izin untuk menghapus pembayaran.');
        }
        // Hanya petugas yang menginput yang bisa hapus
        if ($user->id !== $pembayaran->collector_id) {
            return Response::deny('Anda tidak dapat menghapus pembayaran milik petugas lain.');
        }


        if ($pembayaran->setoran_id !== null) {
            return Response::deny('Pembayaran sudah disetorkan, tidak dapat dihapus.');
        }

        return Response::allow();
    }

    /**
     * Restore pembayaran.
     */
    public function restore(User $user, Pembayaran $pembayaran): bool
    {
        return false;
    }

    /**
     * Force delete pembayaran.
     */
    public function forceDelete(User $user, Pembayaran $pembayaran): bool
    {
        return false;
    }
}
