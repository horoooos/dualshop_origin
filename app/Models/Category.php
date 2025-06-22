<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_type',
        'is_seasonal',
        'slug',
        'description',
        'parent_id',
        'icon'
    ];

    protected $casts = [
        'is_seasonal' => 'boolean'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function allowedSpecKeys()
    {
        $map = [
            'Блоки питания' => ['brand', 'wattage', 'form_factor', 'certificate', 'fan_size', 'pfc', 'color'],
            'Процессоры' => ['brand', 'socket', 'series', 'cores', 'threads', 'base_freq', 'max_freq', 'cache', 'tdp', 'unlocked', 'architecture', 'tech_process', 'integrated_graphics', 'graphics_frequency', 'pcie_version', 'pcie_lanes', 'memory_type', 'max_memory', 'max_memory_frequency', 'ecc_support', 'virtualization', 'package_type'],
            'Видеокарты' => [
                'brand', 'model', 'gpu', 'architecture', 'process',
                'memory', 'memory_type', 'bus_width', 'bandwidth',
                'ray_tracing', 'fsr', 'dlss', 'ports', 'tdp',
                'fan_count', 'cooling', 'power', 'warranty', 'country'
            ],
            'Материнские платы' => ['socket', 'chipset', 'form_factor', 'memory_type', 'memory_slots', 'pcie_slots', 'sata_ports', 'm2_slots', 'dimensions', 'memory_frequency', 'pcie_x1_slots', 'multi_gpu', 'lan', 'lan_chip', 'usb_ports', 'display_outputs', 'audio_codec', 'audio_ports', 'cooling', 'color', 'rgb', 'features'],
            'Корпуса' => ['form_factor', 'type', 'fans_included', 'max_fans', 'usb_ports', 'tempered_glass', 'mesh_front', 'color', 'material', 'window'],
            'Накопители' => ['type', 'form_factor', 'interface', 'capacity', 'read_speed', 'write_speed', 'rpm', 'cache', 'brand', 'model', 'nand_type', 'controller', 'encryption', 'series', 'durability', 'tbw', 'dimensions', 'thickness', 'power_consumption', 'features', 'intended_use', 'rotation_speed', 'cache_size', 'model_code'],
            'Оперативная память' => ['type', 'capacity', 'frequency', 'brand', 'series', 'model_code', 'total_capacity', 'modules_kit', 'xmp_profile', 'expo_profile', 'latency_cas', 'voltage', 'throughput', 'features', 'form_factor', 'min_frequency', 'timings', 'height'],
            'Системы охлаждения' => ['type', 'radiator_size', 'fans', 'rgb', 'socket_support', 'noise_level', 'design', 'heat_pipes', 'brand', 'model', 'block_rotation', 'base_material', 'microchannel_density', 'fan_control', 'pump_control', 'color', 'radiator_material', 'display', 'fan_count', 'fan_bearing', 'airflow', 'noise_level', 'serviceability', 'tubing', 'compatibility'],
        ];
        return $map[$this->name] ?? [];
    }
} 