<?php

namespace App\View\Composers;

use Illuminate\View\View;

class EquipmentImageComposer
{
    /**
     * Peta nama alat → nama file gambar.
     * Gambar disimpan di public/images/equipments/
     *
     * Untuk menambah alat baru, cukup tambahkan entry di sini
     * tanpa perlu menyentuh view manapun.
     */
    protected array $imageMap = [
        'Laptop ASUS ROG'               => 'laptop-asus-rog.png',
        'Raspberry Pi 5'                => 'raspberry-pi-5.png',
        'Arduino Mega 2560'             => 'arduino-mega.png',
        'Cisco Router 2901'             => 'cisco-router-2901.png',
        'Cisco Switch Catalyst 2960'    => 'cisco-switch-2960.png',
        'Server Dell PowerEdge'         => 'server-dell-poweredge.png',
        'Monitor LG UltraWide 34"'      => 'monitor-lg-ultrawide.png',
        'VR Headset Meta Quest 3'       => 'vr-headset-meta-quest3.png',
        '3D Printer Creality Ender'     => 'printer-3d-creality.png',
        'Kabel UTP Cat6 + RJ45 Kit'     => 'kabel-utp-rj45.png',
        'GPU Workstation NVIDIA A4000'  => 'gpu-nvidia-a4000.png',
        'Sensor Kit IoT'                => 'sensor-kit-iot.png',
        'Oscilloscope Digital Rigol'    => 'oscilloscope-rigol.png',
        'Webcam Logitech C920'          => 'webcam-logitech-c920.png',
        'External HDD 2TB'              => 'external-hdd-2tb.png',
    ];

    /**
     * Bind $imageMap ke semua view equipment (catalog & index).
     */
    public function compose(View $view): void
    {
        $view->with('imageMap', $this->imageMap);
    }
}
