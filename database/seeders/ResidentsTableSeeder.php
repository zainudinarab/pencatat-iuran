<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Resident;


class ResidentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            ['slug' => 'H10', 'name' => 'Hariyadi', 'blok' => 'H', 'nomor_rumah' => '10', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'H11', 'name' => 'Sri Rahayu', 'blok' => 'H', 'nomor_rumah' => '11', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'H12', 'name' => 'Tahmid', 'blok' => 'H', 'nomor_rumah' => '12', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'H13', 'name' => 'Masrikan', 'blok' => 'H', 'nomor_rumah' => '13', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'H14', 'name' => 'Khiril Irwan', 'blok' => 'H', 'nomor_rumah' => '14', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'H15', 'name' => 'Khoirul Anam', 'blok' => 'H', 'nomor_rumah' => '15', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'H16', 'name' => 'Zainuddin', 'blok' => 'H', 'nomor_rumah' => '16', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'H17', 'name' => 'Basuki', 'blok' => 'H', 'nomor_rumah' => '17', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'H18', 'name' => 'Samsul Maarif', 'blok' => 'H', 'nomor_rumah' => '18', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I01', 'name' => 'Nurdin Bramono', 'blok' => 'I', 'nomor_rumah' => '01', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I02', 'name' => 'Ainun Ni\'am', 'blok' => 'I', 'nomor_rumah' => '02', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I03', 'name' => 'Boni Sugihartono', 'blok' => 'I', 'nomor_rumah' => '03', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I04', 'name' => 'Muzakki', 'blok' => 'I', 'nomor_rumah' => '04', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I05', 'name' => 'Ahmad Nasrullah', 'blok' => 'I', 'nomor_rumah' => '05', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I06', 'name' => 'Lutfi', 'blok' => 'I', 'nomor_rumah' => '06', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I07', 'name' => 'M. Faturochman', 'blok' => 'I', 'nomor_rumah' => '07', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I08', 'name' => 'Muhammad Iqbal Prasetya', 'blok' => 'I', 'nomor_rumah' => '08', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I09', 'name' => 'M. Abdillah', 'blok' => 'I', 'nomor_rumah' => '09', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I10', 'name' => 'Khoirul Wahyudi', 'blok' => 'I', 'nomor_rumah' => '10', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I11', 'name' => 'Safiil Maarif', 'blok' => 'I', 'nomor_rumah' => '11', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I12', 'name' => 'Andik Purwanto', 'blok' => 'I', 'nomor_rumah' => '12', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I13', 'name' => 'Abdul Basit', 'blok' => 'I', 'nomor_rumah' => '13', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I14', 'name' => 'Asmanul Husen', 'blok' => 'I', 'nomor_rumah' => '14', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I15', 'name' => 'Mukhlisin', 'blok' => 'I', 'nomor_rumah' => '15', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I16', 'name' => 'Achmad Tholib', 'blok' => 'I', 'nomor_rumah' => '16', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I17', 'name' => 'Fauzi/ Nurul Khoirun Nisa\'', 'blok' => 'I', 'nomor_rumah' => '17', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I18', 'name' => 'Arif / Himmatul Ulya', 'blok' => 'I', 'nomor_rumah' => '18', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I19', 'name' => 'Fajarroeddin', 'blok' => 'I', 'nomor_rumah' => '19', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'I20', 'name' => 'M. Nadhir', 'blok' => 'I', 'nomor_rumah' => '20', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J01', 'name' => 'Yudha Ari P', 'blok' => 'J', 'nomor_rumah' => '01', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J02', 'name' => 'Hendra Siswanto', 'blok' => 'J', 'nomor_rumah' => '02', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J03', 'name' => 'Abdul Latif', 'blok' => 'J', 'nomor_rumah' => '03', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J04', 'name' => 'Muhammad Malik Mudzoffar', 'blok' => 'J', 'nomor_rumah' => '04', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J05', 'name' => 'Achmat Rosyid', 'blok' => 'J', 'nomor_rumah' => '05', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J06', 'name' => 'Zaky Mubarok', 'blok' => 'J', 'nomor_rumah' => '06', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J07', 'name' => 'M. Badaruddin', 'blok' => 'J', 'nomor_rumah' => '07', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J08', 'name' => 'Nungki Yulia Hapsari', 'blok' => 'J', 'nomor_rumah' => '08', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J09', 'name' => 'Aprilian Fery', 'blok' => 'J', 'nomor_rumah' => '09', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J10', 'name' => 'Moch. Umar', 'blok' => 'J', 'nomor_rumah' => '10', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J11', 'name' => 'Abdul Ghofar', 'blok' => 'J', 'nomor_rumah' => '11', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J12', 'name' => 'Toni Suhartono', 'blok' => 'J', 'nomor_rumah' => '12', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J13', 'name' => 'Taufiqul Hakim', 'blok' => 'J', 'nomor_rumah' => '13', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J14', 'name' => 'Winda Heri Santoso', 'blok' => 'J', 'nomor_rumah' => '14', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J15', 'name' => 'Ghozali', 'blok' => 'J', 'nomor_rumah' => '15', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J16', 'name' => 'Istiqomatul M', 'blok' => 'J', 'nomor_rumah' => '16', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J17', 'name' => 'M. Arifin Abuqori', 'blok' => 'J', 'nomor_rumah' => '17', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'J18', 'name' => 'Fajar Pribadi', 'blok' => 'J', 'nomor_rumah' => '18', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K01', 'name' => 'Kusbandrio', 'blok' => 'K', 'nomor_rumah' => '01', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K02', 'name' => 'Yarman Latuba', 'blok' => 'K', 'nomor_rumah' => '02', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K03', 'name' => 'Ahmad Farhan', 'blok' => 'K', 'nomor_rumah' => '03', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K04', 'name' => '', 'blok' => 'K', 'nomor_rumah' => '04', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K05', 'name' => 'Nur Muliawati', 'blok' => 'K', 'nomor_rumah' => '05', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K06', 'name' => 'Ikrom Junaidi', 'blok' => 'K', 'nomor_rumah' => '06', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K07', 'name' => 'Elisa Aprilia', 'blok' => 'K', 'nomor_rumah' => '07', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K08', 'name' => 'Eka Wahyu Jenianti', 'blok' => 'K', 'nomor_rumah' => '08', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K09', 'name' => 'Deni Suhartono', 'blok' => 'K', 'nomor_rumah' => '09', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K10', 'name' => 'Ridwan', 'blok' => 'K', 'nomor_rumah' => '10', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K11', 'name' => 'Faizah', 'blok' => 'K', 'nomor_rumah' => '11', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K12', 'name' => 'Samsul Huda', 'blok' => 'K', 'nomor_rumah' => '12', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K13', 'name' => 'Wayoto', 'blok' => 'K', 'nomor_rumah' => '13', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K14', 'name' => 'Abdul Aziz Alanshori', 'blok' => 'K', 'nomor_rumah' => '14', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K15', 'name' => 'Firmanda Firdaus', 'blok' => 'K', 'nomor_rumah' => '15', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K16', 'name' => 'Heri Purwanto', 'blok' => 'K', 'nomor_rumah' => '16', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K17', 'name' => 'Bayu Adi Kurniawan', 'blok' => 'K', 'nomor_rumah' => '17', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'K18', 'name' => 'Dewi Sri Malasari', 'blok' => 'K', 'nomor_rumah' => '18', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'L01', 'name' => 'M. Tholib', 'blok' => 'L', 'nomor_rumah' => '01', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'L02', 'name' => 'Miftahillah', 'blok' => 'L', 'nomor_rumah' => '02', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'L03', 'name' => 'Pujiani', 'blok' => 'L', 'nomor_rumah' => '03', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'L04', 'name' => 'Zainudin', 'blok' => 'L', 'nomor_rumah' => '04', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'L05', 'name' => 'Prasetya Wisuda', 'blok' => 'L', 'nomor_rumah' => '05', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'L06', 'name' => 'Isom Fuadi Fikri', 'blok' => 'L', 'nomor_rumah' => '06', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'L07', 'name' => 'Agus Ainul Yaqin', 'blok' => 'L', 'nomor_rumah' => '07', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'L08', 'name' => 'Achmad Thoha', 'blok' => 'L', 'nomor_rumah' => '08', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'L09', 'name' => 'Anas Nasirudin', 'blok' => 'L', 'nomor_rumah' => '09', 'RT' => '11', 'RW' => '05', 'address' => ''],
            ['slug' => 'L10', 'name' => 'M. Nur Kholis', 'blok' => 'L', 'nomor_rumah' => '10', 'RT' => '11', 'RW' => '05', 'address' => ''],
        ];

        foreach ($data as $resident) {
            Resident::create([
                'id'=>$resident['slug'],
                'name' => $resident['name'],
                'phone_number' => '', // Kosongkan seperti permintaan Anda
                'slug' => $resident['slug'],
                'blok' => $resident['blok'],
                'nomor_rumah' => $resident['nomor_rumah'],
                'RT' => $resident['RT'],
                'RW' => $resident['RW'],
                'address' => $resident['address']
            ]);
        }
    }
}
