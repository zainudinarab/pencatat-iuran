<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RT;
use App\Models\Gang;
use App\Models\House;
use App\Models\User;
use App\Models\SaldoPosRt;

class RTSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin User', 'password' => bcrypt('admin')]
        );
        $adminUser->assignRole('admin');

        // Ketua RT
        $ketuaRT = User::firstOrCreate(
            ['email' => 'ketua@gmail.com'],
            ['name' => 'Khoirul Wahyudi', 'password' => bcrypt('12345')]
        );
        $ketuaRT->assignRole('ketua_rt');

        // Bendahara RT
        $bendaharaRT = User::firstOrCreate(
            ['email' => 'zainudinarab@gmail.com'],
            ['name' => 'zainudin', 'password' => bcrypt('arab')]
        );
        $bendaharaRT->assignRole('bendahara_rt');


        // Buat RT
        $rt = RT::create([
            'name' => 'RT 11',
            'ketua_rt_id' => $ketuaRT->id,
            'bendahara_id' => $bendaharaRT->id,
            'rw' => 'RW 3',
            'village' => 'Desa ABC',
            'district' => 'Kecamatan XYZ',
            'city' => 'Kota Jakarta',
        ]);
        // Buat saldo pos Ketua RT (default saldo 0)
        SaldoPosRt::create([
            'rt_id' => $rt->id,
            'user_id' => $ketuaRT->id,
            'pos' => 'ketua_rt',
            'saldo' => 1000000, // contoh saldo awal
        ]);

        // Buat saldo pos Bendahara (default saldo 0)
        SaldoPosRt::create([
            'rt_id' => $rt->id,
            'user_id' => $bendaharaRT->id,
            'pos' => 'bendahara_rt',
            'saldo' => 500000, // contoh saldo awal
        ]);

        // Data gang & ketua gang
        $gangKetuaData = [
            'Gang 4' => ['email' => 'gang4@gmail.com', 'name' => 'Ketua Gang 4'],
            'Gang 5' => ['email' => 'gang5@gmail.com', 'name' => 'Ketua Gang 5'],
            'Gang 6' => ['email' => 'gang6@gmail.com', 'name' => 'Ketua Gang 6'],
            'Gang 7' => ['email' => 'gang7@gmail.com', 'name' => 'Ketua Gang 7'],
        ];

        // Data rumah per gang
        $housesData = [
            'Gang 4' => [
                ['H10', 'H', 10, 'Hariyadi'],
                ['H11', 'H', 11, 'Sri Rahayu'],
                ['H12', 'H', 12, 'Tahmid'],
                ['H13', 'H', 13, 'Masrikan'],
                ['H14', 'H', 14, 'Khiril Irwan'],
                ['H15', 'H', 15, 'Khoirul Anam'],
                ['H16', 'H', 16, 'Zainuddin'],
                ['H17', 'H', 17, 'Basuki'],
                ['H18', 'H', 18, 'Samsul Maarif'],
                ['I01', 'I', 1, 'Nurdin Bramono'],
                ['I02', 'I', 2, "Ainun Ni'am"],
                ['I03', 'I', 3, 'Boni Sugihartono / Muhammad Alfito Ega Kurniawan'],
                ['I04', 'I', 4, 'Muzakki'],
                ['I05', 'I', 5, 'Ahmad Nasrullah'],
                ['I06', 'I', 6, 'Lutfi'],
                ['I07', 'I', 7, 'M. Faturochman'],
                ['I08', 'I', 8, 'Muhammad Iqbal Prasetya'],
                ['I09', 'I', 9, 'M. Abdillah'],
                ['I10', 'I', 10, 'Khoirul Wahyudi'],
            ],
            'Gang 5' => [
                ['I11', 'I', 11, 'Safiil Maarif'],
                ['I12', 'I', 12, 'Andik Purwanto'],
                ['I13', 'I', 13, 'Abdul Basit'],
                ['I14', 'I', 14, 'Asmanul Husen'],
                ['I15', 'I', 15, 'Mukhlisin'],
                ['I16', 'I', 16, 'Achmad Tholib'],
                ['I17', 'I', 17, "Fauzi/ Nurul Khoirun Nisa'"],
                ['I18', 'I', 18, 'Arif / Himmatul Ulya'],
                ['I19', 'I', 19, 'Fajarroeddin'],
                ['I20', 'I', 20, 'M. Nadhir'],
                ['J01', 'J', 1, 'Yudha Ari P'],
                ['J02', 'J', 2, 'Hendra Siswanto'],
                ['J03', 'J', 3, 'Abdul Latif'],
                ['J04', 'J', 4, 'Muhammad Malik Mudzoffar'],
                ['J05', 'J', 5, 'Achmat Rosyid'],
                ['J06', 'J', 6, 'Zaky Mubarok'],
                ['J07', 'J', 7, 'M. Badaruddin'],
                ['J08', 'J', 8, 'Nungki Yulia Hapsari'],
                ['J09', 'J', 9, 'Aprilian Fery'],
            ],
            'Gang 6' => [
                ['J10', 'J', 10, 'Moch. Umar'],
                ['J11', 'J', 11, 'Abdul Ghofar'],
                ['J12', 'J', 12, 'Toni Suhartono'],
                ['J13', 'J', 13, 'Taufiqul Hakim'],
                ['J14', 'J', 14, 'Winda Heri Santoso'],
                ['J15', 'J', 15, 'Ghozali'],
                ['J16', 'J', 16, 'Istiqomatul M'],
                ['J17', 'J', 17, 'M.Arifin Abuqori'],
                ['J18', 'J', 18, 'Fajar Pribadi'],
                ['K01', 'K', 1, 'Kusbandrio'],
                ['K02', 'K', 2, 'Yarman Latuba'],
                ['K03', 'K', 3, 'Ahmad Farhan'],
                ['K04', 'K', 4, null],
                ['K05', 'K', 5, 'Nur Muliawati'],
                ['K06', 'K', 6, 'Ikrom Junaidi'],
                ['K07', 'K', 7, 'Elisa Aprilia'],
                ['K08', 'K', 8, 'Eka Wahyu Jenianti'],
                ['K09', 'K', 9, 'Deni Suhartono'],
            ],
            'Gang 7' => [
                ['K10', 'K', 10, 'Ridwan'],
                ['K11', 'K', 11, 'Faizah'],
                ['K12', 'K', 12, 'Samsul Huda'],
                ['K13', 'K', 13, 'Wayoto'],
                ['K14', 'K', 14, 'Abdul Aziz Alanshori'],
                ['K15', 'K', 15, 'Firmanda Firdaus'],
                ['K16', 'K', 16, 'Heri Purwanto'],
                ['K17', 'K', 17, 'Bayu Adi Kurniawan'],
                ['K18', 'K', 18, 'Dewi Sri Malasari'],
                ['L01', 'L', 1, 'M. Tholib'],
                ['L02', 'L', 2, 'Miftahillah'],
                ['L03', 'L', 3, 'Pujiani'],
                ['L04', 'L', 4, 'Zainuddin/ Arab'],
                ['L05', 'L', 5, 'Prasetya Wisuda'],
                ['L06', 'L', 6, 'Isom Fuadi Fikri'],
                ['L07', 'L', 7, 'Agus Ainul Yaqin'],
                ['L08', 'L', 8, 'Achmad Thoha'],
                ['L09', 'L', 9, 'Anas Nasirudin'],
                ['L10', 'L', 10, 'M. Nur Kholis'],
            ],
        ];


        // Loop gang
        foreach ($gangKetuaData as $gangName => $ketua) {
            // Buat user ketua gang (role PetugasRt)
            $ketuaGang = User::firstOrCreate(
                ['email' => $ketua['email']],
                ['name' => $ketua['name'], 'password' => bcrypt('12345')]
            );
            $ketuaGang->assignRole('petugas_rt');

            // Buat data gang
            $gang = Gang::create([
                'rt_id' => $rt->id,
                'ketua_gang_id' => $ketuaGang->id,
                'name' => $gangName,
            ]);

            // Tambah rumah
            foreach ($housesData[$gangName] as [$id, $blok, $nomer, $pemilik]) {
                House::create([
                    'id' => $id,
                    'blok' => $blok,
                    'nomer' => $nomer,
                    'rt_id' => $rt->id,
                    'gang_id' => $gang->id,
                    'name' => $pemilik ?? 'Nama Tidak Diketahui',
                    'address' => "Alamat Rumah {$id}",
                ]);
            }
        }
    }
}
