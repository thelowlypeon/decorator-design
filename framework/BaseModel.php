<?php
/**
 * Base model class
 *
 * All data objects should extend this
 */
class BaseModel {
    protected static $properties=array('id');
    private $decorators=array();
    private $decorated=false;
    private $attributes=array();

    /**
     * Constructor will add any additional properties as defined in child classes,
     *   and create all decorators that are enabled
     * 
     * @param array $data, an assoc array containing data to initialize with
     * @param bool  $decorate, whether to create the decorators upon construction
     */
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

    /**
     * Assign instance variables with assoc array
     *
     * @param array $data
     * @return $this
     */
    public function initialize($data) {
        foreach ($data as $key=>$value) {
            $this->{$key} = $value;
        }
        return $this;
    }

    /**
     * Pre-save hook
     */
    public function pre_save() {}

    /**
     * Save object to db.
     *
     * @return bool success
     */
    public function save() {
        $this->pre_save();
        $success = true;
        if ($success) {
            $this->post_save();
            return true;
        }
        return false;
    }

    /**
     * Post-save hook
     */
    public function post_save() {}

    /**
     * Gets the properties of this object
     *
     * @return string[]
     */
    public function properties() {
        return self::$properties;
    }

    /**
     * Override this method to add properties when declaring your model
     *
     * @return string[]
     */
    public function defaultProperties() {
        return array();
    }

    /**
     * Add properties to self::$properties
     *
     * @return string[], the properties
     */
    public function addProperties(array $properties=array()) {
        if (!empty($properties)) {
            self::$properties = array_merge(self::$properties, $properties);
        }
        return self::properties();
    }

    /**
     * Get all decorators. Constructing the decorators is where the magic happens!
     * Store the decorators locally so we don't need to fish through all declared classes later
     *
     * @return $this
     */
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

    /**
     * Return a json array of all this object's attributes
     *
     * @return string
     */
    public function to_json() {
        $data = array();
        foreach (static::$properties as $key) {
            $data[$key] = $this->{$key};
        }
        return json_encode($data);
    }

    /** MAGIC METHODS TO CHECK FOR ATTRIBUTES OR METHODS IN DECORATORS, OR PROPERTIES **/

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
}
