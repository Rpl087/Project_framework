<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $equipments = [
            [
                'name' => 'Laptop ASUS ROG',
                'description' => 'Laptop ASUS ROG Strix G16 dengan Intel Core i7, RAM 16GB, SSD 512GB, GPU RTX 4060 untuk pemrograman dan pengembangan software.',
                'total_stock' => 15,
                'available_stock' => 15,
                'category' => 'umum',
                'status' => 'good',
            ],
            [
                'name' => 'Raspberry Pi 5',
                'description' => 'Raspberry Pi 5 Model B 8GB RAM, cocok untuk proyek IoT, embedded system, dan pembelajaran Linux.',
                'total_stock' => 20,
                'available_stock' => 20,
                'category' => 'umum',
                'status' => 'good',
            ],
            [
                'name' => 'Arduino Mega 2560',
                'description' => 'Board mikrokontroler Arduino Mega 2560 dengan 54 pin digital I/O untuk proyek robotika dan embedded system.',
                'total_stock' => 25,
                'available_stock' => 25,
                'category' => 'umum',
                'status' => 'good',
            ],
            [
                'name' => 'Cisco Router 2901',
                'description' => 'Router Cisco 2901 untuk praktikum jaringan komputer, konfigurasi routing, VLAN, dan keamanan jaringan.',
                'total_stock' => 8,
                'available_stock' => 8,
                'category' => 'khusus',
                'status' => 'good',
            ],
            [
                'name' => 'Cisco Switch Catalyst 2960',
                'description' => 'Managed switch Cisco Catalyst 2960 24-port untuk praktikum switching, VLAN, dan manajemen jaringan.',
                'total_stock' => 10,
                'available_stock' => 10,
                'category' => 'khusus',
                'status' => 'good',
            ],
            [
                'name' => 'Server Dell PowerEdge',
                'description' => 'Server Dell PowerEdge T440 dengan Xeon Silver, RAM 32GB, HDD 2TB untuk praktikum cloud computing dan virtualisasi.',
                'total_stock' => 3,
                'available_stock' => 3,
                'category' => 'khusus',
                'status' => 'good',
            ],
            [
                'name' => 'Monitor LG UltraWide 34"',
                'description' => 'Monitor LG UltraWide 34 inci resolusi 3440x1440 IPS untuk desain UI/UX dan pengembangan web.',
                'total_stock' => 12,
                'available_stock' => 12,
                'category' => 'umum',
                'status' => 'good',
            ],
            [
                'name' => 'VR Headset Meta Quest 3',
                'description' => 'Headset VR Meta Quest 3 untuk pengembangan aplikasi Virtual Reality dan simulasi interaktif.',
                'total_stock' => 5,
                'available_stock' => 5,
                'category' => 'khusus',
                'status' => 'good',
            ],
            [
                'name' => '3D Printer Creality Ender',
                'description' => 'Printer 3D Creality Ender-3 V3 untuk prototyping casing hardware, komponen IoT, dan proyek maker.',
                'total_stock' => 4,
                'available_stock' => 4,
                'category' => 'khusus',
                'status' => 'good',
            ],
            [
                'name' => 'Kabel UTP Cat6 + RJ45 Kit',
                'description' => 'Kit crimping kabel UTP Cat6 lengkap dengan tang crimping, RJ45 connector, dan LAN tester untuk praktikum jaringan.',
                'total_stock' => 30,
                'available_stock' => 30,
                'category' => 'umum',
                'status' => 'good',
            ],
            [
                'name' => 'GPU Workstation NVIDIA A4000',
                'description' => 'Workstation dengan GPU NVIDIA RTX A4000 16GB VRAM untuk deep learning, machine learning, dan AI research.',
                'total_stock' => 2,
                'available_stock' => 2,
                'category' => 'khusus',
                'status' => 'good',
            ],
            [
                'name' => 'Sensor Kit IoT',
                'description' => 'Kit sensor lengkap (suhu, kelembaban, ultrasonik, PIR, LDR, GPS) untuk proyek Internet of Things.',
                'total_stock' => 20,
                'available_stock' => 20,
                'category' => 'umum',
                'status' => 'good',
            ],
            [
                'name' => 'Oscilloscope Digital Rigol',
                'description' => 'Oscilloscope digital Rigol DS1054Z 4-channel 50MHz untuk analisis sinyal dan debugging hardware.',
                'total_stock' => 6,
                'available_stock' => 6,
                'category' => 'khusus',
                'status' => 'good',
            ],
            [
                'name' => 'Webcam Logitech C920',
                'description' => 'Webcam Logitech C920 HD Pro 1080p untuk praktikum computer vision, image processing, dan video conference.',
                'total_stock' => 15,
                'available_stock' => 15,
                'category' => 'umum',
                'status' => 'good',
            ],
            [
                'name' => 'External HDD 2TB',
                'description' => 'Hard disk eksternal Seagate 2TB USB 3.0 untuk backup data, transfer dataset, dan penyimpanan proyek.',
                'total_stock' => 10,
                'available_stock' => 10,
                'category' => 'umum',
                'status' => 'maintenance',
            ],
        ];

        foreach ($equipments as $equipment) {
            Equipment::create($equipment);
        }
    }
}
