<?php
namespace HiddenCMS\Slider\Migrations;
use HB\HiddenCMS\Addons\Migration;
class CreateSliderTables implements Migration
{
    public function up($db)
    {
        $db->execute_checked('CREATE TABLE IF NOT EXISTS slider_sets (
            slider_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(150) NOT NULL, effect VARCHAR(20) NOT NULL DEFAULT "slide",
            delay_ms INT NOT NULL DEFAULT 5000, speed_ms INT NOT NULL DEFAULT 600,
            height INT NOT NULL DEFAULT 480, autoplay TINYINT NOT NULL DEFAULT 1,
            published TINYINT NOT NULL DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        $db->execute_checked('CREATE TABLE IF NOT EXISTS slider_slides (
            slide_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            slider_id INT UNSIGNED NOT NULL, image_id INT UNSIGNED NOT NULL,
            title VARCHAR(150) NOT NULL, description TEXT NOT NULL, alt VARCHAR(255) NOT NULL,
            button_label VARCHAR(100) NOT NULL, button_url VARCHAR(2048) NOT NULL,
            position INT NOT NULL DEFAULT 0, published TINYINT NOT NULL DEFAULT 1,
            KEY slider_order (slider_id, position),
            CONSTRAINT slider_slide_set FOREIGN KEY (slider_id) REFERENCES slider_sets(slider_id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
    }
    public function down($db)
    {
        $db->execute_checked('DROP TABLE IF EXISTS slider_slides');
        $db->execute_checked('DROP TABLE IF EXISTS slider_sets');
    }
}
