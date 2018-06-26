<?php
namespace Slimork\Providers\Hash;

use Slimork\Providers\Hash\Hasher\BCryptHasher;

class HashManager {

    protected $container;
    protected $settings;

    public function __construct($container) {
        $this->container = $container;
        $this->settings  = $this->container->get('settings');
    }

    public function driver() {
        $driver   = $this->getDefaultDreiver();
        $settings = array_key_exists($driver, $this->settings['hash']) ? $this->settings['hash'][$driver] : [];

        switch($driver) {
            case 'bcrypt':
                return new BCryptHasher($settings);
                break;
        }
    }

    public function make($value, array $options = []) {
        return $this->driver()->make($value, $options);
    }

    public function check($value, $hashed_value) {
        return $this->driver()->check($value, $hashed_value);
    }

    public function needsRehash($hashed_value, array $options = []) {
        return $this->driver()->needsRehash($hashed_value, $options);
    }

    public function getDefaultDreiver() {
        return $this->settings['hash']['driver'] ?? 'bcrypt';
    }

}
