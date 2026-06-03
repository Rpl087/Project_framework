<?php

namespace App\View\Composers;

use Illuminate\View\View;

class EquipmentImageComposer
{
    /**
     * Peta legacy: nama alat → nama file gambar (untuk data seeder yang sudah ada).
     * Gambar yang diupload via form disimpan langsung di kolom `image` DB,
     * sehingga tidak perlu mengedit file ini untuk alat baru.
     *
     * Gambar disimpan di: public/images/equipments/
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
        'External HDD 2TB'             => 'external-hdd-2tb.png',
    ];

    /**
     * Bind $imageMap ke semua view equipment.
     * FITUR-6: Jika equipment punya kolom `image` (uploaded), itu yang dipakai di view.
     */
    public function compose(View $view): void
    {
        $view->with('imageMap', $this->imageMap);
    }

    /**
     * Helper statik: dapatkan URL gambar untuk satu equipment.
     * Priority: DB image column → legacy imageMap → null (tampilkan placeholder)
     */
    public static function getImageUrl($equipment, array $imageMap): ?string
    {
        // 1. Prioritas: kolom image dari DB (hasil upload)
        if (!empty($equipment->image)) {
            $uploadedPath = public_path('images/equipments/' . $equipment->image);
            if (file_exists($uploadedPath)) {
                return asset('images/equipments/' . $equipment->image);
            }
        }

        // 2. Fallback: legacy imageMap (data seeder)
        if (isset($imageMap[$equipment->name])) {
            $legacyPath = public_path('images/equipments/' . $imageMap[$equipment->name]);
            if (file_exists($legacyPath)) {
                return asset('images/equipments/' . $imageMap[$equipment->name]);
            }
        }

        // 3. Tidak ada gambar — return null untuk tampilkan placeholder di view
        return null;
    }
}
