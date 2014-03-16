<?php

class BaseModel {
    protected static $properties=array('id');
    private $decorators=array();
    private $decorated=false;
    private $attributes=array();

    public function __construct($data=array(), $decorate=true) {
        if ($additional_properties = $this->defaultProperties()) {
            $this->addProperties($additional_properties);
        }

        if ($decorate) {
            $this->decorate();
        }
        if (!empty($data)) {
            $this->initialize($data);
        }
    }

    public function initialize($data) {
        foreach ($data as $key=>$value) {
            $this->{$key} = $value;
        }
        return $this;
    }

    public function pre_save() {}

    public function save() {
        return true;
    }

    public function post_save() {}

    public function properties() {
        return self::$properties;
    }

    public function defaultProperties() {
        return array();
    }

    public function addProperties(array $properties=array()) {
        if (!empty($properties)) {
            self::$properties = array_merge(self::$properties, $properties);
        }
        return self::properties();
    }

    public function decorate() {
        if (!$this->decorated) {
            foreach (get_declared_classes() as $class) {
                if (is_subclass_of($class, get_called_class() . 'Decorator')) {
                    $this->decorators[$class] = new $class($this);
                }
            }
            $this->decorated = true;
        }
        return $this;
    }

    public function __call($method, $args) {
        foreach ($this->decorators as $class => $decorator) {
            if (method_exists($decorator, $method)) {
                return call_user_func_array(array($decorator, $method), $args);
            }
        }
        throw new Exception("No method {$method} exists for " . get_called_class());
    }

    public function __set($key, $value) {
        if (in_array($key, static::$properties)) {
            $this->attributes[$key] = $value;
            return $value;
        } else {
            throw new Exception("No attribute {$key} on " . get_called_class());
        }
    }

    public function __get($key) {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        } elseif (in_array($key, static::$properties)) {
            return null;
        }
        throw new Exception("No attribute {$key} exists on " . get_called_class());
    }

    public function to_json() {
        $data = array();
        foreach (static::$properties as $key) {
            $data[$key] = $this->{$key};
        }
        return json_encode($data);
    }
}
